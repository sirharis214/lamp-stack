# lamp-stack
This is a sample full stack SAAS using linux apache mysql php rabbitmq.

# frontend server setup
1. Create a VM using Ubuntu 22.04.2 LTS iSO
	- For MacOS M1 chip with VM's running on UTM: use jammy-desktop-arm64
	- Memory: 3072 MB
	- Cores: 2
	- Storage: >= 25 GB
2. Use frontend_requirements.txt to download dependencies
	- `xargs -a frontend_requirements.txt sudo apt-get install -y`
3. Git configuration using [github_setup](docs/github_setup.md)
4. RabbitMQ configuration: execute the script as root [rabbitmq-config.sh](./rabbitmq-config.sh) or perform the steps manually.
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
