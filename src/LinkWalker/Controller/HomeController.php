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
        $this->crawler->crawl($this->getSettingValue('link'), $this->settings);
    }

    /**
     * sanitize incoming settings
     * @param $settings
     *
     */
    public function prepareSettings($settings)
    {
        $args = [
            'link' => FILTER_SANITIZE_URL,
            'depth' => [
                'filter'    => FILTER_VALIDATE_INT,
                'flags'     => FILTER_FORCE_ARRAY,
                'options'   => ['min_range' => 1, 'max_range' => 10]
            ],
            'node' => FILTER_SANITIZE_STRING
        ];
        $settingsNotSanitized = json_decode($settings, 1);

        $this->settings = filter_var_array($settingsNotSanitized, $args);
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