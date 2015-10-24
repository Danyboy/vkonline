#!/bin/sh

for i in {0..23}; do
    echo "insert into hour (id) values ('${i}');"
done


