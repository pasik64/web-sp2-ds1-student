<?php

/**
 *      Vyber konfigurace pro ds1 dle domeny a typu prostredi.
 */

    // DEV
    define("DS1_SELECTED_CONFIGURATION", "web_student.cz_dev");

    // PRODUKCE
    // define("DS1_SELECTED_CONFIGURATION", "web_student.cz_production");

    // tohle potrebuji tady kuli volbe kontextu, ktery musi byt pred konfiguraci ready
    // definice moznych kontextu
    define("DS1_CONTEXT_VALUE_WEB", "web");
    define("DS1_CONTEXT_VALUE_ADMIN", "admin");

