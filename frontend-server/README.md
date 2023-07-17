# frontend-server

frontend-server hosts an apache webserver. It uses the rabbitmq client library to send requests to a single Exchange with the routing key which determines which of the 2 queues get the request. We also create a reply queue on the same exchange and send it as a parameter in the request so the consumer can send a response back to producer through that reply queue, the queue is automaticalled deleted once frontend server consume's that response from reply queue. 

## Exchange
1. `data-exchange` binded with both Queues.
	- also binds to reply queues

## Queues
1. data-rabbitmq
2. data-backend

### Reply queues | auto deleted
1. data-rabbitmq-response
2. data-backend-response

# rabbitmq/rabbitmq.ini
Frontend server needs configuration for both consumers. The value for `ROUTING_KEY` is what distinguishes them.

```shell
[rabbitmq-server]
BROKER_HOST = 10.0.0.11
BROKER_PORT = 5672
USER = haris
PASSWORD = p
VHOST = dev
EXCHANGE = data-exchange
QUEUE = data-rabbitmq
ROUTING_KEY = data.rabbitmq
EXCHANGE_TYPE = topic
AUTO_DELETE = true

[backend-server]
BROKER_HOST = 10.0.0.11
BROKER_PORT = 5672
USER = haris
PASSWORD = p
VHOST = dev
EXCHANGE = data-exchange
QUEUE = data-backend
ROUTING_KEY = data.backend
EXCHANGE_TYPE = topic
AUTO_DELETE = true
```

# Which queue recieves request

The `name` of the form submit button in [index.php](./index.php#L43) or [login.php](./login.php#L43) determines which function gets triggered in [client.php](./php/client.php#L31). Each function uses either `$client` or `$client2`which are rabbitmqClient classes generated with rabbitmq-server creds or backend-server creds. The requests go to the same Exchange but the individual routing_keys determine which queue the request is sent to.
