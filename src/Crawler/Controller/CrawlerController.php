<?php


namespace Crawler\Controller;


use Crawler\CrawlerInterface;
use DI\Container;
use GuzzleHttp\Client;
use DOMDocument;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\BadResponseException;

class CrawlerController
{
    /**
     * @var HtmlDomParser
     */
    private $dom;

    /**
     * @var array $pageLinks
     */
    private $pageLinks;

    /**
     * @var Container
     */
    private $container;

    public function __construct(Container $container, DOMDocument $dom)
    {
        $this->dom = $dom;
        $this->container = $container;
    }

    public function runCrawl($url)
    {
        $this->crawl($url);
    }

    /**
     * crawl url
     *
     * @param string $url
     */
    public function crawl($url)
    {
        $url = new UrlController($this->container, $url);

            if($url->isUrlValid()) {
                $this->pageLinks[] = ['url' => $url->getBaseUrl(), 'status' => '200'];

                if (@$this->dom->loadHTMLFile($url->getBaseUrl())) {
                    $links = $this->dom->getElementsByTagName('a');
                    foreach ($links as $link) {
                        $crawledLink = $link->getAttribute('href');
                            $linkObj = new LinksController($crawledLink);
                            if(null === $linkObj->getHost()) {
                                $crawledLink = $url->getScheme() . '://' . $url->getHost() . '/' . $linkObj->getLink();
                                $this->pageLinks[] = ['url' => $crawledLink, 'status' => $url->getResponseCode($crawledLink)];
                            }
                    }
                }
                echo $this->getPageLinks();
        } else {
            echo json_encode(['badResponse' => 'bad uri']);
        }
    }

    public function getPageLinks()
    {
        return json_encode($this->pageLinks);
    }
}