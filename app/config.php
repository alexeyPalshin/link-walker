<?php
/**
 * Created by PhpStorm.
 * User: Alexey Palshin
 * Date: 12.08.2016
 * Time: 23:44
 */

use function DI\object;

return [
    // Configure Twig
    Twig_Environment::class => function () {
        $loader = new Twig_Loader_Filesystem(__DIR__ . '/../src/LinkWalker/Views');
        return new Twig_Environment($loader);
    },
];