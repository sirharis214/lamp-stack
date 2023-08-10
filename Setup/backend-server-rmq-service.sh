#!/bin/bash

root_file="/lib/systemd/system"
service_name="backend-service.service"
server_file="/home/haris/Github/lamp-stack/backend-server/rabbitmqServer.php"

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
  local welcome="LampStack | Backend Server | RabbitMQ Consumer Service"
  echo -e "${GREEN}$(centered_message "$welcome")${RESET}"
}

# Function to simulate a long-running process
run_execution() {
	if [ ! -f "${root_file}/${service_name}" ]; then
		echo -e "${CYAN}Creating ${service_name} ${RESET}\t[ RUNNING ]"
		touch "${root_file}/${service_name}"
		echo -e "${CYAN}Creating ${service_name} ${RESET}\t[ ${GREEN}OK${RESET} ]"
	fi
	echo -e "${CYAN}Adding ${service_name} configuration${RESET}\t[ RUNNING ]"
	cat << EOF > "${root_file}/${service_name}"
	[Unit]
	Description= ${service_name} listening on Queue data-backend

	[Service]
	Type=simple
	ExecStart=/usr/bin/php -f ${server_file}

	[Install]
	WantedBy=multi-user.target
	EOF
	echo -e "${CYAN}Adding ${service_name} configuration${RESET}\t[ ${GREEN}OK${RESET} ]"
	echo -e "${CYAN}Starting ${service_name} ${RESET}\t[ RUNNING ]"
	sudo systemctl start ${service_name}
	echo -e "${CYAN}Starting ${service_name} ${RESET}\t[ ${GREEN}OK${RESET} ]"
	#echo "Get ${service_name} status"
	#sudo systemctl status ${service_name}

	echo -e "${CYAN}Enabling ${service_name} ${RESET}\t[ RUNNING ]"
	sudo systemctl enable ${service_name}
	echo -e "${CYAN}Enabling ${service_name} ${RESET}\t[ ${GREEN}OK${RESET} ]"
}

# Call the welcome_message function to display the centered welcome message
welcome_message

# Call the run_execution function to execute the task and display its status
run_execution

echo "Execution completed."
