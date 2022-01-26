<?php

namespace App\Consumer;

use App\RabbitQueue\RabbitQueueReceiver;

/**

*/
class Consumer{
   
    private $rabbit_queue_receiver;
    
    /**
     * 
     * @param type $em
     */
    public function __construct($em){
        $this->rabbit_queue_receiver = new RabbitQueueReceiver($em);
    }
    
    /**
     * 
     * @param type $io
     * @return void
     */
    public function informConsumer($io): void{
        $amount_of_consumers = $this->rabbit_queue_receiver->getConsumers();
        if ($amount_of_consumers == 1){
            $io->title("You are currently the only consumer! Enjoy it while it lasts.");
        }
        else if ($amount_of_consumers == 2)
        {
            $io->title("There is " . $amount_of_consumers - 1 . " more consumer sharing the queue with you!");
        }
        else{
           $io->title("There are " . $amount_of_consumers - 1 . " more consumers sharing the queue with you!");
        }
    }
    
    /**
     * Calls the asynchronous RabbitMQ function that polls the queue 
     * @return void
     */
    public function consumeQueue(){
        $this->rabbit_queue_receiver->receive();
    }
}

