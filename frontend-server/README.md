# frontend-server

frontend-server hosts an apache webserver. It uses the rabbitmq client library to send requests to 1 of 2 Exchanges.

# Exchanges
1. `data-exchange` binded with the Queue `data-get-data`
	- rabbitmq-server listens on Queue `data-get-data` and processes requests
2. `data-backend-exchange` binded with Queue `data-get-backend-data`
	- backend-server listens on Queue `data-get-backend-data` and processes requests

# rabbitmq/rabbitmq.ini

We will have configuration for 2 end points. 

```shell
[dev-server]
BROKER_HOST = 10.0.0.11
BROKER_PORT = 5672
USER = haris
PASSWORD = p
VHOST = dev
EXCHANGE = data-exchange
QUEUE = data-get-data
EXCHANGE_TYPE = topic
AUTO_DELETE = true

[backend-server]
BROKER_HOST = 10.0.0.11
BROKER_PORT = 5672
USER = haris
PASSWORD = p
VHOST = dev
EXCHANGE = data-backend-exchange
QUEUE = data-get-backend-data
EXCHANGE_TYPE = direct
AUTO_DELETE = false
```

# Which queue recieves request

The `name` of the submit button in [login.php](./login.php#43) determines which function gets triggered in [client.php](./php/client.php). Each function uses either `$client` or `$client2` which sends requests to either Exchange data-exchange or data-backend-exchange.
