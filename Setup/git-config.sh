#!/bin/bash

config_file="$HOME/.gitconfig"
credential_file="$HOME/.git-credentials"
user_name="sirharis214"
user_email="hariskido214@gmail.com"
PAT=""

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
  local welcome="LampStack | All Servers | Git Configuration"
  echo -e "${GREEN}$(centered_message "$welcome")${RESET}"
}

# Function to simulate a long-running process
run_execution() {
	echo -e "${CYAN}Configuring global git variables for user name & email${RESET}\t[ RUNNING ]"
	git config --global user.name "${user_name}"
	git config --global user.email "${user_email}"
	echo -e "${CYAN}Configuring global git variables for user name & email${RESET}\t[ ${GREEN}OK${RESET} ]"
	# Check if .gitconfig file exists
	if [ ! -f "$config_file" ]; then
		echo -e "${CYAN}Creating new .gitconfig file${RESET}\t[ RUNNING ]"
		touch "$config_file"
		echo -e "${CYAN}Creating new .gitconfig file${RESET}\t[ ${GREEN}OK${RESET} ]"
	fi
	# Append the desired configuration to the end of the .gitconfig file
	cat << EOF >> "$config_file"
	[credential]
		helper = store
	EOF
	echo -e "${CYAN}Credential helper added to $config_file ${RESET}\t[ ${GREEN}OK${RESET} ]"
	# Check if .git-credentials file exists
	if [ ! -f "$credential_file" ]; then
		echo -e "${CYAN}Creating new .git-credentials file${RESET}\t[ RUNNING ]"
		touch "$credential_file"
		echo -e "${CYAN}Creating new .git-credentials file${RESET}\t[ ${GREEN}OK${RESET} ]"
	fi
	echo -e "${CYAN}Configuring .get-credentials file${RESET}\t[ RUNNING ]"
	echo "https://${user_name}:${PAT}@github.com" > $credential_file
	echo -e "${CYAN}Configuring .get-credentials file${RESET}\t[ ${GREEN}OK${RESET} ]"
}

# Call the welcome_message function to display the centered welcome message
welcome_message

# Call the run_execution function to execute the task and display its status
run_execution

echo "Execution completed."

