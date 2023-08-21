#!/bin/bash

# Check if script is run as root
if [ "$EUID" -ne 0 ]; then
  echo "Please run this script as root"
  exit 1
fi

# Create user if it doesn't exist
username="automation-user"
password="p"

if id "$username" &>/dev/null; then
  echo "User '$username' already exists."
else
  # Create the user with password
  useradd -m -s /bin/bash "$username"
  echo "$username:$password" | chpasswd

  # Create sudoers file for the user in /etc/sudoers.d/
  echo "$username ALL=(ALL) NOPASSWD: ALL" > "/etc/sudoers.d/$username"
  chmod 440 "/etc/sudoers.d/$username"

  echo "User '$username' created with password '$password' and password-less sudo permission."
fi

