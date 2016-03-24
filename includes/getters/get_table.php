<?php 
include '../../online_table.php';
$myOnlineHistiry = new OnlineHistory();

$myOnlineHistiry->show_today_online_users($_GET['d'],$_GET['u']);
?>