<?php
require_once('get_host_info.inc');

class rabbitMQServer{
	private $machine = "";
	public  $broker_host;
	private $broker_port;
	private $user;
	private $password;
	private $vhost;
	private $exchange;
	private $queue;
	private $routing_key = '*';
	private $exchange_type = "topic";
	private $auto_delete = false;

	function __construct($machine, $server){
		$this->machine     = getHostInfo($machine);
		$this->broker_host = $this->machine[$server]["BROKER_HOST"];
		$this->broker_port = $this->machine[$server]["BROKER_PORT"];
		$this->user        = $this->machine[$server]["USER"];
		$this->password    = $this->machine[$server]["PASSWORD"];
		$this->vhost       = $this->machine[$server]["VHOST"];
		
		if (isset($this->machine[$server]["EXCHANGE_TYPE"]) ){
			$this->exchange_type = $this->machine[$server]["EXCHANGE_TYPE"];
		}
		
		if (isset($this->machine[$server]["AUTO_DELETE"]) ){
			$this->auto_delete = $this->machine[$server]["AUTO_DELETE"];
		}
		
		$this->exchange = $this->machine[$server]["EXCHANGE"];
		$this->queue = $this->machine[$server]["QUEUE"];
		echo "Server started".PHP_EOL;
	}

	function process_message($msg){
		// send the ack to clear the item from the queue
		if ($msg->getRoutingKey() !== "*"){
			return;
		}
		
		$this->conn_queue->ack($msg->getDeliveryTag());
		try{
			if ($msg->getReplyTo() ){
				// message wants a response
				// process request
				$body = $msg->getBody();
				$payload = json_decode($body, true);
				$response;
				
				if (isset($this->callback) ){
					$response = call_user_func($this->callback, $payload);
				}

				$params = array();
				$params['host'] = $this->broker_host;
				$params['port'] = $this->broker_port;
				$params['login'] = $this->user;
				$params['password'] = $this->password;
				$params['vhost'] = $this->vhost;
				
				$conn = new AMQPConnection($params);
				$conn->connect();
				
				$channel = new AMQPChannel($conn);
				
				$exchange = new AMQPExchange($channel);
				$exchange->setName($this->exchange);
				$exchange->setType($this->exchange_type);

				$conn_queue = new AMQPQueue($channel);
				$conn_queue->setName($msg->getReplyTo());
				$replykey = $this->routing_key.".response";
				$conn_queue->bind($exchange->getName(), $replykey);
				
				$exchange->publish(json_encode($response), $replykey, AMQP_NOPARAM, array('correlation_id'=>$msg->getCorrelationId()) );

				return;
			}
		}
		catch(Exception $e){
			// ampq throws exception if get fails...
			echo "error: rabbitMQServer: process_message: exception caught: ".$e;
		}
		
		// message does not require a response, send ack immediately
		$body = $msg->getBody();
		$payload = json_decode($body, true);
		if (isset($this->callback) ){
			call_user_func($this->callback,$payload);
		}
		echo "processed one-way message\n";
	}

	function process_requests($callback){
		try{
			$this->callback = $callback;
			$params = array();
			$params['host'] = $this->broker_host;
			$params['port'] = $this->broker_port;
			$params['login'] = $this->user;
			$params['password'] = $this->password;
			$params['vhost'] = $this->vhost;
			
			$conn = new AMQPConnection($params);
			$conn->connect();

			$channel = new AMQPChannel($conn);

			$exchange = new AMQPExchange($channel);
			$exchange->setName($this->exchange);
			$exchange->setType($this->exchange_type);

			$this->conn_queue = new AMQPQueue($channel);
			$this->conn_queue->setName($this->queue);
			$this->conn_queue->bind($exchange->getName(),$this->routing_key);

			$this->conn_queue->consume(array($this,'process_message'));

			// Loop as long as the channel has callbacks registered
			while (count($channel->callbacks) ){
				$channel->wait();
			}
		}
		catch (Exception $e){
			trigger_error("Failed to start request processor: ".$e,E_USER_ERROR); 
		}
	}
}


