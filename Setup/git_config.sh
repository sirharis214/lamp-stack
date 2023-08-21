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
    local padding_length=$(( (term_width - ${#message}) / 2 ))
    local padding="$(printf '#%.0s' $(seq 1 "$padding_length"))"
    echo "${padding} ${message} ${padding}"
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
    local running_msg="[ RUNNING ]"
    local ok_msg="[ ${GREEN}OK${RESET} ]"
    local padding="      "  # Adjust the number of spaces as needed
    
    echo -e -n "${CYAN}Configuring global git variables for user name & email${RESET}\t${running_msg}"
    git config --global user.name "${user_name}"
    git config --global user.email "${user_email}"
    echo -e "\r${CYAN}Configuring global git variables for user name & email${RESET}\t${ok_msg}${padding}"

    # Check if .gitconfig file exists
    if [ ! -f "$config_file" ]; then
        echo -e -n "${CYAN}Creating new .gitconfig file${RESET}\t${running_msg}"
	    touch "$config_file"
	    echo -e "\r${CYAN}Creating new .gitconfig file${RESET}\t${ok_msg}${padding}"
    fi
# This has to break out the indent due to here.doc
# Append the desired configuration to the end of the .gitconfig file
    echo -e -n "${CYAN}Adding credential helper to $config_file ${RESET}\t${running_msg}"
cat << EOF >> "$config_file"
[credential]
    helper = store
EOF
	echo -e "\r${CYAN}Adding credential helper to $config_file ${RESET}\t${ok_msg}${padding}"
	# Check if .git-credentials file exists
	if [ ! -f "$credential_file" ]; then
		echo -e -n "${CYAN}Creating new .git-credentials file${RESET}\t${running_msg}"
		touch "$credential_file"
		echo -e "\r${CYAN}Creating new .git-credentials file${RESET}\t${ok_msg}${padding}"
	fi
	echo -e -n "${CYAN}Configuring .get-credentials file${RESET}\t${running_msg}"
	echo "https://${user_name}:${PAT}@github.com" > $credential_file
	echo -e "\r${CYAN}Configuring .get-credentials file${RESET}\t${ok_msg}${padding}"
}

# Call the welcome_message function to display the centered welcome message
welcome_message

# Call the run_execution function to execute the task and display its status
run_execution

echo "Execution completed."

