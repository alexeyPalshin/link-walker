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
                        if (!$this->filterUrl($crawledLink) && $this->startsWith($crawledLink, '/')) {
                            $crawledLink = $url->getScheme() . '://' . $url->getHost() . $crawledLink;
                                $this->pageLinks[] = ['url' => $crawledLink];
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