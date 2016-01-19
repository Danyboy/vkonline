<?php 
include 'get_charts.php';


$myOnlineHistiry = new OnlineHistoryCharts();

$users = json_decode($_GET['users']);
$my_date = $_GET['d'];
$current_user = $_GET['u'];

$data_by_day_with_names = $myOnlineHistiry->get_user_activity_by_day_with_names($users, $my_date, $myOnlineHistiry->get_current_id($current_user));

//echo $data_by_day_with_names;
echo "{ 
    \"data\": $data_by_day_with_names[0],
    \"names\": $data_by_day_with_names[1]
    }";

?>