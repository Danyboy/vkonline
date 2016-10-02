<?php
include 'includes/start.php';
include 'includes/getters/get_charts.php';

$users = json_decode($_GET['users']);
$my_date = $_GET['d'];
$current_user = $_GET['u'];
$myOnlineHistiry = new OnlineHistoryCharts();
?>

  <div>
    <div id="chart_day" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
    <div id="chart_year" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
  </div>

  <script src="//code.highcharts.com/highcharts.js"></script>

<?php
include 'includes/chart_day.php';
?>
<?php
include 'includes/chart_year.php';
?>

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

  <div id="chart_interval" style="min-width: 310px; height: 400px; margin: 0 auto"></div>

  <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>

  <script type="text/javascript">
    <?php
      $data_by_day_with_names = $myOnlineHistiry->get_user_activity_by_day_with_names($users, $my_date, $myOnlineHistiry->get_current_id($current_user))
      ?>;
    var data_by_day = <?php echo json_encode($data_by_day_with_names[0])?>;
    var data_by_days = <?php echo json_encode($myOnlineHistiry->get_user_activity_by_days($users, $my_date, $myOnlineHistiry->get_current_id($current_user))) ?>;
    var data_by_users = <?php echo json_encode($myOnlineHistiry->get_activity_by_user($users, $myOnlineHistiry->get_current_id($current_user))) ?>;
    var php_names = <?php echo json_encode($data_by_day_with_names[1])?>;
    //FIXED bug with incorrect user names if sorting id in data and names is different
    //TODO check php query before json encode

    series_activity_user_by_day = generate_array_for_graphs(data_by_day, php_names, 24);
    series_activity_by_user = generate_array_for_graphs(data_by_users, php_names, 24);
    series_activity_user_by_days = generate_array_for_graphs(data_by_days, php_names, 315);
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
include 'includes/chart_interval.php';
include 'includes/end.php';
?>