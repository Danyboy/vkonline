#!/bin/bash

my_run(){
    cd /var/www/vko/
    php ./online_table.php get_followers | tr ' ' '\n' | xargs -n1 -i --max-procs=10 bash -c "php ./online_table.php add_user {}"
}

my_run

#PHP
#[danil@vk vkonline]$ time php ./online_table.php add_data
#1.42user 0.35system 1:35.46elapsed 1%CPU (0avgtext+0avgdata 43012maxresident)k
#544inputs+0outputs (4major+12106minor)pagefaults 0swaps

#BASH
#2.17user 1.27system 0:21.19elapsed 16%CPU (0avgtext+0avgdata 42996maxresident)k
#0inputs+0outputs (0major+202401minor)pagefaults 0swaps

#[danil@efnez vkonline]$ time php online_table.php add_data
#1.70user 0.19system 0:54.11elapsed 3%CPU (0avgtext+0avgdata 49476maxresident)k
#3512inputs+0outputs (54major+23485minor)pagefaults 0swaps
#[danil@efnez vkonline]$ 
#[danil@efnez vkonline]$ 
#[danil@efnez vkonline]$ 
#[danil@efnez bash]$ time ./update_status.sh 
#2.64user 1.93system 0:08.11elapsed 56%CPU (0avgtext+0avgdata 49580maxresident)k
#8inputs+0outputs (0major+371068minor)pagefaults 0swaps

