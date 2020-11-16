<?php
    require_once __DIR__."/config.php";
    require_once __DIR__."/check-session.php";
    // remove all session variables
    session_unset();

    // destroy the session
    session_destroy();

    header("Location: ../login.php?logout:success");
    exit();
