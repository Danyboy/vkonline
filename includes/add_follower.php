<?php 
include '../online_table.php';

class OnlineHistoryFollower extends OnlineHistory{

	function add_follower($user){
		//error_log("test user {$user}");

		$check_user_query = "SELECT COUNT(id) FROM followers WHERE id={$user};";
		$insert_follower_query = "INSERT INTO followers (id) VALUES ($user);";
		$create_users_db = "CREATE TABLE users{$user} (id int primary key, name varchar, link varchar);";
		$create_online_db = "CREATE TABLE online{$user} (user_id int, status timestamp, foreign key (user_id) references users{$user} (id));";
		$create_online_index = "CREATE INDEX online{$user}_status ON online{$user} (status);";

		if ($this->user_non_exists($check_user_query, $user)){
			//TODO check priv
			$this->query_to_json($insert_follower_query);
			$this->query_to_json($create_users_db);
			$this->query_to_json($create_online_db);
			$this->query_to_json($create_online_index);
			$this->add_user_activity($user); //check working
		}
	}
//Add follower
//curl --data "user=749972" http://91.232.225.25:43480/includes/add_follower.php
// curl --data "user=1502541" http://vko.efnez.ru/includes/add_follower
//Check
//SELECT id FROM followers;
}

$user = $_POST['user'];
//$user = $_GET['user'];
$myOnlineHistiry = new OnlineHistoryFollower();
$myOnlineHistiry->add_follower($user);
?>