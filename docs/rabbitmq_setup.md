# Common rabbitmq commands
[helpful link](https://www.shellhacks.com/rabbitmq-list-queues-rabbitmqctl/) 

* Check rabbitmq server status
	- `systemctl status rabbitmq-server`
* Get service management and operator tasks
	- `sudo rabbitmqctl status`
* Enable management plugin (localhost:15672)
	- `sudo rabbitmq-plugin enable rabbitmq_management`
	- `sudo service rabbitmq-server restart`
* List vHosts
	- `sudo rabbitmqctl list_vhosts`
* List Exchanges
	- `sudo rabbitmqctl list_exchanges`
	- list of particular vhost: `sudo rabbitmqctl list_exchanges -p <vHost>`
* List Queues
	- `sudo rabbitmqctl list_queues`
	- list of particular vhost: `sudo rabbitmqctl list_queues -p <vHost>`
* Remove a vHost
	- `sudo rabbitmqctl delete_vhost <vhost>`
* Remove a Exchange
	- `rabbitmqadmin -u <username> -p <password> delete exchange --vhost=<vHost> name=<exchange name>`
* Remove a Queue
	- `rabbitmqadmin -u <username> -p <password> delete queue --vhost=<vHost> name=<queue name>`
* New user and password
	- `rabbitmqctl add_user <username> <password>`
* Make user a administrator
	- `rabbitmqctl set_user_tags <username> administrator`
* Set permissions for the user
	- `rabbitmqctl set_permissions -p “/” username ".*" ".*" ".*"`
	- `… -p <vhost> <username> <conf> <write> <read>`

First `".*"` for **configure** permission on every entity. Second `".*"` for **write** permission on every entity. Third `".*"` for **read** permission on every entity.

The 3 “.*” fields are reg expressions that determine pattern names that user is allowed to 
(1) config, (2) write, and (3) read. 

We will create a new vhost called `dev` and give haris permisson to create and manage Exchanges and Queues in that vhost as long as their name begins with `data-` 

* Create new vHost
	- `sudo rabbitmqctl add_vhost dev`
* Create new admin user
	- `rabbitmqctl add_user haris p`
	- `rabbitmqctl set_user_tags haris administrator`
* Set user permissions
	- user haris will only be able to create exchange/queues beginning with "data-"
	- `sudo rabbitmqctl set_permissions -p dev haris "^data-.*" "^data-.*" "^data-.*"`
* Create exchange on new vhost
	- `rabbitmqadmin -u haris -p p --vhost=dev declare exchange name=data-exchange type=topic`
* Create queue on new vhost
	- `rabbitmqadmin -u haris -p p --vhost=dev declare queue name=data-get-data durable=true`
* Create binding between Exchange and Queue with binding key same as queue name
	- `rabbitmqadmin -u haris -p p --vhost=dev declare binding source=data-exchange destination_type=queue destination=data-get-data routing_key=data-get-data`
