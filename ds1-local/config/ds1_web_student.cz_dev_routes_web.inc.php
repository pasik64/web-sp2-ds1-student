<?php
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\HttpFoundation\Response;


$routes = new RouteCollection();

    // ZAKLADNI STRANKY
        // HOMEPAGE
        $routes->add('homepage',
        new Route('/',
            array(
                'page'        => 1,
                '_controller' => 'ds1\controllers_web\homepage_controller::indexAction',
            )
        ));

        // 404 - stranka nenalezena TODO + pridat do .htaccess /404


/*
$routes->add('homepage',
    new Route('/',
        array('_controller' => function () {return new Response("homepage");}
        )));
*/
