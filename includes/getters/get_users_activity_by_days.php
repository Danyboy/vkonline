<?php
include 'get_charts.php';
$myOnlineHistiry = new OnlineHistoryCharts();

$users = json_decode($_GET['users']);
$my_date = $_GET['d'];
$current_user = $_GET['u'];

$data_by_days = $myOnlineHistiry->get_user_activity_by_days($users, $my_date, $myOnlineHistiry->get_current_id($current_user));
$my_names = $myOnlineHistiry->get_current_users_name($users, $myOnlineHistiry->get_current_id($current_user));
echo "{ 
	\"data\": $data_by_days,
	\"names\": $my_names
}";
?>