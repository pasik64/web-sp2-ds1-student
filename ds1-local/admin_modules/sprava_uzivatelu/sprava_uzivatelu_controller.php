<?php
namespace ds1\admin_modules\sprava_uzivatelu;

use Symfony\Component\HttpFoundation\Request;
use ds1\core\ds1_base_controller;

class sprava_uzivatelu_controller extends ds1_base_controller
{
    // timto rikam, ze je NUTNE PRIHLASENI ADMINA
    protected $admin_secured_forced = true; // vynuceno pro jistotu, ale mel by stacit kontext admin

    public function indexAction(Request $request, $page = "")
    {
        // zavolat metodu rodice, ktera provede obecne hlavni kroky a nacte parametry
        parent::indexAction($request, $page);

        // test, jestli je uzivatel prihlasen, pokud NE, tak redirect na LOGIN
        $this->checkAdminLogged();

        $sprava_uzivatelu = new sprava_uzivatelu();
        $sprava_uzivatelu->SetPDOConnection($this->ds1->GetPDOConnection());

        $action = $this->loadRequestParam($request,"action", "all", "admin_users_list_all");

        // univerzalni content params
        $content_params = array();
        $content_params["base_url"] = $this->webGetBaseUrl();
        $content_params["base_url_link"] = $this->webGetBaseUrlLink();
        $content_params["page_number"] = $this->page_number;
        $content_params["route"] = $this->route;        // mam tam orders, je to automaticky z routingu
        $content_params["route_params"] = array();
        $content_params["controller"] = $this;

        // JMENA EXTERNICH ROUT
        $content_params["pokoje_pacient_name"] = "sprava_uzivatelu";

        $content = "";

        // defaultni vysledek akce
        $result_msg = "";
        $result_ok = true;


        $prihlaseny_uzivatel_udaje = array(); //bude obsahovat údaje o přihlášeném uživateli
        $prihlaseny_uzivatel_udaje = $this->ds1->user_admin->getAdminUserFromSession(); //zjistím údaje o přihlášeném uživateli (zajímá mě jméno - práva pak vyčtu z DB)

        if($action == "admin_users_list_all"){
            if($prihlaseny_uzivatel_udaje["login"] != "admin"){ //pokud není přihlášený admin, tak ho přesměruji
                $admin_url =  $this->makeUrlByRoute($this->route, array("action" => "normal_user_no_access"));
                header("Location: $admin_url");
                exit();
            }
            
            $zadano_vyhledavani = $this->loadRequestParam($request, "uzivatel_hledani", "post", null);

            // MD FIXME, mam objekt pro praci se SESSION
            $_SESSION["uzivatel_zadano_prijmeni"] = $zadano_vyhledavani["prijmeni"];

            //musíme si ze session "vytáhnout" informace o zadaném příjmení ve vyhledávání
            $content_params["vyhledavani_prijmeni"] = $_SESSION["uzivatel_zadano_prijmeni"];


            $content_params["form_submit_url"] = $this->makeUrlByRoute($this->route);
            $content_params["role_edit"] = "admin_users_edit_role";
            //pokud přihlášený admin, pak pokračuji...

            //pokud nemám specifikované příjmení, pak zobrazím všechny uživatele systému
            if($content_params["vyhledavani_prijmeni"] == "")
            {
                $data_uzivatelu = $sprava_uzivatelu -> getDataVsechUzivatelu();
                $content_params["info_tabulka_zobrazeno"] = "V tabulce jsou aktuálně vypsáni <b>VŠICHNI</b> uživatelé";
            }
            else
            {
                //pokud mám specifikované příjmení, pak zobrazím jen uživatele s daným příjmením
                $data_uzivatelu_test = $sprava_uzivatelu -> getDataUzivateluPrijmeni($content_params["vyhledavani_prijmeni"]);
                if(sizeof($data_uzivatelu_test) > 0)
                {
                    //existuje alespoň jeden uživatel s daným příjmením = zobrazím jen omezený počet uživatelů
                    $data_uzivatelu = $data_uzivatelu_test;
                    $content_params["info_tabulka_zobrazeno"] = "V tabulce jsou aktuálně vypsáni uživatelé s příjmením <b>$content_params[vyhledavani_prijmeni]</b>";
                }
                else
                {
                    //uživatel se zadaným příjmením neexistuje = zobrazím všechny uživatele
                    $data_uzivatelu = $sprava_uzivatelu -> getDataVsechUzivatelu();
                    $content_params["info_tabulka_zobrazeno"] = "Uživatel se zadaným příjmením <b>$content_params[vyhledavani_prijmeni]</b> neexistuje -> v tabulce jsou aktuálně vypsáni všichni uživatelé";
                }
            }

            //přidělím uživateli informace o jeho roli
            for($i = 0; $i < sizeof($data_uzivatelu); $i++){
                $data_uzivatelu[$i]["role_data"] = $sprava_uzivatelu -> getRoleUzivatelByIDUzivatel($data_uzivatelu[$i]["id"]);
            };

            /*foreach ($data_uzivatelu as $uzivatel_data){
                $uzivatel_data["role_data"] = $dokumentace_pacient -> getRoleUzivatelByIDUzivatel($uzivatel_data["id"]);
            }*/
            $content_params["uzivatele_info"] = $data_uzivatelu;
            $content_params["url_uprava_typu_roli"] = $this->makeUrlByRoute($this->route, array("action" => "admin_users_uprava_typu_roli")); //odkaz na přidání role
            $uzivatele = $sprava_uzivatelu -> getVsechnaPrijmeniUzivatele(); //všechna příjmení uživatelů
            $content_params["napoveda_prijmeni_uzivatelu"] = $uzivatele;
            $content = $this->renderPhp(DS1_DIR_ADMIN_MODULES_FROM_ADMIN . "sprava_uzivatelu/templates/sprava_uziv_admin_list.inc.php", $content_params, true);
        } else if($action == "admin_users_uprava_typu_roli")
        {
            if ($prihlaseny_uzivatel_udaje["login"] != "admin")
            {
                $admin_url = $this->makeUrlByRoute($this->route, array("action" => "normal_user_no_access"));
                header("Location: $admin_url");
                exit();
            }
            $uzivatelske_role = $sprava_uzivatelu -> getUzivatelskeRole();
            $content_params["url_uzivatele_list"] = $this->makeUrlByRoute($this->route, array("action" => "admin_users_list_all"));
            $content_params["vsechny_role"] = $uzivatelske_role;
            $content_params["role_edit"] = "admin_uprava_role";
            $content_params["url_pridani_role"] = $this->makeUrlByRoute($this->route, array("action" => "admin_add_role"));

            $content = $this->renderPhp(DS1_DIR_ADMIN_MODULES_FROM_ADMIN . "sprava_uzivatelu/templates/sprava_uziv_uprava_typu_roli.inc.php", $content_params, true);

        } else if ($action == "admin_add_role") {
            if ($prihlaseny_uzivatel_udaje["login"] != "admin")
            {
                $admin_url = $this->makeUrlByRoute($this->route, array("action" => "normal_user_no_access"));
                header("Location: $admin_url");
                exit();
            }
            $content_params["form_submit_url"] = $this->makeUrlByRoute($this->route);
            $content_params["form_action_save_role"] = "save_role_result";
            $content_params["url_seznam_roli"] = $this->makeUrlByRoute($this->route, array("action" => "admin_users_uprava_typu_roli"));
            $content = $this->renderPhp(DS1_DIR_ADMIN_MODULES_FROM_ADMIN . "sprava_uzivatelu/templates/sprava_uziv_add_role.inc.php", $content_params, true);
        } else if ($action == "save_role_result") {
            if ($prihlaseny_uzivatel_udaje["login"] != "admin") {
                $admin_url = $this->makeUrlByRoute($this->route, array("action" => "normal_user_no_access"));
                header("Location: $admin_url");
                exit();
            }

            $nova_role = $this->loadRequestParam($request, "nova_role", "post", null);

            $role_test = $sprava_uzivatelu -> getRoleIDByNazevRole($nova_role);

            if ($role_test != null) {
                $content_params["url_add_role"] = $this->makeUrlByRoute($this->route, array("action" => "admin_add_role"));
                $content_params["url_seznam_roli"] = $this->makeUrlByRoute($this->route, array("action" => "admin_users_uprava_typu_roli"));
                $content = $this->renderPhp(DS1_DIR_ADMIN_MODULES_FROM_ADMIN . "sprava_uzivatelu/templates/sprava_uziv_add_role_error.inc.php", $content_params, true);
            } else {
                $sprava_uzivatelu -> addNewRole($nova_role);
                $content_params["url_add_role"] = $this->makeUrlByRoute($this->route, array("action" => "admin_add_role"));
                $content_params["url_seznam_roli"] = $this->makeUrlByRoute($this->route, array("action" => "admin_users_uprava_typu_roli"));
                $content = $this->renderPhp(DS1_DIR_ADMIN_MODULES_FROM_ADMIN . "sprava_uzivatelu/templates/sprava_uziv_add_role_done.inc.php", $content_params, true);
            }

        }
        else if($action == "admin_uprava_role")
        {
            if ($prihlaseny_uzivatel_udaje["login"] != "admin")
            {
                $admin_url = $this->makeUrlByRoute($this->route, array("action" => "normal_user_no_access"));
                header("Location: $admin_url");
                exit();
            }
            $role_vybrano = $this->loadRequestParam($request, "role", "all", null);
            $role_prideleni_objektu = $sprava_uzivatelu -> getDbObjektyPrideleniByIdRole($role_vybrano["id"]);
            $db_objekty = $sprava_uzivatelu -> getDbObjekty();

            $content_params["url_uprava_typu_roli"] = $this->makeUrlByRoute($this->route, array("action" => "admin_users_uprava_typu_roli"));
            $content_params["uprava_role_edit"] = "admin_uprava_role_edit";
            $content_params["role"] = $role_vybrano;
            $content_params["role_prideleni_objektu"] = $role_prideleni_objektu;
            $content_params["db_objekty"] = $db_objekty;

            $content = $this->renderPhp(DS1_DIR_ADMIN_MODULES_FROM_ADMIN . "sprava_uzivatelu/templates/sprava_uziv_uprava_role.inc.php", $content_params, true);

        } else if($action == "admin_uprava_role_edit")
        {
            if ($prihlaseny_uzivatel_udaje["login"] != "admin") {
                $admin_url = $this->makeUrlByRoute($this->route, array("action" => "normal_user_no_access"));
                header("Location: $admin_url");
                exit();
            }

            $role = $this->loadRequestParam($request, "role", "all", null);
            $prideleni = $this->loadRequestParam($request, "prideleni", "all", null);
            $objekt = $this->loadRequestParam($request, "objekt", "all", null);

            $content_params["url_uprava_role"] = $this->makeUrlByRoute($this->route, array("action" => "admin_uprava_role", "role" => $role));
            $content_params["uprava_role_edit"] = "admin_uprava_role_edit";
            $content_params["role"] = $role;
            $content_params["role_prideleni"] = $prideleni;
            $content_params["form_submit_url"] = $this->makeUrlByRoute($this->route);
            $content_params["form_action_save_prava"] = "save_prava_review";
            $content_params["objekt"] = $objekt;

            $content = $this->renderPhp(DS1_DIR_ADMIN_MODULES_FROM_ADMIN . "sprava_uzivatelu/templates/sprava_uziv_uprava_role_edit.inc.php", $content_params, true);

        } else if($action == "save_prava_review")
        {
            if ($prihlaseny_uzivatel_udaje["login"] != "admin") {
                $admin_url = $this->makeUrlByRoute($this->route, array("action" => "normal_user_no_access"));
                header("Location: $admin_url");
                exit();
            }

            $zadana_prava = $this->loadRequestParam($request, "zadanaPrava", "post", null);
            $role = $this->loadRequestParam($request, "role", "post", null);
            $objekt = $this->loadRequestParam($request, "objekt", "post", null);

            $role_nazev = $sprava_uzivatelu -> getRoleNazevByIdRole($role);
            $objekt_nazev = $sprava_uzivatelu -> getObjektNazevByIdObjektu($objekt);
            $prideleni_soucasne = $sprava_uzivatelu -> getPrideleniPravByIdObjektuAndIdRole($objekt, $role);
            $prideleni_soucasne_string = "";
            if ($prideleni_soucasne != null) {
                if ($prideleni_soucasne["read"] == 1) {
                    $prideleni_soucasne_string = "read";
                }
                if ($prideleni_soucasne["insert"] == 1) {
                    if ($prideleni_soucasne_string != "") {
                        $prideleni_soucasne_string = $prideleni_soucasne_string.", insert";
                    } else {
                        $prideleni_soucasne_string = "insert";
                    }
                }
                if ($prideleni_soucasne["update"] == 1) {
                    if ($prideleni_soucasne_string != "") {
                        $prideleni_soucasne_string = $prideleni_soucasne_string.", update";
                    } else {
                        $prideleni_soucasne_string = "update";
                    }
                }
                if ($prideleni_soucasne["delete"] == 1) {
                    if ($prideleni_soucasne_string != "") {
                        $prideleni_soucasne_string = $prideleni_soucasne_string.", delete";
                    } else {
                        $prideleni_soucasne_string = "delete";
                    }
                }
            }
            if ($prideleni_soucasne_string == "") {
                $prideleni_soucasne_string = "žádná";
            }
            $prideleni_nove_string = "";
            if ($zadana_prava != null) {
                foreach ($zadana_prava as $prava) {
                    if ($prava == "read") {
                        $prideleni_nove_string = "read";
                    }
                    if ($prava == "insert") {
                        if ($prideleni_nove_string != "") {
                            $prideleni_nove_string = $prideleni_nove_string . ", insert";
                        } else {
                            $prideleni_nove_string = "insert";
                        }
                    }
                    if ($prava == "update") {
                        if ($prideleni_nove_string != "") {
                            $prideleni_nove_string = $prideleni_nove_string . ", update";
                        } else {
                            $prideleni_nove_string = "update";
                        }
                    }
                    if ($prava == "delete") {
                        if ($prideleni_nove_string != "") {
                            $prideleni_nove_string = $prideleni_nove_string . ", delete";
                        } else {
                            $prideleni_nove_string = "delete";
                        }
                    }
                }
            }
            if ($prideleni_nove_string == "") {
                $prideleni_nove_string = "žádné";
            }

            $content_params["zadana_prava"] = $zadana_prava;
            $content_params["role_nazev"] = $role_nazev;
            $content_params["role_id"] = $role;
            $content_params["objekt_id"] = $objekt;
            $content_params["objekt_nazev"] = $objekt_nazev;
            $content_params["prideleni_soucasne"] = $prideleni_soucasne_string;
            $content_params["prideleni_nove"] = $prideleni_nove_string;
            $content_params["url_add_result"] = $this->makeUrlByRoute($this->route, array("action" => "role_add_prava_to_objekt"));
            
            $role_item = array();
            $role_item["nazev"] = $sprava_uzivatelu -> getRoleNazevByIdRole($role);
            $role_item["id"] = $role;

            $objekt_item = array();
            $objekt_item["nazev"] = $objekt_nazev;
            $objekt_item["id"] = $objekt;
            $content_params["url_uprava_role_edit"] = $this->makeUrlByRoute($this->route, array("action" => "admin_uprava_role_edit", "prideleni" => $prideleni_soucasne, "role" => $role_item, "objekt" => $objekt_item));


            $content = $this->renderPhp(DS1_DIR_ADMIN_MODULES_FROM_ADMIN . "sprava_uzivatelu/templates/sprava_uziv_uprava_role_review.inc.php", $content_params, true);

        } else if($action == "role_add_prava_to_objekt") {
            if ($prihlaseny_uzivatel_udaje["login"] != "admin") { //pokud není přihlášený admin, tak ho přesměruji
                $admin_url = $this->makeUrlByRoute($this->route, array("action" => "normal_user_no_access"));
                header("Location: $admin_url");
                exit();
            }

            $zadana_prava = $_SESSION["zadana_prava"];
            $role_id = $_SESSION["role_id"];
            $objekt_id = $_SESSION["objekt_id"];

            $role_item = array();
            $role_item["nazev"] = $sprava_uzivatelu -> getRoleNazevByIdRole($role_id);
            $role_item["id"] = $role_id;

            $sprava_uzivatelu -> saveRolePravaToObject($role_id, $objekt_id, $zadana_prava);
            $content_params["role"] = $role_id;
            $content_params["url_uprava_role"] = $this->makeUrlByRoute($this->route, array("action" => "admin_uprava_role", "role" => $role_item));
            $content_params["url_uprava_typu_roli"] = $this->makeUrlByRoute($this->route, array("action" => "admin_users_uprava_typu_roli"));
            $content = $this->renderPhp(DS1_DIR_ADMIN_MODULES_FROM_ADMIN . "sprava_uzivatelu/templates/sprava_uziv_uprava_role_result.inc.php", $content_params, true);
        }
        else if($action == "admin_users_edit_role")
        {
            if ($prihlaseny_uzivatel_udaje["login"] != "admin"){ //pokud není přihlášený admin, tak ho přesměruji
                $admin_url =  $this->makeUrlByRoute($this->route, array("action" => "normal_user_no_access"));
                header("Location: $admin_url");
                exit();
            }

            //pokud přihlášený admin, pak pokračuji...
            //zjistím, zda jsem se dostal přes hlavní tlačítko nebo u tlačítko u konkrétního uživatele
            $uzivatel_login = $this->loadRequestParam($request,"login_uzivatel", "all", -1);
            $content_params["uzivatel_jmeno_klik"] = $uzivatel_login;

            $content_params["form_submit_url"] = $this->makeUrlByRoute($this->route);
            $content_params["form_action_insert_role"] = "admin_users_add_role_review";
            //vytvořím nápovědu pro login
            $where_array = array();
            $limit_pom = "";
            $vsechny_mozne_loginy = $sprava_uzivatelu->DBSelectAll(TABLE_USERS_ADMIN, "login", $where_array, $limit_pom, "");
            //před předáním do template odstraním pole v poli (chci jen názvy loginů)
            $vsechny_mozne_loginy_edit = array();

            foreach ($vsechny_mozne_loginy as $login){
                array_push($vsechny_mozne_loginy_edit, $login["login"]);
            }

            $content_params["vsechny_loginy"] = $vsechny_mozne_loginy_edit;

            //vytvořím nápovědu pro role
            $where_array = array();
            $limit_pom = "";
            $vsechny_mozne_role = $sprava_uzivatelu->DBSelectAll(TABLE_UZIVATELSKE_ROLE, "nazev,id", $where_array, $limit_pom, "");
            //před předáním do template odstraním pole v poli (chci jen názvy rolí)
            $vsechny_zbyle_role = array();

            $uzivatel = $sprava_uzivatelu -> getUzivatelByLogin($uzivatel_login);
            $role_uzivatele = $sprava_uzivatelu -> getRoleUzivatelByIDUzivatel($uzivatel["id"]);
            $content_params["vsechny_role_uzivatele"] = $role_uzivatele;


            foreach ($vsechny_mozne_role as $role){
                $contains = false;
                foreach ($role_uzivatele as $uzivatel_role) {
                    if ($role["id"] == $uzivatel_role["id"]) {
                        $contains = true;
                    }
                }
                if (!$contains) {
                    array_push($vsechny_zbyle_role, $role);
                }
            }

            $content_params["vsechny_zbyle_role"] = $vsechny_zbyle_role;
            $content_params["url_uzivatele_list"] = $this->makeUrlByRoute($this->route, array("action" => "admin_users_list_all")); //odkaz na seznam všech uživatelů

            //udělám si seznam všech příjmení uživatelů (pro nápovědu při vyhledávání)
            $content = $this->renderPhp(DS1_DIR_ADMIN_MODULES_FROM_ADMIN . "sprava_uzivatelu/templates/sprava_uziv_add_edit.inc.php", $content_params, true);
        }
        else if($action == "admin_users_add_role_review")
        {
            if($prihlaseny_uzivatel_udaje["login"] != "admin"){ //pokud není přihlášený admin, tak ho přesměruji
                $admin_url =  $this->makeUrlByRoute($this->route, array("action" => "normal_user_no_access"));
                header("Location: $admin_url");
                exit();
            }

            // nacist zadana data
            $login_zadano = $this->loadRequestParam($request, "zadanyLogin", "post", null);
            $role_zadano = $this->loadRequestParam($request, "zadanaRole", "post", null);

            //zjistím si, jestli existuje daný uživatel a role
            $uzivatel = $sprava_uzivatelu -> getUzivatelByLogin($login_zadano);

            $vsechny_zadane_role = array();

            if ($role_zadano != null) {
                foreach ($role_zadano as $role) {

                    if ($role == NULL && $uzivatel == NULL) { //pokud neexistuje role ani uživatel
                        $error_url = $this->makeUrlByRoute($this->route, array("action" => "error_admin_prideleni"));
                        $_SESSION["error_text"] = "CHYBA! V DB není definována zvolená role, ani zvolený uživatel - příště vybírejte z nabízených možností.";
                        header("Location: $error_url");
                        exit();
                    } else if ($role == NULL) { //pokud neexistuje role
                        $error_url = $this->makeUrlByRoute($this->route, array("action" => "error_admin_prideleni"));
                        $_SESSION["error_text"] = "CHYBA! V DB není definována zvolená role - příště vybírejte z nabízených možností.";
//                header("Location: $error_url");
                        exit();
                    } else if ($uzivatel == NULL) { //pokud neexistuje uživatel
                        $_SESSION["error_text"] = "CHYBA! V DB není definován zvolený uživatel - příště vybírejte z nabízených možností.";
                        $error_url = $this->makeUrlByRoute($this->route, array("action" => "error_admin_prideleni"));
                        header("Location: $error_url");
                        exit();
                    }
                    array_push($vsechny_zadane_role, $role);
                }
            }

            //načtu si údaj z DB týkající se daného uživatele
            $where_array = array();
            $where_array[] = $sprava_uzivatelu->DBHelperGetWhereItem("login", $login_zadano);
            $limit_pom = "limit 1";
            $uzivatel_data = $sprava_uzivatelu->DBSelectOne(TABLE_USERS_ADMIN, "*", $where_array, $limit_pom);

            //přidám data, která chci zobrazit (kromě základních informací z tabulky uživatelů)

            $soucasne_role_pred_zmenou = $sprava_uzivatelu -> getRoleUzivatelByIDUzivatel($uzivatel_data["id"]);

            $soucasne_role_string = "";
            for($i = 0; $i < sizeof($soucasne_role_pred_zmenou); $i++){
                if($i != (sizeof($soucasne_role_pred_zmenou) - 1)){ //pokud nejsem u posledního prvku pole, pak přidám ","
                    $soucasne_role_string = $soucasne_role_string.$soucasne_role_pred_zmenou[$i]["nazev"].", ";
                }else{
                    $soucasne_role_string = $soucasne_role_string.$soucasne_role_pred_zmenou[$i]["nazev"];
                }
            }
            $uzivatel_data["predchozi_role"] = $soucasne_role_string;

            $nove_role_string = "";
            for($i = 0; $i < sizeof($vsechny_zadane_role); $i++){
                $nazev_role = $sprava_uzivatelu -> getRoleNazevByIdRole($vsechny_zadane_role[$i]);
                if($i != (sizeof($vsechny_zadane_role) - 1)){ //pokud nejsem u posledního prvku pole, pak přidám ","
                    $nove_role_string = $nove_role_string.$nazev_role.", ";
                }else{
                    $nove_role_string = $nove_role_string.$nazev_role;
                }
            }


            $uzivatel_data["nova_role"] = $nove_role_string;

            $uzivatel_data["nove_role_id"] = $vsechny_zadane_role;

            //printr($uzivatel_data);exit;
            //pokud neexsituje, pak error vypíšu

            $content_params["uzivatel_data"] = $uzivatel_data;
            $content_params["url_uzivatele_list"] = $this->makeUrlByRoute($this->route, array("action" => "admin_users_list_all"));
            $content_params["url_add_result"] = $this->makeUrlByRoute($this->route, array("action" => "admin_users_add_role_result"));
            $content = $this->renderPhp(DS1_DIR_ADMIN_MODULES_FROM_ADMIN . "sprava_uzivatelu/templates/sprava_uziv_add_edit_review.inc.php", $content_params, true);
        }
        else if($action == "admin_users_add_role_result")
        {
            if($prihlaseny_uzivatel_udaje["login"] != "admin"){ //pokud není přihlášený admin, tak ho přesměruji
                $admin_url =  $this->makeUrlByRoute($this->route, array("action" => "normal_user_no_access"));
                header("Location: $admin_url");
                exit();
            }

            //vytáhnu si data ze session (v předchozím templatu uložena) a uložím je do DB
            $uzivatel_id = $_SESSION["uzivatel_id"];
            $nove_role_id = $_SESSION["nove_role_id"];


            $sprava_uzivatelu -> saveAdminPridelRoleDB($uzivatel_id, $nove_role_id);
            $content_params["url_uzivatele_list"] = $this->makeUrlByRoute($this->route, array("action" => "admin_users_list_all"));
            $content_params["url_dalsi_zaznam"] = $this->makeUrlByRoute($this->route, array("action" => "admin_users_edit_role"));
            $content = $this->renderPhp(DS1_DIR_ADMIN_MODULES_FROM_ADMIN . "sprava_uzivatelu/templates/sprava_uziv_add_edit_result.inc.php", $content_params, true);
        }
        else if($action == "error_admin_prideleni")
        {
            if($prihlaseny_uzivatel_udaje["login"] != "admin"){ //pokud není přihlášený admin, tak ho přesměruji
                $admin_url =  $this->makeUrlByRoute($this->route, array("action" => "normal_user_no_access"));
                header("Location: $admin_url");
                exit();
            }

            $content_params["error_text"] = $_SESSION["error_text"];
            $content_params["url_dokumentace_list"] = $this->makeUrlByRoute($this->route, array("action" => "admin_users_list_all"));
            $content = $this->renderPhp(DS1_DIR_ADMIN_MODULES_FROM_ADMIN . "sprava_uzivatelu/templates/sprava_uziv_error_page.inc.php", $content_params, true);
        }
        else if($action == "normal_user_no_access"){
            $content = $this->renderPhp(DS1_DIR_ADMIN_MODULES_FROM_ADMIN . "sprava_uzivatelu/templates/sprava_uziv_no_access.inc.php", $content_params, true);
        }

        // vypsat hlavni template
        $main_params = array();
        $main_params["content"] = $content;
        $main_params["result_msg"] = $result_msg;
        $main_params["result_ok"] = $result_ok;

        return $this->renderAdminTemplate($main_params);
    }
}
