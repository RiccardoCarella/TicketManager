<?php
    class User implements JsonSerializable {
        private $db;
        private $id;
        private $username;
        private $password;
        private $name;
        private $surname;
        private $mobile;
        private $email;
        private $role;

        public function __construct($id) {
            $db = new Db();
            $user = $db->row("SELECT * FROM users WHERE user_id = :id" , array("id" => $id));

            $this->db = $db;
            $this->id = $user['user_id'];
            $this->username = $user['user_username'];
            $this->password = $user['user_password'];
            $this->name = $user['user_name'];
            $this->surname = $user['user_surname'];
            $this->mobile = $user['user_mobile'];
            $this->email = $user['user_email'];
            $this->role = $user['user_role'];
        }

        // Getters
        public function getId() {
            return $this->id;
        }

        public function getUsername() {
            return $this->username;
        }

        public function getPassword() {
            return $this->password;
        }

        public function getName() {
            return $this->name;
        }

        public function getSurname() {
            return $this->surname;
        }

        public function getMobile() {
            return $this->mobile;
        }

        public function getEmail() {
            return $this->email;
        }

        public function getRole() {
            return $this->role;
        }

        public function getFullName() {
            return $this->getName()." ".$this->getSurname();
        }
        // Methods

        public function jsonSerialize() {
            return array(
                "id" => $this->getId(),
                "name" => $this->getName(),
                "surname" => $this->getSurname(),
                "fullName" => $this->getFullName(),
                "mobile" => $this->getMobile(),
                "email" => $this->getEmail(),
                "role" => $this->getRole(),
            );
        }

        // Static methods
        public static function list() {
            $db = new Db();

            $users = array();

            $rows = $db->query("SELECT * FROM users");

            foreach($rows as $row) {
                $user = new User($row['user_id']);

                $users[] = $user;
            }

            return $users;
        }
        
        public static function nameList() {
            $db = new Db();

            $users = array();

            $rows = $db->query("SELECT user_id, user_name, user_surname FROM users");

            foreach($rows as $row) {
                $user = new User($row['user_id']);

                $users[] = $user;
            }

            return $users;
        }

        public static function existsWithUsername($username) {
            $db = new Db();
            
            $user = $db->row("SELECT * FROM users WHERE user_username = :username", array(
                "username" => $username
            ));

            if($user) {
                return new User($user['user_id']);
            } else {
                return false;
            }
        }
        
        public static function existsWithEmail($email) {
            $db = new Db();
            
            $user = $db->row("SELECT * FROM users WHERE user_email = :email", array(
                "email" => $email
            ));

            if($user) {
                return new User($user['user_id']);
            } else {
                return false;
            }
        }

        public static function login($username, $password) {
            $db = new Db();

            $user = $db->row("SELECT * FROM users WHERE user_username = :username AND user_password = :password", array(
                "username" => $username,
                "password" => $password
            ));

            if($user) {
                return new User($user['user_id']);
            } else {
                return false;
            }
        }

        public static function addUser($name, $username, $password, $role, $surname = '', $mobile = '', $email = '') {
            $db = new Db();

            $user = $db->query("INSERT INTO users (user_name, user_surname, user_username, user_password, user_role, user_mobile, user_email) 
                                    VALUES (:name, :surname, :username, :password, :role, :mobile, :email)", array(
                                        "name" => $name,
                                        "surname" => $surname,
                                        "username" => $username,
                                        "password" => $password,
                                        "role" => $role,
                                        "mobile" => $mobile,
                                        "email" => $email
                                    ));
            return new User($user);
        }

        // Public methods
        public function deleteUser() {
            $row = $this->db->query("DELETE FROM users WHERE user_id = :id", array(
                "id" => $this->id
            ));

            if($row > 0) {
                return true;
            } else {
                return false;
            }
        }

        public function editUser($name, $username, $password, $role, $surname = '', $mobile = '', $email = '') {
            $row = $this->db->query("UPDATE users SET user_name = :name, user_username = :username, user_password = :password, user_role = :role,
                                                      user_surname = :surname, user_mobile = :mobile, user_email = :email
                                     WHERE user_id = :id", array(
                'id' => $this->id,
                'name' => $name,
                'username' => $username,
                'password' => $password,
                'role' => $role,
                'surname' => $surname,
                'mobile' => $mobile,
                'email' => $email
            ));

            if($row > 0) {
                return true;
            } else {
                return false;
            }
        }
    }