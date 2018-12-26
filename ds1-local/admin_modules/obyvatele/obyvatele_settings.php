<?php

/**
 *  Konfigurace modulu. Vsechny konfigurace pro vsechny moduly se nacitaji vzdy. Nesmi tam tedy dochazet k zadnym
 *  kolizim a konstanty je treba pojmenovavat poradne a radne rozlisovat konstanty pro modul a konstanty pro vice modoulu.
 */

    // obyvatele = pacienti
    // musim vzdy testovat, jestli nahodou neexistuje. Nekdo to mohl presunout do hlavni konfigurace.
    if (!defined("TABLE_OBYVATELE")) {
        define("TABLE_OBYVATELE", TABLE_PREFIX."obyvatele");
    }


    if (!defined("TABLE_OBYVATELE_NA_POKOJICH")) {
        define("TABLE_OBYVATELE_NA_POKOJICH", TABLE_PREFIX."obyvatele_na_pokojich");
    }
