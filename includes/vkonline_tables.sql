
--postgres-# create database vk;
--CREATE DATABASE

--create table users (id int primary key, name varchar, link varchar);
--create table user_online (user_id int, status timestamp, foreign key (user_id) references users (id));

--insert into users (id, name, link) values ($my_id, '${my_name}', '${my_link}');
--insert into user_online (user_id, status) value ($my_id, to_timestamp($my_date));

--UPDATE user_online SET (status = status - INTERVAL(1 HOUR)) WHERE status between '2015-10-25 00:00:00' and '2015-10-26 17:00:00';

UPDATE user_online SET status = status - INTERVAL '1 HOUR' WHERE status between '2015-10-26 17:00:00' and '2015-10-26 19:00:00';

SELECT user_id, EXTRACT(hour FROM status) AS hours, COUNT (EXTRACT(hour FROM status)) * 5 AS count 
                                FROM user_online RIGHT JOIN hours ON hours.hour = hours AND user_id IN (42606657) AND DATE(status) = '21.10.15' 
                                GROUP BY hours, user_id 
                                ORDER BY user_id, hours


CASE WHEN pole BETWEEN '' AND '' 
THEN 1 
ELSE 0
END AS night

EXPLAIN ANALYSE
SELECT user_id, (night::float / (day + 1)) AS stat, night, day
FROM (
    SELECT user_id, 
	SUM (CASE 
	WHEN EXTRACT(hour FROM status) BETWEEN '2' AND '10'
	    THEN 1
	    ELSE 0
	END ) AS night,
	SUM (CASE WHEN EXTRACT(hour FROM status) BETWEEN '11' AND '23'
	    THEN 1
	    WHEN EXTRACT(hour FROM status) BETWEEN '0' AND '2'
	    THEN 1
	    ELSE 0
	END ) AS day
    FROM user_online 
    GROUP BY user_id
    ) AS dayNight
--WHERE (night::float / (day + 1)) > 0.36
ORDER BY stat DESC;


SELECT my_users.user_id, link, name, COUNT (*) AS count, COUNT(*)::float / user_coef.norm AS coef
FROM user_online LEFT JOIN user_online AS my_users 
    ON my_users.status = user_online.status
    INNER JOIN (SELECT user_id, COUNT(*) AS norm 
	FROM user_online 
	GROUP BY user_id) AS user_coef
	    ON user_coef.user_id = my_users.user_id
    INNER JOIN (SELECT link, name, id
	FROM users) AS my_data
	    ON (my_users.user_id = my_data.id) 
WHERE user_online.user_id = 749972 AND my_users.user_id = 749972
GROUP BY user_online.user_id, my_users.user_id, user_coef.norm, my_data.link, my_data.name
ORDER BY coef DESC;