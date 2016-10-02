class MyDate {

function get_correct_date($my_date = ''){
date_default_timezone_set('Europe/Moscow');
if (empty($my_date)){
$my_date = date("d.m.y");
} else if (DateTime::createFromFormat('d.m.y', $my_date) == FALSE){
$my_date = date("d.m.y");
}

return $my_date;
}

function get_correct_date_interval($my_date){
if (json_decode($my_date)){
$my_date = json_decode($my_date);
}
if (is_array($my_date)){
$my_date_start = $this->get_correct_date($my_date[0]);
$my_date_end = $this->get_correct_date($my_date[1]);
} else if ($my_date !== '' && $this->get_correct_date($my_date) !== $this->get_correct_date()){
$my_date_start = $this->get_correct_date($my_date);
$my_date_end = $this->get_correct_date();
} else {
$my_date_start = "01.01.15";
$my_date_end = $this->get_correct_date($my_date);
}

return array($my_date_start,$my_date_end);
}

function get_previous_dates($int, $my_date){
$date = date_create_from_format('d.m.y', $this->get_correct_date($my_date));
date_sub($date, date_interval_create_from_date_string($int . ' days'));

return date_format($date, 'd.m.y');
}

function show_previous_dates($my_date){
for ($i = 3; $i > -2; $i --){
$current_date = $this->get_previous_dates($i, $my_date);
$current_url = preg_replace("/&d=.*/", '', $_SERVER['REQUEST_URI']);
$my_link = preg_match("/\?/",$current_url) ? $current_url . "&d=" . $current_date : $current_url . "?&d=" . $current_date ;
echo "
<li><a href='{$my_link}' data-toggle='collapse' data-target='.navbar-collapse'>" . $current_date . "</a></li>";
}
}
}