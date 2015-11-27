<?php 

class OnlineHistory
{
	//public $id="385525";
	public $id="749972";
	public $current_id="749972";
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

	function get_followers(){
		$count_query = "SELECT id FROM followers";
		return $this->query_to_json($count_query);
	}
	
	function get_friends($current_user) {
	     $url="https://api.vk.com/method/friends.get?user_id={$current_user}"; //https://api.vk.com/method/friends.get?user_id=$id\&access_token=$vkapi_token
	     return $this->send_req($url);
	}

	function get_online_for_all_followers(){
	     foreach (json_decode($this->get_followers()) as $current_user) {	
		    $this->get_online($current_user);
	     }
	}

	function get_online($current_user){
	     //echo $current_user;
	     $chunked = array_chunk(json_decode($this->get_friends($current_user))->response, 400);
	     $result = array();
	     $owner = true;

	     foreach ($chunked as $users){
		if($owner){
		    //TWO BUGS with intersects people
		    array_push($users, $current_user); //BUG not need add owner, if people has each over in friends TEMPORARY
		    $owner = false;
		}
		$json = json_decode($this->get_online_part(implode(",",$users)))->response;
		$result = array_merge($result,$json); 
	     }

	     return $result;

	}

	function get_online_part($str) { 
	     $url="https://api.vk.com/method/users.get?user_ids=" . $str . ",\&fields=online,photo_50,\&lang=en";
	     return $this->send_req($url);
	}
	
	function connect() {	
		$username = "root";
		$password = "simsimopen";
		$hostname = "localhost"; 
		$my_db = "vk";

		//connection to the database
		$this->dbconn = pg_connect("dbname={$my_db} user={$username} password={$password}")
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
                                WHERE user_id IN ($this->id) AND DATE(status) = '11-9-2015' GROUP BY hours, user_id ORDER BY user_id, hours ASC;";
                                
		$user_online_minutes = "SELECT user_id, COUNT (EXTRACT(hour FROM status)) * 5 AS count 
                                FROM user_online 
                                WHERE user_id IN ($this->id) AND DATE(status) = '11-9-2015' GROUP BY user_id;";
		return $this->query_to_json($user_online_minutes_by_hourse);;
	}
	
	function get_users_online_hours($my_date){
	
		$my_date = $this->get_correct_date($my_date);
		
		$count_query_without_data = "SELECT user_id, COUNT (EXTRACT(hour FROM status)) * 5 AS count 
                                FROM user_online 
                                WHERE DATE(status) = '11-9-2015' GROUP BY user_id;";
                                
                $count_query = "SELECT user_id, link, name, COUNT (EXTRACT(hour FROM status)) * 5 AS minutes 
                                FROM user_online JOIN users ON (user_online.user_id = users.id)
				WHERE DATE(status) = '{$my_date}' 
				GROUP BY user_id, link, name ORDER BY minutes DESC;";
		
		return $this->query_to_json($count_query);
	}

	function get_users_online_hours_2($my_date){
	
		$my_date = $this->get_correct_date($my_date);
		
		$count_query_without_data = "SELECT user_id, COUNT (EXTRACT(hour FROM status)) * 5 AS count 
                                FROM online{$this->current_id}
                                WHERE DATE(status) = '11-9-2015' GROUP BY user_id;";
                                
                $count_query = "SELECT user_id, link, name, COUNT (EXTRACT(hour FROM status)) * 5 AS minutes 
                                FROM user_online JOIN users{$current_id} ON (online{$this->current_id}.user_id = users{$current_id}.id)
				WHERE DATE(status) = '{$my_date}'
				GROUP BY user_id, link, name ORDER BY minutes DESC;";
		
		return $this->query_to_json($count_query);
	}

	function show_today_online_users($my_date){
		foreach (json_decode($this->get_users_online_hours($my_date)) as $row) {
		    $my_time = date('H \ч i \м', mktime(0,$row[3]));
			echo "<tr>
			<td><input type='checkbox' name='mycheckbox' value='{$row[0]}'></td>
			<td><a href='http://vk.com/id{$row[0]}'>
			    <img src='{$row[1]}' alt='$row[2]'></a>
			    <a href='./u?users=[{$row[0]},$this->id,749972]&d={$my_date}'>
			    {$row[2]}	<img src='Chart-icon.png' alt='$row[2]' align='right'></a></td>
			<td><a href='c?u={$row[0]}'>
			    {$my_time} <img src='includes/heart_25.png' alt='$row[2]' alight='right'></a></td>
		      </tr>";
	        }
	}

	function save_online_users($value){
		if (strcmp("{$value->online}", "0") !== 0){
            	    $this->save_to_db($value);
		}
	}

