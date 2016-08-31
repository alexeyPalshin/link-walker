<?php

namespace Crawler;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class CrawlerMediator implements CrawlerInterface
{
    /**
     * @var Client
     */
    protected $client;

    protected $memcache;

    public function setColleague(Client $client, \Memcache $memcache)
    {
        $this->client = $client;
        $this->memcache = $memcache;
        $this->memcache->connect('127.0.0.1');
    }

    public function getResponseStatus($url)
    {
        try{
            $response = $this->client->get($url);
            return $response->getStatusCode();
        } catch (ClientException $e) {
            return $e->getResponse()->getStatusCode();
        }
    }

    public function getFromCache($url)
    {
        $key = md5($url);

        return $this->memcache->get($key);
    }

    public function entryCache($url)
    {
        $key = md5($url);

        $this->memcache->set($key, $url);
    }
}