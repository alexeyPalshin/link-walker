<?php


namespace Crawler\Controller;


use Crawler\CrawlerInterface;
use DOMDocument;

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

    public function __construct(DOMDocument $dom)
    {
        $this->dom = $dom;
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
                    var_dump($link->getAttribute('href'));
                }
                $this->pageLinks[] = $link->getAttribute('href');
            }
        }
//var_dump($this->pageLinks);
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

        if (filter_var($url, FILTER_VALIDATE_URL) === false || !preg_match($regex, $url)) {
            echo 'bad uri';
        } else {
            return $url;
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