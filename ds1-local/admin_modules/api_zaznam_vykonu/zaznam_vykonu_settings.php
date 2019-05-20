<?php

/**
 *  Konfigurace modulu. Vsechny konfigurace pro vsechny moduly se nacitaji vzdy. Nesmi tam tedy dochazet k zadnym
 *  kolizim a konstanty je treba pojmenovavat poradne a radne rozlisovat konstanty pro modul a konstanty pro vice modoulu.
 */

    
    // musim vzdy testovat, jestli nahodou neexistuje. Nekdo to mohl presunout do hlavni konfigurace.
    if (!defined("TABLE_ZAZNAM_VYKONU")) {
        define("TABLE_ZAZNAM_VYKONU", TABLE_PREFIX."zaznam_vykonu");
    }

    if (!defined("TABLE_DETAILY_VYKONU_MOZNOSTI")) {
        define("TABLE_DETAILY_VYKONU_MOZNOSTI", TABLE_PREFIX."detaily_vykonu_moznosti");
    }

    if (!defined("TABLE_DETAILY_VYKONU")) {
        define("TABLE_DETAILY_VYKONU", TABLE_PREFIX."detaily_vykonu");
    }

    if (!defined("TABLE_ZAZNAM_VYKONU_DETAIL_OBECNY")) {
        define("TABLE_ZAZNAM_VYKONU_DETAIL_OBECNY", TABLE_PREFIX."zaznam_vykonu_detail_obecny");
    }

    if (!defined("TABLE_ZAZNAM_VYKONU_DETAIL_LEKY")) {
        define("TABLE_ZAZNAM_VYKONU_DETAIL_LEKY", TABLE_PREFIX."zaznam_vykonu_detail_leky");
    }

