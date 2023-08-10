#!/bin/bash

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
  local welcome="LampStack | Git Configuration"
  echo -e "${GREEN}$(centered_message "$welcome")${RESET}"
}

# Function to simulate a long-running process
run_execution() {
	echo -e "${CYAN}Initial message on command about to be ran${RESET}\t[ RUNNING ]"
	# bash command 
	echo -e "${CYAN}Post command message${RESET}\t[ ${GREEN}OK${RESET} ]"
}

# Call the welcome_message function to display the centered welcome message
welcome_message

# Call the run_execution function to execute the task and display its status
run_execution

echo "Execution completed."
