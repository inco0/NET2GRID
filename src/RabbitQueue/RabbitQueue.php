<?php

namespace App\RabbitQueue;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Channel\AMQPChannel;

/*
 * Parent class responsible for establishing a connection with the RabbitMQ queue
 */
Class RabbitQueue{
    
    private const queue_hostname = "candidatemq.n2g-dev.net";
    private const queue_username = "cand_02vp";
    private const queue_port = 5672;
    private const queue_password = "GkLWaqWnNgErKTo0";
    protected const queue_exchange = "cand_02vp";
    protected const queue_queue = "cand_02vp_results";
    protected $n_of_consumers;
    protected $connection;
    protected $channel;
    
    /**
     * Creates an AMQP connection, a channel and declares the queue to be used
     */
    public function __construct(){
        $this->connection = new AMQPStreamConnection(self::queue_hostname, self::queue_port, self::queue_username, self::queue_password, "/", false, "AMQPLAIN", null, "en_US", 3.0, 3.0, null, false, 30); //Set heartbeat to a value bigger than 0 seconds so that the connection does not get dropped
        $this->channel = $this->connection->channel();
        $queue_info_array = $this->channel->queue_declare(self::queue_queue, true);
        $this->n_of_consumers = $queue_info_array[2] + 1; // The amount of consumers that were concurrently running at the point of creation
    }
    
    /**
     * Closes the AMQP connection and channel
     */
    public function __destruct(){
        $this->channel->close();
        $this->connection->close();
    }
    
    /**
     * @return int The amount of consumer applications running at the time this object was created
     */
    public function getConsumers(): int{
        return $this->n_of_consumers;
    }
    
    /**
     * @return AMQPStreamConnection The AMQP connection this object uses
     */
    public function getConnection(): AMQPStreamConnection{
        return $this->connection;
    }
    
    /**
     * @return AMQP Channel The AMQP channel this object uses
     */
    public function getChannel(): AMQPChannel{
        return $this->channel;
    }
}