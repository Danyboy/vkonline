<?php 
include 'includes/start.php';

$my_date = $_GET['d'];
$users = json_decode($_GET['users']);
$current_user = $_GET['u'];
?>

<div>
<div id="chart_day" style="min-width: 310px; margin: 0 auto"></div>
</div>

<script src="//code.highcharts.com/highcharts.js"></script>

<script type="text/javascript">

//chart.setOptions(Highcharts.dark-unica);

$(document).ready(function() {
var chart = new Highcharts.Chart({
	chart: {
	    renderTo: 'chart_day',
            type: 'areaspline',
	    events: {
        	load: function(){
		    request_data(
		    'includes/get_user_activity_by_day.php?&u=<?php echo $current_user;?>&users=<?php echo json_encode($users);?>&d=<?php echo $my_date;?>'
		    , this);
                    this.showLoading();
                }
    	    }
        },
        title: {
            text: 'Когда и сколько вы были онлайн за <?php echo $myOnlineHistiry->get_correct_date($_GET['d']); ?>'
        },
        legend: {
            layout: 'vertical',
            align: 'left',
            verticalAlign: 'top',
            x: 150,
            y: 100,
            floating: true,
            borderWidth: 0,
            backgroundColor: 'rgba(255,255,255,0.6)'
        },
        xAxis: {
            title: {
                text: 'Время дня'
            }
        },
        yAxis: {
            title: {
                text: 'Минут онлайн'
            }
        },
        tooltip: {
            shared: true,
            valueSuffix: ' минут'
        },
        credits: {
            enabled: false,
        },
        plotOptions: {
            areaspline: {
                fillOpacity: 0.5
            },
	    series: {
                marker: {
                    enabled: true
                }
            }

        },
  });
});
</script>

<?php 
include 'includes/end.php';
?>