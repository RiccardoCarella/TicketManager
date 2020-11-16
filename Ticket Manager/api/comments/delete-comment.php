<?php

    // Controlli
    if ($_POST['fromAjax'] == 1) {
        require_once __DIR__."/../../lib/config.php";

        if(!$_POST['id']) {
            $result = array(
                "success" => false,
                "msg" => "Errore: id non valido"
            );
        } else {
            $comment = new Comment($_POST['id']);
            $comment->deleteComment();

            if($comment) {
                $result = array(
                    "success" => true
                );
            } else {
                $result = array(
                    "success" => false,
                    "msg" => "Errore nell'eliminazione del commento"
                );
            }

            header("Content-type: application/json");
            echo json_encode($result);
            exit();
        }
    }
    