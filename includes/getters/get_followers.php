<?php
include '../../online_table.php';

class OnlineHistoryFollowers extends OnlineHistory
{

  function show_all_followers($current_user)
  {

    $current_user = $this->get_current_id($current_user);

    foreach (json_decode($this->get_all_followers($current_user)) as $row) {
      $follower = json_decode($this->get_users_online_hours($row[0]))[0];

      echo "
                <tr hight>
                        <td>
                                <input type='checkbox' name='mycheckbox' value='{$row[0]}'>
                        </td>";

      $this->show_followers_chart($current_user, $row[0], $follower[2], $follower[1]);

      echo "
                        <td><a href='c?u={$row[0]}&cu={$row[0]}'>
                                {$follower[3]} <br> <img src='img/heart.png' alt='$follower[2]' alight='right'
                                title='Показать совместимость {$follower[2]} c другими пользователями'>
                        </a></td>
                </tr>";

    }
  }

  function get_all_followers($current_user)
  {
    $count_query = "
		SELECT * FROM followers;
		";

    return $this->query_to_json($count_query);
  }

  function get_users_online_hours($current_user)
  {

    $my_date = $this->get_correct_date();

    $count_query = "
            SELECT id, link, name FROM users{$current_user}  WHERE (id = {$current_user});
            ";

    $count_query_with_time = "
	    SELECT user_id, link, name, COUNT (EXTRACT(hour FROM status)) * 5 AS minutes 
            FROM online{$current_user} JOIN users{$current_user} ON (online{$current_user}.user_id = users{$current_user}.id)
            WHERE (user_id = {$current_user}) AND DATE(status) = '{$my_date}'
            GROUP BY user_id, link, name ORDER BY minutes DESC;
            ";

    return $this->query_to_json($count_query);
  }

  function show_followers_chart($current_user, $chart_uid, $user_name_spaced, $img)
  {
    $user_name = str_replace(" ", "<br>", "{$user_name_spaced}");

    echo "
	<td>
		<div class='layout'>
			<div class='col1'>
				<a href='//vk.com/id{$chart_uid}' target='_blank'>
					<img src='{$img}' title='$user_name_spaced'></a>
				</div>
				<div class='col2'>
					<a 
					href=\"/?u=" . $chart_uid .
      "\" id=\"date_link\"
					onclick=\"
					location.href=this.href+get_date_and_users();return false;
					\">
					{$user_name}
				</a>
			</div>
			<div class='col3'>
				<a 
				href=\"u?u=" . $chart_uid .
      "&users=[" . $chart_uid . "," . $current_user . "]\" id=\"date_link\"
				onclick=\"
				location.href=this.href+get_date_and_users();return false;
				\">
				<img src='img/chart.png' align='right' 
				title='Сравнить график активности с {$user_name_spaced}'>
			</a>
		</div>
	</div>
</td>

";
  }


}

$myOnlineHistoryFollowers = new OnlineHistoryFollowers();

$myOnlineHistoryFollowers->show_all_followers($_GET['u']);


?>