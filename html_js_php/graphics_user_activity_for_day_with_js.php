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
		echo "Connected to PostgreSQL<br>";

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


}
	
$myOnlineHistiry = new OnlineHistory();
$myOnlineHistiry->connect();
$hours_query = "select extract(hour from status) as hours from user_online where user_id=749972 group by hours order by hours desc;";
$my_categories = $myOnlineHistiry->query_to_json($hours_query);

$count_query = "select extract(hour from status) as hours, count (extract(hour from status)) as count from user_online where user_id=749972 group by hours order by hours desc;";
$my_data = $myOnlineHistiry->query_to_json($count_query);

//$count_query2 = "select count (extract(hour from status)) as count from user_online where user_id=749972 group by hours order by hours desc;";
//$my_data2 = $myOnlineHistiry->query_to_json($count_query2);

echo "$my_data";

?>

<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
<script type="text/javascript">

var cat = <?php echo $my_categories ?>;
var data = <?php echo $my_data ?>;
//arr hourse_count;
//for(var i=0; i<data.length;i++) hourse_count[i] = parseInt(ar[i], 10);
//alert(ar);

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
            text: 'Average fruit consumption during one week'
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
            categories: cat,
            plotBands: [{ // visualize the weekend
                from: 4.5,
                to: 6.5,
                color: 'rgba(68, 170, 213, .2)'
            }]
        },
        yAxis: {
            title: {
                text: 'Fruit units'
            }
        },
        tooltip: {
            shared: true,
            valueSuffix: ' units'
        },
        credits: {
            enabled: false
        },
        plotOptions: {
            areaspline: {
                fillOpacity: 0.5
            }
        },
        series: [{
            name: 'John',
            data: [3, 4, 3, 5, 4, 10, 12, 11]
            //data: ar
        }, {
            name: 'Jane',
            data: [1, 3, 4, 3, 3, 5, 4]
        }]
    });
});
})(jQuery);
jQuery(document).ready(function(){jQuery("#view-menu").click(function(e){jQuery("#wrap").toggleClass("toggled")}),jQuery("#sidebar-close").click(function(e){jQuery("#wrap").removeClass("toggled")}),jQuery(document).keydown(function(e){var t;"INPUT"!=e.target.tagName&&(39==e.keyCode?t=document.getElementById("next-example"):37==e.keyCode&&(t=document.getElementById("previous-example")),t&&(location.href=t.href))}),jQuery("#switcher-selector").bind("change",function(){var e=jQuery(this).val();return e&&(window.location=e),!1})});
</script>
<body>
<script src="http://code.highcharts.com/highcharts.js"></script>

<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
    // pass PHP variable declared above to JavaScript variable
//    var ar = <?php echo json_encode($result) ?>;



</body>
</html>