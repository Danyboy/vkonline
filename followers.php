<?php
include 'includes/start.php';
?>
  <!-- Main jumbotron for a primary marketing message or call to action -->
  <div class="jumbotron">
    <div id="main_info" class="container">
      <p>
        Все пользователи сервиса.
      </p>
      <div style="width: 10%; margin: 0 auto;">
        <div id="login_button" onclick="VK.Auth.login(authInfo);"></div>
      </div>
    </div>
  </div>

  <div class="container-fluid">
    <div class="row">

      <div class="" id="scroll-table">
        <div stylee=" width: 807px; display: table; margin: 0 auto;">
          <div class="table-responsive">
            <table class="table" id="users_statistics">
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
                  ajaxStart: function () {
                    $body.addClass("loading");
                  },
                  ajaxStop: function () {
                    $body.removeClass("loading");
                  }
                });

                $.get("includes/getters/get_followers?&u=<?php echo "{$_GET['u']}&d={$_GET['d']}";?>", function (data, status) {
                  document.getElementById('ajaxTable').innerHTML = data;
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