<?php 
include '../online_table.php';

class OnlineHistoryCompatibility extends OnlineHistory{

	function get_users_compatibility($user, $current_user){
	
                $count_query = "
			SELECT id, link, name, hours_together, coef
			FROM (
			    SELECT my_users.user_id, COUNT (*)::float / 12 AS hours_together, COUNT(*)::float / user_coef.their_hours AS coef
			    FROM online{$current_user} LEFT JOIN online{$current_user} AS my_users
			        ON my_users.status = online{$current_user}.status
			        INNER JOIN (SELECT user_id, COUNT(*) AS their_hours
				FROM online{$current_user}
				GROUP BY user_id) AS user_coef
				    ON user_coef.user_id = my_users.user_id
			    WHERE online{$current_user}.user_id = {$user}
			    GROUP BY online{$current_user}.user_id, my_users.user_id, user_coef.their_hours
			    ORDER BY hours_together DESC
			) AS my_comp
			JOIN users{$current_user} 
			ON (my_comp.user_id = id);
				";

		return $this->query_to_json($count_query);
	}

	function show_users_compatibility($user, $current_user){
		$current_user = $this->get_current_id($current_user);
		$users_compatibility_table = json_decode($this->get_users_compatibility($user, $current_user));
		foreach ($users_compatibility_table as $row) {
		    $my_coef = number_format($row[4], 2, '.', '');
		    $my_coef_percent = number_format(100 * $my_coef, 0, '.', '');
		    $time_together = number_format($row[3], 0, '.', '');
		    $their_coef = $row[3] / $users_compatibility_table[0][3];
		    $their_coef_percent = number_format(100 * $their_coef, 0, '.', '');
		    $compatibility_coef = number_format($row[4] * $their_coef, 2, '.', '');

		    echo "<tr>
			<td><input type='checkbox' name='mycheckbox' value='{$row[0]}'></td>
			<td><a href='http://vk.com/id{$row[0]}'>
			    <img src='{$row[1]}' alt='$row[2]'></a>

			    <a href='./c?cu={$current_user}&u={$row[0]}'> {$row[2]}	
			    </a>

			    <a href='./u?u={$current_user}&users=[{$row[0]},$this->current_id,$user]&d={$my_date}'>
			    <img src='img/chart.png' alt='$row[2]' align='right'></a></td>
			<td>{$time_together} ч</td>
			<td>{$my_coef_percent}</td>
			<td>{$their_coef_percent}</td>
			<td>{$compatibility_coef}</td>
		      </tr>";
	        }
	}
	
}

?>