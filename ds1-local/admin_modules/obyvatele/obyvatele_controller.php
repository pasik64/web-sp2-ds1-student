<?php
namespace ds1\admin_modules\obyvatele;

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

        // univerzalni content params
        $content_params = array();
        $content_params["base_url_link"] = $this->webGetBaseUrlLink();
        $content_params["page_number"] = $this->page_number;
        $content_params["route"] = $this->route;        // mam tam orders, je to automaticky z routingu
        $content_params["route_params"] = array();
        $content_params["controller"] = $this;

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
            $obyvatel_id = $this->loadRequestParam($request,"obyvatel_id", "all", -1);
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

            // nacist obyvatele
            $obyvatel = $obyvatele->adminGetItemByID($obyvatel_id);

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

                //$content_params["form_submit_url"] = $this->makeUrlByRoute($this->route);
                //$content_params["form_action_update_obyvatel"] = "obyvatel_update_go";
                $content_params["url_obyvatele_list"] = $this->makeUrlByRoute($this->route, array("action" => "obyvatele_list_all"));
                $content_params["url_obyvatel_update"]  = $this->makeUrlByRoute($this->route, array("action" => "obyvatel_update_prepare", "obyvatel_id" => $obyvatel_id));

                // FIXME - casem nejak predavat jmena externich rout
                $content_params["pokoje_route_name"] = "pokoje";

                $content = $this->renderPhp(DS1_DIR_ADMIN_MODULES_FROM_ADMIN . "obyvatele/templates/admin_obyvatel_detail.inc.php", $content_params, true);
            }
            else {
                $result_msg = "Obyvatel nenalezen - ID nebylo získáno z URL nebo obyvatel neexistuje.";
                $result_ok = false;

                $action = "obyvatele_list_all";
            }
        }

        if ($action == "obyvatele_list_all") {
            // vypsat vsechny obyvatele

            $count = 50;
            $where_array = array();
            // count_on_page a page se u prikazu count neuvazuje
            $total = $obyvatele->adminLoadItems("count", 1, 1, $where_array);
            //echo "total: $total"; exit;

            // vygenerovat strankovani - obecna metoda,
            $pagination_params["page_number"] = $this->page_number;
            $pagination_params["count"] = $count;
            $pagination_params["total"] = $total;
            $pagination_params["route"] = $this->route;
            $pagination_params["route_params"] = array();
            $pagination_html = $this->renderPhp("admin/partials/admin_pagination.inc.php", $pagination_params, true);
            // echo $pagination_html; exit;

            // parametry pro skript s obsahem, uz mam neco pripraveno, NESMIM NULOVAT
            $content_params["obyvatele_list_name"] = "všichni"; // dle filtru
            $content_params["obyvatel_detail_action"] = "obyvatel_detail_show";
            $content_params["obyvatel_update_prepare_action"] = "obyvatel_update_prepare";
            $content_params["obyvatele_count"] = $count;
            $content_params["obyvatele_total"] = $total;
            //$content_params["search_params"] = $search_params;
            $content_params["obyvatele_list"] = $obyvatele->adminLoadItems("data", $this->page_number, $count, $where_array, "prijmeni", "asc");
            $content_params["pagination_html"] = $pagination_html;

            // url pro vytvoreni obyvatele
            $content_params["url_obyvatel_add_prepare"] = $this->makeUrlByRoute($this->route, array("action" => "obyvatel_add_prepare"));

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
}