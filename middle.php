<script language="javascript">
function get_checked_users(input){
    var users = new Array(input.length);
    for (i = 0; i < input.length; i++){
	users[i] = input[i].value;
    }
    
    return users;
}
</script>

<div class="table-responsive">
            <table class="table table-striped" id="users_statistics">
              <thead>
                <tr>
                  <th>
                    <a href="u?"
                    onclick="my_users=get_checked_users(document.querySelectorAll('input[name=mycheckbox]:checked'));
                    location.href=this.href+'&users=['+my_users+']';return false;">
                    Сравнить</a>
                  </th>
                  <th>Графики пользователя</th>
                  <th>Онлайн сегодня</th>
                </tr>
              </thead>
              <tbody>
