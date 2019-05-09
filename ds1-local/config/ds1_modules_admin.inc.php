<?php

    $modules_admin = array();

    // type:
    // DS1_MODULE_TYPE_ADMIN_PLUGIN - zobrazi se v adminovi
    // DS1_MODULE_TYPE_ADMIN_API - admin api

    // ************************************************************************************
    // **************   Modul obyvatele          ******************************************

    // novy modul
    $module = array();
    $module["name"] = "obyvatele";
    $module["type"] = DS1_MODULE_TYPE_ADMIN_PLUGIN;
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
    $module["type"] = DS1_MODULE_TYPE_ADMIN_PLUGIN;
    $module["title"] = "Pokoje";
    $module["route_name"] = "pokoje";
    $module["route_path"] = "/plugin/$module[name]";
    $module["route"] = array("controller_name" => "pokoje_controller", "controller_action" => "indexAction");
    $module["settings_file"] = "pokoje_settings"; // bez koncovky a bez cesty

    // pridat modul
    $modules_admin[] = $module;

    // **************   KONEC Modul pokoje         ****************************************
    // ************************************************************************************

    // ************************************************************************************
    // **************   Modul dokumentace	***********************************************
    // novy modul
    $module = array();
    $module["name"] = "dokumentace_pacient";
    $module["type"] = DS1_MODULE_TYPE_ADMIN_PLUGIN;
    $module["title"] = "Dokumentace (student 1)";
    $module["route_name"] = "dokumentace_pacient";
    $module["route_path"] = "/plugin/$module[name]";
    $module["route"] = array("controller_name" => "dokumentace_pacient_controller", "controller_action" => "indexAction");
    // pridat modul
    $modules_admin[] = $module;
    // **************   KONEC Modul dokumentace        ************************************
    // ************************************************************************************

    // ************************************************************************************
    // **************   Modul správa uživatelů ***********************************************
    // novy modul
    $module = array();
    $module["name"] = "sprava_uzivatelu";
    $module["type"] = DS1_MODULE_TYPE_ADMIN_PLUGIN;
    $module["title"] = "Správa uživatelů (student 1)";
    $module["route_name"] = "sprava_uzivatelu";
    $module["route_path"] = "/plugin/$module[name]";
    $module["route"] = array("controller_name" => "sprava_uzivatelu_controller", "controller_action" => "indexAction");
    // pridat modul
    $modules_admin[] = $module;
    // **************   KONEC Modul správa uživatelů        ************************************
    // ************************************************************************************

    // ************************************************************************************
    // **************   Modul sluzby        ***********************************************

    // novy modul
    $module = array();
    $module["name"] = "sluzby";
    $module["type"] = DS1_MODULE_TYPE_ADMIN_PLUGIN;
    $module["title"] = "Služby (student 2)";
    $module["route_name"] = "sluzby";
    $module["route_path"] = "/plugin/$module[name]";
    $module["route"] = array("controller_name" => "sluzby_controller", "controller_action" => "indexAction");

    // pridat modul
    $modules_admin[] = $module;

    // **************   KONEC Modul sluzby         ****************************************
    // ************************************************************************************


    // ************************************************************************************
    // **************   API Modul obyvatele          **************************************

    $module = array();
    $module["name"] = "api_obyvatele";
    $module["type"] = DS1_MODULE_TYPE_ADMIN_API;
    $module["title"] = "Obyvatelé API";
    $module["settings_file"] = ""; // bez koncovky a bez cesty

    // priklad testovacich URL:
    /*
     * testovaci url 1: http://localhost/github_web-sp2-ds1-student/web/admin/index.php/api/api_obyvatele_test
     * testovaci url 2: http://localhost/github_web-sp2-ds1-student/web/admin/index.php//api/api_obyvatele_index_action
     */

    // pokud mam vice routes, tak takto:
    $module["routes"]["test"] = array(
        "route_name" => "api_obyvatele_test",
        "route_path" => "/api/api_obyvatele/test",
        "controller_name" => "api_obyvatele_controller",
        "controller_action" => "apiTestAction"
    );
    $module["routes"]["index_action"] = array(
                                                "route_name" => "api_obyvatele_index_action",
                                                "route_path" => "/api/api_obyvatele/index_action",
                                                "controller_name" => "api_obyvatele_controller",
                                                "controller_action" => "apiIndexAction"
                                            );

    // pridat modul
    $modules_admin[] = $module;

    // **************   KONEC API Modul obyvatele          ********************************
    // ************************************************************************************


    return $modules_admin;