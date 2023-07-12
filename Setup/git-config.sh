#!/bin/bash

config_file="$HOME/.gitconfig"
credential_file="$HOME/.git-credentials"
user_name="sirharis214"
user_email="hariskido214@gmail.com"
PAT="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"

echo "configuring global git variables"
git config --global user.name "${user_name}"
git config --global user.email "${user_email}"

# Check if .gitconfig file exists
if [ ! -f "$config_file" ]; then
    echo "Creating new .gitconfig file..."
    touch "$config_file"
fi

# Append the desired configuration to the end of the .gitconfig file
cat << EOF >> "$config_file"
[credential]
    helper = store
EOF
echo "Credential helper added to $config_file"

# Check if .git-credentials file exists
if [ ! -f "$credential_file" ]; then
    echo "Creating new .git-credentials file..."
    touch "$credential_file"
fi

touch "$credential_file"
echo "https://${user_name}:${PAT}@github.com" > $credential_file
echo "${credentials_file} created and configured"
