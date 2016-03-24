<?php 
include 'includes/start.php';

$my_date = $_GET['d'];
$users = json_decode($_GET['users']);
$current_user = $_GET['u'];
?>

<script src="//code.highcharts.com/stock/highstock.js"></script>

<?php
//<script src="//code.highcharts.com/stock/modules/exporting.js"></script>

include 'includes/charts/chart_day_by_users.php';
include 'includes/charts/chart_all_users_activity.php';
include 'includes/charts/chart_interval_stock.php';
?>

<?php 
include 'includes/end.php';
?>