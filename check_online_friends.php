<?php 

class OnlineHistory
{
//	var $id="";
	var $url="https://api.vk.com/method/friends.get?user_id=749972";
	private $json;
	private $dbhandle;

	function get_data($url) { 
	     $ch = curl_init(); 
	     $timeout = 5; 
	     curl_setopt($ch, CURLOPT_URL, $url);
	     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	     curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	     $data = curl_exec($ch);
	     curl_close($ch);
	     return $data;
	}

	function connect()
	{	
		$username = "root";
		$password = "simsimopen";
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
		$json = $this->get_data($this->url);
		print($json);
		$obj = json_decode($json);
		print_r($obj);
		$this->connect();
		foreach( $obj->response as $value)
		{
			$this->save_to_db($value);
//			print($value );
		}

	}


}
	
(new OnlineHistory() )-> download();

// connect on builder64
//create table space_table (user_name varchar(255), user_score int);
//INSERT INTO space_table (user_name, user_score) VALUES ('efnez', '122')
//select mytime from user_time where mytime >= (CURDATE() - INTERVAL 3 DAY);
//

?>
