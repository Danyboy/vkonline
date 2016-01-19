<?php 
include 'includes/start.php';

$my_date = $_GET['d'];
$users = json_decode($_GET['users']);
$current_user = $_GET['u'];
?>

<script src="https://code.highcharts.com/stock/highstock.js"></script>
<script src="https://code.highcharts.com/stock/modules/exporting.js"></script>

<div>
<div id="chart_day" style="min-width: 310px; margin: 0 auto"></div>
</div>

<script type="text/javascript">

$(document).ready(function() {
var chart = new Highcharts.Chart({
	chart: {
	    renderTo: 'chart_day',
            type: 'areaspline',
	    events: {
        	load: function(){
		    request_data(
		    'includes/get_user_activity_by_day.php?&u=<?php echo $current_user;?>&users=<?php echo json_encode($users);?>&d=<?php echo $my_date;?>'
		    , this, 24);
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


<div>
<div id="chart_days" style="min-width: 310px; margin: 0 auto"></div>
</div>

<div class="jumbotron">
    <div class="container" id="interval">
    <div class="input-daterange input-group" id="datepicker">
    <span class="input-group-addon">Сколько часов вы были онлайн с </span>
    <input type="text" class="input-sm form-control" name="start" data-date-format="dd.mm.yy" size="5" 
        value="<?php echo $myOnlineHistiry->get_correct_date_interval($_GET['d'])[0]; ?>"/>
    <span class="input-group-addon">по</span>
    <input type="text" class="input-sm form-control" name="end" data-date-format="dd.mm.yy" size="5" 
        value="<?php echo $myOnlineHistiry->get_correct_date_interval($_GET['d'])[1]; ?>"/>
    <span class="input-group-addon">
        <a href="" onclick=
        "my_date=get_range(); location.href=window.location.href+'&d=['+'%22'+my_date[0]+'%22'+','+'%22'+my_date[1]+'%22'+']';return false;">
        Узнать</a>
    </span>
    </div>
    </div>
</div>


<script type="text/javascript">
$('#chart_days').highcharts('StockChart', 
	    {
	chart: {
	    renderTo: 'chart_days',
            type: 'areaspline',
	    height: 550,
	    events: {
        	load: function(){
		    request_data(
		    'includes/get_users_activity_by_days?&u=<?php echo $current_user;?>&users=<?php echo json_encode($users);?>&d=<?php echo $my_date;?>'
		    , this, 365, true);
                    this.showLoading();
                }
    	    }
        },
	title: {
            text: 'Сколько часов вы были онлайн с <?php echo $myOnlineHistiry->get_correct_date_interval($_GET['d'])[0]; ?>
                 по <?php echo $myOnlineHistiry->get_correct_date_interval($_GET['d'])[1]; ?>'
        },
	legend: {
            enabled: true,
            floating: true,
            align: 'left',
            backgroundColor: 'rgba(255,255,255,0.6)',
            borderColor: 'black',
            borderWidth: 0,
            layout: 'vertical',
            verticalAlign: 'top',
            y: 100,
            x: 150
        },
        rangeSelector: {
            selected: 0,
	    inputEnabled: false,
        },
	credits: {
            enabled: false,
        },
	xAxis: {
            type: 'datetime',
	    title: {
                text: 'День'
            }
        },
        yAxis: {
	    title: {
//		align: 'left',
                text: 'Часов онлайн'
            },
	    labels: {
		align: 'left'
	    },
            plotLines: [{
                value: 0,
                width: 2,
                color: 'silver'
            }]
        },
        tooltip: {
	    valueSuffix: ' часов',
            valueDecimals: 2
        },
        });
</script>

<?php 
include 'includes/end.php';
?>