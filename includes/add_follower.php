<?php 
include '../online_table.php';

class OnlineHistoryFollower extends OnlineHistory{

	function add_follower($user){

		$check_user_query = "SELECT COUNT(id) FROM followers WHERE id={$user};";
                $insert_follower_query = "INSERT INTO followers (id) VALUES ($user);";
		if ($this->user_non_exists($check_user_query)){
			//echo $insert_follower_query;
			$this->query_to_json($insert_follower_query);
		}
	}

}

$user = $_POST['user'];
$myOnlineHistiry = new OnlineHistoryFollower();
$myOnlineHistiry->add_follower($user);
?>