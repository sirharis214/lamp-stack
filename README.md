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
## Create 3 VMs
VM creation portion is the same for any setup. The configuration portion varies. 

* Create 3 VM's using Ubuntu 22.04.2 LTS iSO
	- Memory: 3072 MB
	- Cores: 2
	- Storage: >= 25 GB
> For MacOS M1 chip with VM's running on UTM: use jammy-desktop-arm64

### Step 1
Use VM1 to configure frontend-server. The web application will be hosted on this VM and it will utilize rabbitmq client to send requests. rabbitmq client will send requests using 1 of 2 exchanges. Each exchange is binded with a different Queue. Each of the 2 Queues are listened to from VM2 and VM3, respectively. 

* Download git
	- `sudo apt-get update && sudo apt-get install git`
* Configure Git using [this](./Setup/docs/github_setup.md) guide.
* Clone the repo [lamp-stack](https://github.com/sirharis214/lamp-stack.git)
* Download server dependencies using [frontend_requirements.txt](./Setup/frontend_requirements.txt)
	- `xargs -a frontend_requirements.txt sudo apt-get install -y`
* Set the `BROKER_HOST` values for both servers in [rabbitmq.ini](./frontend-server/rabbitmq/rabbitmq.ini) to VM2's (rabbitmq-server) IP Address.
* Now copy all the contents of [frontend-server](./frontend-server) into `var/www/html/`.
* restart apache2
	- `sudo systemctl restart apache2.service`

### Step 2
Use VM2 to configure rabbitmq-server. The rabbitmq service will be running on this VM. All RabbitMQ users, exchanges and queues will be created from this VM. This VM's rabbitmq service listens to the Queue `data-get-data` which is binded with the Exchange `data-exchange`. Requests recieved will be processed locally and then a response will be sent back to VM 1.

* Download git
	- `sudo apt-get update && sudo apt-get install git`
* Configure Git using [this](./Setup/docs/github_setup.md) guide.
* Clone the repo [lamp-stack](https://github.com/sirharis214/lamp-stack.git)
* Download server dependencies using [rabbitmq_requirements.txt](./Setup/rabbitmq_requirements.txt)
	- `xargs -a rabbitmq_requirements sudo apt-get install -y`
* Configure the rabbitmq server by running script [rabbitmq-config.sh](./Setup/rabbitmq-config.sh) as root:
	- Creates vhost
	- Creates new rabbitmq admin user
	- Creates Exchanges, Queues and binds them
* Run [rabbitmqServer.php](./rabbitmq-server/rabbitmqServer.php)

### Step 3 
Use VM3 to configure backend-server. This VM hosts the database. We will create the database, database user, and tables. A rabbitmq service will also be ran on this VM which listens to the Queue `data-get-backend-data` which is binded with the Exchange `data-backend-exchange`. Requests recieved will be processed by performing queries on database and then a response will be send back to VM 1.

* Download git
	- `sudo apt-get update && sudo apt-get install git`
* Configure Git using [this](./Setup/docs/github_setup.md) guide.
* Clone the repo [lamp-stack](https://github.com/sirharis214/lamp-stack.git)
* Download server dependencies using [backend_requirements.txt](./Setup/backend_requirements.txt)
	- `xargs -a backend_requirements.txt sudo apt-get install -y`
* Set the `BROKER_HOST` value in [rabbitmq.ini](./backend-server/rabbitmq.ini) to VM2's (rabbitmq-server) IP Address.
* Now, configure mysql database by running script [mysql-config.sh](./Setup/mysql-config.sh) as root:
	- Creates `dev_db` database
	- Creates new mysql admin user
		- permissions on host: localhost and %
	- Creates `Users` table
* Run [rabbitmqServer.php](./backend-server/rabbitmqServer.php)

