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
            $content_params["url_pridat_role"] = $this->makeUrlByRoute($this->route, array("action" => "admin_users_edit_role")); //odkaz na přidání role
            $uzivatele = $sprava_uzivatelu -> getVsechnaPrijmeniUzivatele(); //všechna příjmení uživatelů
            $content_params["napoveda_prijmeni_uzivatelu"] = $uzivatele;
            $content = $this->renderPhp(DS1_DIR_ADMIN_MODULES_FROM_ADMIN . "sprava_uzivatelu/templates/sprava_uziv_admin_list.inc.php", $content_params, true);
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
            var_dump($_POST);

            //zjistím si, jestli existuje daný uživatel a role
            $uzivatel = $sprava_uzivatelu -> getUzivatelByLogin($login_zadano);

            $vsechny_zadane_role = array();

            foreach ($role_zadano as $role){

                if($role == NULL && $uzivatel == NULL){ //pokud neexistuje role ani uživatel
                    $error_url =  $this->makeUrlByRoute($this->route, array("action" => "error_admin_prideleni"));
                    $_SESSION["error_text"] = "CHYBA! V DB není definována zvolená role, ani zvolený uživatel - příště vybírejte z nabízených možností.";
                    header("Location: $error_url");
                    exit();
                }else if($role == NULL){ //pokud neexistuje role
                    $error_url =  $this->makeUrlByRoute($this->route, array("action" => "error_admin_prideleni"));
                    $_SESSION["error_text"] = "CHYBA! V DB není definována zvolená role - příště vybírejte z nabízených možností.";
//                header("Location: $error_url");
                    exit();
                }else if($uzivatel == NULL){ //pokud neexistuje uživatel
                    $_SESSION["error_text"] = "CHYBA! V DB není definován zvolený uživatel - příště vybírejte z nabízených možností.";
                    $error_url =  $this->makeUrlByRoute($this->route, array("action" => "error_admin_prideleni"));
                    header("Location: $error_url");
                    exit();
                }
                array_push($vsechny_zadane_role, $role);
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
