<?php
    session_start();
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', '');
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'ticket_manager');
    define('ABSOLUTE_PATH', realpath(dirname(__DIR__))); // Root del progetto
    define('BASE_URL', '/Riccardo/Projects/Ticket Manager/');

    require_once __DIR__."/DB.class.php";
    require_once __DIR__."/classes/User.php";
    require_once __DIR__."/classes/Issue.php";
    require_once __DIR__."/classes/Comment.php";
    require_once __DIR__."/classes/Attachment.php";

    function normalize_files_array($files = []) {

        $normalized_array = [];

        foreach($files as $index => $file) {

            if (!is_array($file['name'])) {
                $normalized_array[$index][] = $file;
                continue;
            }

            foreach($file['name'] as $idx => $name) {
                $normalized_array[$index][$idx] = [
                    'name' => $name,
                    'type' => $file['type'][$idx],
                    'tmp_name' => $file['tmp_name'][$idx],
                    'error' => $file['error'][$idx],
                    'size' => $file['size'][$idx]
                ];
            }

        }

        return $normalized_array;

    }

    $_FILES = normalize_files_array($_FILES);