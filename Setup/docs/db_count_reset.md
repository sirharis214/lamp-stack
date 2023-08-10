# Database id count reset

Once you delete a record from the database, the `id` field will be out of sync because its auto increment. In certain cases we might want to reset the values of `id` to make the new entries increment accurately.

# Run these commands in mysql shell to reset ID values
```shell
SET @num := 0;
UPDATE $table_name SET id = @num := (@num+1);
ALTER TABLE $table_name AUTO_INCREMENT =1;
```

## Understanding the commmands
The given MySQL commands are used to reset the ID values in a table. Let's break down each command and understand what they do:

1. SET @num := 0;: This command initializes a user-defined session variable @num and sets its value to 0. User-defined session variables in MySQL are prefixed with @.

2. UPDATE $table_name SET id = @num := (@num+1);: This UPDATE command updates the id column of the table to a new value. The value of @num is incremented by 1 for each row in the table, and that value is assigned to the id column. This effectively reassigns new ID values to each row sequentially.

3. ALTER TABLE $table_name AUTO_INCREMENT = 1;: This ALTER TABLE command resets the auto-increment value for the id column to 1. This means that the next row inserted will be assigned an ID value of 1.

However, it is important to note that the given commands may not be the ideal method to reset ID values, especially if your table contains relationships with other tables using foreign keys. Changing the ID values directly can lead to inconsistencies and potential conflicts with other tables that reference the original ID values.

The given commands effectively reassign new ID values to the existing rows in the table and reset the auto-increment value to 1. If you just want to reassign new sequential ID values to existing rows, these commands may work for you. However, if your table is part of a larger database schema with relationships and constraints, you should carefully consider the implications of resetting ID values and take appropriate measures to maintain data integrity. Always make a backup of your data before performing any significant changes to your database.

