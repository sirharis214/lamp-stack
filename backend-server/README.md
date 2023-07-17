# backend-server

The backend-server will host a mysql database. We will also run a rabbitmq service on backend-server which will point to rabbitmq-server. This rabbitmq service will be listening in on its own Queue. This would require rabbitmq-server to also create the neccessary Exchange and Queues that the backend-server requires.

# Workflow

1. frontend-server uses rabbitMQ client to send a request
	- The request can be sent to 1 of 2 Exchanges
		- `data-exchange` binded with the Queue `data-get-data`
		- 'data-backend-exchange' binded with Queue `data-get-backend-data`
2. rabbitmq-server is running rabbbitmq service and is listening on Queue `data-get-data` 
	- When frontend sends a request on Exchange `data-get-data` it gets forwarded to the `data-get-data` Queue
	- rabbitmq-server is listening on this Queue so it recieves the request
	- rabbitmq-server processes the request locally
	- lastly, rabbitmq-server returns a response back to frontend-server
3. backend-server is running a mysql-server and rabbitmq service which is listening on Queue `data-get-backend-data`
	- When frontend sends a request on Exchange 'data-backend-exchange' it gets forwarded to the `data-get-backend-data` Queue
	- backend-server is listening on this Queue so it recieves the request
	- backend-server processes the request locally by running mysql queries
	- lastly, backend-server returns a response back to frontend-server
