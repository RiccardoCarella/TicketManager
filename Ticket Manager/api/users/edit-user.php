<?php

    // Controlli
    if ($_POST['fromAjax'] == 1) {
        require_once __DIR__."/../../lib/config.php";
        
        $result = array(
            "success" => false,
            "msg" => "Errore nella modifica dell'utente"
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
            $name = $_POST['name'];
            $surname = $_POST['surname'];
            $email = $_POST['email'];
            $username = $_POST['username'];
            $password = $_POST['password'];
            $role = $_POST['role'];
            $mobile = $_POST['mobile'];

            $user = new User($id);

            // Se il nome o il username sono vuoti
            if (empty($name) || empty($username || emtpy($role))) {
                $result = array(
                    "success" => false,
                    "msg" => "Errore: campi vuoti"
                );
                header("Content-type: application/json");
                echo json_encode($result);
                exit();
            } else {
                // Se l'utente cambia email e non la mette a vuoto
                if (!empty($email) && $user->getEmail() != $email ) {
                    // Controllo che non sia già stata utilizzata
                    if (User::existsWithEmail($email)) {
                        $result = array(
                            "success" => false,
                            "msg" => "Errore: e-mail già in uso"
                        );
                        header("Content-type: application/json");
                        echo json_encode($result);
                        exit();
                    }
                }

                // Se il username è stato cambiato
                if ($user->getUsername() != $username) {
                    // Controllo che non sia già stato utilizzato
                    if (User::existsWithUsername($username)) {
                        $result = array(
                            "success" => false,
                            "msg" => "Errore: username già in uso"
                        );
                        header("Content-type: application/json");
                        echo json_encode($result);
                        exit();
                    }
                }

                // Se è stata reimpostata la password la codifico
                if (!empty($password)) {
                    $password = sha1($password);
                } // Sennò la riassegno a quella precedente
                else {
                    $password = $user->getPassword();
                }

                // Aggiorno l'utente passando tutti i parametri
                $userUpdate = $user->editUser($name, $username, $password, $role, $surname, $mobile, $email);
                
                // Se la query è andata a buon fine
                if($userUpdate) {
                    $result = array(
                        "success" => true,
                        "msg" => "Utente modificato con successo!"
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
    