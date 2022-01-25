<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * A doctrine Entity class corresponding to the queue_message table in the database
 * @ORM\Entity(repositoryClass="App\Repository\QueueMessageRepository")
 */
class QueueMessage
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $value;

    /**
     * @ORM\Column(type="string")
     */
    private $timestamp;

    /**
     * 
     * @ORM\Column(type="string")
     */
    private $queue_name;
    
    /**
     * 
     * @return int|null
     */
    public function getId(): ?int{
        return $this->id;
    }
    
    /**
     * 
     * @return int|null
     */
    public function getValue(): ?int{
        return $this->value;
    }
    
    /**
     * 
     * @param int $value
     * @return self
     */
    public function setValue(int $value): self{
        $this->value = $value;
        return $this;
    }
    
    /**
     * 
     * @return string|null
     */
    public function getTimestamp(): ?string{
        return $this->timestamp;
    }
    
    /**
     * 
     * @param string $timestamp
     * @return self
     */
    public function setTimestamp(string $timestamp): self{
        $this->timestamp = $timestamp;
        return $this;
    }
    
    /**
     * 
     * @return string|null
     */
    public function getQueueName(): ?string{
        return $this->queue_name;
    }
    
    /**
     * 
     * @param string $queue_name
     * @return self
     */
    public function setQueueName(string $queue_name): self{
        $this->queue_name = $queue_name;
        return $this;
    }
}
