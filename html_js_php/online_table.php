<?php 

class OnlineHistory
{
	public $id="749972";
	public $json;
	public $dbconn; 	//Why working if remove this?
	private $vkapi_token='cd7781010610415dd8d3a039d5cbaedc0309b19ff19c58d3e8ab67294fa7ab85ed5d29837bedd1a05758a';
	public $result;

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
		$password = "***REMOVED***";
		$hostname = "localhost"; 
		$my_db = "vk";

		//connection to the database
		$this->dbconn = pg_connect("dbname=vk user=root password=***REMOVED***")
		  or die("Unable to connect to PostgreSQL");
		//echo "Connected to PostgreSQL<br>";
	}

	function my_query($query){
		$this->connect();
		$result = pg_query($this->dbconn, "$query");

		if (!$result) {
			echo "Произошла ошибка.\n";
			echo pg_last_error($this->dbconn);
			exit;
		}
		
		return $result;
	}
	
	function query_to_json($query){
		$result = $this->my_query($query);
		$myarray = array();

		while ($row = pg_fetch_row($result)) {
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
				WHERE user_id IN ({$users_string}) 
				GROUP BY hours, user_id 
				ORDER BY user_id ASC, hours ASC";
		
		return $this->query_to_json($count_query);
	}
	
	function get_user_online_minutes_by_hourse(){
		//Temporary not used request
		$user_online_minutes_by_hourse = "SELECT user_id, EXTRACT(hour FROM status) AS hours, COUNT (EXTRACT(hour FROM status)) * 5 AS count 
                                FROM user_online 
                                WHERE user_id IN (749972) AND DATE(status) = '11-9-2015' GROUP BY hours, user_id ORDER BY user_id, hours ASC;";
                                
		$user_online_minutes = "SELECT user_id, COUNT (EXTRACT(hour FROM status)) * 5 AS count 
                                FROM user_online 
                                WHERE user_id IN (749972) AND DATE(status) = '11-9-2015' GROUP BY user_id;";
		return $this->query_to_json($user_online_minutes_by_hourse);;
	}
	
	function get_users_online_hours($my_date){
	
		if (DateTime::createFromFormat('d.m.y', $my_date) == FALSE){
		    date_default_timezone_set('Europe/Moscow');
		    $my_date = date("d.m.y");
		}
		
		//TODO change DATE
		$count_query_without_data = "SELECT user_id, COUNT (EXTRACT(hour FROM status)) * 5 AS count 
                                FROM user_online 
                                WHERE DATE(status) = '11-9-2015' GROUP BY user_id;";
                                
                $count_query = "SELECT user_id, link, name, COUNT (EXTRACT(hour FROM status)) * 5 AS minutes 
                                FROM user_online JOIN users ON (user_online.user_id = users.id)
				WHERE DATE(status) = '{$my_date}' 
				GROUP BY user_id, link, name ORDER BY minutes DESC;";
		
		return $this->query_to_json($count_query);
	}
	
	function show_today_online_users($my_date){
		foreach (json_decode($this->get_users_online_hours($my_date)) as $row) {
		    $my_time = date('H:i', mktime(0,$row[3]));
			echo "<tr>
			<td><a href='http://vk.com/id{$row[0]}'>
			    <img src='{$row[1]}' alt='$row[2]'> {$row[2]}</a></td> 
			<td><a href='./u?users=[{$row[0]},749972,42606657]'>{$row[2]} online activity charts</td>
			<td>{$my_time} m</td>
		      </tr>";
	        }
	}
	
	function save_online_users($value){
		if (strcmp("{$value->online}", "0") !== 0){
            	    $this->save_to_db($value);
		}
	}

        function save_to_db($value){
                $my_name = $value->first_name . " " . $value->last_name;
		
	        $check_user_query = "SELECT COUNT(id) FROM users WHERE id={$value->uid};";
	        $insert_user_query = "INSERT INTO users (id, name, link) VALUES ({$value->uid}, '{$my_name}', '{$value->photo_50}');";
	        $insert_date_query = "INSERT INTO user_online (user_id, status) VALUES ({$value->uid}, CURRENT_TIMESTAMP(0));";

                //echo ($insert_date_query . "\n");
                $is_user_exists = (strcmp($this->query_to_json($check_user_query), '[["0"]]') == 0);
            
                if ($is_user_exists){
            	    //echo ($insert_user_query . "\n");
                    $myqr = $this->my_query($insert_user_query);
		    //echo "{$myqr}";
                }

                $this->my_query($insert_date_query);
        }

	function get_previous_dates($int, $my_date){
	        date_default_timezone_set('Europe/Moscow');
		
		$date = isset($my_date) ? date_create_from_format('d.m.y', $my_date) : date_create_from_format('d.m.y' , date('d.m.y'));
		date_sub($date, date_interval_create_from_date_string($int . ' days'));
		return date_format($date, 'd.m.y');
		//return $date;
	}

	function show_previous_dates($my_dates){
		for ($i = 5; $i > 0; $i --){
		    $current_date = $this->get_previous_dates($i, $my_dates);
		    echo "<li><a href='/?d=" . $current_date . "'>" . $current_date . "</a></li>";
		}
	}
	
        function add_users_activity(){
		$json = $this->get_online();
                $obj = json_decode($json);
                foreach( $obj->response as $value){
		    $this->save_online_users($value);
                }
	}
}

//$myOnlineHistiry = new OnlineHistory();
//$myOnlineHistiry->get_previous_dates(5, "4-12-15");
//$myOnlineHistiry->show_previous_dates($_GET['d']);

//For adding date in table run as
//php online_table.php add_data
if (strcmp("{$argv[1]}", "add_data") == 0){
    $myOnlineHistiry = new OnlineHistory();
    $myOnlineHistiry->add_users_activity();
} 

?>