class rabbitMQClient{
	private $machine = "";
	public  $broker_host;
	private $broker_port;
	private $user;
	private $password;
	private $vhost;
	private $exchange;
	private $queue;
	private $routing_key = "*";
	private $response_queue = array();
	private $exchange_type = "topic";

	function __construct($machine, $server="dev-server"){
		$data              = getHostInfo($machine);
		$this->broker_host = $data[$server]["BROKER_HOST"];
		$this->broker_port = $data[$server]["BROKER_PORT"];
		$this->user        = $data[$server]["USER"];
	        $this->password    = $data[$server]["PASSWORD"];
		$this->vhost       = $data[$server]["VHOST"];
		$this->exchange    = $data[$server]["EXCHANGE"];	
		$this->queue       = $data[$server]["QUEUE"];
		
		if(isset($data[$server]["EXCHANGE_TYPE"]) ){
			$this->exchange_type = $data[$server]["EXCHANGE_TYPE"];
		}

		if(isset($data[$server]["AUTO_DELETE"]) ){
			$this->auto_delete = $data[$server]["AUTO_DELETE"];
		}
	}

	function process_response($response){
		$uid = $response->getCorrelationId();
		if(!isset($this->response_queue[$uid]) ){
			echo "unknown uid\n";
			return true;
		}
		$this->conn_queue->ack($response->getDeliveryTag() );
		$body = $response->getBody();
		$payload = json_decode($body, true);
		
		if(!(isset($payload) ) ){
			$payload = "[empty response]";
		}
		$this->response_queue[$uid] = $payload;
		return false;
	}

	function send_request($message){
		$uid           = uniqid();	
		$json_message = json_encode($message);

		try{
			$params         = array();
			$params['host'] = $this->broker_host; 
			$params['port'] = $this->broker_port;
			$params['login'] = $this->user;
			$params['password'] = $this->password;
			$params['vhost'] = $this->vhost;
			
			# create connection
			$conn = new AMQPConnection($params);
			$conn->connect();
			
			# create a channel
			$channel = new AMQPChannel($conn);
			
			# declare a new exchange
			$new_exchange = new AMQPExchange($channel);
			$new_exchange->setName($this->exchange);
			$new_exchange->setType($this->exchange_type);

			# create a new callback queue 
			$callback_queue = new AMQPQueue($channel);
			$callback_queue->setName($this->queue."-response");
			$callback_queue->declare();
			$callback_queue->bind($new_exchange->getName(), $this->routing_key.".response");

			# new queue
			$this->conn_queue = new AMQPQueue($channel);
			$this->conn_queue->setName($this->queue);
			$this->conn_queue->bind($new_exchange->getName(), $this->routing_key);

			# publish
			$new_exchange->publish($json_message, $this->routing_key, AMQP_NOPARAM, array('reply_to'=>$callback_queue->getName(), 'correlation_id'=>$uid));
			$this->response_queue[$uid] = "waiting";
			$callback_queue->consume(array($this, 'process_response'));

			$response = $this->response_queue[$uid];
			unset($this->response_queue[$uid]);
			return $response;
		}
		catch(Exception $e){
			die("failed to send send_request message to exchange: ".$e->getMessage()."\n");
		}

	}
	
	/**
	  @brief send a one-way message to the server.  These are
	  auto-acknowledged and give no response.

	  @param message the body of the request.  This must make sense to the
	  server
	 */
	function publish($message){
		$json_message = json_encode($message);
		try{
			$params = array();
			$params['host']     = $this->broker_host;
			$params['port']     = $this->broker_port;
			$params['login']    = $this->user;
			$params['password'] = $this->password;
			$params['vhost']    = $this->vhost;
			
			$conn = new AMQPConnection($params);
			$conn->connect();
			
			$channel = new AMQPChannel($conn);
			
			$exchange = new AMQPExchange($channel);
			$exchange->setName($this->exchange);
			$exchange->setType($this->exchange_type);
			
			$this->conn_queue = new AMQPQueue($channel);
			$this->conn_queue->setName($this->queue);
			$this->conn_queue->bind($exchange->getName(),$this->routing_key);
			
			return $exchange->publish($json_message, $this->routing_key);
		}
		catch(Exception $e){
			die("failed to send publish message to exchange: ". $e->getMessage()."\n");
		}
	}
}
?>

