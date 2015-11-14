<?php 

class OnlineHistory
{
	public $id="385525";
	//public $id="749972";
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
	     $req = "{'response':[17,258,1091,1110,1181,2050,2675,2885,3461,3653,4517,4927,5455,5846,6333,6413,7068,7462,7469,7998,8489,8894,9113,9115,9532,10614,10902,12979,15221,16990,17506,17787,18239,18694,18716,19612,22101,22220,23845,24613,26862,29868,31158,34687,36871,37322,41543,45786,46122,50427,50940,53556,53577,58189,58732,59851,60558,66156,67318,67761,71428,75758,76735,83774,84419,84654,89769,97971,98965,99589,100209,101910,105893,116428,118099,118523,120269,128368,134295,134896,136882,138464,146772,150198,152238,152465,152687,154896,161715,163669,173715,176757,187531,189814,196325,200054,200632,202901,203521,207283,207773,208110,208241,215340,219998,221930,228110,230719,236538,241992,246270,246481,246781,250596,251463,252472,253171,257707,263964,268655,270488,277868,282387,282915,288613,295241,301772,303491,320084,320520,323817,324277,327072,328936,330242,339229,344092,345492,347460,350316,354008,356763,358940,368107,370802,374819,375448,377672,378781,379131,381937,388162,392998,401803,402818,416903,426871,434193,439622,446944,449491,452631,454435,462725,463445,477754,478862,479799,483997,491996,495392,497606,498567,499671,504563,504660,504793,505204,512147,516832,519790,526582,529248,529548,533952,535097,535372,535492,535768,548329,549607,550486,554950,559703,563254,578550,578681,579610,581299,617727,636941,641117,641680,651092,656566,663478,666196,666253,680102,680751,688924,689077,690765,710131,720207,725236,734977,749972,761905,764617,765194,806714,815849,817934,833532,836968,856768,908049,930894,956879,968116,968207,995807,1039097,1042307,1046787,1058863,1091766,1104121,1129032,1143844,1164327,1176139,1182350,1199190,1201688,1208227,1217435,1233768,1273544,1282027,1287778,1305683,1323547,1336445,1368861,1369218,1376745,1405226,1415681,1449116,1512111,1531728,1532847,1564076,1583445,1586186,1590369,1620615,1624710,1626145,1634594,1641256,1685463,1707302,1722482,1722961,1723089,1753794,1804199,1847621,1859066,1859948,1875537,1876421,1892254,1895318,1918727,1922690,1922767,1924181,1963415,1984347,1991197,2014294,2017142,2018664,2045755,2047280,2048733,2080376,2082075,2085067,2088469,2089498,2090088,2100784,2161457,2212871,2225231,2255029,2306734,2410576,2411669,2437831,2446106,2482871,2517021,2522948,2558840,2575103,2577455,2586175,2640301,2644625,2698190,2711121,2739080,2770479,2773127,2805561,2814835,2844898,2877260,2905190,2944755,2956195,2971463,2977745,3044062,3045571,3068698,3078034,3078889,3105696,3111833,3146285,3163410,3277113,3365437,3380690,3397500,3413726,3427026,3463742,3639812,3665799,3712649,3767196,3920404,3930265,3939932,4002407,4096855,4112631,4130197,4165516,4181011,4218435,4295048,4320578,4331032,4424831,4507343,4598372,4600335,4640612,4649075,4767609,4837959,4884409,4925157,5047810,5078237,5128582,5144452,5189959,5216721,5275534,5359117,5359942,5379533,5385365,5462624,5516258,5522202,5531739,5581747,5634866,5667621,5688907,5689399,5730325,5746107,5779423,5818400,5859632,5918886,6090342,6100482,6109311,6219043,6343066,6707423,6847672,6925264,6944307,7097375,7098919,7133469,7285427,7412127,7440278,7479075,7519434,7604307,7743046,8039066,8061850,8091321,8247473,8256590,8295944,8355786,8368265,8403217,8443321,8517252,8529394,8679069,8714402,8721402,8725234,8774630,8788518,8826292,9135146,9293567,9335761,9461392,9670728,9681143,9692175,9871656,10526099,10665112,10740452,10820306,10833425,10914301,11044346,11077309,11319160,11391029,11427736,11720456,11982912,12158052,12161603,12168155,13066727,13131596,13264043,13280371,13443596,13452232,14287265,14754405,15309342,15415926,15564172,15685815,15843015,15890091,15891326,16357420,16515015,16777334,17424776,17540617,17596457,17639867,17805697,17914503,17923248,18486024,18640825,18786118,19675168,19863769,20078150,20270950,20295239,20344075,20514940,20526243,20673715,21523406,23437287,23832659,24031071,24151116,24504839,25087037,25809161,26621497,27028367,27177673,27207698,27567874,27598117,27767040,30691486,31883465,32702312,33473688,33703192,33822373,35208789,35699310,35890961,35961881,36062275,36422252,36685500,36944942,37367785,39240518,45242469,47058745,47258247,48898816,49141889,49645298,51773876,51797691,52033163,52064102,52761109,52781808,53429109,53697913,54268051,55520649,57067479,60062043,60439378,60607814,61409976,67053573,68759173,70095871,72768483,72839157,73001914,76087571,77056452,78100380,78345355,78796448,79287531,84364893,85271157,86529071,87338227,87875484,89162370,89973664,90143589,92212932,92944157,93897277,94097779,94105946,94728761,95588607,96956551,97833617,98822609,103189905,103766402,104488748,112116133,112603558,118690018,120325911,123196525,124511284,127323855,133073125,135679393,135912132,136629270,138516435,141782876,143276763,143819404,146309372,149173305,151861435,152888417,153188207,153425982,153872292,154263312,155903416,156830386,157205762,157240991,158418766,161534908,162577579,163319702,164217354,164965826,167118466,168397683,169074532,171641959,172375509,172915988,175372747,176059300,178578401,180665738,182661154,182872043,185023234,198232896,204468147,206982935,208011432,213188913,216891716,222546000,223613477,224573389,228406813,265513214,269760287,295691075,315835935]}";
	     
