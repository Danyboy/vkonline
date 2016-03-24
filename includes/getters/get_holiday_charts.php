<?php 
include '../../online_table.php';

class OnlineHistoryCharts extends OnlineHistory{
	
	function get_all_users_activity_by_day($my_date, $current_user){
		$my_date_start = $this->get_correct_date_interval($my_date)[0];
		$my_date_end = $this->get_correct_date_interval($my_date)[1];
		
		$count_query = "SELECT '2015', to_char(status,'MM-DD') AS day, to_char(COUNT (EXTRACT(hour FROM status))::float / (12 * 1488), 'FM99.00') AS count 
		FROM online749972
		WHERE status between '{$my_date_start}' and '{$my_date_end} 23:59:59'
		GROUP BY day, status::timestamp::date 
		ORDER BY status::timestamp::date;"; 

		$my_date_end_prev = $this->get_previous_dates(365,$my_date_end);
		$my_date_start_prev = $this->get_previous_dates(365,$my_date_start);

		$count_query_prev = "SELECT '2014', to_char(status,'MM-DD') AS day, to_char(COUNT (EXTRACT(hour FROM status))::float / (12 * 1268), 'FM99.00') AS count 
		FROM online749972
		WHERE status between '{$my_date_start_prev}' and '{$my_date_end_prev} 23:59:59'
		GROUP BY day, status::timestamp::date 
		ORDER BY status::timestamp::date;";

		return json_encode(array_merge(json_decode($this->query_to_json($count_query)),json_decode($this->query_to_json($count_query_prev))));
	}	
}

$users = json_decode($_GET['users']);
$my_date = $_GET['d'];
$current_user = $_GET['u'];
$myOnlineHistiry = new OnlineHistoryCharts();
echo $myOnlineHistiry->get_all_users_activity_by_day($my_date, $myOnlineHistiry->get_current_id($current_user));
?>