        function save_to_user_table($value, $current_user){
		//TODO check priviligies
		//$create_table = "CREATE TABLE online{$current_user} user_id INT, status TIMESTAMP, FOREIGN KEY (user_id) REFERENCES users (id));

	        //$insert_date_query = "INSERT INTO online{$current_user} (user_id, status) VALUES ({$value->uid}, CURRENT_TIMESTAMP(0));";
	        $insert_date_query = "INSERT INTO user_online{$current_user} (user_id, status) VALUES ({$value->uid}, CURRENT_TIMESTAMP(0));";
		$this->my_query($insert_date_query);
		$this->my_query($create_table);
	}

        function save_to_db($value, $current_user){
                $my_name = $value->first_name . " " . $value->last_name;
		
	        $check_user_query = "SELECT COUNT(id) FROM users WHERE id={$value->uid};";
	        $insert_user_query = "INSERT INTO users (id, name, link) VALUES ({$value->uid}, '{$my_name}', '{$value->photo_50}');";
	        $insert_date_query = "INSERT INTO user_online (user_id, status) VALUES ({$value->uid}, CURRENT_TIMESTAMP(0));";

                if ($this->user_non_exists($check_user_query)){
                    $myqr = $this->my_query($insert_user_query);
                }

                $this->my_query($insert_date_query);
        }

        function save_to_db_2($value, $current_user){
                $my_name = $value->first_name . " " . $value->last_name;
		
	        $check_user_query = "SELECT COUNT(id) FROM users{$current_user} WHERE id={$value->uid};";
	        $insert_user_query = "INSERT INTO users{$current_user} (id, name, link) VALUES ({$value->uid}, '{$my_name}', '{$value->photo_50}');";
	        $insert_date_query = "INSERT INTO online{$current_user} (user_id, status) VALUES ({$value->uid}, CURRENT_TIMESTAMP(0));";

                if ($this->user_non_exists($check_user_query)){
                    $myqr = $this->my_query($insert_user_query);
                }

                $this->my_query($insert_date_query);
        }
	
	function user_non_exists($query){
                return (strcmp($this->query_to_json($query), '[["0"]]') == 0);
	}

	function get_correct_date($my_date){
		date_default_timezone_set('Europe/Moscow');
		if (DateTime::createFromFormat('d.m.y', $my_date) == FALSE){
		    $my_date = date("d.m.y");
		}
		
		return $my_date;	
	}

        function get_correct_date_interval($my_date){
		if (json_decode($my_date)){
		    $my_date = json_decode($my_date);
		}
                if (is_array($my_date)){
                    $my_date_start = $this->get_correct_date($my_date[0]);
                    $my_date_end = $this->get_correct_date($my_date[1]);
                } else if ($my_date !== '' && $this->get_correct_date($my_date) !== $this->get_correct_date()){
                    $my_date_start = $this->get_correct_date($my_date);
                    $my_date_end = $this->get_correct_date();
                } else {
                    $my_date_start = "01.09.15";
                    $my_date_end = $this->get_correct_date($my_date);
                }

                return array($my_date_start,$my_date_end);
        }
	
	function get_previous_dates($int, $my_date){
		$date = date_create_from_format('d.m.y', $this->get_correct_date($my_date));
		date_sub($date, date_interval_create_from_date_string($int . ' days'));

		return date_format($date, 'd.m.y');
	}

	function show_previous_dates($my_date){
		for ($i = 3; $i > -2; $i --){
		    $current_date = $this->get_previous_dates($i, $my_date);
		    $current_url = preg_replace("/&d=.*/", '', $_SERVER['REQUEST_URI']);
		    $my_link = preg_match("/\?/",$current_url) ? $current_url . "&d=" . $current_date : $current_url . "?&d=" . $current_date ;
		    echo "<li><a href='{$my_link}'>" . $current_date . "</a></li>";
		}
	}
	
        function add_user_activity($current_user){
                foreach($this->get_online($current_user) as $value){
		    $this->$current_id = $current_user;
		    $this->save_online_users($value, $current_user);
                }	
	}

        function add_users_activity(){
		foreach (json_decode($this->get_followers()) as $current_user) {
		    $this->add_user_activity($current_user[0]); //why multy array
	        }
	}
}

//For adding date in table run as
//php online_table.php add_data
if (strcmp("{$argv[1]}", "add_data") == 0){
    $myOnlineHistiry = new OnlineHistory();
    $myOnlineHistiry->add_user_activity($myOnlineHistiry->id);
    //$myOnlineHistiry->add_users_activity();
} 

?>