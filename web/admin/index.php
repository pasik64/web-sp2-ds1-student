<?php
/**
 *      Toto je hlavni skript pro obsluhu cele ADMINISTRACE.
 *
 *      domena.cz/web/admin/index.php
 */

    // nacist autoloader z composeru - pouzivam pro autoloading Symfony component a pro autoloading vlastnich trid
    require_once __DIR__.'/../../vendor/autoload.php';

    // musim vyjmenovat Symfony componenty, ktere chci pouzit
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing;
    use Symfony\Component\HttpKernel;
    use Symfony\Component\EventDispatcher\EventDispatcher;
    use Symfony\Component\HttpFoundation\RequestStack;

    // request stranky
    $request = Request::createFromGlobals();

    // DS1 - nacteni konfigurace - nektere veci se rozhodnou dle contextu
    // rucne se musi nacist vyber konfigurace
    require_once __DIR__.'/../../ds1-local/config/ds1_select_config.inc.php';

    // nastavit context - web nebo admin - jeden skript bude pro admina a druhy pro web
    define("DS1_CONTEXT", DS1_CONTEXT_VALUE_ADMIN);


    // include helper functions - napr. printr
    require_once __DIR__.'/../../ds1-core/core/ds1_helper_functions.inc.php';
    require_once __DIR__.'/../../ds1-core/core/ds1_password_compat_lib.inc.php'; // compat lib. pro password_hash pro verze do Php 5.5
    require_once __DIR__.'/../../vendor/paragonie/random_compat/lib/random.php';  // compat lib. pro random_bytes pro verye do Php 7

    // pomocny objekt pro nacitani ds1
    $ds1_loader = new ds1\core\ds1_loader();

    // config - nacist zakladni konfiguraci v podobe konstant pro tuto instalaci
    require_once __DIR__.'/../../ds1-local/config/ds1_main_config.inc.php';
    require_once __DIR__.'/../../ds1-core/config/ds1_default_config.inc.php'; // defaultni konfigurace, pokud nemam nastaveno
    require_once ($ds1_loader->getPathToConfig());

    // admin moduly - musi byt pred routes
    $modules_admin = include(__DIR__.'/../../ds1-local/config/ds1_modules_admin.inc.php');
    // printr($modules_admin);

    /** @var  RouteCollection */
    $routes = include ($ds1_loader->getPathToRoutes());

    // Symfony komponenty
    $context = new Routing\RequestContext();
    $matcher = new Routing\Matcher\UrlMatcher($routes, $context);
    $resolver = new HttpKernel\Controller\ControllerResolver();

    // URL generator
    $symfony_url_generator = new Symfony\Component\Routing\Generator\UrlGenerator($routes, $context);
    // ukazka pouziti
    //$url = $symfony_url_generator->generate("route_name", array("name" => "nazev", "id" => 5));
    //echo $url; exit;

    // Symfony - zpracovani routes pres matcher
    $dispatcher = new EventDispatcher();
    $dispatcher->addSubscriber(new HttpKernel\EventListener\RouterListener($matcher, new RequestStack()));

    // DS1 - http kernel - jadro frameworku ze symfony
    $ds1_http_kernel = new ds1\core\ds1_http_kernel($dispatcher, $resolver);

    // DS1 - hlavni funkcni objekt
    $ds1 = new ds1\core\ds1($ds1_loader, $symfony_url_generator);
    $ds1->Connect();

    // nastavit moduly pro admina
    $ds1->setModulesForAdmin($modules_admin);

    // PREDANI OBJEKTU DO CONTROLLERU
    // pridat tam ds1_loader - takto tam mohu narvat nejaky pripraveny objekt
    $request->attributes->add(array("ds1" => $ds1));

    // pokud chci videt chyby, tak to potrebuji takto bez try - pro DEV
    if (DS1_DOMAIN_IN_PRODUCTION == false)
    {
        // neni v produkci
        $response = $ds1_http_kernel->handle($request);
    }
    else
    {
        // je v produkci - skryti chyb
        try
        {
            // zpracovani pozacavku
            $response = $ds1_http_kernel->handle($request);

        } catch (Routing\Exception\ResourceNotFoundException $e) {

            $response = new Response('Not Found', 404);
            // nahrait get page response 404

        } catch (Exception $e) {

            $response = new Response('An error occurred', 500);

        }
    } // konec je v produkci

    // odpojit od DB
    $ds1->Disconnect();

    // vratit vysledek
    $response->send();
