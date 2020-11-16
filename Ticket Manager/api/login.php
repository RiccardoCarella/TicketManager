<?php
    require_once __DIR__."/../lib/config.php";

    $result = array(
        "success" => false,
    );

    if ($_POST['fromAjax'] == 1) {
        if (isset($_POST['username']) && isset($_POST['password'])) {
            $username = $_POST['username'];
            $password = sha1($_POST['password']);

            if (empty($username) || empty($password)) {
                $result['msg'] = "Errore: campi vuoti";
            } else {
                $user = User::login($username, $password);
                if ($user) {
                    $_SESSION['id'] = $user->getId();
                    $result['success'] = true;
                } else {
                    $result["msg"] = "Errore: valori errati";
                }
            }
        } else {
            $result["msg"] = "Errore: Non sono stati passati tutti i dati";
        }
    } else {
        $result["msg"] = "Errore: richiesta non accettata";
    }

    header("Content-type: application/json");
    echo json_encode($result);
    exit();