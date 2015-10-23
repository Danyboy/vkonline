<?php 
//include '/home/danil/Projects/vkonline/html_js_php/online_table.php';
include '/home/danil/Projects/vkonline/html_js_php/start.php';

class OnlineHistoryCharts extends OnlineHistory{
	
	//$myOnlineHistiry = new OnlineHistory();
	//$myOnlineHistiry->add_users_activity();
	
	function get_activity_by_user($users){
		
		$users_string = implode(",", $users);
		$count_query = "SELECT user_id, EXTRACT(hour FROM status) AS hours, COUNT (EXTRACT(hour FROM status)) / 12 AS count 
				FROM user_online 
				WHERE user_id IN ({$users_string}) GROUP BY hours, user_id ORDER BY user_id, hours";
		
		return $this->query_to_json($count_query);
	}

	function get_users_activity_by_day($date){
		
		$users_string = implode(",", $users);
		$count_query = "SELECT EXTRACT(hour FROM status) AS hours, COUNT (EXTRACT(hour FROM status)) * 5 AS count 
                                FROM user_online 
                                WHERE DATE(status) = '11-8-2015' GROUP BY hours ORDER BY hours;";
		
		return $this->query_to_json($count_query);
	}

	function get_user_activity_by_day($users, $date){
		
		$users_string = implode(",", $users);
		$count_query = "SELECT user_id, EXTRACT(hour FROM status) AS hours, COUNT (EXTRACT(hour FROM status)) * 5 AS count 
                                FROM user_online 
                                WHERE user_id IN (749972) AND DATE(status) = '11-8-2015' GROUP BY hours, user_id ORDER BY user_id, hours";
		
		return $this->query_to_json($count_query);
	}

	function get_activity_for_all_users_and_dates(){
		//Long query - collect all data
		
		$users_string = implode(",", $users);
		$count_query = "SELECT EXTRACT(hour FROM status) AS hours, COUNT (EXTRACT(hour FROM status)) * 5 AS count 
                                FROM user_online 
                                GROUP BY hours ORDER BY hours;";
		
		return $this->query_to_json($count_query);
	}
	
	
	function get_current_users_name($users){
		$users_string = implode(",", $users);
		$count_query = "SELECT name FROM users WHERE id IN ({$users_string}) ORDER BY id;";
		return $this->query_to_json($count_query);
	}

}

?>

<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
<script type="text/javascript">

var hours_count = new Array(24);
var hours_counts = new Array(2);
var categories = new Array(24);
var my_hours_count = new Array();
var my_series = new Array();

function generate_array_for_graphs(data,names){
    data = JSON.parse(data);
    names = JSON.parse(names);
    my_series_count = 0;
    prevCounter = 0;
    for(var i = 1 ; i < data.length ; i++) {
	currentId = data[i][0];
	prevId = data[i-1][0];

	if (currentId != prevId || i == (data.length - 1) ){
	    my_hours_count = new Array(i - prevCounter);
	    for (var j = 0; j < i - prevCounter; j++){
	        categories[j] = parseInt(data[j + prevCounter][1], 10);
		my_hours_count[j] = parseInt(data[j + prevCounter][2], 10);
	    }
	    prevCounter = i;
	    my_series[my_series_count] = {
			    name: names[my_series_count],
			    data: my_hours_count
	    	            };
	    my_series_count++;
	    //console.debug (my_hours_count);
	}
    }
}


function graph_by_ids(){
    <?php
	$users = json_decode($_GET['users']);
        $myOnlineHistiry = new OnlineHistoryCharts();
	$my_data = $myOnlineHistiry->get_activity_by_user($users);
	$my_users = $myOnlineHistiry->get_current_users_name($users);
    ?>

    //TODO bug if one of id hasnt online minutes in hours
    var data = <?php echo json_encode($my_data) ?>;
    var names = <?php echo json_encode($my_users) ?>;
    generate_array_for_graphs(data, names);
}

graph_by_ids();

</script>


<script src="http://code.highcharts.com/highcharts.src.js"></script>
<!--<script src="http://code.highcharts.com/highcharts.js"></script>-->



<?php
//include '/home/danil/Projects/vkonline/html_js_php/start.php';
?>

<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
<script type="text/javascript">

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
                text: 'Online hours in this hour per year'
            }
        },
        tooltip: {
            shared: true,
            valueSuffix: ' hours'
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

<?php
include '/home/danil/Projects/vkonline/html_js_php/end.php';
?>