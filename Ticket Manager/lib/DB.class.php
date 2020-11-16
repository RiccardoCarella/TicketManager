<?php
require_once __DIR__."/config.php";

class DB
{
    /**
     *  Settings
     */
    private $db_username = DB_USERNAME;
    private $db_password = DB_PASSWORD;
    private $db_host = DB_HOST;
    private $db_name = DB_NAME;
    private $db_port = 3306;
    
    // PDO Object
    private $pdo;
    
    // PDO Statement
    private $query;
    
    // PDO Parameters
    private $parameters;
    
    /**
     *  Construct
     */
    public function __construct($settings = null)
    {
    	global $_PDO;

		if (defined('DB_PORT'))
		{
			$this->db_port = DB_PORT;
		}

        if ($settings != null)
        {
            foreach ($settings as $key => $value) {
                switch ($key) {
                    case 'username':
                        $this->db_username = $value;
                        break;
                    case 'password':
                        $this->db_password = $value;
                        break;
                    case 'host':
                        $this->db_host = $value;
                        break;
                    case 'name':
                        $this->db_name = $value;
                        break;
					case 'port':
						$this->db_port = $value;
					break;
                    default:
					break;
                }
            }
        }


        if (!$_PDO)
		{
			// Initialize
			$pdo = NULL;
			$this->connect();
			$_PDO = $this->pdo;
		}
		else
		{
			$this->pdo = $_PDO;
		}
    }
    
    /*
     *  Connect to DB
     */
    private function connect()
    {
        // Declare PDO options and params
        $dsn = 'mysql:dbname='.$this->db_name.';host='.$this->db_host.';port='.$this->db_port.';';
        $options = array(
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_bin",
			PDO::ATTR_TIMEOUT => 2
        );
        
        try
        {
            // Connecting PDO object
            $this->pdo = new PDO($dsn,$this->db_username,$this->db_password,$options);
        }
        catch (Exception $e)
        {
            $this->log($e->getMessage(),$this->query);
        }
    }
    
    /**
     *  Preparing query with params
     */
    private function init($query,$params)
    {
        // Check if DB is connected
        if ($this->pdo !== NULL)
        {
            try
            {
                // Prepare query
                $this->query = $this->pdo->prepare($query);
                
                // If there are already params
				$this->parameters = $params;
                /*if (empty($this->parameters) && $params != NULL)
                {
                    // There are passed parameters
                    if (!empty($params) && is_array($params))
                    {
                        // Get params columns
                        $columns = array_keys($params);

                        // Foreach params
                        foreach ($columns as $column)
                        {
                            // Append to global array
                            $this->parameters[":".$column] = $params[$column];
                        }
                    }
                }*/
                
                // There are parameters
                if (!empty($this->parameters))
                {
                    // Loop parameters
                    foreach ($this->parameters as $key => $value)
                    {
                        // Get type of parameter
                        switch($value)
                        {
                            case is_int($value):
                                $type = PDO::PARAM_INT;
                                break;
                            case is_bool($value):
                                $type = PDO::PARAM_BOOL;
                                break;
                            case is_null($value):
                                $type = PDO::PARAM_NULL;
                                break;
                            default:
                                $type = PDO::PARAM_STR;
                                break;
                        }
                        
                        // Bind parameters with type
                        $this->query->bindValue($key,$value,$type);
                    }
                }
                
                // Execute query
                $this->query->execute();
            }
            catch (Exception $e)
            {
                $this->log($e,$this->query);
            }
        }
        else
        {
            $this->log("DB not connected");
        }
    }
    
    /**
     *  Logs errors
     */
    private function log($message,$query = NULL)
    {
        if ($query != NULL)
        {
            $error = "[".$query."]: ".$message;
        }
        else
        {
            $error = $message;
        }
        error_log("[DB] ".$error);
    }


	/**
	 * Execute query
	 *
	 * @param string $query
	 * @param array $params
	 * @return bool|int|array
	 */
    public function query($query, $params = NULL)
    {
        $query = trim(str_replace('\r',' ', $query));
        
        // Initialize and execute query with parameters
        $this->init($query, $params);
        
        // Get statement (SELECT | INSERT | DELETE)
        $statement = strtolower(explode(" ", preg_replace("/\s+|\t+|\n+/", " ", $query))[0]);
        
        // Switch statement
        switch ($statement)
        {
            case 'select':
            case 'show':
                return $this->query->fetchAll(PDO::FETCH_ASSOC);
                break;
            case 'delete':
            case 'update':
                return $this->query->rowCount();
                break;
            case 'insert':
            	if (intval($this->lastInsertId()) > 0)
				{
					return intval($this->lastInsertId());
				}
				else if ($this->query->rowCount()) {
            		return $this->query->rowCount();
				}
				else {
            		return false;
				}
                break;
            default:
                return FALSE;
        }
    }
    
    /**
     *  Get single value
     */
    public function single($query, $params = NULL)
    {
        $rows = $this->query($query, $params);
        
        if (is_array($rows) && count($rows) > 0)
        {
            $data = $rows[0];
            if (is_array($data) && count($data) > 0)
            {
                return array_values($data)[0];
            }
            else
            {
                return FALSE;
            }
        }
        else
        {
            return FALSE;
        }
    }
    
    /**
     *  Get single row
     */
    public function row($query, $params = NULL)
    {
        $rows = $this->query($query, $params);
        
        if (is_array($rows) && count($rows) > 0)
        {
            return $rows[0];
        }
        else
        {
            return FALSE;
        }
    }

    /**
     *  Bind value
     */
    public function bind($key,$value)
    {
        $this->parameters[":".$key] = $value;
    }


    /**
     *  Return last insert id
     *  @return string
     */
    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }
}
