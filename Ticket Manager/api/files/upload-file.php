<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
    require_once __DIR__."/../../lib/config.php";
    if(isset($_FILES)) {
        $attachments = $_FILES;

        $files = [];

        // Loop through each file
        foreach($attachments['file'] as $attachment) {
            // Get the temp file path
            $tmpFilePath = $attachment['tmp_name'];
            // Get the values
            $name = $attachment['name'];
            $size = $attachment['size'];
            $extension = pathinfo($name, PATHINFO_EXTENSION);
    
            // Make sure we have a file path
            if ($tmpFilePath != ""){
    
                // Add them to the server
                $addAttachment = Attachment::addAttachment($name, $extension, $size);
                // Setup our new file path
                $newFilePath = $addAttachment->getPath();
                
                // Upload the file into the dir
                move_uploaded_file($tmpFilePath, $newFilePath);

                // Salvo i metadati del file
                $files[] = array(
                    "id" => $addAttachment->getId(),
                    "url" => $addAttachment->getURL(),
                );
            }
        }

        $result = array(
            "success" => true,
            "data" => $files,
        );

        header("Content-type: application/json");
        echo json_encode($result);
        exit();
    }