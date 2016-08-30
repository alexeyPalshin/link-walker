<?php


namespace Crawler\Controller;


use Crawler\CrawlerColleague;
use DI\Container;

class UrlController extends CrawlerColleague
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
     * @var \Crawler\CrawlerInterface
     */
    private $mediator;

    /**
     * UrlController constructor.
     * @param Container $container
     * @param $baseUrl
     * @throws \DI\NotFoundException
     */
    public function __construct(Container $container, $baseUrl)
    {
        $this->container = $container;

        // TODO: call parent methods without call __construct
        parent::__construct($this->container->get('CrawlerColleague'));

        $this->baseUrl = filter_var($baseUrl, FILTER_SANITIZE_URL);
        $this->processUrl();
        $this->mediator = $this->getCrawlerMediator();
        $this->putUrlToCache($this->baseUrl);
    }

    /**
     * Remove all illegal characters from a url & validate url
     */
    public function processUrl()
    {
        if(filter_var($this->baseUrl, FILTER_VALIDATE_URL) && preg_match(self::REGEX, $this->baseUrl)) {
            $this->isValid = true;
            $urlPaths = parse_url($this->baseUrl);

            foreach (['scheme', 'host', 'path', 'port', 'query'] as $path) {
                if (isset($urlPaths[$path])) {
                    $this->$path .= $urlPaths[$path];
                }
            }
        }
    }

    public function putUrlToCache($url)
    {
        if(null !== $this->mediator->getFromCache($url)) {
            $this->mediator->entryCache($url);
        }
    }

    /**
     * @return mixed|string
     */
    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    /**
     * @return bool
     */
    public function isUrlValid()
    {
        return $this->isValid;
    }

    public function getHost()
    {
        return $this->host;
    }

    public function getScheme()
    {
        return $this->scheme;
    }
}