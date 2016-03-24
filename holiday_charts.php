<?php 
include 'includes/start.php';

$my_date = $_GET['d'];
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

<script type="text/javascript">

    var chart;

    function requestData() 
    {
        $.ajax({
            url: 'includes/getters/get_holiday_charts.php?&d=<?php echo $my_date;?>',
            datatype: "json",
            success: function(data) 
            {
               chart.hideLoading();
               chart.series[0].setData(generate_array_for_graphs(data, [2014], 19)[0].data);
               chart.series[1].setData(generate_array_for_graphs(data, [2014], 19)[1].data);
               chart.xAxis[0].setCategories(categories[0]);
           },      
       });
    }

    $(document).ready(function() {
        chart = new Highcharts.Chart({
           chart: {
               renderTo: 'chart_day',
               type: 'spline',
	    //type: 'stockchart',
       height: 550,
       animation: {
        duration: 1000,
        easing: 'easeOutBounce'
    },
    events: {
       load: function(){
          requestData();
          this.showLoading();
      }
  }
},
title: {
    text: 'Сколько часов онлайн были все друзья в новогодние праздники'
},
legend: {
    layout: 'vertical',
    align: 'left',
    verticalAlign: 'top',
    x: 90,
    y: 40,
    floating: true,
    borderWidth: 1,
    backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
},
xAxis: {
    categories: 
    categories[0],
    gridLineWidth: 1,
    title: {
        text: 'Дата'
    }
},
yAxis: {
    floor: 0,
    min: 0.5,
    title: {
        text: 'Нормированных часов'
    }
},
tooltip: {
    shared: true,
    valueSuffix: ' нормированных часов'
},
credits: {
    enabled: false,
},
plotOptions: {
    areaspline: {
        fillOpacity: 0.5
    }
},
series: [{
    name: '2016',
    data: [],
},{
    name: '2015',
    data: [],
    color: '#ffa500',
}]
});
    });
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