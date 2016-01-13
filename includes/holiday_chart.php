<script type="text/javascript">
//jQuery.noConflict();

var example = 'areaspline', 
theme = 'default';
(function($){ // encapsulate jQuery
    $(function () {
    $('#chart_day').highcharts({
        chart: {
            type: 'areaspline'
            //type: 'spline'
        },
        title: {
	    text: 'Сколько часов были онлайн все ваши друзья с <?php echo $myOnlineHistiry->get_correct_date_interval($_GET['d'])[0]; ?> по <?php echo $myOnlineHistiry->get_correct_date_interval($_GET['d'])[1]; ?>'
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
            categories[0],
            //[1,2],
            title: {
                text: 'Дата'
            }
        },
        yAxis: {
            title: {
                text: 'часов онлайн'
            }
        },
        tooltip: {
            shared: true,
            valueSuffix: ' часов'
        },
        credits: {
            enabled: false,
        },
        plotOptions: {
            areaspline: {
                fillOpacity: 0.5
            }
        },
        series: series_activity_user_by_day
    });
});
})(jQuery);
jQuery(document).ready(function(){jQuery("#view-menu").click(function(e){jQuery("#wrap").toggleClass("toggled")}),jQuery("#sidebar-close").click(function(e){jQuery("#wrap").removeClass("toggled")}),jQuery(document).keydown(function(e){var t;"INPUT"!=e.target.tagName&&(39==e.keyCode?t=document.getElementById("next-example"):37==e.keyCode&&(t=document.getElementById("previous-example")),t&&(location.href=t.href))}),jQuery("#switcher-selector").bind("change",function(){var e=jQuery(this).val();return e&&(window.location=e),!1})});
</script>
