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

rabbitmq_queue_name="${prefix}-rabbitmq"
rabbitmq_routing_key="${prefix}.rabbitmq"

backend_queue_name="${prefix}backend"
backend_routing_key="${prefix}.backend"

echo "enabling rabbitmq_management, required to create exchanges and queues"
sudo rabbitmq-plugins enable rabbitmq_management
echo "creating vHost: $vhost_name"
sudo rabbitmqctl add_vhost $vhost_name
echo "creating admin user: $user_name"
sudo rabbitmqctl add_user $user_name $user_pass
sudo rabbitmqctl set_user_tags $user_name administrator
sudo rabbitmqctl set_permissions -p $vhost_name $user_name "^data-.*" "^data-.*" "^data-.*"
echo "creating Exchange: $exchange_name"
sudo rabbitmqadmin -u ${user_name} -p ${user_pass} --vhost=${vhost_name} declare exchange name=${exchange_name} type=${exchange_type}

echo "creating Queue: $rabbitmq_queue_name"
rabbitmqadmin -u ${user_name} -p ${user_pass} --vhost=${vhost_name} declare queue name=${rabbitmq_queue_name} durable=true
echo "binding exchange ${exchange_name} to queue ${rabbitmq_queue_name} with routing key ${rabbitmq_routing_key}"
rabbitmqadmin -u ${user_name} -p ${user_pass} --vhost=${vhost_name} declare binding source=${exchange_name} destination_type=queue destination=${rabbitmq_queue_name} routing_key=${rabbitmq_routing_key}

echo "creating Queue: $backend_queue_name"
rabbitmqadmin -u ${user_name} -p ${user_pass} --vhost=${vhost_name} declare queue name=${backend_queue_name} durable=true
echo "binding exchange ${exchange_name} to queue ${queue_name} with routing key ${backend_routing_key}"
rabbitmqadmin -u ${user_name} -p ${user_pass} --vhost=${vhost_name} declare binding source=${exchange_name} destination_type=queue destination=${backend_queue_name} routing_key=${backend_routing_key}
echo "rabbitmq-server configuration complete" 
