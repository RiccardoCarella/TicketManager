<?php
    require_once __DIR__."/../../lib/config.php";
    if($_POST['fromAjax'] == 1) {
        $ids = $_POST['ids'];
        foreach($ids as $id) {
            $attachment = new Attachment($id);
            $removeAttachment = $attachment->deleteAttachment();
            if($removeAttachment) {
                $result = array(
                    "success" => true,
                );
            } else {
                $result = array(
                    "success" => false,
                    "msg" => "Errore nella query"
                );
            }
        }

        header("Content-type: application/json");
        echo json_encode($result);
        exit();
    }