#!/bin/bash

root_file="/lib/systemd/system"
service_name="backend-server.service"
server_file="/home/haris/Github/lamp-stack/backend-server/rabbitmqServer.php"


if [ ! -f "${root_file}/${service_name}" ]; then
    echo "Creating ${service_name}"
    touch "${root_file}/${service_name}"
fi

echo "Adding rabbitmq-server configuration"
cat << EOF > "${root_file}/${service_name}"
[Unit]
Description= backend-server listening on Queue data-backend

[Service]
Type=simple
ExecStart=/usr/bin/php -f ${server_file}

[Install]
WantedBy=multi-user.target
EOF

echo "Starting ${service_name}"
sudo systemctl start ${service_name}

#echo "Get ${service_name} status"
#sudo systemctl status ${service_name}

echo "enabling ${service_name}"
sudo systemctl enable ${service_name}
                
