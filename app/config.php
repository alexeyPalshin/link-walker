<?php
/**
 * Created by PhpStorm.
 * User: Alexey Palshin
 * Date: 12.08.2016
 * Time: 23:44
 */

use function DI\object;
use Interop\Container\ContainerInterface;

return [
    'memcacheHost' => '127.0.0.1',
    'memcachePort' => '11211',
    'documentVersion'  => '1.0',
    'documentEncoding' => 'UTF-8',
    DOMDocument::class => function (ContainerInterface $c) {
        return new DOMDocument($c->get('documentVersion'), $c->get('documentEncoding'));
    },

    // Configure Twig
    Twig_Environment::class => function () {
        $loader = new Twig_Loader_Filesystem(__DIR__ . '/../src/LinkWalker/Views');
        return new Twig_Environment($loader);
    },

    CrawlerColleague::class => function () {
        $client = new \GuzzleHttp\Client();
        $mediator = new \Crawler\CrawlerMediator();
        $mediator->setColleague($client, new Memcache());
        return $mediator;
    },

    Memcache::class => function (ContainerInterface $c) {
        $cache = new Memcache();
        $cache->connect($c->get('memcacheHost'), $c->get('memcachePort'));
        return $cache;
    },
];