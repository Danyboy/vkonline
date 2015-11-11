<?php
include 'includes/start.php';
?>
<!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
      <div id="main_info" class="container">
        <p> Привет! Здесь можно узнать сколько времени вы провели онлайн вконтакте 
	    <input class="datepicker" data-date-format="dd.mm.yy" size="5"
	     onkeydown="if (event.keyCode == 13) document.getElementById('date_link').click()"
	     value=<?php echo $myOnlineHistiry->get_correct_date($_GET['d']); ?>> 
    	    или в 
	    <a href="u?users=[339229,749972,42606657]" id="date_link"
	     onclick="my_date=$('.datepicker').val(); location.href=this.href+'&d='+my_date;return false;">
    	    любую другую дату</a>.
            Есть поминутная статистика и <a href="u?users=[339229,749972,42606657]&d=">красочные графики</a>. 
        </p>
	<div style="width: 10%; margin: 0 auto;">
	    <div id="login_button" onclick="VK.Auth.login(authInfo);"></div>
	</div>
      </div>
    </div>

    <div class="container-fluid">
      <div class="row">

<div>
  <div style="display: table; margin: 0 auto;">
<div class="table-responsive">
            <table class="table table-striped" id="users_statistics">
              <thead>
                <tr>
                  <th>Графики пользователя</th>
                  <th>Коэфф бессоницы</th>
                  <th>Онлайн ночью</th>
                  <th>Онлайн днём</th>
                </tr>
              </thead>
              <tbody>

<?php
$myOnlineHistiry->show_insomnia_users();
?>

<script language="javascript">
VK.init({
        apiId: 5121918
});

function authInfo(response) {
    if (response.session) {
        add_logged_user(response.session.mid);
        document.getElementById('login_button').style.display = 'none';
        change_info_for_logged(response.session.mid);
    } else {
	//alert('not auth');
  }
}

function change_info_for_logged(id){
    document.getElementById("main_info").innerHTML = document.getElementById("main_info").innerHTML.replace(/749972/g, id);
}

function add_logged_user(id) {
    var table = document.getElementById('users_statistics');
    var reg = new RegExp("id"+id, "g");
    var tbody = table.children[0];

    for (var r = 0; r < table.rows.length; r++) {
        var current_row = table.rows[r];
        if (reg.test(current_row.innerHTML)){
    	    tbody.insertBefore(current_row, table.rows[0]);
        }
    }
}

VK.UI.button('login_button');
VK.Auth.getLoginStatus(authInfo);

$(".datepicker").datepicker({
    format: "dd.mm.yy",
    startDate: "22/12/14",
    endDate: new Date(),
    autoclose: true,
    todayHighlight: true
});

function get_checked_users(input){
    var users = new Array(input.length);
    for (i = 0; i < input.length; i++){
        users[i] = input[i].value;
    }

    return users;
}
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