<?php

namespace LinkWalker\Controller;

use Crawler\Controller\CrawlerController;
use Twig_Environment;

class HomeController
{
    /**
     * @var Twig_Environment
     */
    private $twig;

    private $crawler;

    public $settings = [];

    /**
     * HomeController constructor.
     * @param Twig_Environment $twig
     * @param CrawlerControllerController $crawler
     */
    public function __construct(Twig_Environment $twig, CrawlerController $crawler)
    {
        $this->twig = $twig;
        $this->crawler = $crawler;
    }

    public function __invoke()
    {
        echo $this->twig->render('home.twig');
    }

    /**
     * @param $settings
     */
    public function doCrawl($settings)
    {
        $this->prepareSettings($settings);
        $this->crawler->crawl($this->getSettingValue('link'));
    }

    /**
     * @param $settings
     *
     */
    public function prepareSettings($settings)
    {
        $this->settings = json_decode($settings, 1);
    }

    /**
     * Available settings key: link, depth, node
     * @param $key
     * @return mixed
     */
    public function getSettingValue($key)
    {
        return $this->settings[$key];
    }
}