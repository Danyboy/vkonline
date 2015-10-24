SELECT user_id, EXTRACT(hour FROM status) AS hours, COUNT (EXTRACT(hour FROM status)) * 5 AS count 
                                FROM user_online RIGHT JOIN hours ON hours.hour = hours AND user_id IN (42606657) AND DATE(status) = '21.10.15' 
                                GROUP BY hours, user_id 
                                ORDER BY user_id, hours