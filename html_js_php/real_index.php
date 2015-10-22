
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
		//Temporary
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
		    date_default_timezone_set('MSK');
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
		echo "<tr>
			<td><a href='http://vk.com/id{$row[0]}'>
			    <img src='{$row[1]}' alt='$row[2]'> {$row[2]}</a></td> 
			<td><a href='./u?users=[{$row[0]},749972,42606657]'>{$row[2]} online activity graphics</td>
			<td>{$row[3]} m</td>
		      </tr>";
	        }
	}
	
	function remove_offline_users($value){
		if (strcmp("{$value->online}", "0") !== 0){
            	    $this->save_to_db($value);
		}
	}

        function save_to_db($value){
                $my_name = $value->first_name . " " . $value->last_name;
		
	        $check_user_query = "SELECT COUNT(id) FROM users WHERE id={$value->uid};";
	        $insert_user_query = "INSERT INTO users (id, name, link) VALUES ({$value->uid}, '{$my_name}', '{$value->photo_50}';";
	        $insert_date_query = "INSERT INTO user_online (user_id, status) VALUES ({$value->uid}, CURRENT_TIMESTAMP(0));";

                //echo ($insert_date_query . "\n");
                //my_query($insert_date_query);
                
                $is_user_exists = (strcmp($this->query_to_json($check_user_query), '[["1"]]') !== 0);
            
                if ($is_user_exists){
            	    echo ($insert_user_query . "\n");
                    //my_query($insert_user_query);
                }
        }
	
        function add_users_activity(){
		$json = $this->get_online();
                $obj = json_decode($json);
                foreach( $obj->response as $value){
		    $this->remove_offline_users($value);
                }
	}
}

//$myOnlineHistiry = new OnlineHistory();
//$myOnlineHistiry->add_users_activity();
//$myOnlineHistiry->show_today_online_users();

//
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>How much your friend online in vk</title>

    <!-- Bootstrap core CSS -->
    <link href="../../dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="dashboard.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="../../assets/js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="/">VKonline</a>
        </div>
       <nav id="bs-navbar" class="collapse navbar-collapse">
      <ul class="nav navbar-nav">
      
      
      
        <li>
          <a href="http://vk.pr.etersoft.ru/all/13.10.15.html">13.10.15</a>
        </li>
      </ul>
    </nav>

      </div>
    </div>
    <div class="container-fluid">
      <div class="row">
          <h2 class="sub-header">
          <?php  date_default_timezone_set('UTC'); echo date("d.m.y"); ?>
          
          </h2>
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>User</th>
                  <th>Graphics user online activity</th>
                  <th>Time on vk</th>
                </tr>
              </thead>
              <tbody>
                <tr>
    
<?php 
$myOnlineHistiry = new OnlineHistory();
$myOnlineHistiry->show_today_online_users($_GET['d']);
?>

</tr>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="../../dist/js/bootstrap.min.js"></script>
    <script src="../../assets/js/docs.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
