<?php
    require_once __DIR__."/../../lib/config.php";

    $result = array(
        "success" => false,
        "data" => array(),
    );
    if($_GET['start'] != 'Invalid date' && $_GET['end'] != 'Invalid date') {
        $start = $_GET['start'];
        $end = $_GET['end'];
        $issues = Issue::listDateInterval($start, $end);
    } else {
        $issues = Issue::list();
    }

    $issues = array_filter($issues, function($issue) {
        $save = true;

        //Se sono Editor
        if($_SESSION['role'] == 2) {
            //Se la issue non è mia ed è privata
            if($_SESSION['id'] != $issue->getAuthor()->getId() && $issue->getPrivate() == 1) {
                //La nascondo
                $save = false;
            }
        }
        
        //Se sono Viewer 
        if($_SESSION['role'] == 3) {
            //Se la issue non è mia
            if($_SESSION['id'] != $issue->getAuthor()->getId()) {
                //La nascondo
                $save = false;
            }
        }

        return $save;
    });
    $issues = array_values($issues);
    $result['success'] = true;
    $result['data'] = $issues;
    
    header("Content-type: application/json");
    echo json_encode($result);
    exit();