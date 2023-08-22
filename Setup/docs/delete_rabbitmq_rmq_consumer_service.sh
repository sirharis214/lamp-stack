#!/bin/bash

# Stop the service
sudo systemctl stop rabbitmq-service.service

# Delete the service file
sudo rm /lib/systemd/system/rabbitmq-service.service

# Reload systemd manager
sudo systemctl daemon-reload

# Clean up logs and data (optional)
# ... Add commands to remove associated logs, data, or files ...

# Restart systemd manager
sudo systemctl restart systemd

# List active services to verify
sudo systemctl list-units --type=service

