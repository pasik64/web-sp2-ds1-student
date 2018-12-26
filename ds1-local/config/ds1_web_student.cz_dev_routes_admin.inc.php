<?php
    use Symfony\Component\Routing\Route;
    use Symfony\Component\Routing\RouteCollection;
    use Symfony\Component\HttpFoundation\Response;

    // ROUTING PRO ADMINA
    $routes = new RouteCollection();

    // ZAKLADNI STRANKY

    // dashboard - uvodni stranka
    $routes->add('dashboard',
        new Route('/',
            array(
                'page'        => 1,
                '_controller' => 'ds1\controllers_admin\dashboard_controller::indexAction',
            )
        ));


    // admin pages
    $admin_pages_pom = array("user_manager");
    if ($admin_pages_pom != null) {
        foreach ($admin_pages_pom as $admin_page) {

            // pridat jednotlive stranky do routes
            $routes->add($admin_page,
                new Route('/'.$admin_page,
                    array(
                        'page'        => 1,
                        '_controller' => "ds1\controllers_admin\\".$admin_page."_controller::indexAction",
                    )
                ));
        }
    }
    // konec admin pages

    $routes->add(DS1_ROUTE_ADMIN_LOGIN,
        new Route('/login',
            array(
                'page'        => 1,
                '_controller' => 'ds1\controllers_admin\user_admin_controller::loginAction',
            )
        ));

    $routes->add(DS1_ROUTE_ADMIN_LOGOUT,
        new Route('/logout',
            array(
                'page'        => 1,
                '_controller' => 'ds1\controllers_admin\user_admin_controller::logoutAction',
            )
        ));


    // admin moduly musi jit az jako posledni
    // tady se normalne dostanu k modulum pro admina
    //printr($modules_admin);

    if ($modules_admin != null)
        foreach ($modules_admin as $admin_module) {

            $ctrl_name = $admin_module["route"]["controller_name"];
            $ctrl_action = $admin_module["route"]["controller_action"];

            // bude to ulozeno ve slozce ds1_local/admin_modules/nazev_modulu/controller ...
            $controller = "ds1\admin_modules\\$admin_module[name]\\$ctrl_name::$ctrl_action";
            //echo $controller; exit;

            $routes->add($admin_module["route_name"],
                new Route($admin_module["route_path"],
                    array(
                        'page'        => 1,
                        '_controller' => $controller
                    )
                ));
        }


    // START RUCNI API, zatim to neni pridano do admin modulu
    // obyvatele API
    $routes->add("obyvatele_api",               // nejde pres konstantu, jeste tady neni z admin modulu
        new Route("/plugin/obyvatele-api",
            array(
                '_controller' => "ds1\admin_modules\obyvatele\obyvatele_controller::apiAction"
            )
        ));
    // KONEC API


    // 404 - stranka nenalezena TODO + pridat do .htaccess /404


    // NEMAZAT !!!
    // zachyti ostatni akce, vcetne chyb u obrazku
    $routes->add('homeodpad',
        new Route(
            '/{action}',
            // defaults
            array(
                '_controller' => 'ds1\controllers_admin\dashboard_controller::odpadAction'
            ),
            array('action' => '.+')   // requirements - diky .+ muze category obsahovat i lomitko
        )
    );


    // VRATIT VYSLEDEK
    return $routes;


