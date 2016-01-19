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

var chart;

function requestData() 
{
    $.ajax({
    url: 'includes/get_user_activity_by_day.php?&u=<?php echo $current_user;?>&users=<?php echo json_encode($users);?>&d=<?php echo $my_date;?>',
    datatype: "json",
    success: function(data) 
    {
	chart.hideLoading();
	var my_data = JSON.parse(data);
	var norm_data = generate_array_for_graphs(JSON.stringify(my_data.data), JSON.stringify(my_data.names), 24);
	for (i = 0; i < norm_data.length; i++) {
	    chart.addSeries({              
                name: norm_data[i].name,
                data: norm_data[i].data
            }, false);
	    chart.redraw();
	}

	chart.xAxis[0].setCategories(categories[0]);
	chart.setOptions(Highcharts.dark-unica);
    },      
    });
}

$(document).ready(function() {
chart = new Highcharts.Chart({
	chart: {
	    renderTo: 'chart_day',
            type: 'areaspline',
	    events: {
        	load: function(){
		    requestData();
                    this.showLoading();
                }
    	    }
        },
        title: {
            text: 'Когда и сколько вы были онлайн за <?php echo $myOnlineHistiry->get_correct_date($_GET['d']); ?>'
        },
	plotOptions: {
            series: {
                marker: {
                    enabled: false
                }
            }
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
            categories: 
            categories[0],
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
            }
        },
        series: []
  });
});
</script>

<?php 
include 'includes/end.php';
?>