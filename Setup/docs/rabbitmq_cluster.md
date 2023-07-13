# Creating a RabbitMQ Cluster
Start by downloading `rabbitmq-server` on each VM. Once all VM's are on the same rabbitmq cluster, We will configure rabbitmq from the rabbitmq-server VM.

# VM Hostnames
1. frontend-server
2. rabbitmq-server
3. backend-server

# Steps

## 1
Update the file `/etc/hosts` for each of the VM's. You'll need to add the IP address and hostname of each VM.

* get the ip address 
	- `ip a`
* check hostname
	- `hostname`
* change hostname
	- `hostnamectl set-hostname <new-host-name>`
* Test if hostnames are reachable
	- Ping VM2 From VM1 and VM3 using hostname instead of IP: `ping rabbitmq-server`

## 2
* In rabbitmq-server VM (VM2), confirm the rabbitmq node name follows the format: `rabbit@hostname`
	- `sudo rabbitmqctl eval "node()."`
* If the node name format does not match, you should confirm the hostname configurations in step 1 and then perform the next 2 steps. 
* Check rabbitmqctl status
	- `sudo rabbitmqctl status`
* Restart rabbitmq
	- `sudo systemctl restart rabbitmq-server`

## 3
Now that the hostname's are set for each VM and they're able to ping each other, we can add all 3 VM's onto the same cluster.

* From VM2 (rabbitmq-server) get the cluster info
	- `sudo cat /var/lib/rabbitmq/.erlang.cookie`
* Copy the output, we will need to paste this string into the other VM's

### on VM's 1 & 3
* Stop the rabbitmq server
	- `sudo systemctl stop rabbitmq-server`
* Edit the /var/lib/rabbitmq/.erlang.cookie
	- replace the text with the string from VM2's `.erlang.cookie` output that we copied
* start rabbitmq-server
	`sudo systemctl start rabbitmq-server`
* stop rabbitmq application
	- `sudo rabbitmqctl stop_app`
* join the cluster on VM2
	- `sudo rabbitmqctl join_cluster rabbit@rabbitmq-server`
* start the rabbitmq application
	- `sudo rabbitmqctl start_app`
* check the cluster status
	- `sudo rabbitmqctl cluster_status`

If you run into an issue when joining the cluster, you should confirm if rabbitmq application is working on VM2. Also, double check the file `/etc/hosts` and confirm all VM's IP and Hostname is added to the list. Repeat steps to joining the cluster on VM2.
