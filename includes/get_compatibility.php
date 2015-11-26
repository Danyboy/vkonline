<?php 
include '../online_table.php';

class OnlineHistoryCompatibility extends OnlineHistory{

	function get_users_compatibility($user){
	
                $count_query = "
			SELECT id, link, name, hours_together, coef
			FROM (
			    SELECT my_users.user_id, COUNT (*)::float / 12 AS hours_together, COUNT(*)::float / user_coef.their_hours AS coef
			    FROM user_online LEFT JOIN user_online AS my_users
			        ON my_users.status = user_online.status
			        INNER JOIN (SELECT user_id, COUNT(*) AS their_hours
				FROM user_online
				GROUP BY user_id) AS user_coef
				    ON user_coef.user_id = my_users.user_id
			    WHERE user_online.user_id = {$user}
			    GROUP BY user_online.user_id, my_users.user_id, user_coef.their_hours
			    ORDER BY hours_together DESC
			) AS my_comp
			JOIN users 
			ON (my_comp.user_id = id);
				";

		return $this->query_to_json($count_query);
	}

	function show_users_compatibility($user){
		$users_compatibility_table = json_decode($this->get_users_compatibility($user));
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

			    <a href='./c?u={$row[0]}'> {$row[2]}	
			    </a>

			    <a href='./u?users=[{$row[0]},$this->id,$user]&d={$my_date}'>
			    <img src='Chart-icon.png' alt='$row[2]' align='right'></a></td>
			<td>{$time_together} ч</td>
			<td>{$my_coef_percent}</td>
			<td>{$their_coef_percent}</td>
			<td>{$compatibility_coef}</td>
		      </tr>";
	        }
	}
	
}

?>