	     return $this->send_req($url);
	}

	function get_online(){
	     $chunked = array_chunk(json_decode($this->get_friends())->response, 400);
	     $result = array();

	     foreach ($chunked as $arr){
		$json = json_decode($this->get_online_part(implode(",",$arr)))->response;
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
                                WHERE user_id IN (385525) AND DATE(status) = '11-9-2015' GROUP BY hours, user_id ORDER BY user_id, hours ASC;";
                                
		$user_online_minutes = "SELECT user_id, COUNT (EXTRACT(hour FROM status)) * 5 AS count 
                                FROM user_online 
                                WHERE user_id IN (385525) AND DATE(status) = '11-9-2015' GROUP BY user_id;";
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
			    <a href='./u?users=[{$row[0]},385525,690765]&d={$my_date}'>
			    {$row[2]}	<img src='Chart-icon.png' alt='$row[2]' align='right'></a></td>
			<td><a href='c?u={$row[0]}'>
			    {$my_time} <img src='includes/heart_25.png' alt='$row[2]' alight='right'></a></td>
		      </tr>";
	        }
	}

	function get_insomnia_users(){
                $count_query = "
			        SELECT user_id, link, name, (night::float / (day + 1)) AS stat, night, day
			        FROM (
			            SELECT user_id, 
			                SUM (CASE 
			                WHEN EXTRACT(hour FROM status) BETWEEN '0' AND '8'
			                    THEN 1
			                    ELSE 0
			                END ) AS night,
			                SUM (CASE WHEN EXTRACT(hour FROM status) BETWEEN '9' AND '23'
			                    THEN 1
			                    ELSE 0
			                END ) AS day
			            FROM user_online 
			            GROUP BY user_id
			            ) AS dayNight JOIN users ON (dayNight.user_id = users.id)
			        ORDER BY night DESC;";

		return $this->query_to_json($count_query);
	}

	function get_users_compatibility($user){
	
                $count_query = "
			SELECT id, link, name, count, coef
			FROM (
			    SELECT my_users.user_id, COUNT (*) AS count, COUNT(*)::float / user_coef.norm AS coef
			    FROM user_online LEFT JOIN user_online AS my_users
			        ON my_users.status = user_online.status
			        INNER JOIN (SELECT user_id, COUNT(*) AS norm
				FROM user_online
				GROUP BY user_id) AS user_coef
				    ON user_coef.user_id = my_users.user_id
			    WHERE user_online.user_id = {$user}
			    GROUP BY user_online.user_id, my_users.user_id, user_coef.norm
			    ORDER BY count DESC
			) AS my_comp
			JOIN users 
			ON (my_comp.user_id = id);
				";

		return $this->query_to_json($count_query);
	}

	function show_users_compatibility($user){
		foreach (json_decode($this->get_users_compatibility($user)) as $row) {
		    $num = number_format($row[4], 2, '.', '');
		    $time_together = number_format($row[3] / 12, 0, '.', '');
		    echo "<tr>
			<td><input type='checkbox' name='mycheckbox' value='{$row[0]}'></td>
			<td><a href='http://vk.com/id{$row[0]}'>
			    <img src='{$row[1]}' alt='$row[2]'></a>
			    <a href='./u?users=[{$row[0]},385525,690765]&d={$my_date}'>
			    {$row[2]}	<img src='Chart-icon.png' alt='$row[2]' align='right'></a></td>
			<td>{$num}</td>
			<td>{$time_together} ч</td>
		      </tr>";
	        }
	}
	
	function show_insomnia_users(){
		foreach (json_decode($this->get_insomnia_users()) as $row) {
		    $num = number_format($row[3], 2, '.', '');
		    $summ = $row[4] + $row[5];
		    $weight = $num * $summ;
		    echo "<tr>
			<td><input type='checkbox' name='mycheckbox' value='{$row[0]}'></td>
			<td><a href='http://vk.com/id{$row[0]}'>
			    <img src='{$row[1]}' alt='$row[2]'></a>
			    <a href='./u?users=[{$row[0]},385525,690765]&d={$my_date}'>
			    {$row[2]}	<img src='Chart-icon.png' alt='$row[2]' align='right'></a></td>
			<td>{$num}</td>
			<td>{$row[4]} ч</td>
			<td>{$row[5]} ч</td>
			<td>{$summ} ч</td>
			<td>{$weight}</td>
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
                foreach($this->get_online() as $value){
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