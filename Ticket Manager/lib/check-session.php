<?php
    require_once __DIR__."/DB.class.php";
    $db = new Db();
    $result = $db->row('SELECT user_password, user_username FROM users WHERE user_id = :id', array(
        'id' => $_SESSION['id'],
    ));
    
    $user = new User($_SESSION['id']);
    $_SESSION['username'] = $user->getUsername();
    $_SESSION['password'] = $user->getPassword();
    $_SESSION['role'] = $user->getRole();
    $_SESSION['name'] = $user->getName();
    $_SESSION['surname'] = $user->getSurname();
    $_SESSION['fullName'] = $user->getFullName();
    $_SESSION['mobile'] = $user->getMobile();
    $_SESSION['email'] = $user->getEmail();

    if (!$result) {
        header("Location: login.php?error=nomatchfound");
        exit();
    }