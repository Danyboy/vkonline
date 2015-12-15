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
	    <a href="u?users=[339229,385525,749972]" id="date_link"
	     onclick="my_date=$('.datepicker').val(); location.href=this.href+'&d='+my_date;return false;">
    	    любую другую дату</a>.
            Есть поминутная статистика, <a href="u?users=[339229,385525,749972]&d=">красочные графики</a>,
	    детектор 
	    <a href="insomnia">бессоницы</a> и 
	    <a href="c?u=385525">совместимости <img src='includes/heart_25.png' alt='$row[2]'></a>
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
                  <th>
                    <a href="u?"
                    onclick="my_users=get_checked_users(document.querySelectorAll('input[name=mycheckbox]:checked'));
                    location.href=this.href+'users=['+my_users+']';return false;">
                    Сравнить</a>
                  </th>
                  <th>Графики пользователя</th>
                  <th>Часов онлайн</th>
                </tr>
              </thead>
              <tbody>

<?php
$myOnlineHistiry->show_today_online_users($_GET['d'],$_GET['u']);
?>

<script src="includes/charts_model.js"></script>
<script language="javascript">

VK.init({
        apiId: 5121918
});

function authInfo(response) {
    if (response.session) {
        add_logged_user(response.session.mid);
        document.getElementById('login_button').style.display = 'none';
	//add_follower(response.session.mid);
        change_info_for_logged(response.session.mid);
	//location.replace("/?u=response.session.mid");

	url = location.search;
	if (! url.indexOf(response.session.mid) > -1){
	    //document.location.replace("/?u=" + response.session.mid);
	}
    } else {
	//alert('not auth');
  }
}

function change_info_for_logged(id){
    document.getElementById("main_info").innerHTML = document.getElementById("main_info").innerHTML.replace(/385525/g, id);
}

function add_follower(id) {
    post("includes/add_follower.php", "user=id", "post");
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