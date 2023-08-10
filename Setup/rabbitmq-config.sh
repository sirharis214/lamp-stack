#!/bin/bash

# Must run this script as root
# This script is ment to setup the initial required rabbitmq configurations
# create vHost dev
# create new admin user on new vHost
# permission for thew user will be set to allow creation of
# exchanges and queues with the prefix "data-"
# create new Exchange and Queue & bind them

vhost_name="dev"
user_name="haris"
user_pass="p"
prefix="data"
exchange_name="${prefix}-exchange"
exchange_type="direct"
# rabbitmq server's rabbitmq consumer queue
rabbitmq_queue_name="${prefix}-rabbitmq"
rabbitmq_routing_key="${prefix}.rabbitmq"
# backend server's rabbitmq consumer queue
backend_queue_name="${prefix}-backend"
backend_routing_key="${prefix}.backend"

# Function to get the terminal width
get_terminal_width() {
  tput cols
}

# Function to display the centered message
centered_message() {
  local message="$1"
  local term_width=$(get_terminal_width)

  local padding_length=$(( (term_width - ${#message} - 2) / 2 ))
  local padding_left="$(printf '%*s' "$padding_length" "")"
  local padding_right="$(printf '%*s' "$padding_length" "")"

  echo "${padding_left} ${message} ${padding_right}"
}

# Define ANSI escape codes for colors
GREEN="\033[0;32m"
CYAN="\033[0;36m"
RED="\033[1;31m"
RESET="\033[0m"

# Function to display a green-colored message
welcome_message() {
  local welcome="LampStack | Rabbitmq Server | Rabbitmq-Server Configuration"
  echo -e "${GREEN}$(centered_message "$welcome")${RESET}"
}

# Function to simulate a long-running process
run_execution() {
	# enable rabbitmq_management
	echo -e "${CYAN}Enabling rabbitmq_management, required to create exchanges and queues${RESET}\t[ RUNNING ]"
	sudo rabbitmq-plugins enable rabbitmq_management
	echo -e "${CYAN}Enabling rabbitmq_management, required to create exchanges and queues${RESET}\t[ ${GREEN}OK${RESET} ]"
	# new vHost
	echo -e "${CYAN}Creating vHost: $vhost_name ${RESET}\t[ RUNNING ]"
	sudo rabbitmqctl add_vhost $vhost_name
	echo -e "${CYAN}Creating vHost: $vhost_name ${RESET}\t[ ${GREEN}OK${RESET} ]"
	# rabbitmq admin user
	echo -e "${CYAN}Creating admin user: $user_name ${RESET}\t[ RUNNING ]"
	sudo rabbitmqctl add_user $user_name $user_pass
	sudo rabbitmqctl set_user_tags $user_name administrator
	sudo rabbitmqctl set_permissions -p $vhost_name $user_name "^data-.*" "^data-.*" "^data-.*"
	echo -e "${CYAN}Creating admin user: $user_name ${RESET}\t[ ${GREEN}OK${RESET} ]"
	# exchange
	echo -e "${CYAN}Creating Exchange: $exchange_name ${RESET}\t[ RUNNING ]"
	sudo rabbitmqadmin -u ${user_name} -p ${user_pass} --vhost=${vhost_name} declare exchange name=${exchange_name} type=${exchange_type}
	echo -e "${CYAN}Creating Exchange: $exchange_name ${RESET}\t[ ${GREEN}OK${RESET} ]"
	# rabbitmq-queue
	echo -e "${CYAN}Creating Queue: $rabbitmq_queue_name ${RESET}\t[ RUNNING ]"
	rabbitmqadmin -u ${user_name} -p ${user_pass} --vhost=${vhost_name} declare queue name=${rabbitmq_queue_name} durable=true
	echo -e "${CYAN}Creating Queue: $rabbitmq_queue_name ${RESET}\t[ ${GREEN}OK${RESET} ]"
	# binding
	echo -e "${CYAN}Binding exchange ${exchange_name} to queue ${rabbitmq_queue_name} with routing key ${rabbitmq_routing_key} ${RESET}\t[ RUNNING ]"
	rabbitmqadmin -u ${user_name} -p ${user_pass} --vhost=${vhost_name} declare binding source=${exchange_name} destination_type=queue destination=${rabbitmq_queue_name} routing_key=${rabbitmq_routing_key}
	echo -e "${CYAN}Binding exchange ${exchange_name} to queue ${rabbitmq_queue_name} with routing key ${rabbitmq_routing_key} ${RESET}\t[ ${GREEN}OK${RESET} ]"
	# backend-queue
	echo -e "${CYAN}Creating Queue: $backend_queue_name ${RESET}\t[ RUNNING ]"
	rabbitmqadmin -u ${user_name} -p ${user_pass} --vhost=${vhost_name} declare queue name=${backend_queue_name} durable=true
	echo -e "${CYAN}Creating Queue: $backend_queue_name ${RESET}\t[ ${GREEN}OK${RESET} ]"
	# binding
	echo -e "${CYAN}Binding exchange ${exchange_name} to queue ${queue_name} with routing key ${backend_routing_key} ${RESET}\t[ RUNNING ]"
	rabbitmqadmin -u ${user_name} -p ${user_pass} --vhost=${vhost_name} declare binding source=${exchange_name} destination_type=queue destination=${backend_queue_name} routing_key=${backend_routing_key}
	echo -e "${CYAN}Binding exchange ${exchange_name} to queue ${queue_name} with routing key ${backend_routing_key} ${RESET}\t[ ${GREEN}OK${RESET} ]"
}

# Call the welcome_message function to display the centered welcome message
welcome_message

# Call the run_execution function to execute the task and display its status
run_execution

echo "Execution completed."

