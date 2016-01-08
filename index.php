<?php
include 'includes/start.php';
?>
<!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
      <div id="main_info" class="container">
      <div id="main_info_overwiev">
	<p> Узнайте сколько времени вы провели онлайн ВКонтакте 
           <input class="datepicker" data-date-format="dd.mm.yy" size="5"
            onkeydown="if (event.keyCode == 13) document.getElementById('date_link').click()"
            value=<?php echo $myOnlineHistiry->get_correct_date($_GET['d']); ?>> 
           или в 
	    <a 
		title="Выберите дату и нажмите на ссылку"
    		href="u?u=<?php echo $myOnlineHistiry->get_current_id($_GET['u']); ?>&users=[<?php echo $myOnlineHistiry->get_current_id($_GET['u']); ?>,<?php echo $myOnlineHistiry->get_current_id($_GET['u']); ?>]" id="date_link"
		onclick="
		location.href=this.href+get_date_and_users();return false;
		">
    		любую другую дату</a>.
            Есть поминутная статистика, 
	    <a 
	    title="Отображающие вашу дневную онлайн активность по часам"
    		href="u?u=<?php echo $myOnlineHistiry->get_current_id($_GET['u']); ?>&users=[<?php echo $myOnlineHistiry->get_current_id($_GET['u']); ?>,
		<?php echo $myOnlineHistiry->get_current_id($_GET['u']); ?>]" id="date_link"
		onclick="
		location.href=this.href+get_date_and_users();return false;
	    ">
	    красочные графики</a>,
	    детектор 
	    <a href="insomnia?u=<?php echo $myOnlineHistiry->get_current_id($_GET['u']); ?>" 
	    title="Покажет сколько часов вы были онлайн днём и ночью, и отношение этих величин">бессоницы</a> и 
	    <a 
	    title="Покажет сколько времени вы были онлайн одновременно с другими пользователям"
	    href="c?cu=<?php echo $myOnlineHistiry->get_current_id($_GET['u']); ?>&u=<?php echo $myOnlineHistiry->get_current_id($_GET['u']); ?>">совместимости <img src='img/heart.png' alt='$row[2]'></a>
        </p>
    </div>
    <div class="alert alert-info" id="login">
	<div style="width: 10%; margin: 0 auto;">
	    <div id="login_button" data-toggle="tooltip" onclick="VK.Auth.login(authInfo);"
	    title="Войти через ВКонтакте для отображения вашей статистики и сохранения онлайн-истории ваших друзей">
	    </div>
	</div>
	<p>Войдите через ВКонтакте для отображения вашей статистики и сохранения онлайн-истории ваших друзей. </p>
	</div>
      </div>
    </div>

    <div class="container-fluid">
      <div class="row">

<div class="" id="scroll-table">
  <div stylee=" width: 807px; display: table; margin: 0 auto;">
<div class="table-responsive">
            <table class="table table-striped" id="users_statistics">
              <thead>
                <tr>
                  <th>
                    <a 
                    title="Графики отмеченных пользователей"
                    href="u?u=<?php echo $myOnlineHistiry->get_current_id($_GET['u']); ?>"
                    onclick="
                    location.href=this.href+get_date_and_users();return false;
		    ">
                    Сравнить</a>
                  </th>
                  <th>Графики пользователя</th>
                  <th>Онлайн</th>
                </tr>
              </thead>
              <tbody>

<?php
$myOnlineHistiry->show_today_online_users($_GET['d'],$_GET['u']);
?>

<script language="javascript">

VK.init({
        apiId: 5121918
});

function authInfo(response) {
    if (response.session) {
	id = response.session.mid;
        add_logged_user(response.session.mid);
	add_follower(response.session.mid);
	//stateChange(response.session.mid);
	set_user_url(id);
	change_info_for_logged(id);
        document.getElementById('login').style.display = 'none';
        document.getElementById('after_login').style.display = 'none';
    } else {
	//alert('not auth');
        document.getElementById('main_info_overwiev').style.display = 'none';
  }
}

if (window!=window.top) {
    document.getElementById('scroll-table').className = 'scrollit';
}

VK.UI.button('login_button');
VK.Auth.getLoginStatus(authInfo);

$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip(); 
});

$(".datepicker").datepicker({
    format: "dd.mm.yy",
    startDate: "22/12/14",
    endDate: new Date(),
    autoclose: true,
    todayHighlight: true
});

</script>

 </div>
</div>

                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

<?php
include 'includes/end.php';
?>