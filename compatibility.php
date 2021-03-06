<?php
include 'includes/start.php';
?>
  <!-- Main jumbotron for a primary marketing message or call to action -->
  <div class="jumbotron">
    <div id="main_info" class="container">
      <p> Здесь можно узнать свой коэффициент совместимости с разными пользователями - сколько часов вы провели онлайн
        вместе.
        Для сортировки по определённому столбцу нужно нажать на его заголовок.
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
                  location.href=this.href+get_date_and_users();return false;">
                    С</a>
                </th>
                <th class="sorttable_nosort"> Пользователи</th>
                <th class="sorttable_numeric"> Онлайн <br> вместе</th>
                <th class="sorttable_numeric"> Коэф. <br> совместимости</th>
                <th class="sorttable_numeric"> Твой процент онлайн вместе</th>
                <th class="sorttable_numeric"> Их процент онлайн вместе</th>
              </tr>
              </thead>
              <tbody>

              <?php
              include 'includes/getters/get_compatibility.php';
              $myOnlineCompatibility = new OnlineHistoryCompatibility();
              $myOnlineCompatibility->show_users_compatibility($_GET['cu'], $_GET['u']);
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

                VK.UI.button('login_button');
                VK.Auth.getLoginStatus(authInfo);

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