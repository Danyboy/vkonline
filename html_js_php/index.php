<?php
include '/home/danil/Projects/vkonline/html_js_php/start.php';
include '/home/danil/Projects/vkonline/html_js_php/middle.php';
//include '/home/danil/Projects/vkonline/html_js_php/online_table.php';

//$myOnlineHistiry = new OnlineHistory();
$myOnlineHistiry->show_today_online_users($_GET['d']);

include '/home/danil/Projects/vkonline/html_js_php/end.php';
?>