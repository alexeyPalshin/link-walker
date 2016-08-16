<?php


namespace Crawler\Controller;


use Crawler\CrawlerInterface;
use GuzzleHttp\Client;
use DOMDocument;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\BadResponseException;

class CrawlerController implements CrawlerInterface
{
    /**
     * @var HtmlDomParser
     */
    private $dom;

    /**
     * @var array $pageLinks
     */
    private $pageLinks;

    public function __construct(DOMDocument $dom, Client $client)
    {
        $this->dom = $dom;
        $this->client = $client;
    }

    /**
     * crawl url
     *
     * @param string $url
     */
    public function crawl($url)
    {
        $url = new UrlController($url);

        if($url->isUrlValid()) {
            if($this->getResponseStatus($url->getUrl()) == 200) {
                if($url->isUrlValid() && $this->getResponseStatus($url->getUrl()));

                if (@$this->dom->loadHTMLFile($url->getUrl())) {
                    $links = $this->dom->getElementsByTagName('a');
                    foreach ($links as $link) {
                            var_dump($link->getAttribute('href'));
                        $this->pageLinks[] = $link->getAttribute('href');
                    }
                }

//                echo $this->getPageLinks();
            }
        }
    }

    /**
     * return response status code
     *
     * @param $url
     * @return int $status
     */
    public function getResponseStatus($url)
    {
        try {
            $response = $this->client->get($url);
            return $response->getStatusCode();
        } catch (BadResponseException $e) {
            echo 'bad response with ' . $e->getResponse()->getStatusCode() . ' status';
        }
    }

    public function getPageLinks()
    {
        return $this->pageLinks;
    }
//
//    public function setPageContent($url)
//    {
//        $this->pageContent = $this->dom->file_get_html($url);
//    }
}