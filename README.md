# lamp-stack
This is a sample full stack SAAS using linux apache mysql php rabbitmq.

# VM's
We will be using 3 Virtual Machines running on Ubuntu 22.04 LTS.

1. Front end Apache Web server
2. RabbitMQ message broker server
3. Back end Mysql Database server

# Architecture 
We can create a rabbitmq cluster or run rabbitmq in standalone mode. In a clustered mode, multiple RabbitMQ nodes form a cluster, and they share message queues, exchanges, and bindings. This enables high availability and load balancing. In a cluster, messages can be routed between nodes based on various routing mechanisms, ensuring that they reach the appropriate destination.

However, RabbitMQ also supports standalone mode, where a single RabbitMQ node operates independently without being part of a cluster. In standalone mode, the node can still receive and process messages, create queues, exchanges, and bindings, and perform all the basic messaging operations. Standalone mode is suitable for simpler deployments or scenarios where high availability and scalability are not critical requirements.

For the sake of simplicity we will be going with the standalone mode. But [here](./Setup/docs/rabbitmq_cluster.md) is a guide to setting up a rabbitmq cluster. In standalone mode, rabbitmq-server needs to be downloaded on all VM's but the configuration only needs to be done on the RabbitMQ server. The rabbitmq user, password, queue and exchange info needs to be shared with the other VM's so they can communicate to the rabbitMQ server.

Also, in standalone mode, the rabbitmq management console is only available on the rabbitmq server. This differs from cluster mode where each node(VM) that is part of the cluster can access the management console. 

# Setup
## Create VMs
The VM setup portion is the same for all 3 VM's.

* Create VM using Ubuntu 22.04.2 LTS iSO
	- Memory: 3072 MB
	- Cores: 2
	- Storage: >= 25 GB
> For MacOS M1 chip with VM's running on UTM: use jammy-desktop-arm64

### Step 1 
Configure rabbitmq-server. We configure rabbitmq-server VM first because we need its IP address for frontend-server's [rabbitmq.ini](./html/rabbitmq/rabbitmq.ini).

### Step 2
Configure frontend-server.
* Download frontend server dependencies using [frontend_requirements.txt](./Setup/frontend_requirements.txt)
	- `xargs -a frontend_requirements.txt sudo apt-get install -y`
