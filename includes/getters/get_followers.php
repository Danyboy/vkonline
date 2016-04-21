<?php 
include '../../online_table.php';

class OnlineHistoryFollowers extends OnlineHistory{

	function get_all_followers($current_user){
		$count_query = "
		SELECT * FROM followers;
		";

		return $this->query_to_json($count_query);
	}

	function show_all_followers($current_user){
        
    	    $current_user = $this->get_current_id($current_user);
    	    
    	    foreach (json_decode($this->get_all_followers($current_user)) as $row) {
                //$my_time = date('H \ч i \м', mktime(0,$row[3]));
                echo "
                <tr hight>
                        <td>
                                <input type='checkbox' name='mycheckbox' value='{$row[0]}'>
                        </td>";
 
                        $this->show_chart($current_user, $row[0], "Test Name", "//vk.com/images/camera_50.png");
        
                        echo "
                        <td><a href='c?u={$current_user}&cu={$row[0]}'>
                                {$my_time} <br> <img src='img/heart.png' alt='Test Name' alight='right'
                                title='Показать совместимость Test Name c другими пользователями'>
                        </a></td>
                </tr>";
	    }
	}

}

$myOnlineHistoryFollowers = new OnlineHistoryFollowers();

$myOnlineHistoryFollowers->show_all_followers($_GET['u']);


?>