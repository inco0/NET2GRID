<?php

namespace App\Main;

require_once __DIR__ . '/../../vendor/autoload.php';

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Exception\RuntimeException;
use Doctrine\Persistence\ManagerRegistry;
use App\Producer\Producer;
use App\Consumer\Consumer;

/**
 * The starting point of the project, runs with a console command configures and produces either a consumer or a producer
*/
class Main extends Command{
    
    private $io;
    private $entity_manager;

    /**
     * @param ManagerRegistry $manager_registry Uses autowiring to create a ManagerRegistry object
     */
    public function __construct(ManagerRegistry $manager_registry){	
        parent::__construct();
        $this->entity_manager = $manager_registry->getManager(); // The entity manager that will be used to store objects in the database
    }
    
    /**
     * Configures a console command with a name and an argument that needs to be passed
     * @return void
     */
    protected function configure(): void{
        $this->setName('app:start')
            ->setDescription('Starts the application.')
            ->addArgument('type', InputArgument::REQUIRED, 'Run the application as a producer or a consumer.');
    }
    
    /**
     * Runs when the console command of the configuration is executed and calls the corresponding function
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws RunTimeException When the argument passed is neither a "producer" nor "consumer"
     */
    protected function execute(InputInterface $input, OutputInterface $output): int{
        $type = $input->getArgument('type');
        $this->io = new SymfonyStyle($input, $output);
        try{
            if ($type == 'producer'){
                    $this->startProducer();
            } else if ($type == 'consumer'){
                    $this->startConsumer();
            }
            else{
                throw new \RuntimeException("Wrong argument passed. Enter either producer or consumer to run the application accordingly.");
            }
        }
        catch (RuntimeException $e){
            print $e.body;
        }
        return 1;
    }
    
    /**
     * Creates a producer application that is responsible for pushing messages in the queue
     */
    private function startProducer(): void{
        $producer = new Producer();
        try{
            $producer->produce($this->io);
        }
        catch (AMQPConnectionClosedException){
            print "The connection has closed unexpectedly \n";
        }
    }
    
    /**
     * Creates a consumer application that polls the RabbitMQ queue
     */
    private function startConsumer(): void{
        $consumer = new Consumer($this->entity_manager);
        $consumer->informConsumer($this->io);
        try{
            $consumer->consumeQueue();
        }
        catch (AMQPConnectionClosedException){
            print "The connection has closed unexpectedly \n";
        }
    }
}