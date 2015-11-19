<?php 

class OnlineHistoryInsomnia extends OnlineHistory{

	function get_insomnia_users(){
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
			            FROM user_online 
			            GROUP BY user_id
			            ) AS dayNight JOIN users ON (dayNight.user_id = users.id)
			        ORDER BY night DESC;";

		return $this->query_to_json($count_query);
	}

	function show_insomnia_users(){
		foreach (json_decode($this->get_insomnia_users()) as $row) {
		    $num = number_format($row[3], 2, '.', '');
		    $summ = $row[4] + $row[5];
		    $weight = $num * $summ;
		    echo "<tr>
			<td><input type='checkbox' name='mycheckbox' value='{$row[0]}'></td>
			<td><a href='http://vk.com/id{$row[0]}'>
			    <img src='{$row[1]}' alt='$row[2]'></a>
			    <a href='./u?users=[{$row[0]},385525,690765]'>
			    {$row[2]}	<img src='Chart-icon.png' alt='$row[2]' align='right'></a></td>
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