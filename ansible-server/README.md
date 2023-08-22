# run the ansible playbook
`ansible-playbook -i inventory.ini configure_servers.yml`

## Useful commands
* list all the hosts in inventory
	- `ansible all -i inventory.ini --list-hosts`
*  connectivity test, Ansible will attempt to establish an SSH connection to each target host and report whether the connection was successful (a "pong" response) or not
	- `ansible all --key-file ~/.ssh/ansible -i inventory.ini -m ping`
