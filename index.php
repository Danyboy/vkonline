<?php
include '/home/danil/Projects/vkonline/start.php';
?>

<!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
      <div class="container">
        <p> Привет! Здесь можно узнать сколько времени вы провели онлайн вконтакет <?php echo $myOnlineHistiry->get_correct_date($_GET['d']); ?> или в <a href="u?users=[339229,749972,42606657]&d=24.02.15">любую другую дату</a>.
            Есть поминутная статистика и <a href="u?users=[339229,749972,42606657]&d=">красочные графики</a>. </p>
      </div>
    </div>
    <div class="container-fluid">
      <div class="row">
      
<div>
  <div style="display: table; margin: 0 auto;">

<?php
include '/home/danil/Projects/vkonline/middle.php';
$myOnlineHistiry->show_today_online_users($_GET['d']);
?>

 </div>
</div>

<?php

include '/home/danil/Projects/vkonline/end.php';
?>