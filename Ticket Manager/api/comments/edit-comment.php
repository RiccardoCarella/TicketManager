<?php
    // Controlli
    if ($_POST['fromAjax'] == 1) {
        require_once __DIR__."/../../lib/config.php";
        
        $result = array(
            "success" => false,
            "msg" => "Errore nella modifica della issue"
        );
        if(!$_POST['id']) {
            $result = array(
                "success" => false,
                "msg" => "Errore: id non valido"
            );
            header("Content-type: application/json");
            echo json_encode($result);
            exit();
        } else {
            $id = $_POST['id'];
            $date = $_POST['date'];
            $issue = $_POST['issue'];
            $author = $_POST['author'];
            $content = $_POST['content'];
            
            $comment = new Comment($id);

            // Se ci sono campi vuoti
            if (empty($date) || empty($issue) || empty($author) || empty($date)) {
                $result = array(
                    "success" => false,
                    "msg" => "Errore: campi vuoti"
                );
                header("Content-type: application/json");
                echo json_encode($result);
                exit();
            } else {
                // Aggiorno la issue passando tutti i parametri
                $commentUpdate = $comment->editComment($content, $date, $issue, $author);
                
                // Se la query è andata a buon fine
                if($commentUpdate) {
                    $result = array(
                        "success" => true,
                        "msg" => "Issue modificata con successo!"
                    );
    
                    header("Content-type: application/json");
                    echo json_encode($result);
                    exit();
                } else {
                    $result = array(
                        "success" => false,
                        "msg" => "Errore nella query!"
                    );
                    
                    header("Content-type: application/json");
                    echo json_encode($result);
                    exit();
                }
            }
            
        }
    }