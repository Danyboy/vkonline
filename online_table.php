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
	     $url="https://api.vk.com/method/users.get?user_ids=" . implode(",",json_decode($this->get_friends())->response) . "," . $this->id . ",\&fields=online,photo_50,\&lang=en";
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

	function show_today_online_users($my_date){
		foreach (json_decode($this->get_users_online_hours($my_date)) as $row) {
		    $my_time = date('H \ч i \м', mktime(0,$row[3]));
			echo "<tr>
			<td><input type='checkbox' name='mycheckbox' value='{$row[0]}'></td>
			<td><a href='http://vk.com/id{$row[0]}'>
			    <img src='{$row[1]}' alt='$row[2]'></a>
			    <a href='./u?users=[{$row[0]},749972,42606657]&d={$my_date}'>
			    {$row[2]}	<img src='Chart-icon.png' alt='$row[2]' align='right'></a></td>
			<td>{$my_time}</td>
		      </tr>";
	        }
	}

	function get_insomnia_users(){
	
		$my_date = $this->get_correct_date($my_date);
		
                $count_query = "SELECT user_id, link, name, (night::float / (day + 1)) AS stat, night, day
FROM (
SELECT user_id, 
SUM (CASE 
WHEN EXTRACT(hour FROM status) BETWEEN '2' AND '6'
THEN 1
ELSE 0
END ) AS night,
SUM (CASE WHEN EXTRACT(hour FROM status) BETWEEN '7' AND '23'
THEN 1
WHEN EXTRACT(hour FROM status) BETWEEN '0' AND '1'
THEN 1
ELSE 0
END ) AS day
FROM user_online 
GROUP BY user_id
) AS dayNight JOIN users ON (dayNight.user_id = users.id)
--WHERE (night::float / (day + 1)) > 0.36
ORDER BY stat DESC;
";

		return $this->query_to_json($count_query);
	}

	function show_insomnia_users(){
		foreach (json_decode($this->get_insomnia_users()) as $row) {
			echo "<tr>
			<td><a href='http://vk.com/id{$row[0]}'>
			    <img src='{$row[1]}' alt='$row[2]'>
			    {$row[2]}</a></td>
			<td>{$row[3]}</td>
			<td>{$row[4]} ч</td>
			<td>{$row[5]} ч</td>
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

                $is_user_exists = (strcmp($this->query_to_json($check_user_query), '[["0"]]') == 0);
                if ($is_user_exists){
                    $myqr = $this->my_query($insert_user_query);
                }

                $this->my_query($insert_date_query);
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