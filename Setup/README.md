# Manually Configure Servers

If you follow this setup guide, you only need 3 VM's since you choose to manually configure the server's. Manually configuring the servers is time consuming compared to the Ansible approach, however it is an effective oppertunity to understand the configurations occuring in each server. 

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
> For MacOS M1 chip with VM's running on UTM: use jammy-desktop-arm64. [Here](./docs/creating_VMs.md) is a in-dept guide of creating these VM's on UTM.

* It is helpful if you set static IP's for the VM's at this point.
	- Follow [these](#setting-static-ip-on-vm) steps to set static IP's on your VM's

### Step 1
Use VM1 to configure frontend-server which will host the apache web server. The web application utilizes a rabbitmq `Client` class to send requests. rabbitmq client will send requests over the exchange with 1 of 2 routing key's. Each routing key points to its own Queue. Each of the 2 Queues are listened to from VM2 and VM3, respectively. 

* Download git
	- `sudo apt-get update && sudo apt-get install git`
* Configure git using 1 of the 2 methods 
	- run the script [git-config.sh](./git-config.sh) by first updating the variables 
	- manually approach, follow [this](./docs/github_setup.md) guide
* Clone the repo [lamp-stack](https://github.com/sirharis214/lamp-stack.git)
* Download server dependencies using [frontend_requirements.txt](./frontend/frontend_requirements.txt)
	- `xargs -a frontend_requirements.txt sudo apt-get install -y`
* Set the `BROKER_HOST` values for both servers in [rabbitmq.ini](../frontend-server/rabbitmq/rabbitmq.ini) to VM2's (rabbitmq-server) IP Address.
* Now copy all the contents of [frontend-server](../frontend-server) into `/var/www/html/`
* Restart apache2
	- `sudo systemctl restart apache2.service`

### Step 2
Use VM2 to configure rabbitmq-server. All RabbitMQ users, exchanges and queues will be created from this VM. The rabbitmq service will be running on this VM which uses our file `rabbitmq-server/rabbitmqServer.php` to listen for messages in the Queue `data-rabbitmq`. Requests recieved will be processed locally and then a response will be sent back to VM1 via a reply-queue that VM1 declared at the time of sending request.

* Download git
	- `sudo apt-get update && sudo apt-get install git`
* Configure git using 1 of the 2 methods 
	- run the script [git-config.sh](./git-config.sh) by first updating the variables 
	- manually approach, follow [this](./docs/github_setup.md) guide
* Clone the repo [lamp-stack](https://github.com/sirharis214/lamp-stack.git)
* Download server dependencies using [rabbitmq_requirements.txt](./rabbitmq/rabbitmq_requirements.txt)
	- `xargs -a rabbitmq_requirements.txt sudo apt-get install -y`
* Configure the rabbitmq-server by running script [rabbitmq-config.sh](./rabbitmq/rabbitmq-config.sh) as root:
	- Creates vhost
	- Creates new rabbitmq admin user
	- Creates Exchange, Queues and binds them
* Create systemd service for [rabbitmqServer.php](../rabbitmq-server/rabbitmqServer.php) from the [rabbitmq-server](./rabbitmq-server) directory so rabbitmq service listens into the queue at start up.
	- Run the script as root [rabbitmq-server-rmq-service.sh](./rabbitmq/rabbitmq-server-rmq-service.sh)

### Step 3 
Use VM3 to configure backend-server. This VM hosts the database. We will create the database, database user, and tables. We will also run a rabbitmqServer.php on this VM which listens to the Queue `data-backend`. Requests recieved will be processed by performing queries on database and then a response will be send back to VM1 via a reply-queue that VM1 declared at the time of sending request.

* Download git
	- `sudo apt-get update && sudo apt-get install git`
* Configure git using 1 of the 2 methods 
	- run the script [git-config.sh](./git-config.sh) by first updating the variables 
	- manually approach, follow [this](./docs/github_setup.md) guide
* Clone the repo [lamp-stack](https://github.com/sirharis214/lamp-stack.git)
* Download server dependencies using [backend_requirements.txt](./backend/backend_requirements.txt)
	- `xargs -a backend_requirements.txt sudo apt-get install -y`
* Set the `BROKER_HOST` value in [rabbitmq.ini](../backend-server/rabbitmq.ini) to VM2's (rabbitmq-server) IP Address.
* Now, configure mysql database by running script [mysql_config.sh](./backend/mysql_config.sh) as root:
	- Creates `dev_db` database
	- Creates new mysql admin user
		- sets user's permissions on dev_db via localhost
	- Creates `Users` table
* Create a systemd service for [rabbitmqServer.php](../backend-server/rabbitmqServer.php) from the [backend-server](../backend-server) directory so rabbitmq service listens into the queue at start up.
	- Run the script as root [backend-server-rmq-service.sh](./backend/backend-server-rmq-service.sh)

# Setting static IP on VM

Its useful to set static IP's for these VM's, removes the need to manually update the rabbitmq.ini file in the event the rabbitmq-server restarts, also helps remember the IP's of each server. Log into each VM and repeat these steps.

1. Click network settings and choose the current network

<image src="./docs/images/18_wired_settings.png" height="60%" width="40%">
	
2. Jot the DNS from the Details section.

<image src="./docs/images/19_dns.png" height="20%" width="60%">

3. Navigate to the IPv4 section & choose the manual option
4. Fill out the fields shown in the picture, choose ip's that are easy to remember.
	- frontend-server: `10.0.0.10`
	- rabbitmq-server: `10.0.0.11`
	- backend-server: `10.0.0.12`

<image src="./docs/images/20_manual_network_settings.png" height="60%" width="40%">
 
