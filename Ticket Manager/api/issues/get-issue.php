<?php
    require_once __DIR__."/../../lib/config.php";

    $result = array("success" => false, "data" => array());

    $issue = new Issue($_GET['id']);

    $result['success'] = true;
    $result['data'] = $issue;
    
    header("Content-type: application/json");
    echo json_encode($result);
    exit();