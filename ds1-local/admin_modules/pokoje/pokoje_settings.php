<?php

/**
 *  Konfigurace modulu. Vsechny konfigurace pro vsechny moduly se nacitaji vzdy. Nesmi tam tedy dochazet k zadnym
 *  kolizim a konstanty je treba pojmenovavat poradne a radne rozlisovat konstanty pro modul a konstanty pro vice modoulu.
 */


    if (!defined("TABLE_POKOJE")) {
        define("TABLE_POKOJE", TABLE_PREFIX."pokoje");
    }

    if (!defined("TABLE_SKUPINY_POKOJU")) {
        define("TABLE_SKUPINY_POKOJU", TABLE_PREFIX . "skupiny_pokoju");
    }