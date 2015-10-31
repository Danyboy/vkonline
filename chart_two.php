<script type="text/javascript">
jQuery.noConflict();

var example = 'areaspline', 
theme = 'default';
(function($){ // encapsulate jQuery
    $(function () {
    $('#container_day').highcharts({
        chart: {
            type: 'areaspline'
        },
        title: {
            text: 'Сколько часов вы были онлайн с 12.12.14 по <?php echo $myOnlineHistiry->get_correct_date(json_decode($_GET['d'])[1]); ?>'
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
            categories[1],
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
        plotOptions: {
            areaspline: {
                fillOpacity: 0.5
            }
        },
        series: series_activity_user_by_days
    });
});
})(jQuery);
jQuery(document).ready(function(){jQuery("#view-menu").click(function(e){jQuery("#wrap").toggleClass("toggled")}),jQuery("#sidebar-close").click(function(e){jQuery("#wrap").removeClass("toggled")}),jQuery(document).keydown(function(e){var t;"INPUT"!=e.target.tagName&&(39==e.keyCode?t=document.getElementById("next-example"):37==e.keyCode&&(t=document.getElementById("previous-example")),t&&(location.href=t.href))}),jQuery("#switcher-selector").bind("change",function(){var e=jQuery(this).val();return e&&(window.location=e),!1})});
</script>