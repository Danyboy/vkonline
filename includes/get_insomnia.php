<?php 
include '../online_table.php';

class OnlineHistoryInsomnia extends OnlineHistory{

	function get_insomnia_users($current_user){
		$count_query = "
		SELECT user_id, link, name, (night::float / (day + 1)) AS stat, night, day
		FROM (
		SELECT user_id, 
		SUM (CASE 
		WHEN EXTRACT(hour FROM status) BETWEEN '0' AND '8'
		THEN 1
		ELSE 0
		END ) AS night,
		SUM (CASE WHEN EXTRACT(hour FROM status) BETWEEN '9' AND '23'
		THEN 1
		ELSE 0
		END ) AS day
		FROM online{$current_user}
		GROUP BY user_id
		) AS dayNight JOIN users{$current_user} ON (dayNight.user_id = users{$current_user}.id)
		ORDER BY night DESC;";

		return $this->query_to_json($count_query);
	}

	function show_insomnia_users($current_user){
		$current_user = $this->get_current_id($current_user);
		foreach (json_decode($this->get_insomnia_users($current_user)) as $row) {
			$num = number_format($row[3], 2, '.', '');
			$summ = $row[4] + $row[5];
			$weight = $num * $summ;
			echo "<tr>
			<td><input type='checkbox' name='mycheckbox' value='{$row[0]}'></td>";

			$this->show_chart($current_user, $row[0], $row[2], $row[1]);
			echo "		    
			<td>{$num}</td>
			<td>{$row[4]} ч</td>
			<td>{$row[5]} ч</td>
			<td>{$summ} ч</td>
			<td>{$weight}</td>
		</tr>";
	}
}

}

?>