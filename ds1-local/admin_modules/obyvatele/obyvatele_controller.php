<?php
namespace ds1\admin_modules\obyvatele;

use ds1\admin_modules\pokoje\pokoje;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

use ds1\core\ds1_base_controller;

class obyvatele_controller extends ds1_base_controller
{
    // timto rikam, ze je NUTNE PRIHLASENI ADMINA
    protected $admin_secured_forced = true; // vynuceno pro jistotu, ale mel by stacit kontext admin

    public function indexAction(Request $request, $page = "")
    {
        // zavolat metodu rodice, ktera provede obecne hlavni kroky a nacte parametry
        parent::indexAction($request, $page);

        // KONTROLA ZABEZPECENI - pro jistotu
        // test, jestli je uzivatel prihlasen, pokud NE, tak redirect na LOGIN
        $this->checkAdminLogged();

        // objekt pro praci s obyvateli
        $obyvatele = new obyvatele();
        $obyvatele->SetPDOConnection($this->ds1->GetPDOConnection());

        // AKCE
        // action - typ akce
        $action = $this->loadRequestParam($request,"action", "all", "obyvatele_list_all");
        //echo "action: ".$action;

        // vyhledavaci string nemam
        $search_string = $this->loadRequestParam($request,"search_string", "all", "");

        // nacist obyvatele, pokud mam
        $obyvatel_id = $this->loadRequestParam($request,"obyvatel_id", "all", -1);
        if ($obyvatel_id > 0) {
            $obyvatel = $obyvatele->adminGetItemByID($obyvatel_id);
        }

        // univerzalni content params
        $content_params = array();
        $content_params["base_url"] = $this->webGetBaseUrl();
        $content_params["base_url_link"] = $this->webGetBaseUrlLink();
        $content_params["page_number"] = $this->page_number;
        $content_params["route"] = $this->route;        // mam tam orders, je to automaticky z routingu
        $content_params["route_params"] = array();
        $content_params["controller"] = $this;

        // JMENA EXTERNICH ROUT
        $content_params["pokoje_route_name"] = "pokoje";


        $content = "";

        // defaultni vysledek akce
        $result_msg = "";
        $result_ok = true;


        // AKCE - VYPISY

        // opravdu vytvorit obyvatele
        if ($action == "obyvatel_add_go") {
            // nacist data
            $obyvatel_new = $this->loadRequestParam($request, "obyvatel", "post", null);
            //printr($obyvatel_new);

            // FIXME mozna casem kontrola, jestli mam datum ve spravnem formatu - spoleham na prohlizec
            // datum_narozeni

            // kontrola, jestli obyvatel s temito daty - napr. jmenem, prijmenim a datem narozeni uz neexistuje
            $existuje = $obyvatele->adminExistsObyvatelByParams($obyvatel_new);

            // pokud neexistuje, tak pridat
            if ($existuje == false) {
                // mohu pridat
                $obyvatel_id = $obyvatele->adminInsertItem($obyvatel_new);

                // prepnout na editaci obyvatele
                $action = "obyvatel_update_prepare";
            }
            else {
                // FIXME pokud existuje, tak dotaz s odkazem na action2 = add_force
                // zatim chyba, ze uz existuje
                $result_ok = false;
                $result_msg = "Tento obyvatel již existuje. Nemohu ho přidat.";
                $action = "obyvatele_list_all";
            }
        }

        // formular pro vytvoreni noveho obyvatele
        if ($action == "obyvatel_add_prepare") {
            // parametry pro skript s obsahem - POZOR: nesmim je vynulovat, uz mam pripravenou cast
            $content_params["form_submit_url"] = $this->makeUrlByRoute($this->route);
            $content_params["form_action_insert_obyvatel"] = "obyvatel_add_go";
            $content_params["url_obyvatele_list"] = $this->makeUrlByRoute($this->route, array("action" => "obyvatele_list_all"));

            $content = $this->renderPhp(DS1_DIR_ADMIN_MODULES_FROM_ADMIN . "obyvatele/templates/admin_obyvatel_insert_form.inc.php", $content_params, true);
        }


        if ($action == "obyvatel_update_go") {
            $obyvatel_new = $this->loadRequestParam($request, "obyvatel", "post", null);

            if ($obyvatel_id > 0 && $obyvatel_new != null) {
                // mohu provest update
                //printr($obyvatel_new);

                // provest update
                $ok = $obyvatele->adminUpdateItem($obyvatel_id, $obyvatel_new);

                if ($ok) {
                    $result_ok = true;
                    $result_msg = "Změny obyvatele byly uloženy.";
                }
                else {
                    $result_ok = false;
                    $result_msg = "Změny obyvatele se nepovedlo uložit.";
                }
            }

            // presun do detailu
            $action = "obyvatel_detail_show";
        }

        if ($action == "obyvatel_update_prepare") {

            if (!isset($obyvatel_id)) {
                // pokud nemam obyvatele, tak nactu z URL. Jinak uz ho MAM treba z INSERTu
                $obyvatel_id = $this->loadRequestParam($request,"obyvatel_id", "all", -1);
            }

            if ($obyvatel_id > 0 && $obyvatel != null) {
                // vypis
                // parametry pro skript s obsahem - POZOR: nesmim je vynulovat, uz mam pripravenou cast
                $content_params["obyvatel_id"] = $obyvatel_id;
                $content_params["obyvatel"] = $obyvatel;
                $content_params["form_submit_url"] = $this->makeUrlByRoute($this->route);
                $content_params["form_action_update_obyvatel"] = "obyvatel_update_go";
                $content_params["url_obyvatele_list"] = $this->makeUrlByRoute($this->route, array("action" => "obyvatele_list_all"));

                $content = $this->renderPhp(DS1_DIR_ADMIN_MODULES_FROM_ADMIN . "obyvatele/templates/admin_obyvatel_update_form.inc.php", $content_params, true);
            }
            else {
                $result_msg = "Obyvatel nenalezen - ID nebylo získáno z URL nebo obyvatel neexistuje.";
                $result_ok = false;

                $action = "obyvatele_list_all";
            }
        }


        // pridani obyvatele na pokoj - GO
        if ($action == "obyvatel_na_pokoje_add_go") {

            // data noveho zaznamu
            $obyvatel_na_pokojich = $this->loadRequestParam($request,"obyvatel", "all", null);
            //printr($obyvatel_na_pokojich);

            if ($obyvatel_na_pokojich != null) {
                // FIXME mozna nejake kontroly

                // provest insert
                $pom_id = $obyvatele->adminInsertUbytovaniObyvatele($obyvatel_na_pokojich);
                if ($pom_id > 0) {
                    $result_ok = true;
                    $result_msg = "Změny obyvatele byly uloženy.";
                }
                else {
                    $result_ok = false;
                    $result_msg = "Změny obyvatele se nepovedlo uložit.";
                }
            }

            // zobrazit aktualni stav
            $action = "obyvatel_na_pokoje_add_prepare";
        }

        if ($action == "obyvatel_na_pokoje_delete_go") {
            $obyvatel_na_pokoji_id = $this->loadRequestParam($request,"obyvatel_na_pokoji_id", "all", -1);

            // FIXME, je treba pridat historii s moznosti vratit se zpet

            if ($obyvatel_id > 0 && $obyvatel_na_pokoji_id > 0) {
                // nacist a ulozit

                // smazat a vypsat hlasku FIXME - do hlasky vlozit primo insert pro vlozeni zpet
                $ok = $obyvatele->adminDeleteUbytovaniObyvatele($obyvatel_na_pokoji_id, $obyvatel_id);

                if ($ok) {
                    $result_ok = true;
                    $result_msg = "Záznam o ubytování byl smazán.";
                }
                else {
                    $result_ok = false;
                    $result_msg = "Záznam o ubytování se nepodařilo smazat.";
                }
            }

            // zobrazit detail obyvatele na pokoji
            $action = "obyvatel_na_pokoje_add_prepare";
        }


        // Pridani obyvatele na POKOJ - PREPARE
        if ($action == "obyvatel_na_pokoje_add_prepare") {
            if (!isset($obyvatel_id)) {
                // pokud nemam obyvatele, tak nactu z URL. Jinak uz ho MAM treba z INSERTu
                $obyvatel_id = $this->loadRequestParam($request,"obyvatel_id", "all", -1);
            }

            // form pro pridani ubytovani
            if ($obyvatel_id > 0 && $obyvatel != null) {
                $content_params["obyvatel_id"] = $obyvatel_id;
                $content_params["obyvatel"] = $obyvatel;
                $content_params["form_submit_url"] = $this->makeUrlByRoute($this->route);
                $content_params["form_action"] = "obyvatel_na_pokoje_add_go";
                $content_params["url_obyvatele_list"] = $this->makeUrlByRoute($this->route, array("action" => "obyvatele_list_all"));
                $content_params["url_obyvatel_detail"] = $this->makeUrlByRoute($this->route, array("action" => "obyvatel_detail_show", "obyvatel_id" => $obyvatel_id));

                // seznam vsech pokoju
                $pokoje = new pokoje($this->ds1->GetPDOConnection());
                $content_params["pokoje_list"] = $pokoje->adminLoadItems("data", 1, -1);

                // info o aktualnim stavu:
                $content_params["obyvatel_na_pokojich"] = $obyvatele->adminLoadAllUbytovaniObyvatelu($obyvatel_id);

                $content = $this->renderPhp(DS1_DIR_ADMIN_MODULES_FROM_ADMIN . "obyvatele/templates/admin_obyvatel_na_pokojich_insert_form.inc.php", $content_params, true);

            }
            else {
                $result_msg = "Obyvatel nenalezen - ID nebylo získáno z URL nebo obyvatel neexistuje.";
                $result_ok = false;

                $action = "obyvatele_list_all";
            }
        }

        // DETAIL OBYVATELE
        if ($action == "obyvatel_detail_show") {
            if (!isset($obyvatel_id)) {
                // pokud nemam obyvatele, tak nactu z URL. Jinak uz ho MAM treba z INSERTu
                $obyvatel_id = $this->loadRequestParam($request,"obyvatel_id", "all", -1);
            }

            // nacist obyvatele
            $obyvatel = $obyvatele->adminGetItemByID($obyvatel_id);

            if ($obyvatel_id > 0 && $obyvatel != null) {
                // vypis
                // parametry pro skript s obsahem - POZOR: nesmim je vynulovat, uz mam pripravenou cast
                $content_params["obyvatel_id"] = $obyvatel_id;
                $content_params["obyvatel"] = $obyvatel;

                // ubytovani obyvatele
                $content_params["obyvatel_na_pokojich"] = $obyvatele->adminLoadAllUbytovaniObyvatelu($obyvatel_id);
                $content_params["url_obyvatele_na_pokoje_add_prepare"] = $this->makeUrlByRoute($this->route, array("action" => "obyvatel_na_pokoje_add_prepare", "obyvatel_id" => $obyvatel_id));

                //$content_params["form_submit_url"] = $this->makeUrlByRoute($this->route);
                //$content_params["form_action_update_obyvatel"] = "obyvatel_update_go";
                $content_params["url_obyvatele_list"] = $this->makeUrlByRoute($this->route, array("action" => "obyvatele_list_all"));
                $content_params["url_obyvatel_update"]  = $this->makeUrlByRoute($this->route, array("action" => "obyvatel_update_prepare", "obyvatel_id" => $obyvatel_id));

                // konfigurace pro sablonu
                $template_config = $this->getConfigForPhpTemplate(DS1_DIR_ADMIN_MODULES_FROM_ADMIN . "obyvatele/templates/admin_obyvatel_detail_config.json", array());
                $content_params["template_config"] = $template_config;

                $content = $this->renderPhp(DS1_DIR_ADMIN_MODULES_FROM_ADMIN . "obyvatele/templates/admin_obyvatel_detail.inc.php", $content_params, true);
            }
            else {
                $result_msg = "Obyvatel nenalezen - ID nebylo získáno z URL nebo obyvatel neexistuje.";
                $result_ok = false;

                $action = "obyvatele_list_all";
            }
        }

        // jen vyber dat
        if ($action == "obyvatele_list_search")
        {
            $count_on_page = 50;
            $where_array = array();
            // count_on_page a page se u prikazu count neuvazuje
            $total = $obyvatele->adminSearchItems($search_string, "count", 1, 1);
            //echo "total: $total"; exit;

            $obyvatele_list = $obyvatele->adminSearchItems($search_string, "data", $this->page_number, $count_on_page);

        }
        else if ($action == "obyvatele_list_all")
        {
            $count_on_page = 50;
            $where_array = array();
            // count_on_page a page se u prikazu count neuvazuje
            $total = $obyvatele->adminLoadItems("count", 1, 1, $where_array);
            //echo "total: $total"; exit;

            $obyvatele_list = $obyvatele->adminLoadItems("data", $this->page_number, $count_on_page, $where_array, "prijmeni", "asc");
        }

        // vlastni controller pro list nebo search
        if ($action == "obyvatele_list_search" || $action == "obyvatele_list_all") {
            // vypsat vsechny obyvatele

            // vygenerovat strankovani - obecna metoda,
            $pagination_params["page_number"] = $this->page_number;
            $pagination_params["count"] = $count_on_page;
            $pagination_params["total"] = $total;
            $pagination_params["route"] = $this->route;
            $pagination_params["route_params"] = array("action" => $action);
            $pagination_html = $this->renderPhp("admin/partials/admin_pagination.inc.php", $pagination_params, true);
            // echo $pagination_html; exit;

            // parametry pro skript s obsahem, uz mam neco pripraveno, NESMIM NULOVAT
            $content_params["obyvatele_list_name"] = "všichni"; // dle filtru
            $content_params["obyvatel_detail_action"] = "obyvatel_detail_show";
            $content_params["obyvatel_update_prepare_action"] = "obyvatel_update_prepare";
            $content_params["obyvatele_count"] = $count_on_page;
            $content_params["obyvatele_total"] = $total;
            //$content_params["search_params"] = $search_params;
            $content_params["obyvatele_list"] = $obyvatele_list;
            $content_params["pagination_html"] = $pagination_html;

            // url pro vytvoreni obyvatele
            $content_params["url_obyvatel_add_prepare"] = $this->makeUrlByRoute($this->route, array("action" => "obyvatel_add_prepare"));

            // search
            $content_params["url_obyvatele_autocomplete"] = $this->makeUrlByRoute(DS1_ROUTE_ADMIN_OBYVATELE_API);
            $content_params["form_search_submit_url"] = "";
            $content_params["form_search_action"] = "obyvatele_list_search";
            $content_params["search_string"] = $search_string;


            // vysledek nejake akce
            $content_params["result_msg"] = $result_msg;
            $content_params["result_ok"] = $result_ok;

            $content = $this->renderPhp(DS1_DIR_ADMIN_MODULES_FROM_ADMIN . "obyvatele/templates/admin_obyvatele_list.inc.php", $content_params, true);
        }

        // vypsat hlavni template
        $main_params = array();
        $main_params["content"] = $content;
        $main_params["result_msg"] = $result_msg;
        $main_params["result_ok"] = $result_ok;

        return $this->renderAdminTemplate($main_params);
        //return new Response("Controller pro obyvatele.");
    }


