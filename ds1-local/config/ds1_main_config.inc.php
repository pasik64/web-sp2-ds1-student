<?php
/**
 * Tento skript obsahuje univerzalni veci pro dev i produkci.
 */

    // ****************************************************************************************
    // ***********    START CESTY A ADRESARE      *********************************************
    // ****************************************************************************************

        // cesta z homepage webu do rootu projektu
        define("DS1_PROJECT_ROOT_FROM_WEB", '../');
        define("DS1_PROJECT_ROOT_FROM_ADMIN", '../../');


        // zvolit project root dle contextu - definuje se primo v index.php
        if (DS1_CONTEXT == DS1_CONTEXT_VALUE_WEB) {
            define("DS1_PROJECT_ROOT", DS1_PROJECT_ROOT_FROM_WEB);
        } else {
            define("DS1_PROJECT_ROOT", DS1_PROJECT_ROOT_FROM_ADMIN);
        }

        // cesta z PROJECT_ROOT
        define("DS1_DIR_ROOT", "ds1-core/");               // hlavni repozitar
        define("DS1_DIR_ROOT_LOCAL", "ds1-local/");   // lokalni modifikace a konfigurace

        // cesta k admin modules z admina
        define("DS1_DIR_ADMIN_MODULES_FROM_ADMIN", DS1_PROJECT_ROOT_FROM_ADMIN.DS1_DIR_ROOT_LOCAL."admin_modules/");

        // typy modulu
        define("DS1_MODULE_TYPE_ADMIN_PLUGIN", "admin_plugin"); // plugin, ktery se zozbrazi v adminovi
        define("DS1_MODULE_TYPE_ADMIN_API", "admin_api");       // api pro pristup do administrace

        // adresar s twigem
        define("DS1_DIR_TEMPLATES_TWIG_LOCAL", DS1_PROJECT_ROOT.DS1_DIR_ROOT_LOCAL."templates_twig/");
        define("DS1_DIR_TEMPLATES_TWIG_CACHE", DS1_PROJECT_ROOT."cache/twig/");

        // adresar s twigem pro admina - VOLBA ADRESARE PRO ADMINA - musi byt prave jeden
        define("DS1_DIR_TEMPLATES_TWIG_ADMIN", DS1_PROJECT_ROOT.DS1_DIR_ROOT."templates_twig/");

        // adresar s lokalnimi sablonami
        define("DS1_DIR_TEMPLATES_PHP_LOCAL", DS1_PROJECT_ROOT.DS1_DIR_ROOT_LOCAL."templates_php/");

        // adresar s globalnimi sablonami - typicky pro ADMINA
        define("DS1_DIR_TEMPLATES_PHP_GLOBAL", DS1_PROJECT_ROOT.DS1_DIR_ROOT."templates_php/");

        // WEB - volba hlavni sablony
        define("DS1_MAIN_TEMPLATE_WEB", "sablona.twig");
        define("DS1_MAIN_TEMPLATE_WEB_USE_TWIG", true);

        // ADMIN - volba hlavni sablony
        define("DS1_MAIN_TEMPLATE_ADMIN", "ds1_admin.twig"); // funkcni demo - kompletni: DS1_admin_demo_all.twig
        define("DS1_MAIN_TEMPLATE_ADMIN_LOGIN", "ds1_admin_login.twig"); // specialni login template
        define("DS1_MAIN_TEMPLATE_ADMIN_EMPTY", "ds1_admin_empty.twig"); // prazdna stranka napr. pro tisk
        define("DS1_MAIN_TEMPLATE_ADMIN_USE_TWIG", true);


    // ****************************************************************************************
    // ***********    KONEC CESTY A ADRESARE      *********************************************
    // ****************************************************************************************

    // ****************************************************************************************
    // ***********    START DB      ***********************************************************
    // ****************************************************************************************

        // NAZVY TABULEK - DS1
        define("TABLE_PREFIX", "ds1_");

        // uzivatele, kteri pracuji s aplikaci
        define("TABLE_USERS_ADMIN", TABLE_PREFIX."uzivatele");

        // NAZVY SLOUPCU V DB, pokud jsou treba
        define("TABLE_USER_COLUMN_PASSWORD_BCRYPT", "password_bcrypt");

        /* konstanty k pluginum se presunuly do konfigurace jednotlivych modulu - napr. pokoje/pokoje_settings.php
         * ds1.php - metoda setModulesForAdmin nacte vsechny konstanty k modulum
         * vsechny konstanty jsou automaticky globalni - vlastnost php
         */

        // obyvatele = pacienti - konstanta presunuta k pluginu (presun konfigurace)
        // define("TABLE_OBYVATELE", TABLE_PREFIX."obyvatele");

        // obyvatele na pokojich - presun konfigurace
        //define("TABLE_OBYVATELE_NA_POKOJICH", TABLE_PREFIX."obyvatele_na_pokojich");

        // INFO: modul pokoje - KONSTANTY - presun do pokoje_settings.php


        // dodatky pro modul dokumentace
        define("TABLE_DOKUMENTACE", TABLE_PREFIX."dokumentace");
        define("TABLE_OBJEKTY", TABLE_PREFIX."objekty");
        define("TABLE_UZIVATELSKE_ROLE_DB_OBJEKTY", TABLE_PREFIX."uzivatelske_role_db_objekty");
        define("TABLE_DOKUMENTACE_DRUH_ZAPISU", TABLE_PREFIX."dokumentace_druh_zapisu");
        define("TABLE_DRUH_ZAPISU_UZIVATELSKE_ROLE", TABLE_PREFIX."druh_zapisu_uzivatelske_role");
        define("TABLE_UZIVATELSKE_ROLE", TABLE_PREFIX."uzivatelske_role");
        define("TABLE_UZIVATELE_PRIDELENI_ROLI", TABLE_PREFIX."uzivatele_prideleni_roli");

        // planovani vykonu - admin modul
        define("TABLE_SLUZBA", TABLE_PREFIX."sluzba");
        define("TABLE_TYP_VYKONU", TABLE_PREFIX."typ_vykonu");
        define("TABLE_PLAN_VYKONU", TABLE_PREFIX."plan_vykonu");
        define("TABLE_ZAZNAM_VYKONU", TABLE_PREFIX."zaznam_vykonu");
        define("TABLE_ZAZNAM_VYKONU_DETAIL", TABLE_PREFIX."zaznam_vykonu_detail");

    // ****************************************************************************************
    // ***********    KONEC DB      ***********************************************************
    // ****************************************************************************************

    // ****************************************************************************************
    // ***********    START API       *********************************************************
    // ****************************************************************************************

        // zakladni kontrola pri metodach typu login, aby mi to nekdo nehackoval
        define("DS1_API_TOKEN", "bAd8swEj6jdsDbeudgehj$0d5*d");

    // ****************************************************************************************
    // ***********    KONEC API       *********************************************************
    // ****************************************************************************************

    // ****************************************************************************************
    // ***********    START OSTATNI      ******************************************************
    // ****************************************************************************************

        // jmeno klice v session
        define("DS1_SESSION_MAIN_KEY", 'ds1.cz');

        // klice k recaptche
        define("RECAPTCHA_CHECK_URL", "https://www.google.com/recaptcha/api/siteverify");
        define("RECAPTCHA_SITE_KEY", "");
        define("RECAPTCHA_SECRET_KEY", "");

        define("DS1_USER_TOKEN_VALID_HOURS", 24); // kolik hodin je platny token u uzivatele


        // ADMIN - POCET RADKU NA STRANCE
        define("DS1_ADMIN_PAGE_ITEMS_COUNT", 20);

    // ****************************************************************************************
    // ***********    KONEC OSTATNI      ******************************************************
    // ****************************************************************************************



    // ****************************************************************************************
    // ***********    START ROUTES       ******************************************************
    // ****************************************************************************************


        // **************************   ADMIN *****************************************************
        // admin login a logout
        define("DS1_ROUTE_ADMIN_LOGIN", "admin-login");
        define("DS1_ROUTE_ADMIN_LOGOUT", "admin-logout");


    // ****************************************************************************************
    // ***********    KONEC ROUTES       ******************************************************
    // ****************************************************************************************







