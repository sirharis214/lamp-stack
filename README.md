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
