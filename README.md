# lamp-stack
This is a sample full stack SAAS using linux apache mysql php rabbitmq.

# VM's
We will be using 3 Virtual Machines running on Ubuntu 22.04 LTS.

1. Front end Apache Web server
2. RabbitMQ message broker server
3. Back end Mysql Database server

# Setup
## Creating VMs
VM creation portion is the same for any setup. The configuration portion varies. 

* Create 3 VM's using Ubuntu 22.04.2 LTS iSO
	- Memory: 3072 MB
	- Cores: 2
	- Storage: >= 25 GB
> For MacOS M1 chip with VM's running on UTM: use jammy-desktop-arm64
[Here](./Setup/docs/creating_VMs.md) is a in-dept guide of creating these VM's on UTM.

### Step 1
Use VM1 to configure frontend-server. The web application will be hosted on this VM and it will utilize rabbitmq client to send requests. rabbitmq client will send requests over the exchange with 1 of 2 routing key's. Each routing key points to its own Queue. Each of the 2 Queues are listened to from VM2 and VM3, respectively. 

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
Use VM2 to configure rabbitmq-server. The rabbitmq service will be running on this VM. All RabbitMQ users, exchanges and queues will be created from this VM. We will run a rabbitmqServer.php script in this VM which listens for messages in the Queue `data-rabbitmq`. Requests recieved will be processed locally and then a response will be sent back to VM 1 via a reply queue that VM 1 declared at the time of sending request.

* Download git
	- `sudo apt-get update && sudo apt-get install git`
* Configure Git using [this](./Setup/docs/github_setup.md) guide.
* Clone the repo [lamp-stack](https://github.com/sirharis214/lamp-stack.git)
* Download server dependencies using [rabbitmq_requirements.txt](./Setup/rabbitmq_requirements.txt)
	- `xargs -a rabbitmq_requirements sudo apt-get install -y`
* Configure the rabbitmq server by running script [rabbitmq-config.sh](./Setup/rabbitmq-config.sh) as root:
	- Creates vhost
	- Creates new rabbitmq admin user
	- Creates Exchange, Queues and bind them
* Run [rabbitmqServer.php](./rabbitmq-server/rabbitmqServer.php)

### Step 3 
Use VM3 to configure backend-server. This VM hosts the database. We will create the database, database user, and tables. We will also run a rabbitmqServer.php on this VM which listens to the Queue `data-backend`. Requests recieved will be processed by performing queries on database and then a response will be send back to VM 1 via a reply queue that VM 1 declared at the time of sending request.

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

# Setting static IP's on VM's

Its useful to set static IP's for these VM's, helps remember the IP's. Log into each VM and repeat these steps.

1. Click network settings and choose the current network

<image src="Setup/docs/images/18_wired_settings.png" height="60%" width="40%">
	
2. Jot the DNS from the Details section.

<image src="Setup/docs/images/19_dns.png" height="20%" width="60%">

3. Navigate to the IPv4 section & choose the manual option
4. Fill out the fields shown in the picture, choose ip's that are easy to remember.
	- frontend-server: `10.0.0.10`
	- rabbitmq-server: `10.0.0.11`
	- backend-server: `10.0.0.12`

<image src="Setup/docs/images/20_manual_network_settings.png" height="60%" width="40%">

