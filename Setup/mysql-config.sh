#!/bin/bash

# Must run this script as root
# This script is ment to setup the initial required database
# create db: dev_db
# create user: haris_db_user
# permission for the user as admin only to this db
# create table Users:
# id: primary key, INT, auto increment
# username: varchar, not null
# email
# role
# created_on
# updated_on
# hashed_data: hashed password

db_name="dev_db"
user_name="haris_db_user"
user_pass="p"
table_name="Users"

echo "creating DB: ${db_name}"
mysql -e "CREATE DATABASE ${db_name};"
echo "creating db user"
mysql -e "CREATE USER ${user_name}@localhost IDENTIFIED BY '${user_pass}';"
echo "setting permissions for user"
mysql -e "GRANT ALL PRIVILEGES ON ${db_name}.* TO '${user_name}'@'localhost';"
mysql -e "FLUSH PRIVILEGES;"
echo "creating db table"
mysql -e "USE ${db_name}; CREATE TABLE ${table_name}(id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, username VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL, created_on VARCHAR(255) NOT NULL, updated_on VARCHAR(255), hashed_data VARCHAR(255) NOT NULL);"
