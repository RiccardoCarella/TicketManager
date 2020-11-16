<?php
    require_once __DIR__."/../../lib/config.php";

    $result = array(
        "success" => false,
        "data" => array(),
    );

    $users = User::list();

    $result['success'] = true;
    $result['data'] = array();

    foreach ($users as $user) {
        $result['data'][] = array(
            "id" => $user->getId(),
            "username" => $user->getUsername(),
            "password" => $user->getPassword(),
            "role" => $user->getRole(),
            "name" => $user->getName(),
            "surname" => $user->getSurname(),
            "mobile" => $user->getMobile(),
            "email" => $user->getEmail()
        );
    }
    header("Content-type: application/json");
    echo json_encode($result);
    exit();