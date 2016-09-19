<?php


namespace Crawler\Controller;


use Crawler\CrawlerInterface;
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

            if($url->isUrlValid() && $this->getResponseStatus($url->getUrl()) == 200) {
                $this->pageLinks[] = ['url' => $url->getUrl(), 'status' => '200'];

                if (@$this->dom->loadHTMLFile($url->getUrl())) {
                    $links = $this->dom->getElementsByTagName('a');
                    foreach ($links as $link) {
                        $crawledLink = $link->getAttribute('href');
                        if (!$this->filterUrl($crawledLink) && $this->startsWith($crawledLink, '/')) {
                            $crawledLink = $url->getScheme() . '://' . $url->getHost() . $crawledLink;
                            $status = $this->getResponseStatus($crawledLink);
                            if($status == 200) {
                                $this->pageLinks[] = ['url' => $crawledLink, 'status' => $status];
                            }
                        }
                    }
                }
                echo $this->getPageLinks();
        } else {
            echo json_encode(['badResponse' => 'bad uri']);
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
            return 'bad response with ' . $e->getResponse()->getStatusCode() . ' status';
        }
    }

    public function getPageLinks()
    {
        return json_encode($this->pageLinks);
    }


    public function filterUrl($link)
    {
        if ($this->startsWith($link, 'javascript:') ||
            $this->startsWith($link, 'mailto:') ||
            $this->startsWith($link, 'tel:') ||
            $this->startsWith($link, '#') ||
            $link == '') {
            return;
        }

        if($this->startsWith($link, '/')) {
            $link .= $this->scheme . $this->host;
        }
    }

    /**
     * Check haystack starts with needle.
     *
     * @param string $haystack String to check.
     * @param string $needle   Check if $haystack start with it
     *
     * @return boolean
     */
    protected function startsWith($haystack, $needle) {
        return strrpos($haystack, $needle, -strlen($haystack)) !== false;
    }
}