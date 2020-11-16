<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once "templates/header.php";?>

</head>

<body>
    <div class="container h-100">
        <div class="row">
            <div class="col-5 mx-auto pt-5">
                <h1 class="font-weight-light text-center">Login</h1>
                <?php
                    require_once("templates/login-form.php")
                    ?>
            </div>
        </div>
    </div>
    <?php
        require_once("templates/scripts.php");
    ?>
    <script src="js/login.js"></script>
</body>

</html>