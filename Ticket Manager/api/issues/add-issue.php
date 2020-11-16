<?php

    // Controlli
    require_once __DIR__."/../../lib/config.php";
    if($_POST['fromAjax'] == 1) {
        $content = $_POST['content'];
        $title = $_POST['title'];
        $topic = $_POST['topic'];
        $warner = $_POST['warner'];
        $creator = $_SESSION['id'];
        $author = $_POST['author'];
        $receiver = $_POST['receiver'];
        $kind = $_POST['kind'];
        $priority = $_POST['priority'];
        $status = $_POST['status'];
        $creationDate = $_POST['creationDate'];
        $lastUpdate = $_POST['lastUpdate'];
        $private = $_POST['private'];
        $result = array(
            "success" => false,
            "msg" => "Errore nell'aggiunta della issue"
        );

        if (empty($title) || empty($creator) || empty($author) || empty($receiver) || empty($kind) || empty($priority) || empty($status)) {
            $result = array(
                "success" => false,
                "msg" => "Errore: campi vuoti"
            );
            header("Content-type: application/json");
            echo json_encode($result);
            exit();
        } else {
            $issue = Issue::addIssue($title, $content, $topic, $warner, $creator, $author, $receiver, $kind, $priority, $status, $creationDate, $lastUpdate, $private);
            
            if($issue) {
                // Se ci sono allegati
                if(isset($_POST['attachmentsId'])) {
                    $attachmentsId = $_POST['attachmentsId'];
                    $attachmentsId = array_map('intval', explode(',', $attachmentsId));
                    foreach($attachmentsId as $attachmentId) {  
                        $attachment = new Attachment($attachmentId);
                        // Li linko alle issue
                        $attachmentLink = $attachment->linkToIssue($issue->getId());
                    }
                }

                $result = array(
                    "success" => true,
                    "msg" => "Issue aggiunta con successo!",
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