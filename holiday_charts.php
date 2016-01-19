<?php 
include 'includes/start.php';
include 'includes/get_holiday_charts.php';

$users = json_decode($_GET['users']);
$my_date = $_GET['d'];
$current_user = $_GET['u'];
$myOnlineHistiry = new OnlineHistoryCharts();
?>

<div>
<div id="chart_day" style="min-width: 310px; margin: 0 auto"></div>
</div>

<script src="//code.highcharts.com/highcharts.js"></script>

<div class="jumbotron">
    <div class="container" id="interval">
    <div class="input-daterange input-group" id="datepicker">
    <span class="input-group-addon">Сколько часов все были онлайн с </span>
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
include 'includes/chart_holiday.php';
?>

<script type="text/javascript">
//var data_by_day = send_request('includes/get_holiday_charts.php');
<?php //echo json_encode($myOnlineHistiry->get_all_users_activity_by_day($my_date, $myOnlineHistiry->get_current_id($current_user))) ?>;
var disable = false;
series_activity_user_by_day = generate_array_for_graphs(data_by_day, php_names, 19);
series_activity_user_by_day[0].name = ['2015-2016'];
series_activity_user_by_day[1].name = ['2014-2015'];
series_activity_user_by_day[1].color = '#ffa500';
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