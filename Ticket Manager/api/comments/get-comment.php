<?php
    require_once __DIR__."/../../lib/config.php";

    $result = array("success" => false, "data" => array());

    $comment = new Comment($_GET['id']);
    
    $result['success'] = true;
    $result['data'] = $comment;
    header("Content-type: application/json");
    echo json_encode($result);
    exit();