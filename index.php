<?php
include '/home/danil/Projects/vkonline/start.php';
include '/home/danil/Projects/vkonline/middle.php';

$myOnlineHistiry->show_today_online_users($_GET['d']);

include '/home/danil/Projects/vkonline/end.php';
?>