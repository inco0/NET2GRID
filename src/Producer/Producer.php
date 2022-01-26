<?php

namespace App\Producer;

use App\Utilities\Helper;
use App\RabbitQueue\RabbitQueueSender;
use Symfony\Component\Console\Exception\RuntimeException;

/**
* Takes input from the user, produces messages and pushes them to the queue
*/
Class Producer{
    
    private $api_reader;
    private $hostname;
    private $rabbit_queue_sender;
    private $max = 10;

    public function __construct(){
            $this->api_reader = new APIReader();
            $this->hostname = Helper::hostname;
            $this->rabbit_queue_sender = new RabbitQueueSender();
    }
    
    /**
     * Main function that produces messages and pushes them in the queue
     * @param type $io
     * @return void
     */
    public function produce($io): void{
        while (true){
            $n_of_messages = $this->readInput($io);
            for ($i = 0; $i < $n_of_messages; $i++){
                $message_array = $this->produceMessage();

                $message = $message_array["message"];
                $routing_key = $message_array["routing_key"];
                $this->pushToQueue($message, $routing_key);

                $value = Helper::getValue($message);
                $timestamp = Helper::getTimestamp($message);

                $io->text("Pushing message " . $i+1 . " with value $value and timestamp $timestamp to the queue");
                //sleep(5);
            }
            $ending_s = $n_of_messages == 1 ? "" : "s";
            $io->success("$n_of_messages message" . $ending_s .  " succesfully inserted in the queue.");
        }
    }
    
    /**
     * Reads from the api and creates an array with the value and timestamp as body message and the routing key
     * @return array  An associative array ["message" => value.timestamp, "routing_key" => routingkey]
     */
    private function produceMessage(): array{
        $api_associative_array = $this->api_reader->readAPI($this->hostname);
        $routing_key = Helper::getRoutingKey($api_associative_array);
        $message = $api_associative_array["value"] . "." . $api_associative_array["timestamp"];
        $message_array = ["message" => $message, "routing_key" => $routing_key];
        return $message_array;
    }
    
    /**
     * Uses the rabbit sender to push a message on the queue
     * @param string $message
     * @param string $routing_key
     * @return void
     */
    private function pushToQueue(string $message, string $routing_key): void{
        $this->rabbit_queue_sender->send($message, $routing_key);
    }
    
    /**
     * Reads input from the user to see how many messages to create and push
     * @param type $io
     * @return int The amount of messages
     */
    private function readInput($io): int{
        $number = function ($amount){
            try{
                if (!is_numeric($amount)){
                    throw new \RuntimeException("You must enter a numeric value.");
                }
                if ($amount < 0 | $amount > $this->max){
                    throw new \RuntimeException("You must enter a number from 1 to $this->max.");
                }
            }
            catch (RuntimeException $e){
                print "\n" . $e->getMessage();
            }
            return (int)$amount;
        };
        $amount = $io->ask("Enter the amount of messages you want to push up to $this->max or press Enter to push one.", 1, $number);
        return $amount;
    }
}
	
