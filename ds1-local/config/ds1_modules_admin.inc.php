<?php

    $modules_admin = array();

    // ************************************************************************************
    // **************   Modul obyvatele          ******************************************

    // novy modul
    $module = array();
    $module["name"] = "obyvatele";
    $module["title"] = "Obyvatelé";
    $module["route_name"] = "obyvatele";
    $module["route_path"] = "/plugin/$module[name]";
    $module["route"] = array("controller_name" => "obyvatele_controller", "controller_action" => "indexAction");
    $module["settings_file"] = "obyvatele_settings"; // bez koncovky a bez cesty

    // pridat modul
    $modules_admin[] = $module;

    // **************   KONEC Modul obyvatele          ************************************
    // ************************************************************************************

    // ************************************************************************************
    // **************   Modul pokoje        ***********************************************

    // novy modul
    $module = array();
    $module["name"] = "pokoje";
    $module["title"] = "Pokoje";
    $module["route_name"] = "pokoje";
    $module["route_path"] = "/plugin/$module[name]";
    $module["route"] = array("controller_name" => "pokoje_controller", "controller_action" => "indexAction");
    $module["settings_file"] = "pokoje_settings"; // bez koncovky a bez cesty

    // pridat modul
    $modules_admin[] = $module;

    // **************   KONEC Modul pokoje         ****************************************
    // ************************************************************************************


    return $modules_admin;