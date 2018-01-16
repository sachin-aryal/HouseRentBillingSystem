<?php
ob_start();
include_once 'db_connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Deepa Apartment PVT. LTD</title>
    <link rel="stylesheet" href="css/bootstrap.css" type="text/css">
    <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="js/app.js" type="text/javascript"></script>
    <script src="js/jquery.min.js" type="text/javascript"></script>
    <script src="js/bootstrap.js" type="text/javascript"></script>
    <script src="js/notify.min.js" type="text/javascript"></script>
    <script src="js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            <?php if(isset($_SESSION["message"])){?>
            $.notify('<?php echo $_SESSION["message"] ?>', '<?php echo $_SESSION['messageType'] ?>');
            <?php unset($_SESSION["message"]);unset($_SESSION["messageType"]); } ?>
        });
    </script>
</head>
<body>
<div class="nav-side-menu">
    <div class="brand">HomeRent Manager</div>
    <i class="fa fa-bars fa-2x toggle-btn" data-toggle="collapse" data-target="#menu-content"></i>

    <div class="menu-list">

        <ul id="menu-content" class="menu-content collapse out">

            <li  data-toggle="collapse" class="collapsed">
                <a href="index.php"><i class="fa fa-home fa-lg"></i> Home </a>
            </li>
            <li  data-toggle="collapse" class="collapsed">
                <a href="rent.php"><i class="fa fa-money fa-lg"></i> Rent </a>
            </li>
            <li data-toggle="collapse" class="collapsed">
                <a href="people.php"><i class="fa fa-group fa-lg"></i> People in Rent</a>
            </li>
        </ul>
    </div>
</div>
</body>
</html>