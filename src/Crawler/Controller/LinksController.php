<?php


namespace Crawler\Controller;


class LinksController extends UrlController
{
    public $link;

    public $incorrectHosts = ['javascript:', 'mailto:', 'tel:'];

    public function __construct($link)
    {
        $this->link = $link;
        $this->processUrl($this->link);
        if(!$this->checkLinksHost()) {
            unset($this->link);
        }
    }

    public function checkLinksHost()
    {
        if(!in_array($this->getHost(), $this->incorrectHosts)) {
            return true;
        }
    }

    public function getLink()
    {
        return $this->link;
    }
}