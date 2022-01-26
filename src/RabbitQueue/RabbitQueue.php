<?php

namespace App\RabbitQueue;

use PhpAmqpLib\Connection\AMQPStreamConnection;

/*
 * Parent class responsible for establishing a connection with the RabbitMQ queue
 */
Class RabbitQueue{
    
    protected $connection;
    protected $channel;
    private const queue_hostname = "candidatemq.n2g-dev.net";
    private const queue_username = "cand_02vp";
    private const queue_port = 5672;
    private const queue_password = "GkLWaqWnNgErKTo0";
    protected const queue_exchange = "cand_02vp";
    protected const queue_queue = "cand_02vp_results";
    protected $n_of_consumers;
    
    public function __construct(){
        //Set heartbeat to 60 seconds so that the connection does not get dropped
        $this->connection = new AMQPStreamConnection(self::queue_hostname, self::queue_port, self::queue_username, self::queue_password, "/", false, "AMQPLAIN", null, "en_US", 3.0, 3.0, null, false, 60);
        $this->channel = $this->connection->channel();
        $queue_info_array = $this->channel->queue_declare(self::queue_queue, true);
        $this->n_of_consumers = $queue_info_array[2] + 1;
    }
    
    public function __destruct(){
        $this->channel->close();
        $this->connection->close();
    }
    
    /**
     * @return int The amount of consumer applications running
     */
    public function getConsumers(): int{
        return $this->n_of_consumers;
    }
    
    /**
     * 
     * @return AMQP Connection
     */
    public function getConnection(){
        return $this->connection;
    }
    
    /**
     * 
     * @return AMQP Channel
     */
    public function getChannel(){
        return $this->channel;
    }
}
