<?php

namespace AppBundle\Service;

use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\ParameterBag;

class EdtService
{
    private $host;
    private $projectId;
    private $login;
    private $password;
    private $resourcesMapping;

    public function __construct($config)
    {
        $this->host = $config["host"];
        $this->projectId = $config["projectId"];
        $this->login = $config["login"];
        $this->password = $config["password"];
        $this->resourcesMapping = $config["resourcesMapping"];
    }
    
    public function raw(ParameterBag $query)
    {
        $client = new Client();
        $response = $client->get($this->host, array(
           "query" => $query->all()
        ));

        return new \SimpleXMLElement($response->getBody());
    }

    public function getResources($detail = 13)
    {
        $client = new Client();
        $response = $client->get($this->host, array(
            "query" => array(
                "function" => "getResources",
                "projectId" => $this->projectId,
                "detail" => $detail ? $detail : 13,
                "login" => $this->login,
                "password" => $this->password
            )
        ));

        return new \SimpleXMLElement($response->getBody());
    }
    
    public function getActivities($resources, $detail = 17)
    {
        $client = new Client();
        $response = $client->get($this->host, array(
            "query" => array(
                "function" => "getActivities",
                "projectId" => $this->projectId,
                "resources" => $resources,
                "detail" => $detail ? $detail : 17,
                "login" => $this->login,
                "password" => $this->password
            )
        ));

        return new \SimpleXMLElement($response->getBody());
    }

    public function getEvents($resources, $date, $detail = 8)
    {
        $client = new Client();
        $response = $client->get($this->host, array(
            "query" => array(
                "function" => "getEvents",
                "projectId" => $this->projectId,
                "resources" => $resources,
                "date" => $date,
                "detail" => $detail ? $detail : 8,
                "login" => $this->login,
                "password" => $this->password
            )
        ));

        return new \SimpleXMLElement($response->getBody());
    }
}