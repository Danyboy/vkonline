<?php 
include '/home/danil/Projects/vkonline/start.php';

class OnlineHistoryCharts extends OnlineHistory{
	
	function get_all_users_activity_by_day($my_date){
		
		$count_query = "SELECT EXTRACT(hour FROM status) AS hours, COUNT (EXTRACT(hour FROM status)) * 5 AS count 
                                FROM user_online 
                                WHERE DATE(status) = '11-8-2015' GROUP BY hours ORDER BY hours;";
		
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

	function get_activity_by_user($users){
		
		$users_string = implode(",", $users);
		$count_query = "SELECT user_id, EXTRACT(hour FROM status) AS hours, COUNT (EXTRACT(hour FROM status)) / 12 AS count 
				FROM user_online 
				WHERE user_id IN ({$users_string}) 
				GROUP BY hours, user_id 
				ORDER BY user_id, hours";
		
		return $this->query_to_json($count_query);
	}

	function get_user_activity_by_day($users, $my_date){
	
		$my_date = $this->get_correct_date($my_date);		
		
		$users_string = implode(",", $users);
		$count_query = "SELECT user_id, EXTRACT(hour FROM status) AS hours, COUNT (EXTRACT(hour FROM status)) * 5 AS count 
                                FROM user_online
                                WHERE user_id IN ({$users_string}) AND DATE(status) = '{$my_date}' 
                                GROUP BY hours, user_id 
                                ORDER BY user_id, hours";
		
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

var categories = new Array(24);
var my_hours_count = new Array();

function generate_array_for_graphs(data,names){
    var my_series = new Array();
    data = JSON.parse(data);
    names = JSON.parse(names);
    my_series_count = 0; //Number of current user
    prevCounter = 0; //Array number where starts new user

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
    
    return my_series;
}

var series_activity_by_user = new Array();
var series_activity_user_by_day = new Array();

function graph_by_ids(){
    <?php
	$users = json_decode($_GET['users']);
	$my_date = json_decode($_GET['d']);
        $myOnlineHistiry = new OnlineHistoryCharts();

	$my_users = $myOnlineHistiry->get_current_users_name($users);
	$activity_by_user = $myOnlineHistiry->get_activity_by_user($users);
	$activity_user_by_day = $myOnlineHistiry->get_user_activity_by_day($users, $my_date)
    ?>

    //TODO bug if one of id hasnt online minutes in hours
    var data_by_day = <?php echo json_encode($activity_user_by_day ) ?>;
    var data = <?php echo json_encode($activity_by_user) ?>;
    var names = <?php echo json_encode($my_users) ?>;
    series_activity_by_user = generate_array_for_graphs(data, names);
    series_activity_user_by_day = generate_array_for_graphs(data_by_day, names);
}

graph_by_ids();

</script>

<script src="http://code.highcharts.com/highcharts.js"></script>

<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
<div id="container_day" style="min-width: 310px; height: 400px; margin: 0 auto"></div>

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
        series: series_activity_by_user
    });
});
})(jQuery);
jQuery(document).ready(function(){jQuery("#view-menu").click(function(e){jQuery("#wrap").toggleClass("toggled")}),jQuery("#sidebar-close").click(function(e){jQuery("#wrap").removeClass("toggled")}),jQuery(document).keydown(function(e){var t;"INPUT"!=e.target.tagName&&(39==e.keyCode?t=document.getElementById("next-example"):37==e.keyCode&&(t=document.getElementById("previous-example")),t&&(location.href=t.href))}),jQuery("#switcher-selector").bind("change",function(){var e=jQuery(this).val();return e&&(window.location=e),!1})});
</script>

<?php 
//Next chart
?>

<script type="text/javascript">
jQuery.noConflict();

var example = 'areaspline', 
theme = 'default';
(function($){ // encapsulate jQuery
    $(function () {
    $('#container_day').highcharts({
        chart: {
            type: 'areaspline'
        },
        title: {
            text: 'Time of day by popularity'
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
                text: 'Online hours in this hour per day'
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
        series: series_activity_user_by_day
    });
});
})(jQuery);
jQuery(document).ready(function(){jQuery("#view-menu").click(function(e){jQuery("#wrap").toggleClass("toggled")}),jQuery("#sidebar-close").click(function(e){jQuery("#wrap").removeClass("toggled")}),jQuery(document).keydown(function(e){var t;"INPUT"!=e.target.tagName&&(39==e.keyCode?t=document.getElementById("next-example"):37==e.keyCode&&(t=document.getElementById("previous-example")),t&&(location.href=t.href))}),jQuery("#switcher-selector").bind("change",function(){var e=jQuery(this).val();return e&&(window.location=e),!1})});
</script>


<?php
include '/home/danil/Projects/vkonline/end.php';
?>