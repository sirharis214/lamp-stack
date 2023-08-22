To manually uninstall MySQL server and its configuration files from a server, you can follow these steps. Please note that the exact steps might vary depending on your operating system and package manager. These instructions assume you are using a Debian-based distribution (e.g., Ubuntu) with the `apt` package manager.

## 1. Stop MySQL Service:

First, stop the MySQL service to ensure that no active processes are running:

```sh
sudo service mysql stop
```

Alternatively, you can use the following command:

```sh
sudo systemctl stop mysql
```

## 2. Remove MySQL Packages:

Use the following command to uninstall MySQL packages along with their configuration files:

```sh
sudo apt-get remove --purge mysql-server mysql-client mysql-common mysql-server-core-* mysql-client-core-*
```

## 3. Remove MySQL Data and Configuration Files:

The previous step only removes the packages, not the data and configuration files. You can remove these files manually:

```sh
sudo rm -rf /etc/mysql /var/lib/mysql /var/log/mysql
```

Be cautious when using `rm -rf` as it forcefully removes directories and files without asking for confirmation.

## 4. Clean Up Dependencies:

To remove any leftover dependencies and related packages that were installed alongside MySQL, you can run:

```sh
sudo apt-get autoremove
```

## 5. Remove MySQL User and Group:

If you want to remove the MySQL user and group created during installation, you can use the following commands:

```sh
sudo deluser mysql
sudo delgroup mysql
```

## 6. Update System Cache:

Update the package manager's cache to reflect the changes:

```sh
sudo apt-get update
```

## 7. Verify Removal:

Confirm that MySQL has been completely removed by checking if the MySQL directories and files no longer exist. Additionally, you can try running the following command to ensure that MySQL-related binaries are not found:

```sh
which mysql
```

If the output is empty, MySQL has likely been successfully removed.

Remember that these instructions are specific to Debian-based systems using `apt`. If you're using a different operating system or package manager, the steps might differ. Always exercise caution when removing software and data from your system to avoid unintended data loss or system issues.

