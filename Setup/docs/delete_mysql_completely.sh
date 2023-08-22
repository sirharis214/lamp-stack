#!/bin/bash

# Step 1: Stop MySQL Service
sudo service mysql stop
# Alternatively: sudo systemctl stop mysql

# Step 2: Remove MySQL Packages
sudo apt-get remove --purge mysql-server mysql-client mysql-common mysql-server-core-* mysql-client-core-*

# Step 3: Remove MySQL Data and Configuration Files
sudo rm -rf /etc/mysql /var/lib/mysql /var/log/mysql

# Step 4: Clean Up Dependencies
sudo apt-get autoremove

# Step 5: Remove MySQL User and Group
sudo deluser mysql
sudo delgroup mysql

# Step 6: Update System Cache
sudo apt-get update

# Step 7: Verify Removal
if ! command -v mysql &> /dev/null; then
    echo "MySQL has been successfully removed."
else
    echo "MySQL removal might not be complete."
fi

