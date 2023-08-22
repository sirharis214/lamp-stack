#!/bin/bash

db_name="dev_db"
user_name="haris_db_user"
user_pass="p"
table_name="Users"
	
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
  local welcome="LampStack | Backend Server | MySQL Database Configuration"
  echo -e "${GREEN}$(centered_message "$welcome")${RESET}"
}

# Function to simulate a long-running process
run_execution() {
	# Check if MySQL server is installed
	if ! dpkg-query -W -f='${Status}' mysql-server | grep -q "installed"; then
	  echo -e "${CYAN}MySQL server is not installed${RESET}\t${RED}[ ! ]${RESET}"
	  exit 1
	fi

	check_error() {
	  if [ $? -ne 0 ]; then
		echo -e "${CYAN}Error occurred while executing the last command.${RESET}"
		if [ -f "mysql_error.txt" ]; then
		  echo -e "Error Message: $(cat mysql_error.txt)"
		  rm mysql_error.txt
		fi
		exit 1
	  fi
	}

        # Function to check if a database exists
        database_exists() {
          local db="$1"
          mysql -e "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '${db}'" | grep -q "${db}"
          return $?
        }

        # Check if the database exists before creating it
	if database_exists "${db_name}"; then
  	  echo -e "${CYAN}Database ${db_name} already exists${RESET}\t[ ${GREEN}OK${RESET} ]"
	else
  	  echo -e "${CYAN}Creating database${RESET}\t[ RUNNING ]"
  	  mysql -e "CREATE DATABASE ${db_name};" 2> mysql_error.txt
  	  check_error
  	  echo -e "${CYAN}Database ${db_name} created${RESET}\t[ ${GREEN}OK${RESET} ]"
  	  echo -e "Creating database user${RESET}\t[ RUNNING ]"
	  mysql -e "CREATE USER ${user_name}@localhost IDENTIFIED BY '${user_pass}';" 2> mysql_error.txt
	  check_error
	  echo -e "${CYAN}Database user ${user_name}@localhost created${RESET}\t[ ${GREEN}OK${RESET} ]"

	  echo -e "${CYAN}Setting permissions for ${user_name}@localhost${RESET}\t[ RUNNING ]"
	  mysql -e "GRANT ALL PRIVILEGES ON ${db_name}.* TO '${user_name}'@'localhost';" 2> mysql_error.txt
	  mysql -e "FLUSH PRIVILEGES;"
	  check_error
	  echo -e "${CYAN}Permissions to ${db_name} added for ${user_name}@localhost${RESET}\t[ ${GREEN}OK${RESET} ]"

	  echo -e "${CYAN}Creating database table${RESET}\t[ RUNNING ]"
	  mysql -e "USE ${db_name}; CREATE TABLE ${table_name}(id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, username VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL, created_on VARCHAR(255) NOT NULL, updated_on VARCHAR(255), hashed_data VARCHAR(255) NOT NULL);" 2> mysql_error.txt
	  check_error
	  echo -e "${CYAN}Created table ${table_name}${RESET}\t[ ${GREEN}OK${RESET} ]"
	  rm mysql_error.txt
	fi
}

# Call the welcome_message function to display the centered welcome message
welcome_message

# Call the run_execution function to execute the task and display its status
run_execution

echo "Execution completed."

