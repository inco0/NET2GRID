<?php

namespace App\Database;

use Doctrine\Common\Persistence\ManagerRegistry;

Class DatabaseManager{
    
    private $manager_registry;
    
    public function __construct(){
        //$this->manager_registry = $manager_registry;
    }
    
    public function store($message): void{
        $entity_manager = $this->getDoctrine()->getManager();
        
        $value = Helper::getValue($message);
        $timestamp = Helper::getTimestamp($message);
        
        $queue_message = new QueueMessage();
        $queue_message->setValue($value);
        $queue_message->setTimestamp($timestamp);
        
        $entity_manager->persist($queue_message);
        $entity_manager->flush();
        print "Message successfully stored in the database";
    }
}
