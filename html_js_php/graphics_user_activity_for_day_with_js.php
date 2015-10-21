<!DOCTYPE html>
<html>


<?php 

class OnlineHistory
{
	private $id="749972";
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
		$password = "simsimopen";
		$hostname = "localhost"; 
		$my_db = "vk";

		//connection to the database
		$this->dbconn = pg_connect("dbname=vk user=root password=simsimopen")
		  or die("Unable to connect to PostgreSQL");
		//echo "Connected to PostgreSQL<br>";

		//$selected = mysql_select_db($my_db,$this->dbhandle) 
		  //or die("Could not select examples");
	}
	
	function my_query($query)
	{
		$this->result = pg_query($this->dbconn, "$query");
		if (!$this->result) {
		  echo "Произошла ошибка.\n";
		  exit;
		}
		//echo "$this->result";
		

	}
	
	function query_to_json($query){
		$this->my_query($query);
		$myarray = array();
		while ($row = pg_fetch_row($this->result)) {
		    $myarray[] = $row;
		}

		$json = json_encode($myarray);
//		echo "$json";
		return $json;
	}

	function get_minutes_by_id($id){
		$this->connect();
		$count_query = "select extract(hour from status) as hours, count (extract(hour from status)) * 5 as count from user_online where user_id in ({$id}) group by hours order by hours asc;";
		return $this->query_to_json($count_query);
	}

	function get_minutes_by_ids($users){
		//TODO change by array and for
		$ids = array();
		
		for($i = 0; $i < count($users); ++$i) {
			$ids[$i] = $this->get_minutes_by_id($users[$i]);
		}
		return $ids;
	}

}

//$myOnlineHistiry = new OnlineHistory();
//$my_data = $myOnlineHistiry->get_minutes_by_id(749972);

//echo "$my_data";

?>

<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
<script type="text/javascript">

var hours_count = new Array(24);
var hours_counts = new Array(2);
var categories = new Array(24);

function parse_data_from_array(data){
//    alert(data[0][0]);
    var my_hours_count = new Array(24);
    //TODO change
    categories = new Array(24);
    for(var i=0; i<data.length;i++) {
    	categories[i] = parseInt(data[i][0], 10);
	my_hours_count[i] = parseInt(data[i][1], 10);
    }
    
    return my_hours_count;
}

var my_series;
function generate_array_for_graphs(data){
    my_series = new Array(2);
    for(var i=0; i<data.length;i++) {
	my_series[i] = {name: 'i',
	                data: parse_data_from_array(JSON.parse(data[i]))};
    }

}

function graph_by_ids(){
    <?php
    
	$users = json_decode($_GET['users']);
	
        $myOnlineHistiry = new OnlineHistory();
	$my_data = $myOnlineHistiry->get_minutes_by_ids($users);
//	$js_array = json_encode($my_data);
//	echo "var data = ". $js_array . ";\n";
    ?>
    
//
    var data = <?php echo json_encode($my_data) ?>;
//    alert(data[0]);
    generate_array_for_graphs(data);

    //var data0 = <?php echo $my_data[0] ?>;
    //var data1 = <?php echo $my_data[1] ?>;
    //TODO bug if one of id hasnt online minutes in hours
    //hours_counts[1] = parse_data_from_array(data1);
    //hours_counts[0] = parse_data_from_array(data0);
    
    //alert(hours_counts[0]);
}

graph_by_ids();

jQuery.noConflict();

var example = 'areaspline', 
theme = 'default';
(function($){ // encapsulate jQuery
    $(function () {
    $('#container').highcharts({
        chart: {
            type: 'areaspline'
        },
        title: {
            text: 'Hours of day by popularity'
        },
        legend: {
            layout: 'vertical',
            align: 'left',
            verticalAlign: 'top',
            x: 150,
            y: 100,
            floating: true,
            borderWidth: 1,
            backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
        },
        xAxis: {
            categories: categories,
            title: {
                text: 'Hours'
            }
        },
        yAxis: {
            title: {
                text: 'Online minutes in this hours per year'
            }
        },
        tooltip: {
            shared: true,
            valueSuffix: ' minutes'
        },
        credits: {
            enabled: false,
            valueSuffix: ' hour'
        },
        plotOptions: {
            areaspline: {
                fillOpacity: 0.5
            }
        },
        series: my_series
    });
});
})(jQuery);
jQuery(document).ready(function(){jQuery("#view-menu").click(function(e){jQuery("#wrap").toggleClass("toggled")}),jQuery("#sidebar-close").click(function(e){jQuery("#wrap").removeClass("toggled")}),jQuery(document).keydown(function(e){var t;"INPUT"!=e.target.tagName&&(39==e.keyCode?t=document.getElementById("next-example"):37==e.keyCode&&(t=document.getElementById("previous-example")),t&&(location.href=t.href))}),jQuery("#switcher-selector").bind("change",function(){var e=jQuery(this).val();return e&&(window.location=e),!1})});
</script>
<body>
<script src="http://code.highcharts.com/highcharts.js"></script>

<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
</body>
</html>