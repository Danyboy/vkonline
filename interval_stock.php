<?php 
include 'includes/start.php';

$my_date = $_GET['d'];
$users = json_decode($_GET['users']);
$current_user = $_GET['u'];
?>

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

<script src="https://code.highcharts.com/stock/highstock.js"></script>
<script src="https://code.highcharts.com/stock/modules/exporting.js"></script>

<script type="text/javascript">


$('#chart_days').highcharts('StockChart', 
	    {
	chart: {
	    renderTo: 'chart_days',
            type: 'areaspline',
	    events: {
        	load: function(){
		    request_data(
		    'includes/get_users_activity_by_days?&u=<?php echo $current_user;?>&users=<?php echo json_encode($users);?>&d=<?php echo $my_date;?>'
		    , this, 365, true);
                    this.showLoading();
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
            borderWidth: 1,
            backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
        },
        rangeSelector: {
            selected: 0,
	    inputEnabled: false,
//	    buttonTheme: {
//	        visibility: 'hidden'
//	    },
//	    labelStyle: {
//	        visibility: 'hidden'
//	    }
        },
	credits: {
            enabled: false,
        },
	xAxis: {
            type: 'datetime',
        },
        yAxis: {
            labels: {
            },
            plotLines: [{
                value: 0,
                width: 2,
                color: 'silver'
            }]
        },
        tooltip: {
            valueDecimals: 2
        },
        });
</script>

<?php 
include 'includes/end.php';
?>