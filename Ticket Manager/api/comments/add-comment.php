<?php

    // Controlli
    if ($_POST['fromAjax'] == 1) {
        require_once __DIR__."/../../lib/config.php";

        $content = $_POST['content'];
        $date = $_POST['date'];
        $issueId = $_POST['issueId'];
        $author = $_SESSION['id'];

        $result = array(
            "success" => false,
            "msg" => "Errore nell'aggiunta del commento"
        );

        if (empty($content) || empty($date) || empty($issueId || empty($author))) {
            $result = array(
                "success" => false,
                "msg" => "Errore: campi vuoti"
            );
            header("Content-type: application/json");
            echo json_encode($result);
            exit();
        } else {
            $comment = Comment::addComment($content, $date, $author, $issueId);
            if($comment) {
                $result = array(
                    "success" => true,
                );

                header("Content-type: application/json");
                echo json_encode($result);
                exit();
            } else {
                $result = array(
                    "success" => false,
                    "msg" => "Errore nella query"
                );

                header("Content-type: application/json");
                echo json_encode($result);
                exit();
            }
        }
    }
    