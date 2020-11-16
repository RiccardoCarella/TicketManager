<?php
    // Controlli
    if ($_POST['fromAjax'] == 1) {
        require_once __DIR__."/../../lib/config.php";
        
        $result = array(
            "success" => false,
            "msg" => "Errore nella modifica della issue"
        );
        
        if(!$_POST['issueId']) {
            $result = array(
                "success" => false,
                "msg" => "Errore: id non valido"
            );
            header("Content-type: application/json");
            echo json_encode($result);
            exit();
        } else {
            $id = $_POST['issueId'];
            $title = $_POST['title'];
            $content = $_POST['content'];
            $topic = $_POST['topic'];
            $warner = $_POST['warner'];
            $creator = $_POST['creator'];
            $author = $_POST['author'];
            $receiver = $_POST['receiver'];
            $kind = $_POST['kind'];
            $priority = $_POST['priority'];
            $status = $_POST['status'];
            $billed = $_POST['billed'];
            $private = $_POST['private'];
            $creationDate = $_POST['creationDate'];
            $lastUpdate = $_POST['lastUpdate'];
            $attachmentsToDelete = [];
            if(isset($_POST['attachmentsToDelete'])) {
                $attachmentsToDelete = $_POST['attachmentsToDelete'];
            }
            
            $issue = new Issue($id);

            // Se ci sono campi vuoti
            if (empty($title) || empty($creator) || empty($author) || empty($creationDate) || empty($lastUpdate) || empty($status)) {
                $result = array(
                    "success" => false,
                    "msg" => "Errore: campi vuoti"
                );
                header("Content-type: application/json");
                echo json_encode($result);
                exit();
            } else {
                // Aggiorno la issue passando tutti i parametri
                $issueUpdate = $issue->editIssue($title, $content, $topic, $warner, $creator, $author, $receiver, $kind, $priority, $status, $billed, $private, $creationDate, $lastUpdate);
                
                // Se la query è andata a buon fine
                if($issueUpdate || count($attachmentsToDelete) > 0 || isset($_POST['attachmentsId'])) {
                    // Controllo se ci sono file da eliminare
                    if(count($attachmentsToDelete) > 0) {
                        foreach($attachmentsToDelete as $attachmentId) {
                            $attachment = new Attachment($attachmentId);
                            $attachment->deleteAttachment();
                        }
                    }

                    // Se ci sono allegati
                    if(isset($_POST['attachmentsId'])) {
                        $attachmentsId = $_POST['attachmentsId'];
                        $attachmentsId = array_map('intval', explode(',', $attachmentsId));
                        foreach($attachmentsId as $attachmentId) {  
                            $attachment = new Attachment($attachmentId);
                            // Li linko alle issue
                            $attachmentLink = $attachment->linkToIssue($id);
                        }
                    }

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