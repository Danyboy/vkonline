<?php 
include '../../online_table.php';

class OnlineHistoryCharts extends OnlineHistory{
	
	function get_all_users_activity_by_day($my_date){
		
		$count_query = "SELECT EXTRACT(hour FROM status) AS hours, COUNT (EXTRACT(hour FROM status)) * 5 AS count 
		FROM user_online 
		WHERE DATE(status) = '11-8-2015' GROUP BY hours ORDER BY hours;";
		
		$most_popular_hour = "SELECT EXTRACT(hour FROM status) AS hours, COUNT (user_id)
		FROM user_online 
		GROUP BY hours 
		ORDER BY hours;";

		return $this->query_to_json($count_query);
	}	

	function get_activity_for_all_users_and_dates(){
		//Long query - collect all data
		
		$users_string = implode(",", $users);
		$count_query = "SELECT EXTRACT(hour FROM status) AS hours, COUNT (EXTRACT(hour FROM status)) * 5 AS count 
		FROM user_online 
		GROUP BY hours ORDER BY hours;";
		
		return $this->query_to_json($count_query);
	}

	function get_activity_days_by_users($users){
		
		$users_string = implode(",", $users);
		$count_query = "Select user_id, Count(*)
		From (
		SELECT user_id, status::timestamp::date AS day
		FROM user_online 
		WHERE user_id IN ({$users_string}) 
		GROUP BY day, user_id 
		ORDER BY day, user_id) 
		AS mycount
		GROUP BY user_id
		ORDER BY user_id;";
		
		return $this->query_to_json($count_query);
	}

	function get_activity_by_user($users, $current_user){
		
		$users_string = implode(",", $users);
		$count_query = "SELECT user_id, EXTRACT(hour FROM status) AS hours, COUNT (EXTRACT(hour FROM status))::float / 12 AS count 
		FROM online{$current_user} 
		WHERE user_id IN ({$users_string}) 
		GROUP BY hours, user_id 
		ORDER BY user_id, hours;";
		
		return $this->query_to_json($count_query);
	}

	function get_user_activity_by_day($users, $my_date, $current_user){
		
		$my_date = $this->get_correct_date($my_date);		
		
		$users_string = implode(",", $users);
		$count_query = "SELECT user_id, EXTRACT(hour FROM status) AS hours, COUNT (EXTRACT(hour FROM status)) * 5 AS count 
		FROM online{$current_user}
		WHERE user_id IN ({$users_string}) AND DATE(status) = '{$my_date}' 
		GROUP BY hours, user_id 
		ORDER BY user_id, hours;";
		
		return $this->query_to_json($count_query);
	}
	
	function get_user_activity_by_days($users, $my_date, $current_user){
		$my_date_start = $this->get_correct_date_interval($my_date)[0];
		$my_date_end = $this->get_correct_date_interval($my_date)[1];
		
		$users_string = implode(",", $users);
		$count_query = "SELECT user_id, status::timestamp::date AS day, COUNT (EXTRACT(hour FROM status))::float / 12 AS count 
		FROM online{$current_user}
		WHERE user_id IN ({$users_string}) AND status between '{$my_date_start}' and '{$my_date_end} 23:59:59' 
		GROUP BY user_id, day 
		ORDER BY user_id, day;";
		
		return $this->query_to_json($count_query);
	}

	function get_current_users_name($users, $current_user){
		$users_string = implode(",", $users);
		$count_query = "SELECT name FROM users{$current_user} WHERE id IN ({$users_string}) ORDER BY id;";
		return $this->query_to_json($count_query);
	}

	function get_user_activity_by_day_with_names($users, $my_date, $current_user){
		$data = json_decode($this->get_user_activity_by_day($users, $my_date, $current_user));
		$users = array($data[0][0]);
		foreach ($data as $user){
			if (end($users) != $user[0]){
				array_push($users, $user[0]);
			}
		}
		return array(json_encode($data), $this->get_current_users_name($users, $current_user));
	}

}
?>