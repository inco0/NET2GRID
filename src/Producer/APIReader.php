<?php

namespace App\Producer;

use Symfony\Component\HttpClient\HttpClient;

Class APIReader{
    
    private $http_client;

    public function __construct(){
            $this->http_client = HttpClient::create();
        }

    public function readAPI(string $hostname): array{
        try{
                $response = $this->http_client->request('GET', $hostname);
                $response_array = $response->toArray();
                return $response_array;
        }
        catch (HttpExceptionInterface $e){
                print "Rest API call failed";
        }
    }
}
