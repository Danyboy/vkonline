<?php 

class OnlineHistory
{
	private $id="749972";
	private $json;
	private $dbhandle;
	private $vkapi_token='cd7781010610415dd8d3a039d5cbaedc0309b19ff19c58d3e8ab67294fa7ab85ed5d29837bedd1a05758a';

	function send_req($url){
	     $ch = curl_init(); 
	     $timeout = 5; 
	     curl_setopt($ch, CURLOPT_URL, $url);
	     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	     curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	     $data = curl_exec($ch);
	     curl_close($ch);
// 	     print $data;
	     return $data;
	}
	
	function get_friends() { 
	     $url="https://api.vk.com/method/friends.get?user_id=749972"; //https://api.vk.com/method/friends.get?user_id=$id\&access_token=$vkapi_token
	     return $this->send_req($url);
	}

	function get_online() { 
	     $url="https://api.vk.com/method/users.get?user_ids=" . $this->get_friends() . "," . $this->id . ",\&fields=online,photo_50,\&lang=en";
// 	     print $url;
	     return $this->send_req($url);
	}

	function connect()
	{	
		$username = "root";
		$password = "***REMOVED***";
		$hostname = "localhost"; 
		$my_db = "vk_online";

		//connection to the database
		$this->dbhandle = mysql_connect($hostname, $username, $password) 
		  or die("Unable to connect to MySQL");
		echo "Connected to MySQL<br>";

		$selected = mysql_select_db($my_db,$this->dbhandle) 
		  or die("Could not select examples");
	}

	function save_to_db($id){
		$sql="INSERT INTO user_time (mytime, userid) VALUES (current_timestamp(), $id);";
		if(!mysql_query($sql,$this->dbhandle)) //dbhandle is mysql connection object
		{
		     die('Error : ' . mysql_error());
		}	
	}

	function download()
	{	
// 		$json = $this->get_friends($this->url);
 		$json = $this->get_online();
// 		print($json);
		$obj = json_decode($json);
// 		print_r($obj);
// 		$this->connect();
		foreach( $obj->response as $value)
		{
//			$this->save_to_db($value);
			print_r($value->uid . " ");
			print_r($value->online . " ");
			print_r($value->first_name . " ");
			print_r($value->last_name . " ");
			print_r($value->photo_50. " ");
			print("\r\n");
// 			print($value->online_mobile);
		}

	}


}
	
$myOnlineHistiry = new OnlineHistory();
$myOnlineHistiry->download();

// connect on builder64
//create table space_table (user_name varchar(255), user_score int);
//INSERT INTO space_table (user_name, user_score) VALUES ('efnez', '122')
//select mytime from user_time where mytime >= (CURDATE() - INTERVAL 3 DAY);
//

?>
