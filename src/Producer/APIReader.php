<?php

namespace App\Producer;

use Symfony\Component\HttpClient\HttpClient;

Class APIReader{
    
    private $http_client;

    public function __construct(){
            $this->http_client = HttpClient::create();
        }
       
    /**
     * Reads from an api and returns its content
     * @param string $hostname The url of the api
     * @return An associative array equivalent to the json object found in the url
     */
    public function readAPI(string $hostname): array{
        try{
                $response = $this->http_client->request('GET', $hostname);
                $response_array = $response->toArray(); //Maybe deserialize the object instead
                return $response_array;
        }
        catch (HttpExceptionInterface $e){
                print "Rest API call failed";
        }
    }
}
