
<?php 

class OnlineHistory
{
	public $id="749972";
	public $json;
	private $dbhandle;
	private $vkapi_token='cd7781010610415dd8d3a039d5cbaedc0309b19ff19c58d3e8ab67294fa7ab85ed5d29837bedd1a05758a';
	public $result;
	//public $dbhandle;

	function send_req($url){
	     $ch = curl_init(); 
	     $timeout = 5; 
	     curl_setopt($ch, CURLOPT_URL, $url);
	     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	     curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

	     $data = curl_exec($ch);
	     curl_close($ch);
	     return $data;
	}
	
	function get_friends() { 
	     $url="https://api.vk.com/method/friends.get?user_id={$this->id}"; //https://api.vk.com/method/friends.get?user_id=$id\&access_token=$vkapi_token
	     return $this->send_req($url);
	}

	function get_online() { 
	     $url="https://api.vk.com/method/users.get?user_ids=" . $this->get_friends() . "," . $this->id . ",\&fields=online,photo_50,\&lang=en";
	     return $this->send_req($url);
	}

	function connect() {	
		$username = "root";
		$password = "simsimopen";
		$hostname = "localhost"; 
		$my_db = "vk";

		//connection to the database
		$this->dbconn = pg_connect("dbname=vk user=root password=simsimopen")
		  or die("Unable to connect to PostgreSQL");
		//echo "Connected to PostgreSQL<br>";
	}

	function my_query($query){
		$this->connect();
		$this->result = pg_query($this->dbconn, "$query");
		if (!$this->result) {
			echo "Произошла ошибка.\n";
			echo pg_last_error($this->dbconn);
			exit;
		}
	}
	
	function query_to_json($query){
		$this->my_query($query);
		$myarray = array();
		while ($row = pg_fetch_row($this->result)) {
		    $myarray[] = $row;
		}

		$json = json_encode($myarray);
		return $json;
	}

	function get_minutes_by_ids($users){
		$this->connect();
		
		$users_string = implode(",", $users);
		$count_query = "SELECT user_id, EXTRACT(hour FROM status) AS hours, COUNT (EXTRACT(hour FROM status)) / 12 AS count 
				FROM user_online 
				WHERE user_id IN ({$users_string}) GROUP BY hours, user_id ORDER BY user_id, hours ASC";
		
		return $this->query_to_json($count_query);
	}
	
	function remove_offline_users($value){
		if (strcmp("{$value->online}", "0") !== 0){
            	    $this->save_to_db($value);
		}
	}

        function save_to_db($value){
                $my_name = $value->first_name . " " . $value->last_name;

	        $insert_user_query = "INSERT INTO users (id, name, link) VALUES ($value->uid, '{$my_name}', '{$value->photo_50}';";
	        $insert_date_query = "INSERT INTO user_online (user_id, status) VALUES ($value->uid, CURRENT_TIMESTAMP(0));";

//                echo ($insert_user_query . "\n");
                echo ($insert_date_query . "\n");
                //my_query($insert_user_query);
                //my_query($insert_date_query);
        }
	
        function add_users_activity(){
		$json = $this->get_online();
                $obj = json_decode($json);
                foreach( $obj->response as $value){
		    $this->remove_offline_users($value);
                }
	}
}

$myOnlineHistiry = new OnlineHistory();
$myOnlineHistiry->add_users_activity();

//<html><body></body></html>
?>

