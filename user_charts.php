<?php 
include '/home/danil/Projects/vkonline/start.php';

class OnlineHistoryCharts extends OnlineHistory{
	
	function get_all_users_activity_by_day($my_date){
		
		$count_query = "SELECT EXTRACT(hour FROM status) AS hours, COUNT (EXTRACT(hour FROM status)) * 5 AS count 
                                FROM user_online 
                                WHERE DATE(status) = '11-8-2015' GROUP BY hours ORDER BY hours;";
                                
                $most_popular_hour = "SELECT EXTRACT(hour FROM status) AS hours, COUNT (user_id)
                                FROM user_online 
                                GROUP BY hours 
                                ORDER BY hours;";
		
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

	function get_activity_days_by_users($users){
		
		$users_string = implode(",", $users);
		$count_query = "Select user_id, Count(*)
				From (
				SELECT user_id, status::timestamp::date AS day
                                FROM user_online 
                                WHERE user_id IN ({$users_string}) 
                                GROUP BY day, user_id 
                                ORDER BY day, user_id) 
                                AS mycount
                                GROUP BY user_id
				ORDER BY user_id;";
		
		return $this->query_to_json($count_query);
	}

	function get_activity_by_user($users){
		
		$users_string = implode(",", $users);
		$count_query = "SELECT user_id, EXTRACT(hour FROM status) AS hours, COUNT (EXTRACT(hour FROM status)) / 12 AS count 
				FROM user_online 
				WHERE user_id IN ({$users_string}) 
				GROUP BY hours, user_id 
				ORDER BY user_id, hours;";
		
		return $this->query_to_json($count_query);
	}

	function get_user_activity_by_day($users, $my_date){
	
		$my_date = $this->get_correct_date($my_date);		
		
		$users_string = implode(",", $users);
		$count_query = "SELECT user_id, EXTRACT(hour FROM status) AS hours, COUNT (EXTRACT(hour FROM status)) * 5 AS count 
                                FROM user_online
                                WHERE user_id IN ({$users_string}) AND DATE(status) = '{$my_date}' 
                                GROUP BY hours, user_id 
                                ORDER BY user_id, hours;";
		
		return $this->query_to_json($count_query);
	}
	
	function get_user_activity_by_days($users, $my_date){
		if (is_array($my_date)){
		    $my_date_start = $this->get_correct_date($my_date[0]);
		    $my_date_end = $this->get_correct_date($my_date[1]);
		} else {
		    $my_date_start = "2015-09-11";
		    $my_date_end = $this->get_correct_date($my_date);
		}

		$users_string = implode(",", $users);
		$count_query = "SELECT user_id, status::timestamp::date AS day, COUNT (EXTRACT(hour FROM status)) / 12 AS count 
                                FROM user_online
                                WHERE user_id IN ({$users_string}) AND status between '{$my_date_start}' and '{$my_date_end} 23:59:59' 
                                GROUP BY user_id, day 
                                ORDER BY user_id, day;";
		
		return $this->query_to_json($count_query);
	}
	
	function get_current_users_name($users){
		$users_string = implode(",", $users);
		$count_query = "SELECT name FROM users WHERE id IN ({$users_string}) ORDER BY id;";
		return $this->query_to_json($count_query);
	}
}

$users = json_decode($_GET['users']);
$my_date = $_GET['d'];
$myOnlineHistiry = new OnlineHistoryCharts();
?>

<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>
<script src="http://code.highcharts.com/highcharts.js"></script>
<script src="includes/charts_model.js"></script>

<script type="text/javascript">
var data_by_day = <?php echo json_encode($myOnlineHistiry->get_user_activity_by_day($users, $my_date)) ?>;
var data_by_days = <?php echo json_encode($myOnlineHistiry->get_user_activity_by_days($users, json_decode($my_date))) ?>;
var data = <?php echo json_encode($myOnlineHistiry->get_activity_by_user($users)) ?>;
var php_names = <?php echo json_encode($myOnlineHistiry->get_current_users_name($users)) ?>;
//TODO bug with uncorrect user names if sorting id in data and names is different 
//TODO check php query before json encode

$('#interval .input-daterange').datepicker({
    format: "dd.mm.yy",
    startDate: "22/12/14",
    endDate: new Date(),
    autoclose: true,
    todayHighlight: true
});

my_init();
</script>

<div>
<div id="chart_day" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
<div id="chart_year" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
</div>

<?php
include '/home/danil/Projects/vkonline/chart_day.php';
include '/home/danil/Projects/vkonline/chart_year.php';
?>

<div class="jumbotron">
    <div class="container" id="interval">
    <div class="input-daterange input-group" id="datepicker">
    <span class="input-group-addon">Сколько часов вы были онлайн с </span>
    <input type="text" class="input-sm form-control" name="start" data-date-format="dd.mm.yy" size="5" value="01.10.15"/>
    <span class="input-group-addon">по</span>
    <input type="text" class="input-sm form-control" name="end" data-date-format="dd.mm.yy" size="5" value="<?php echo $myOnlineHistiry->get_correct_date($_GET['d']); ?>"/>
    <span class="input-group-addon">
        <a href="" onclick=
	"my_date=get_range(); location.href=window.location.href+'&d=['+'%22'+my_date[0]+'%22'+','+'%22'+my_date[1]+'%22'+']';return false;">
        Узнать</a>
    </span>
    </div>
    </div>
</div>

<div id="chart_interval" style="min-width: 310px; height: 400px; margin: 0 auto"></div>

<?php 
include '/home/danil/Projects/vkonline/chart_interval.php';
include '/home/danil/Projects/vkonline/end.php';
?>