<?php 

class OnlineHistoryFollower extends OnlineHistory{

	function add_follower($user){

		$check_user_query = "SELECT COUNT(id) FROM followers WHERE id={$user};";
                $insert_follower_query = "INSERT INTO followers (id) VALUES ($user);";
		if (user_non_exists($check_user_query)){
			$this->query_to_json($insert_follower_query);
		}
	}

}

?>