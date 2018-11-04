<?php

    /**
     * Produkcni routes jsou tady stejne jako dev routes.
     */


    $path = DS1_PROJECT_ROOT . DS1_DIR_ROOT_LOCAL . "config/ds1_web_student.cz_dev_routes_admin.inc.php";
    //echo $path; exit;

    include($path);


    // vratit routy z druheho souboru
    return $routes;