<?php 

class OnlineHistory
{
	//public $id="385525";
	//public $id="749972";
	//public $current_id="749972";
	public $current_id="53083705";
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
	     $url="https://api.vk.com/method/users.get?user_ids=" . $str . ",\&fields=online,photo_50,\&lang=ru";
	     return $this->send_req($url);
	}
	
	function connect() {	
		$username = "root";
		$password = "***REMOVED***";
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
			//error_log("Произошла ошибка.\n");
			error_log(pg_last_error($this->dbconn));
			$this->echo_error();
			exit;
		}
		
		return $result;
	}

	function echo_error(){
		echo "
		    <div class='alert alert-success' id='after_login'>
		        <p>Ваша статистика записывается. Зайдите через пять минут.</p>
		    </div>
		";
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
                                WHERE user_id IN ($this->current_id) AND DATE(status) = '11-9-2015' GROUP BY hours, user_id ORDER BY user_id, hours ASC;";
                                
		$user_online_minutes = "SELECT user_id, COUNT (EXTRACT(hour FROM status)) * 5 AS count 
                                FROM user_online 
                                WHERE user_id IN ($this->current_id) AND DATE(status) = '11-9-2015' GROUP BY user_id;";
		return $this->query_to_json($user_online_minutes_by_hourse);;
	}

	function get_users_online_hours($my_date, $current_user){
	
		$my_date = $this->get_correct_date($my_date);
		
		$count_query_without_data = "SELECT user_id, COUNT (EXTRACT(hour FROM status)) * 5 AS count 
                                FROM online{$current_user}
                                WHERE DATE(status) = '11-9-2015' GROUP BY user_id;";
                                
                $count_query = "SELECT user_id, link, name, COUNT (EXTRACT(hour FROM status)) * 5 AS minutes 
                                FROM online{$current_user} JOIN users{$current_user} ON (online{$current_user}.user_id = users{$current_user}.id)
				WHERE DATE(status) = '{$my_date}'
				GROUP BY user_id, link, name ORDER BY minutes DESC;";
		
		return $this->query_to_json($count_query);
	}

	function show_chart($my_date, $current_user, $chart_uid, $user_name_spaced, $img){
		$user_name = str_replace ( " ", "<br>", "{$user_name_spaced}");
		//$user_name = str_replace ( " ", " ", "{$user_name}");
		echo "
			<td>
			<div class='layout'>
			<div class='col1'>
			    <a href='http://vk.com/id{$chart_uid}' target='_blank'>
			    <img src='{$img}' title='$user_name_spaced'></a>
			</div>
			<div class='col2'>
			    <a 
			    href=\"u?u=" . $this->get_current_id($_GET['u']) . 
			    "&users=[" . $this->get_current_id($_GET['u']). ",". $chart_uid . "]\" id=\"date_link\"
	                    onclick=\"
        		    location.href=this.href+get_date_and_users();return false;
			    \">
				    {$user_name}
			    </a>
			</div>
			<div class='col3'>
			    <a 
			    href=\"u?u=" . $this->get_current_id($_GET['u']) . 
			    "&users=[" . $this->get_current_id($_GET['u']). ",". $chart_uid . "]\" id=\"date_link\"
	                    onclick=\"
        		    location.href=this.href+get_date_and_users();return false;
			    \">
			    <img src='img/chart.png' align='right' 
					title='Сравнить график активности с {$user_name}'>
			    </a>
			</div>
			</div>
			</td>

		";
	}

	function show_today_online_users($my_date, $current_user){
		$current_user = $this->get_current_id($current_user);
		foreach (json_decode($this->get_users_online_hours($my_date, $current_user)) as $row) {
		    $my_time = date('H \ч i \м', mktime(0,$row[3]));
			echo "
			<tr hight>
			<td>
			<input type='checkbox' name='mycheckbox' value='{$row[0]}'>
			</td>";

			$this->show_chart($my_date, $current_user, $row[0], $row[2], $row[1]);
			
			echo "
			<td><a href='c?u={$current_user}&cu={$row[0]}'>
			    {$my_time} <br> <img src='img/heart.png' alt='$row[2]' alight='right'
				title='Показать совместимость $row[2] c другими пользователями'>
			    </a></td>
		      </tr>";
	        }
	}

	function save_online_users($value, $current_user){
		if (strcmp("{$value->online}", "0") !== 0){
            	    $this->save_to_db($value, $current_user);
		}
	}

        function save_to_db($value, $current_user){
                $my_name = addcslashes ($value->first_name . " " . $value->last_name,  "'");
                //$my_name_2 = mysqli::real_escape_string($value->first_name . " " . $value->last_name);
		
	        $check_user_query = "SELECT COUNT(id) FROM users{$current_user} WHERE id={$value->uid};";
	        $insert_user_query = "INSERT INTO users{$current_user} (id, name, link) VALUES ({$value->uid}, '{$my_name}', '{$value->photo_50}');";
	        $update_user_query = "UPDATE users{$current_user} SET name = '{$my_name}', link = '{$value->photo_50}' WHERE id = {$value->uid};";
	        $insert_date_query = "INSERT INTO online{$current_user} (user_id, status) VALUES ({$value->uid}, CURRENT_TIMESTAMP(0));";

                if ($this->user_non_exists($check_user_query)){
                    $myqr = $this->my_query($insert_user_query);
                } else {
            	    $this->my_query($update_user_query);
		}
                $this->my_query($insert_date_query);
        }
	
	function user_non_exists($query){
                return (strcmp($this->query_to_json($query), '[["0"]]') == 0);
	}

	function get_correct_date($my_date = ''){
		date_default_timezone_set('Europe/Moscow');
		if (empty($my_date)){
			    $my_date = date("d.m.y");
		} else if (DateTime::createFromFormat('d.m.y', $my_date) == FALSE){
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
                    $my_date_start = "01.01.16";
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
		    echo "<li><a href='{$my_link}' data-toggle='collapse' data-target='.navbar-collapse'>" . $current_date . "</a></li>";
		}
	}
	
        function add_user_activity($current_user){
                foreach($this->get_online($current_user) as $value){
            	    global $current_id; //, $current_user;
		    $current_id = $current_user;
		    $this->save_online_users($value, $current_user);
                }	
	}

        function add_users_activity(){
		foreach (json_decode($this->get_followers()) as $current_user) {
		    $this->add_user_activity($current_user[0]); //why multy array
	        }
	}

	function get_current_id($id){
                if (!empty($id)){
		    return $id;
                }

                return $this->current_id;
        }
}

//For adding date in table run as
//php online_table.php add_data
if (strcmp("{$argv[1]}", "add_data") == 0){
    $myOnlineHistiry = new OnlineHistory();
    $myOnlineHistiry->add_users_activity();
} 

?>