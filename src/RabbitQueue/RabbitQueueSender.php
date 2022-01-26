<?php

namespace App\RabbitQueue;

use PhpAmqpLib\Message\AMQPMessage;

/**
 * The class that pushes message on the RabbitMQ queue
 */
Class RabbitQueueSender extends RabbitQueue{
    
    public function __construct(){
        parent::__construct();
    }
    
    /**
     * Serializes the message and pushes it on the exchange
     * @param string $message
     * @param string $routing_key
     */
    public function send(string $message, string $routing_key){
        $amqp_message = new AMQPMessage($message);
        $this->channel->basic_publish($amqp_message, self::queue_exchange, $routing_key);
    }
    
}
