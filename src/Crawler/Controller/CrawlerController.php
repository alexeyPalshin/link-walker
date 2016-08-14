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
     * @var object
     */
    private $pageContent;

    /**
     * @var array
     */
    private $pageLinks;

    public function __construct(DOMDocument $dom)
    {
        $this->dom = $dom;
    }

    public function crawl($url)
    {
        $url = $this->prepareUrl($url);

        if (@$this->dom->loadHTMLFile($url)) {
            $anchors = $this->dom->getElementsByTagName('a');
            foreach ($anchors as $element) {
                $this->pageLinks[] = $element->getAttribute('href');
            }
        }
    }

    public function prepareUrl($url)
    {
        $parts = parse_url($url);
        var_dump($parts);
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