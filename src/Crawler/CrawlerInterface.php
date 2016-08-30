<?php


namespace Crawler;


interface CrawlerInterface
{
    public function getResponseStatus($url);

    public function entryCache($url);
}