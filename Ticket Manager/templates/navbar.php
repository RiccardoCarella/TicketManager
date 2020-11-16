<?php
    require_once __DIR__."/../lib/config.php";

    $current_page = $_SERVER['SCRIPT_NAME'];
    $current_page = str_replace(BASE_URL, '', $current_page);
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02"
        aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
            <li
                class="nav-item <?=$current_page === 'issues.php' || $current_page === 'add-issue-view.php' || $current_page === 'edit-issue-view.php' ? 'active' : ''?>">
                <a class="nav-link" href="issues.php">Issues</a>
            </li>
            <li class="nav-item <?=$current_page === 'users.php' ? 'active' : ''?>" style="display: <?= $_SESSION['role'] != 1 ? "none" : "" ?>">
                <a class="nav-link" href="users.php">Utenti</a>
            </li>
        </ul>
        <ul class="navbar-nav mr-2">
            <li class="nav-item dropdown">

                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <span class="mr-1"><?php echo($_SESSION['username'])?></span>
                    <i class="fa fa-user fa-lg"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="profile.php">Profile</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="lib/logout.php">Logout</a>
                </div>
            </li>
        </ul>
    </div>
</nav>