<?php
include 'get_charts.php';
$myOnlineHistiry = new OnlineHistoryCharts();

$users = json_decode($_GET['users']);
$my_date = $_GET['d'];
$current_user = $_GET['u'];

$all_data = $myOnlineHistiry->get_activity_by_user($users, $myOnlineHistiry->get_current_id($current_user));
$my_names = $myOnlineHistiry->get_current_users_name($users, $myOnlineHistiry->get_current_id($current_user));

echo "{ 
	\"data\": $all_data,
	\"names\": $my_names
}";
?>