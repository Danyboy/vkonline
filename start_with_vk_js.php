

<?php
include '/home/danil/Projects/vkonline/start.php';
?>
<!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
      <div class="container">
        <p> Привет! Здесь можно узнать сколько времени вы провели онлайн вконтакет <?php echo $myOnlineHistiry->get_correct_date($_GET['d']); ?> или в <a href="u?users=[339229,749972,42606657]&d=24.02.15">любую другую дату</a>.
            Есть поминутная статистика и <a href="u?users=[339229,749972,42606657]&d=">красочные графики</a>. </p>
	
	<div style="width: 10%; margin: 0 auto;">
	    <div id="login_button" onclick="VK.Auth.login(authInfo);"></div>
	</div>
      </div>
    </div>

<script language="javascript">
VK.init({
        apiId: 5121918
});
function authInfo(response) {
  if (response.session) {
//    alert('user: '+response.session.mid);
  } else {
//    alert('not auth');
  }
}
VK.UI.button('login_button');
VK.Auth.getLoginStatus(authInfo);
</script>

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



<?php
include '/home/danil/Projects/vkonline/end.php';
?>