<?php 
include 'includes/start.php';

class OnlineHistoryCharts extends OnlineHistory{
	
	function get_all_users_activity_by_day($my_date, $current_user){
		$my_date_start = $this->get_correct_date_interval($my_date)[0];
                $my_date_end = $this->get_correct_date_interval($my_date)[1];
		
		$count_query = "SELECT '2015', to_char(status,'MM-DD') AS day, to_char(COUNT (EXTRACT(hour FROM status))::float / (12 * 1488), 'FM99.00') AS count 
                                FROM online749972
                                WHERE status between '{$my_date_start}' and '{$my_date_end} 23:59:59'
                                GROUP BY day, status::timestamp::date 
				ORDER BY status::timestamp::date;"; 

		$my_date_end_prev = $this->get_previous_dates(365,$my_date_end);
		$my_date_start_prev = $this->get_previous_dates(365,$my_date_start);

		$count_query_prev = "SELECT '2014', to_char(status,'MM-DD') AS day, to_char(COUNT (EXTRACT(hour FROM status))::float / (12 * 1488), 'FM99.00') AS count 
                                FROM online749972
                                WHERE status between '{$my_date_start_prev}' and '{$my_date_end_prev} 23:59:59'
                                GROUP BY day, status::timestamp::date 
				ORDER BY status::timestamp::date;";

		return json_encode(array_merge(json_decode($this->query_to_json($count_query)),json_decode($this->query_to_json($count_query_prev))));
	}	
}

$users = json_decode($_GET['users']);
$my_date = $_GET['d'];
$current_user = $_GET['u'];
$myOnlineHistiry = new OnlineHistoryCharts();
?>

<div>
<div id="chart_day" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
</div>

<script src="//code.highcharts.com/highcharts.js"></script>

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
        "my_date=get_range(); location.href=window.location.href+'?&d=['+'%22'+my_date[0]+'%22'+','+'%22'+my_date[1]+'%22'+']';return false;">
        Узнать</a>
    </span>
    </div>
    </div>
</div>

<?php
include 'includes/holiday_chart.php';
?>

<script type="text/javascript">
var data_by_day = <?php echo json_encode($myOnlineHistiry->get_all_users_activity_by_day($my_date, $myOnlineHistiry->get_current_id($current_user))) ?>;
var php_names = [2014];
var disable = false;
series_activity_user_by_day = generate_array_for_graphs(data_by_day, php_names, 19);
series_activity_user_by_day[0].name = ['2015-2016'];
series_activity_user_by_day[1].name = ['2014-2015'];
</script>

<script>
$('#interval .input-daterange').datepicker({
    format: "dd.mm.yy",
    startDate: "22/12/14",
    endDate: new Date(),
    autoclose: true,
    todayHighlight: true
});
</script>

<?php 
include 'includes/end.php';
?>