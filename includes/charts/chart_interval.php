<?php 
include 'includes/start.php';

$my_date = $_GET['d'];
$users = json_decode($_GET['users']);
$current_user = $_GET['u'];
?>
<script src="//code.highcharts.com/highcharts.js"></script>

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

    $(document).ready(function() {
        var chart = new Highcharts.Chart({
           chart: {
               renderTo: 'chart_days',
               type: 'areaspline',
               events: {
                   load: function(){
                      request_data(
                          'includes/getters/get_users_activity_by_days?&u=<?php echo $current_user;?>&users=<?php echo json_encode($users);?>&d=<?php echo $my_date;?>'
                          , this);
                      this.showLoading();
                  }
              }
          },
          title: {
            text: 'Сколько часов вы были онлайн с <?php echo $myOnlineHistiry->get_correct_date_interval($_GET['d'])[0]; ?>
            по <?php echo $myOnlineHistiry->get_correct_date_interval($_GET['d'])[1]; ?>'
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
            categories: 
            categories[2],
            //[1,2],
            title: {
                text: 'День'
            }
        },
        yAxis: {
            title: {
                text: 'Часов онлайн'
            }
        },
        tooltip: {
            shared: true,
            valueSuffix: ' часов'
        },
        credits: {
            enabled: false,
        },
        tooltip: {
            valueDecimals: 2
        },
        plotOptions: {
            areaspline: {
                fillOpacity: 0.5
            }
        }
    });
    });
</script>

<?php 
include 'includes/end.php';
?>