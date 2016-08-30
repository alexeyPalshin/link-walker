<?php
/**
 * Created by PhpStorm.
 * User: Alexey.Palshin
 * Date: 30.08.2016
 * Time: 13:56
 */

namespace Crawler;


abstract class CrawlerColleague
{
    private $crawlerMediator;

    /**
     * @param CrawlerInterface $crawlerInterface
     */
    public function __construct(CrawlerInterface $crawlerInterface)
    {
        $this->crawlerMediator = $crawlerInterface;
    }

    protected function getCrawlerMediator()
    {
        return $this->crawlerMediator;
    }
}