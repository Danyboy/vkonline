<!DOCTYPE html>
<html>


<?php 
include './online_table.php';

class OnlineHistoryCharts extends OnlineHistory{
	
	//$myOnlineHistiry = new OnlineHistory();
	//$myOnlineHistiry->add_users_activity();
	
	function get_minutes_by_ids($users){
		
		$users_string = implode(",", $users);
		$count_query = "SELECT user_id, EXTRACT(hour FROM status) AS hours, COUNT (EXTRACT(hour FROM status)) / 12 AS count 
				FROM user_online 
				WHERE user_id IN ({$users_string}) GROUP BY hours, user_id ORDER BY user_id, hours ASC";
		
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
	$my_data = $myOnlineHistiry->get_minutes_by_ids($users);
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
