<?php
include 'includes/start.php';
?>
  <!-- Main jumbotron for a primary marketing message or call to action -->
  <div class="jumbotron">
    <div id="main_info" class="container">
      <p> Здесь можно узнать свой коэффициент бессоницы - отношение времени онлайн ночью к времени онлайн днём. <br>
        И сколько часов в какое время дня вы провели онлайн за год. Ночью считается время с 0 до 8 утра.
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
            <table class="table table-striped sortable" id="users_statistics">
              <thead>
              <tr>
                <th class="sorttable_nosort">
                  <a
                    title="Сравнить графики отмеченных пользователей"
                    href="u?u=<?php echo $myOnlineHistiry->get_current_id($_GET['u']); ?>"
                    onclick="
                  location.href=this.href+get_date_and_users();return false;
                  ">
                    Сравнить</a>
                </th>
                <th class="sorttable_nosort"> Пользователи</th>
                <th class="sorttable_numeric"> Коэф. бессоницы</th>
                <th class="sorttable_numeric"> Онлайн ночью</th>
                <th class="sorttable_numeric"> Онлайн днём</th>
                <th class="sorttable_numeric"> Всего</th>
                <th class="sorttable_numeric"> Взвешенный К</th>
              </tr>
              </thead>
              <tbody>

              <?php
              include 'includes/getters/get_insomnia.php';
              $myOnlineInsomnia = new OnlineHistoryInsomnia();
              $myOnlineInsomnia->show_insomnia_users($_GET['u']);
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