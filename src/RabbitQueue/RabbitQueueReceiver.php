<?php

namespace App\RabbitQueue;

use App\Entity\QueueMessage;
use App\Utilities\Helper;

/**
 * The class that is responsible for polling the RabbitMQ queue
 */
Class RabbitQueueReceiver extends RabbitQueue{
    
    private $entity_manager;
    
    public function __construct($em){
        parent::__construct();
        $this->entity_manager = $em;
    }
    
    /**
     * Creates an Entity message, sets the attributes and stores them in the database
     * @param string $value_timestamp A string with the form "value"."timestamp"
     * @return void
     */
    public function store(string $value_timestamp): void{
        $value = intval(Helper::getValue($value_timestamp)); // Value is stored as int in the database
        $timestamp = Helper::getTimestamp($value_timestamp);

        $queue_message = new QueueMessage();
        $queue_message->setValue($value);
        $queue_message->setTimestamp($timestamp);
        $queue_message->setQueueName(self::queue_queue);

        $this->entity_manager->persist($queue_message);
        $this->entity_manager->flush(); // Saves the message in the database
        
        print "The message with value $value and timestamp $timestamp has been successfully stored in the database \n\n";
    }
    
    /**
     * Waits asynchronously until there is a message in the queue and polls it
     * @return void
     */
    public function receive(): string{
        $callback = function($value_timestamp){
             $this->store($value_timestamp->body);
        };
        $this->channel->basic_consume(self::queue_queue, "", false, true, false, false, $callback);
        while ($this->channel->is_open()){
            print "================  Waiting for messages to be inserted in the queue. Press CTRL+C to exit.  ================\n";
            $this->channel->wait();
        }
    }
}