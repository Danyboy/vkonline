#!/bin/bash

users_all="385525 42606657 3413726 3397500 434743 12820754 300441800 690765 53083705 715515 8238547 3083771 836968 91794718 18333099 37183583 347129 339190723 14917 71455857 356773529 17517496 1502541 928611 189814"

add_follower(){
    curl --data "user=$1" http://127.1:80/includes/add_follower.php
    #curl --data "user=$1" http://91.232.225.25:43380/includes/add_follower.php
}

add_followers(){
    for i in $users_all ; do
        add_follower $i
    done
}

add_followers