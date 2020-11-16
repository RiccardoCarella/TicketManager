<?php
  require_once("lib/config.php");
  require_once("lib/check-session.php");

  $users = User::nameList();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php
       require_once("templates/header.php");
    ?>
</head>

<body>
    <?php
        require_once("templates/navbar.php")
    ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col text-center mt-5">    
                <div><i class="fa fa-user-circle fa-5x"></i></div>
                <div class="mt-5 d-flex justify-content-center">
                    <div class="col-4">
                        <p class="h2 text-primary">Nome</p>
                        <?php 
                            if($_SESSION['name']){
                                echo("<div class='h4'>".$_SESSION['name']."</div>");
                            }else {
                                echo("<div class='h4'>-</div>");
                            } 
                        ?>
                    </div> 
                    <div class="col-4">
                        <p class="h2 text-primary">Cognome</p>
                        <?php 
                            if($_SESSION['surname']){
                                echo("<div class='h4'>".$_SESSION['surname']."</div>");
                            }else {
                                echo("<div class='h4'>-</div>");
                            } 
                        ?>
                    </div> 
                </div>
                <div class="mt-5 d-flex justify-content-center">
                    <div class="col-4">
                        <p class="h2 text-primary">E-mail</p> 
                        <?php 
                            if($_SESSION['email']){
                                echo("<div class='h4'>".$_SESSION['email']."</div>");
                            }else {
                                echo("<div class='h4'>-</div>");
                            } 
                        ?>
                    </div> 
                    <div class="col-4">
                        <p class="h2 text-primary">Telefono</p> 
                        <?php 
                            if($_SESSION['mobile']){
                                echo("<div class='h4'>".$_SESSION['mobile']."</div>");
                            }else {
                                echo("<div class='h4'>-</div>");
                            } 
                        ?>
                    </div> 
                </div>
                <div class="mt-5 d-flex justify-content-center">
                    <div class="col-4">
                        <p class="h2 text-primary">Username</p>
                        <?php 
                            if($_SESSION['username']){
                                echo("<div class='h4'>".$_SESSION['username']."</div>");
                            }else {
                                echo("<div class='h4'>-</div>");
                            } 
                        ?>
                    </div>
                </div>
                <!-- <div class="mt-5">
                    <p class="h2 text-primary">Password</p> < ?php echo("<div class='h4'>".$_SESSION['password']."</div>") ?>
                </div> -->
            </div>
        </div>
    </div>
    <?php
        require_once("templates/scripts.php");
    ?>
</body>

</html>