<?php

namespace App\RabbitQueue;

use PhpAmqpLib\Message\AMQPMessage;

/**
 * The class that pushes messages on the RabbitMQ queue by publishing them on an exchange
 */
Class RabbitQueueSender extends RabbitQueue{
    
    public function __construct(){
        parent::__construct();
    }
    
    /**
     * Serializes the message and publishes it on the exchange
     * @param string $message The message to be pushed with the structure "value"."timestamp"
     * @param string $routing_key The corresponding routing key in decimal form
     */
    public function send(string $message, string $routing_key){
        $amqp_message = new AMQPMessage($message);
        $this->channel->basic_publish($amqp_message, self::queue_exchange, $routing_key);
    }
}
