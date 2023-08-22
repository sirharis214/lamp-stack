# lamp-stack
This is a sample full stack SAAS using Linux, Apache, Mysql, Php and  RabbitMQ. The main purpose is to demonstrate a 2 way communication between the application's frontend and backend using a message broker, RabbitMQ. We will also utilize Ansible to help manage and configure the server's.

To learn more about rabbitMQ, I suggest reading [this](https://www.cloudamqp.com/blog/part1-rabbitmq-for-beginners-what-is-rabbitmq.html) article by Lovisa Johansson who does a great job explaining the core concepts and usages of rabbitMQ.

## Workflow

<image src="./Setup/docs/images/lamp_stack.png" height=40% width=80%>

The request's workflow begins with frontend-server creating a request using rabbitmq's Client class. At this part of the workflow the frontend-server is the producer. 

1. frontend-server
	* frontend-server declares a temp reply-queue which the rabbitmq/backend-server will send their response to
		- `{queue-name}-response` 
	* frontend-server sends request to exchange with routing_key:
		- `data.rabbitmq`
		- `data.backend`
2. rabbitmq-server
    * in rabbitmq exchange, the routing_key determines which queue the request is sent to
        - requests sent with routing_key `data.rabbitmq` gets sent to the queue `data-rabbitmq`
	    - requests sent with routing_key `data.backend` gets sent to the queue `data-backend`  	
3. rabbitmq-server and backend-server 
	* both servers are listening to their respected queues for any incoming requests
    * once they recieve a request, they process and return a response to the temp reply-queue `{queue-name}-response` 
        - the name of the reply-queue is sent as a parameter of the request thats being recieved
4. after frontend-server consumes the response from reply-queue, the reply-queue auto_deletes and the 2 way communication completes here

# Choosing between 3 VM or 4 VM Setup
There are 2 approaches for this project.

* Option 1: Manually configure each of the 3 servers (frontend-server, rabbitmq-server, backend-server)
	- this approach only requires 3 VM's, 1 for each server.
* Option 2: Using Ansible to configure the 3 servers
	- this approach requires 4 VM's: 3 VM's for each server + 1 VM for Ansible
	- you might be able to keep it a 3 VM setup by hosting Ansible on the rabbitmq-server but that approach was not explored or tested here

We will choose Option 2 for the added simplicity that Ansible provides with managing and configuring servers and also for gaining that experience of using Ansible. If you choose to go with Option 1, you may follow [this](./Setup/README.md) guide that walks through the process for the 3 VM setup.

# VM's for a 4 VM setup
We will be using 4 Virtual Machines running on Ubuntu 22.04 LTS.

1. Frontend Apache Web server
    * static ip: `10.0.0.10`
2. RabbitMQ message broker server
    * static ip: `10.0.0.11`
3. Backend Mysql Database server
    * static ip: `10.0.0.12`
4. Ansible
    * static ip: `10.0.0.13`

# Setup
Here are the steps that need to be taken to create the infrastructure before we can use Ansible to configure the server's.

1. Create each of the 4 VM's manually
    - done in [step 1](#setup-step-1--creating-vms)
2. From the UTM Portal, change each VM's Network Mode to Bridged
3. On each VM, manually download `openssh-server`
4. On each VM, manually set static IP's
	- done in [step 4](#setup-step-4--set-static-ips)
5. On each VM, create a new user called `automation-user` and setup password-less sudo permissions for that user
	- you can use the script [create_user.sh](./Setup/create_user.sh) which creates the user and sets the password-less sudo permission for it
6. On the Ansible VM, create a ssh-key
	- command: `ssh-keygen -t rsa -b 4096`
	- hit enter at all prompts to keep default settings
7. On the Ansible VM, copy the ssh public key to all VM's, including the ansible VM itself.
	- command: `ssh-copy-id automation-user@<VM_IP_ADDRESS>`
	
## Setup Step 1 | Creating VMs
VM creation portion is the same for any setup. The configuration portion varies. 

* Create 4 VM's using Ubuntu 22.04.2 LTS iSO
	- Memory: 3072 MB
	- Cores: 2
	- Storage: >= 25 GB
* For the Ansible VM, you can choose to downgrade the Memory to 2048 MB and storage to 20 GB since it will only be hosting Ansible which doesn't require alot of resources.

> For MacOS M1 chip with VM's running on UTM: use jammy-desktop-arm64. [Here](./Setup/docs/creating_VMs.md) is a in-dept guide of creating these VM's on UTM.

## Setup Step 2 | VM Network Mode: Bridged

<image src="Setup/docs/images/8_edit_vm_settings.png" height="60%" width="20%">

<image src="Setup/docs/images/9_change_network_mode.png" height="40%" width="60%">

## Setup Step 3 | Download openssh-server
* After each VM is created, we can download `openssh-server` by running the following command 
    - `sudo apt-get update && sudo apt-get install openssh-server -y`.

## Setup Step 4 | Set static IP's
Set static IP's for each VM by following the steps below.

1. Click network settings and choose the current network

<image src="Setup/docs/images/18_wired_settings.png" height="60%" width="40%">
	
2. Jot the DNS from the Details section.

<image src="Setup/docs/images/19_dns.png" height="20%" width="60%">

3. Navigate to the IPv4 section & choose the manual option
4. Fill out the fields shown in the picture, choose ip's that are easy to remember.

* Addresses for each VM:
	- frontend-server: `10.0.0.10`
	- rabbitmq-server: `10.0.0.11`
	- backend-server: `10.0.0.12`
	- apache: `10.0.0.13`
* Netmask
	- `255.255.255.0`
* Gateway
	- `10.0.0.1`
* DNS
	- `75.75.75.75, 75.75.76.76`

<image src="Setup/docs/images/20_manual_network_settings.png" height="60%" width="40%">

## Setup Step 5 | Creating new user
On each VM:
* use the script [create_user.sh](./Setup/create_user.sh) which creates the user `automation-user` and sets the password-less sudo permission for it
	- we are creating a new user because we want to avoid changing the default user's permission to password-less sudo access.
	
## Setup Step 6 | Creating ssh-key in Ansible VM
From the Ansible VM:
* create ssh-key
	- command: `ssh-keygen -t rsa -b 4096`
	- Hit enter at all prompts to keep default settings

## Setup Step 7 | Copying public-ssh-key from Ansible VM
From the Ansible VM:
* copy the ssh public key to all VM's, including the ansible VM itself.
	- command: `ssh-copy-id automation-user@<VM_IP_ADDRESS>`
	- note that this has to be done after the `automation-user` is created (setup step 4)

# Ansible playbook 
This concludes the Setup phase. Now we can run the Ansible playbook to configure all the servers. The playbook downloads server dependencies on each VM based on the group the VM is listed under in inventory.ini. We utilize Ansible Roles to configure the servers. Roles are a way to organize and structure your Ansible code to make it more modular, reusable, and maintainable. 

* To run the playbook:
	- navigate to ~/Github/lamp-stack/ansible/
	- command: `ansible-playbook -i inventory.ini configure_servers.yml` 

## Ansible Roles
* base - runs on all 4 VM's
	- configures git
	- creates dir `~/Github` and inside it, clones the repo [lamp-stack](https://github.com/sirharis214/lamp-stack.git)
* frontend - runs on all VM's under the frontend_server group in inventory.ini
	- does a git pull to update the repo ~/Github/lamp-stack
	- deletes all content from /var/www/html/
	- copies all content from `~/Github/lamp-stack/frontend-server/` to `/var/www/html/`
	- restarts apache webserver
* rabbitmq - runs on all VM's under the rabbitmq_server group in inventory.ini
	- enables rabbitmq-management
	- creates vhost, rabbitmq admin user, exchange, queues, bindings
	- creates rabbitmq consumer service for rabbitmq-server listening on queue `data-rabbitmq`
		- `rabbitmq-server/rabbitmqServer.php` into a systemd service
* backend - runs on all VM's under the backend_server group in inventory.ini
	- creates mysql database
	- creates mysql admin user with permissions to new database from localhost
	- creates table
	- creates rabbitmq consumer service for backend-server listening on queue `data-backend`
		- `backend-server/rabbitmqServer.php` into a systemd service
		
# Good to Know

## frontend-server
* restart apache
	- in the frontend-server: `sudo systemctl restart apache2.service`

## rabbitmq-server
* To get the logs for rabbitmq-service.service
	- in rabbitmq-server: `journalctl -u rabbitmq-service.service`
* To check the status of rabbitmq-service.service
	- in backend-server: `sudo systemctl status rabbitmq-service.service`
* To remove rabbitmq-server's rabbitmq consumer service
	- run the script [delete_rabbitmq_rmq_consumer_service.sh](./Setup/docs/delete_rabbitmq_rmq_consumer_service.sh)
	
## backend-server
* To get the logs for backend-service.service
	- in backend-server: `journalctl -u backend-service.service`
* To check the status of backend-service.service
	- in backend-server: `sudo systemctl status backend-service.service`
* To remove backend-server's rabbitmq consumer service
	- run the script [delete_backend_rmq_consumer_service.sh](./Setup/docs/delete_backend_rmq_consumer_service.sh)
* To remove mysql-server from backend-server
	- run the script [delete_mysql_completely.sh](./Setup/docs/delete_mysql_completely.sh)
	
