<?php
ob_start();
include_once 'db_connect.php';
require __DIR__.'/vendor/autoload.php';
$calendar = new Fivedots\NepaliCalendar\Calendar(new \Fivedots\NepaliCalendar\NepaliDataProvider());
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Deepa Private Home</title>
    <link rel="stylesheet" href="css/bootstrap.css" type="text/css">
    <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="js/app.js" type="text/javascript"></script>
    <script src="js/jquery.min.js" type="text/javascript"></script>
    <script src="js/bootstrap.js" type="text/javascript"></script>
    <script src="js/notify.min.js" type="text/javascript"></script>
    <script src="js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="js/datatable.sum.js" type="text/javascript"></script>
</head>
<body>
<div class="nav-side-menu">
    <div class="brand">Deepa Private Home</div>
    <i class="fa fa-bars fa-2x toggle-btn" data-toggle="collapse" data-target="#menu-content"></i>

    <div class="menu-list">

        <ul id="menu-content" class="menu-content collapse out">

            <li>
                <a href="index.php"><i class="fa fa-home fa-lg"></i> Rent </a>
            </li>
            <li>
                <a href="electricity_rate.php"><i class="fa fa-money fa-lg"></i> Electricity Rate </a>
            </li>
            <li>
                <a href="people.php"><i class="fa fa-group fa-lg"></i> Tenant</a>
            </li>
        </ul>
    </div>
</div>
</body>
</html>