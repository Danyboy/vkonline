<?php 
include '../online_table.php';

class OnlineHistoryFollower extends OnlineHistory{

	function add_follower($user){

		$check_user_query = "SELECT COUNT(id) FROM followers WHERE id={$user};";
                $insert_follower_query = "INSERT INTO followers (id) VALUES ($user);";
		$create_users_db = "CREATE TABLE users{$user} (id int primary key, name varchar, link varchar);";
		$create_online_db = "CREATE TABLE online{$user} (user_id int, status timestamp, foreign key (user_id) references users{$user} (id));";

		if ($this->user_non_exists($check_user_query)){
			//TODO check priv
			$this->query_to_json($insert_follower_query);
			$this->query_to_json($create_users_db);
			$this->query_to_json($create_online_db);
		}
	}
//Add follower
//curl --data "user=749972" http://91.232.225.25:43480/includes/add_follower.php
//Check
//SELECT id FROM followers;
}

$user = $_POST['user'];
$myOnlineHistiry = new OnlineHistoryFollower();
$myOnlineHistiry->add_follower($user);
?>