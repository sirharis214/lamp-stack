# Common rabbitmq commands

* Check rabbitmq server status
	- `systemctl status rabbitmq-server`

* Get service management and operator tasks
	- `sudo rabbitmqctl status`

* Enable management plugin (localhost:15672)
	- `sudo rabbitmq-plugin enable rabbitmq_management`
	- `sudo service rabbitmq-server restart`

# Setup:

* This adds a new user and password
	- `rabbitmqctl add_user <username> <password>`
* This makes the user a administrator
	- `rabbitmqctl set_user_tags <username> administrator`
* This sets permissions for the user
	- `rabbitmqctl set_permissions -p “/” username ".*" ".*" ".*"`
	- `… -p <vhost> <username> <conf> <write> <read>`

First `".*"` for **configure** permission on every entity

Second `".*"` for **write** permission on every entity

Third `".*"` for **read** permission on every entity


The 3 “.*” fields are reg expressions that determine pattern names that user is allowed to 
(1) config, (2) write, and (3) read. 

So, let's say you want this user to only read from these queues, you could do:

`rabbitmqctl set_permissions -p "/" "username" "$^" "$^" "^cart-order.*"`

But we will create a new Queue called `lamp-stack` on the default vhost `/` and give haris permission to only that queue.

`sudo rabbitmqctl set_permissions -p / haris "^lamp-stack-.*" "^lamp-stack-.*" "^lamp-stack-.*"`

