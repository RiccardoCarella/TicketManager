<?php

    // Controlli
    if ($_POST['fromAjax'] == 1) {
        require_once __DIR__."/../../lib/config.php";

        $name = $_POST['name'];
        $surname = $_POST['surname'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $role = $_POST['role'];
        $mobile = $_POST['mobile'];
        $email = $_POST['email'];

        $result = array(
            "success" => false,
            "msg" => "Errore nell'aggiunta dell'utente"
        );

        if (empty($name) || empty($username) || empty($password) || empty($role)) {
            $result = array(
                "success" => false,
                "msg" => "Errore: campi vuoti"
            );
            header("Content-type: application/json");
            echo json_encode($result);
            exit();
        } else {
            $password = sha1($password);
            if(!empty($email)) {
                $user = User::existsWithEmail($email);
                // Controllo se l'email è già in uso
                if($user) {
                    $result = array(
                        "success" => false,
                        "msg" => "Errore: e-mail già in uso"
                    );

                    header("Content-type: application/json");
                    echo json_encode($result);
                    exit();
                }
            }

            $user = User::existsWithUsername($username);
            
            // Controllo se il username è già in uso
            if ($user) {
                $result = array(
                    "success" => false,
                    "msg" => "Errore: username già in uso"
                );

                header("Content-type: application/json");
                echo json_encode($result);
                exit();
            } else {
                $user = User::addUser($name, $username, $password, $role, $surname, $mobile, $email);

                if($user) {
                    $result = array(
                        "success" => true
                    );
    
                    header("Content-type: application/json");
                    echo json_encode($result);
                    exit();
                }
            }
        }
    }
    