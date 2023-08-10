# Import and export a database 

## User permissions required 
* Use tmp logins to access the database to update user permissions: `sudo cat /etc/mysql/debian.cnf`

```shell
GRANT SELECT, LOCK TABLES, PROCESS ON *.* TO 'haris_db_user'@'localhost';
FLUSH PRIVILEGES;
```
## Export
mysqldump -u your_username -p your_database_name > output_file.sql

## Import
mysql -u your_username -p your_database_name < input_file.sql

