<?php 
include '../online_table.php';

class OnlineHistoryCompatibility extends OnlineHistory{

	function get_users_compatibility($user){
	
                $count_query = "
			SELECT id, link, name, count, coef
			FROM (
			    SELECT my_users.user_id, COUNT (*) AS count, COUNT(*)::float / user_coef.norm AS coef
			    FROM user_online LEFT JOIN user_online AS my_users
			        ON my_users.status = user_online.status
			        INNER JOIN (SELECT user_id, COUNT(*) AS norm
				FROM user_online
				GROUP BY user_id) AS user_coef
				    ON user_coef.user_id = my_users.user_id
			    WHERE user_online.user_id = {$user}
			    GROUP BY user_online.user_id, my_users.user_id, user_coef.norm
			    ORDER BY count DESC
			) AS my_comp
			JOIN users 
			ON (my_comp.user_id = id);
				";

		return $this->query_to_json($count_query);
	}

	function show_users_compatibility($user){
		foreach (json_decode($this->get_users_compatibility($user)) as $row) {
		    $num = number_format($row[4], 2, '.', '');
		    $time_together = number_format($row[3] / 12, 0, '.', '');
		    echo "<tr>
			<td><input type='checkbox' name='mycheckbox' value='{$row[0]}'></td>
			<td><a href='http://vk.com/id{$row[0]}'>
			    <img src='{$row[1]}' alt='$row[2]'></a>
			    <a href='./u?users=[{$row[0]},385525,690765]&d={$my_date}'>
			    {$row[2]}	<img src='Chart-icon.png' alt='$row[2]' align='right'></a></td>
			<td>{$num}</td>
			<td>{$time_together} ч</td>
		      </tr>";
	        }
	}
	
}

?>