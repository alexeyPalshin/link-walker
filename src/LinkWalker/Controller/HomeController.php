<?php

namespace LinkWalker\Controller;

use Twig_Environment;

class HomeController
{
    /**
     * @var Twig_Environment
     */
    private $twig;

    public function __construct(Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    public function __invoke()
    {
        echo $this->twig->render('home.twig');
    }
}