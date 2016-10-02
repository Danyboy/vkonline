<div>
  <div id="chart_all_activity" style="min-width: 310px; margin: 0 auto"></div>
</div>

<script type="text/javascript">

  $(document).ready(function () {
    var chart = new Highcharts.Chart({
      chart: {
        renderTo: 'chart_all_activity',
        type: 'areaspline',
        events: {
          load: function () {
            request_data(
              'includes/getters/get_all_users_activity?&u=<?php echo $current_user;?>&users=<?php echo json_encode($users);?>&d=<?php echo $my_date;?>'
              , this, 24);
            this.showLoading();
          }
        }
      },
      title: {
        text: 'Распределение время онлайн за последении два года по часам'
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
          text: 'Часов онлайн'
        }
      },
      tooltip: {
        shared: true,
        valueSuffix: ' часов',
        valueDecimals: 0
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