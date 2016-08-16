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
     * @var array
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
        $url = $this->prepareUrl($url);

        if (@$this->dom->loadHTMLFile($url)) {
            $links = $this->dom->getElementsByTagName('a');
            foreach ($links as $link) {
                if($this->startsWith($link->getAttribute('href'), 'http')){
//                    var_dump($link->getAttribute('href'));
                }
                $this->pageLinks[] = $link->getAttribute('href');
            }
        }

        echo $this->getPageLinks();
    }

    /**
     * Remove all illegal characters from a url & validate url
     *
     * @param string $url
     * @return mixed
     */
    public function prepareUrl($url)
    {
        $url = filter_var($url, FILTER_SANITIZE_URL);
        $regex = "/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i";
        $status = $this->getResponseStatus($url);

        if (filter_var($url, FILTER_VALIDATE_URL) === false || !preg_match($regex, $url)) {
            echo 'bad uri';
        } elseif($status != 200) {
            echo 'bad uri status';
        } else {
            return $url;
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
            $status = $response->getStatusCode();

        } catch (BadResponseException $e) {
            var_dump(Psr7\str($e->getResponse()));
        }
//            return $status;
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
        return $needle === "" ||
        strrpos($haystack, $needle, -strlen($haystack)) !== false;
    }

//    public function setPageLinks()
//    {
//        $this->pageLinks = $this->pageContent->find('pre');
//    }
//
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