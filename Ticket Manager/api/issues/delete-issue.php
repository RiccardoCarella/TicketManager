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
            $issue = new Issue($_POST['id']);
            
            $attachments = $issue->getAttachments();
            foreach($attachments as $attachment) {
                $attachment = new Attachment($attachment->getId());
                $attachment->deleteAttachment();
            }

            $issue->deleteIssue();

            if($issue) {
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
    