<?php

namespace App\Producer;

use App\Utilities\Helper;
use App\RabbitQueue\RabbitQueueSender;

/**
* Produces messages and pushes them in the queue every few seconds
*/
Class Producer{
    
    private $api_reader;
    private $hostname;
    private $rabbit_queue_sender;
    private $interval;

    public function __construct(){
            $this->api_reader = new APIReader();
            $this->hostname = Helper::getHostname();
            $this->rabbit_queue_sender = new RabbitQueueSender();
            $this->interval = 10;
    }
    
    /**
     * Main function that produces messages and pushes them in the queue every few seconds
     * @param type $io
     */
    public function produce($io): void{
        $channel = $this->rabbit_queue_sender->getChannel();
        $io->title("Pushing messages to the queue every " . $this->interval . " seconds. Press CTRL+C to stop the program.");
        while ($channel->is_open()){
            $api_array = $this->produceMessage();

            $message = $api_array["message"];
            $routing_key = $api_array["routing_key"];
            $this->pushToQueue($message, $routing_key);

            $value = Helper::getValue($message);
            $timestamp = Helper::getTimestamp($message);

            $io->success("Pushing message with value $value and timestamp $timestamp to the queue");
            sleep($this->interval);
        }
    }
    
    /**
     * Reads from the API and creates an array containing the value, the timestamp and the converted to decimal routing key
     * @return array  An associative array with the following structure ["message" => value.timestamp, "routing_key" => routing_key]
     */
    private function produceMessage(): array{
        $api_associative_array = $this->api_reader->readAPI($this->hostname);
        $routing_key = Helper::getRoutingKey($api_associative_array);
        $message = $api_associative_array["value"] . "." . $api_associative_array["timestamp"];
        $api_array = ["message" => $message, "routing_key" => $routing_key];
        return $api_array;
    }
    
    /**
     * Uses the rabbit sender to push a message in the queue
     * @param string $message
     * @param string $routing_key
     */
    private function pushToQueue(string $message, string $routing_key): void{
        $this->rabbit_queue_sender->send($message, $routing_key);
    }
}