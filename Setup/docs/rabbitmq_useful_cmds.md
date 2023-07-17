# Setup

We will create a new vhost called `dev` and a new user named `haris` with admin permisson's. User will be able to create and manage Exchanges and Queues in the new vhost with name prefix `data-`. 

1. Create new vHost
	- `sudo rabbitmqctl add_vhost dev`
2. Create new admin user
	- `rabbitmqctl add_user haris p`
	- `rabbitmqctl set_user_tags haris administrator`
3. Set user permissions
	- user haris will only be able to create exchange/queues beginning with "data-"
	- `sudo rabbitmqctl set_permissions -p dev haris "^data-.*" "^data-.*" "^data-.*"`
4. Create exchange on new vhost
	- `rabbitmqadmin -u haris -p p --vhost=dev declare exchange name=data-exchange type=topic`
5. Create queue on new vhost
	- `rabbitmqadmin -u haris -p p --vhost=dev declare queue name=data-get-data durable=true`
6. Create binding between Exchange and Queue with binding key same as queue name
	- `rabbitmqadmin -u haris -p p --vhost=dev declare binding source=data-exchange destination_type=queue destination=data-get-data routing_key=data.get.data`

## Common rabbitmq commands

* Check rabbitmq server status
	- `systemctl status rabbitmq-server`
* Get service management and operator tasks
	- `sudo rabbitmqctl status`
* Enable management plugin (localhost:15672)
	- `sudo rabbitmq-plugins enable rabbitmq_management`
	- `sudo service rabbitmq-server restart`
* List vHosts
	- `sudo rabbitmqctl list_vhosts`
* Create new vHost
	- `sudo rabbitmqctl add_vhost <vhost-name>`
* Remove a vHost
	- `sudo rabbitmqctl delete_vhost <vhost-name>`
* List Exchanges
	- `sudo rabbitmqctl list_exchanges`
* List of exchange in particular vhost 
	- `sudo rabbitmqctl list_exchanges -p <vhost-name>`
* Create exchange on new vhost
	- `rabbitmqadmin -u haris -p p --vhost=<vhost-name> declare exchange name=data-exchange type=topic`
* Delete exchange on new vhost
	- `rabbitmqadmin -u <user-name> -p <password> --vhost=<vhost-name> delete exchange name=<exchange-name>`
* Delete exchange on default vhost
	- `rabbitmqadmin delete exchange name=<exchange name>`
* List Queues
	- `sudo rabbitmqctl list_queues`
	- list of particular vhost: `sudo rabbitmqctl list_queues -p <vhost-name>`
* Create queue on new vhost
	- `rabbitmqadmin -u <user-name> -p <password> --vhost=<vhost-name> declare queue name=<queue-name> durable=true`
* Create binding between Exchange and Queue with binding key same as queue name
	- `rabbitmqadmin -u <user-name> -p <password> --vhost=<vhost-name> declare binding source=<exchange-name> destination_type=queue destination=<queue-name> routing_key=<routing.key>`
* Remove a Queue
	- `rabbitmqadmin -u <username> -p <password> delete queue --vhost=<vhost-name> name=<queue-name>`
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

