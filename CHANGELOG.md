# v0.0.3
* backend-server's requestProcessor case "login" changed to "login2"
* playbook includes
	- backend-server VM: run script to create mysql db, user, table, and create backend rmq consumer service
	- rabbitmq-server VM: create all rabbitmq resources, and create rabbitmq rmq consumer service
	- frontend-server VM: git pull, webserver file updates, and webserver restart
	- all VM's: github config and repo cloning
* simplifying server setup and configuration by incorporating Ansible
	- a forth VM is added to host Ansible

# v0.0.2
* bash scripts for setup's and config updated output messages 
* for admin login, display all user's from db Users table in scrollable html table
	- can update username, email, role for users.
	- username validation also occurs here
	- if no changes were made but save-change button was clicked, the alert message says no changes made
	- button to delete user, db queries the delete on table based on user-id & user-email 
* registration form for new user
	- username validation: "string.string" format to pass validation
	- password and password-confirm validation
	
# v0.0.1
* Beginning of CHANGELOG.md

