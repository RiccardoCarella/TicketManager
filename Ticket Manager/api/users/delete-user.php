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
            $user = new User($_POST['id']);
            $user->deleteUser();

            if($user) {
                $result = array(
                    "success" => true
                );
            } else {
                $result = array(
                    "success" => false,
                    "msg" => "Errore nell'eliminazione dell'utente"
                );
            }

            header("Content-type: application/json");
            echo json_encode($result);
            exit();
        }
    }
    