* Configure Git using [this](./Setup/docs/github_setup.md)
* Next, clone the [lamp-stack](https://github.com/sirharis214/lamp-stack.git) repo & copy all the contents of [html](./html) into `var/www/html/`.
* Update [rabbitmq.ini](./html/rabbitmq/rabbitmq.ini) and set the `BROKER_HOST` to rabbitmq-server's IP
* restart apache2
	- `sudo systemctl restart apache2.service`

### Step 3 
Configure backend-server.
* Download backend server dependencies using [backend_requirements.txt](./Setup/backend_requirements.txt)
	- `xargs -a backend_requirements.txt sudo apt-get install -y`
* Configure Git using [this](./Setup/docs/github_setup.md)
* Next, clone the [lamp-stack](https://github.com/sirharis214/lamp-stack.git) repo
* Now, run the scripts as root:
	- [mysql-configure.sh](./Setup/mysql-configure.sh)
		- Creates `dev_db` database
		- Creates new admin mysql user
		- Creates `Users` table
* Update [rabbitmq.ini](./html/rabbitmq/rabbitmq.ini) and set the `BROKER_HOST` to rabbitmq-server's IP

# Running the project on a single VM
* Download [these](./Setup/single_server_requirements.txt) dependencies
	- `xargs -a single_server_requirements.txt sudo apt-get install -y`
* Configure Git using [this](./Setup/docs/github_setup.md)
* Next, clone the [lamp-stack](https://github.com/sirharis214/lamp-stack.git) repo & copy all the contents of [html](./html) into `var/www/html/`.
* Now, run these scripts as root:
	- [rabbitmq-configure.sh](./Setup/rabbitmq-configure.sh)
		- Creates vhost
		- Creates new admin rabbitmq user
		- Creates Exchange, Queue and binds them
	- [mysql-configure.sh](./Setup/mysql-configure.sh)
		- Creates `dev_db` database
		- Creates new admin mysql user
		- Creates `Users` table
* Update [rabbitmq.ini](./html/rabbitmq/rabbitmq.ini) and set the `BROKER_HOST` to localhost `127.0.0.1`
* restart apache2
	- `sudo systemctl restart apache2.service`

# Running the project on 2 VM's
VM1 will serve as the web server. VM2 will serve as rabbitmq-server and backend-server.

## VM1
Follow [Step 2](#step-2) of Setup > Create Vms with the exception of the following step.
* Update [rabbitmq.ini](./html/rabbitmq/rabbitmq.ini) and set the `BROKER_HOST` to VM2's IP
* restart apache2
	- `sudo systemctl restart apache2.service`

## VM2 
* Download backend server dependencies using [backend_requirements.txt](./Setup/backend_requirements.txt)
	- `xargs -a backend_requirements.txt sudo apt-get install -y`
* Configure Git using [this](./Setup/docs/github_setup.md)
* Next, clone the [lamp-stack](https://github.com/sirharis214/lamp-stack.git) repo
* Now, run these scripts as root:
	- [rabbitmq-configure.sh](./Setup/rabbitmq-configure.sh)
		- Creates vhost
		- Creates new admin rabbitmq user
		- Creates Exchange, Queue and binds them
	- [mysql-configure.sh](./Setup/mysql-configure.sh)
		- Creates `dev_db` database
		- Creates new admin mysql user
		- Creates `Users` table
* Update [rabbitmq.ini](./html/rabbitmq/rabbitmq.ini) and set the `BROKER_HOST` to localhost `127.0.0.1`
* run [rabbitmq-server](./html/rabbitmq/rabbitmq-server.php) 
=======
This project will build on-top of itself over a span of 3 phases.

## Phase 1
We will create one VM and develop the frontend with Apache web server. We will also configure the rabbitMQ client/server model. RabbitMQ will be configured to run locally by executing rabbitmqServer.php. 

1. The web server creates requests and sends them through rabbitMQ client 
2. The locally running rabbitMQ server will 
	- consume the requests
	- process the requests
	- return a response

## Phase 2
We will create a second VM and develop the MySQL database. We stil use the VM from phase 1 but the rabbitMQ server portion will migrate to this second VM. RabbitMQ still needs to be configured on both VM's using the script [rabbitmq-config.sh](./rabbitmq-config.sh). 

1. VM 1 (web server) creates the requests and sends them to VM 2 (db+rabbitmq-server) through rabbitmq client
2. VM 2, rabbitMQ server picks up the requests
	- makes the required quries to MySQL db
	- completes processing the requests
	- returns a response

## Phase 3
We will create a third VM and migrate the rabbitMQ server to it. Now VM 1 is soley used for frontend (web server), VM 2 is used for backend (database), VM 3 is used for message exchange.

1. VM 1 creates and sends requests through rabbitMQ client
2. VM 3 rabbitMQ server picks up the request
	- the exchange and queue determine which host will process the requests
3. VM 2 MySQL db provides the data to compute a response


## create VM
The server setup portion is the same for all 3 VM's

* Create a VM using Ubuntu 22.04.2 LTS iSO
> For MacOS M1 chip with VM's running on UTM: use jammy-desktop-arm64
	- Memory: 3072 MB
	- Cores: 2
	- Storage: >= 25 GB

## Phase 1 | Web server and RabbitMQ server on single VM

1. [create VM](#create-vm)
2. Download frontend server dependencies using [frontend_requirements.txt](./Setup/frontend_requirements.txt)
	- `xargs -a frontend_requirements.txt sudo apt-get install -y`
3. Configure Git using [github_setup](docs/github_setup.md)
4. Configure RabbitMQ: execute the script as root [rabbitmq-config.sh](./rabbitmq-config.sh) or perform the steps manually.
	- Enable rabbitmq_management
		- `sudo rabbitmq-plugins enable rabbitmq_management`
		- you can visit `localhost:15672` to view rabbitmq console
	- Create new vHost called dev
		- `sudo rabbitmqctl add_vhost dev`
	- Create new User called haris
		- `sudo rabbitmqctl add_user haris <password>`
	- Set user as admin:
		- `sudo rabbitmqctl set_user_tags haris administrator`
	- Set user permissions:
		- user haris will only be able to create exchange/queues beginning with "data-" in the vHost dev
		- `sudo rabbitmqctl set_permissions -p dev haris "^data-.*" "^data-.*" "^data-.*"`
	- Create new Exchange on new vHost:
		- `rabbitmqadmin -u haris -p <password> --vhost=dev declare exchange name=data-exchange type=topic`
	- Create new Queue on new vHost:
		- `rabbitmqadmin -u haris -p <password> --vhost=dev declare queue name=data-get-data durable=true`
	- Bind Exchange and Queue
		- `rabbitmqadmin -u haris -p <password> --vhost=dev declare binding source=data-exchange destination_type=queue destination=data-get-data routing_key=data.get.data`
5. Copy front end content to /var/www/html and restart apache
	- `sudo rm /var/www/html/index.html && sudo cp -r ~/GitHub/lamp-stack/* /var/www/html/`
	- `sudo systemctl restart apache2.service`
6. Start local rabbitmq server to test webpage + rabbitmq
	- `./rabbitmqServer.php`
	- navigate to localhost and attempt failed and success logins:
		- email: `test@test.com` password: `testpassword`

## Phase 2 | Web server on VM 1 and RabbitMQ + Database on VM 2
> Note: We still need to configure rabbitmq on both VM's but rabbitmqServer.php will only run from second VM.

1. [create VM](#create-vm)
2. Download backend server dependencies using [backend_requirements.txt](./Setup/backend_requirements.txt)
	- `xargs -a backend_requirements.txt sudo apt-get install -y`
3. Configure Git using [github_setup](docs/github_setup.md)
4. Configure RabbitMQ: execute the script as roo: [rabbitmq-config.sh](./Setup/rabbitmq-config.sh) or perform the steps manually.
	- same manual steps as mentioned in part 4 of [frontend server setup](#frontend-server-setup)
5. Configure MySQL DB: execute the script as root: [mysql-config.sh](./Setup/mysql-config.sh)
	- Creates db, create db admin user, create table: Users
	- Users table:
		- id - int pk
		- username - varchar
		- email - varchar
		- role - varchar
		- created_on - varchar
		- updated_on - varchar
		- hashed_data - varchar
	
6.
	
# Notes

We can create a rabbitmq cluster or run rabbitmq in standalone mode. In a clustered mode, multiple RabbitMQ nodes form a cluster, and they share message queues, exchanges, and bindings. This enables high availability, load balancing. In a cluster, messages can be routed between nodes based on various routing mechanisms, ensuring that they reach the appropriate destination.

However, RabbitMQ also supports standalone mode, where a single RabbitMQ node operates independently without being part of a cluster. In standalone mode, the node can still receive and process messages, create queues, exchanges, and bindings, and perform all the basic messaging operations. Standalone mode is suitable for simpler deployments or scenarios where high availability and scalability are not critical requirements.

If we choose to go with a cluster model then we only need to configure rabbitmq user, exchanges, queues once. Then every node in the cluster will be able to share those resources. For example, we can create these resources one time and as long as new nodes are added to the cluster, they can log into rabbitmq management console and have access to the these existing resources.

For a none cluster model, we would have to create rabbitmq user, exchange, queues every time for our application to not error out.

Below are the steps to configure the VM's in a rabbitmq cluster model.

# Notes | Must do at the beginning of each VM

## 1
On each VM we must update the file: /etc/hosts 
add the IP address of each VM and the hostname for each machine.

* check hostname
	- `hostname`
* change hostname
	- `hostnamectl set-hostname <new-host-name>`
* Test if hostname is reachable
	- ping VM1 from VM2 using hostname instead of IP: `ping fronend-server`
	- repeat by pinging VM2 from VM1: `ping backend-server`

## 2
In VM1, confirm the rabbitmq node name follows the format: `rabbit@hostname`
* `sudo rabbitmqctl eval "node()."`

Check rabbitmqctl status
* `sudo rabbitmqctl status`

Restart rabbitmq
* `sudo systemctl restart rabbitmq-server`

## 3
RabbitMQ Cluster
from VM1 get the cluster info:
* `sudo cat /var/lib/rabbitmq/.erlang.cookie`
* Copy the output, we will need to paste this info into the other VM/VMs

in the other VM's:

* stop the rabbitmq server
	- `sudo systemctl stop rabbitmq-server`
* Edit the /var/lib/rabbitmq/.erlang.cookie
* replace the text from VM1's `.erlang.cookie` output that we copied
* start rabbitmq-server
	`sudo systemctl start rabbitmq-server`
* stop rabbitmq application
	- `sudo rabbitmqctl stop_app`
* join the cluster on VM1
	- `sudo rabbitmqctl join_cluster rabbit@frontend-server`
	- this will work only is the rabbitmq application is working on VM1
	- remember to update `/etc/hosts` by adding frontend-server's info
* start the rabbitmq application
	- `sudo rabbitmqctl start_app`
* check the cluster status
	- `sudo rabbitmqctl cluster_status`
