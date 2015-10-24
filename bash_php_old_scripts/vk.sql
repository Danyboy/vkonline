
--postgres-# create database vk;
--CREATE DATABASE

--create table users (id int primary key, name varchar, link varchar);
--create table user_online (user_id int, status timestamp, foreign key (user_id) references users (id));

--insert into users (id, name, link) values ($my_id, '${my_name}', '${my_link}');
--insert into user_online (user_id, status) value ($my_id, to_timestamp($my_date));


create table hours (hour int primary key);

insert into hours (hour) values ('0');
insert into hours (hour) values ('1');
insert into hours (hour) values ('2');
insert into hours (hour) values ('3');
insert into hours (hour) values ('4');
insert into hours (hour) values ('5');
insert into hours (hour) values ('6');
insert into hours (hour) values ('7');
insert into hours (hour) values ('8');
insert into hours (hour) values ('9');
insert into hours (hour) values ('10');
insert into hours (hour) values ('11');
insert into hours (hour) values ('12');
insert into hours (hour) values ('13');
insert into hours (hour) values ('14');
insert into hours (hour) values ('15');
insert into hours (hour) values ('16');
insert into hours (hour) values ('17');
insert into hours (hour) values ('18');
insert into hours (hour) values ('19');
insert into hours (hour) values ('20');
insert into hours (hour) values ('21');
insert into hours (hour) values ('22');
insert into hours (hour) values ('23');