    /**
     * Metoda pro volani api. Tohle se pouziva pro vyhledavani obyvatel pro autocomplete.
     * @param Request $request
     * @return JsonResponse|Response
     */
    public function apiAction(Request $request)
    {
        // zavolat metodu rodice, ktera provede obecne hlavni kroky a nacte parametry
        parent::indexAction($request, "");

        // KONTROLA ZABEZPECENI - pro jistotu
        // test, jestli je uzivatel prihlasen, pokud NE, tak redirect na LOGIN
        $this->checkAdminLogged();

        // objekt pro praci s obyvateli
        $obyvatele = new obyvatele();
        $obyvatele->SetPDOConnection($this->ds1->GetPDOConnection());

        // DATA - v postu dostanu field a search

        // POZOR: data jsou ve formatu application/json a neni mozne je prijmout $_POST, musi se to takto:
        $json_data = file_get_contents("php://input");
        $post_data = (array) json_decode($json_data);   // zaroven pretypovat na pole

        // pro kontrolni vypis zpet do Angularu - zobrazit se to v konzoli:
        // print_r($post_data);

        // nacist vstupni data: field = napr. klicove_slovo, search: vstup od uživatele, např. lyž
        $field = @$post_data["field"];
        $search_string = @$post_data["search"];
        $base_url = @$post_data["base_url"];
        //echo "field: $field, search: $search_string <br/>";

        if ($field == "obyvatele") {
            // hledam obyvatele dle retezce $search

            $count_on_page = 10;    // limit na pocet vysledku pro autocomplete
            $where_array = array();
            // count_on_page a page se u prikazu count neuvazuje
            $total = $obyvatele->adminSearchItems($search_string, "count", 1, 1);
            //echo "total: $total"; exit;

            $obyvatele_list = $obyvatele->adminSearchItems($search_string, "data", 1, $count_on_page);

            // slozit data for response - vysledkem musi byt objekt a nikoliv pole kuli
            /* To avoid XSSI JSON Hijacking, you should pass an associative array as the outer-most array to JsonResponse and not an
            indexed array so that the final result is an object (e.g. {"object": "not inside an array"})
            instead of an array (e.g. [{"object": "inside an array"}]).*/
            $data_for_response = array();
            $data_for_response["msg"] = "ok - field: $field, search: $search_string";

            if ($obyvatele_list)
                foreach ($obyvatele_list as $ob) {
                        // slozit desc = co se zobrazi
                        $desc_pom = "$ob[prijmeni] $ob[jmeno]";

                        if (trim($ob["vek"]) != "") {
                            // prihodit vek
                            $desc_pom .= " ($ob[vek] let)";
                        }

                        // pridat k obyvateli
                        $ob["id_klicove_slovo"] = $ob["id"];
                        $ob["klicove_slovo"] = $search_string;
                        $ob["autocomplete_desc"] = $desc_pom;

                        // url, kam se mam dostat na kliknuti - musim na routu obyvatele a nikoliv obyvatele-api
                        $ob["url"] = $this->makeUrlByRoute(DS1_ROUTE_ADMIN_OBYVATELE, array("action" => "obyvatel_detail_show", "obyvatel_id" => $ob["id"]));

                        // pro testovani
                        //$ob["url"] = $base_url."index.php/plugin/obyvatele?action=obyvatel_detail_show&obyvatel_id=".$ob["id"];

                        // vlozit do vysledku
                        $data_for_response["autocomplete_results"][] = $ob;
                }

            // vratit json response
            return new JsonResponse($data_for_response);
        }

        return new Response("Controller pro obyvatele API.");
    }
}