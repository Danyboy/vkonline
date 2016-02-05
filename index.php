<?php
include 'includes/start.php';
?>
<!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron" style="z-index:    1001">
      <div id="main_info" class="container">
      <div id="main_info_overwiev">
	<p> Узнайте сколько времени вы провели онлайн ВКонтакте 
           <input class="datepicker" data-date-format="dd.mm.yy" size="5"
            onkeydown="if (event.keyCode == 13) document.getElementById('date_link').click()"
            value=<?php echo $myOnlineHistiry->get_correct_date($_GET['d']); ?>> 
           или в 
	    <a 
		title="Выберите дату и нажмите на ссылку"
    		href="u?u=<?php echo $myOnlineHistiry->get_current_id($_GET['u']); ?>&users=[<?php echo $myOnlineHistiry->get_current_id($_GET['u']); ?>
		,<?php echo $myOnlineHistiry->get_current_id($_GET['u']); ?>]" id="date_link"
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
	    href="c?u=<?php echo $myOnlineHistiry->get_current_id($_GET['u']); ?>&cu=<?php echo $myOnlineHistiry->get_current_id($_GET['u']); ?>">совместимости <img src='img/heart.png' alt='$row[2]'></a>
        </p>
    </div>
    <div class="alert alert-info" id="login">
	<p>Войдите через ВКонтакте для отображения вашей статистики и сохранения онлайн-истории ваших друзей. </p>
	<div style="width: 10%; margin: 0 auto;">
	    <div id="login_button" data-toggle="tooltip" onclick="VK.Auth.login(authInfo);"
	    title="Войти через ВКонтакте для отображения вашей статистики и сохранения онлайн-истории ваших друзей">
	    </div>
	</div>
	</div>
      </div>
    </div>

    <div class="container-fluid">
      <div class="row">

<div class="" id="scroll-table">
  <div stylee=" width: 807px; display: table; margin: 0 auto;">
<div class="table-responsive">
            <table class="table table" id="users_statistics">
              <thead>
                <tr>
                  <th>
                    <a 
                    title="Сравнить графики отмеченных пользователей"
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
              <tbody id='ajaxTable'>

<script language="javascript">

$body = $('div');

$(document).on({
    ajaxStart: function() { $body.addClass("loading");    },
    ajaxStop: function() { $body.removeClass("loading"); }    
});

$.get("includes/get_table?&u=<?php echo "{$_GET['u']}&d={$_GET['d']}";?>", function(data, status){
    document.getElementById('ajaxTable').innerHTML = data;
});

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
    } else {
        document.getElementById('after_login').style.display = 'none';
	//alert('not auth');
        //document.getElementById('main_info_overwiev').style.display = 'none';
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

$('.datepicker').datepicker()
    .on('changeDate', function(e) {
	document.getElementById('date_link').click()
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
		<div class="modal" id="modal"><!-- Place at bottom of page --></div>

<?php
include 'includes/end.php';
?>