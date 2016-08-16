<?php


namespace Crawler\Controller;


class UrlController
{
    /**
     * regex pattern for url validate
     * @var string
     */
    const REGEX = "/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i";

    /**
     * @var string
     */
    public $url;

    /**
     * @var null|string
     */
    public $scheme;

    /**
     * @var null|string
     */
    public $host;

    /**
     * @var int
     */
    public $port = 80;

    /**
     * @var null|string
     */
    public $path;

    /**
     * @var null|string
     */
    public $query;

    /**
     * is given url valid
     * @var boolean
     */
    public $isValid;

    /**
     * UrlController constructor.
     * @param $url
     */
    public function __construct($url)
    {
        $this->url = filter_var($url, FILTER_SANITIZE_URL);
        $this->processUrl();
    }

    /**
     * Remove all illegal characters from a url & validate url
     */
    public function processUrl()
    {
        if(filter_var($this->url, FILTER_VALIDATE_URL) && preg_match(self::REGEX, $this->url)) {
            $this->isValid = true;
            $urlPaths = parse_url($this->url);

            foreach (['scheme', 'host', 'path', 'port', 'query'] as $path) {
                if (isset($urlPaths[$path])) {
                    $this->$path .= $urlPaths[$path];
                }
            }
        }
    }

    /**
     * @return mixed|string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return bool
     */
    public function isUrlValid()
    {
        return $this->isValid;
    }
}