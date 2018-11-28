<?php

namespace ds1\admin_modules\pokoje;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

use ds1\core\ds1_base_controller;

class pokoje_controller extends ds1_base_controller
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
        $pokoje = new pokoje();
        $pokoje->SetPDOConnection($this->ds1->GetPDOConnection());

        // AKCE
        // action - typ akce
        $action = $this->loadRequestParam($request,"action", "all", "pokoje_list_all");
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

        // opravdu vytvorit pokoj
        if ($action == "pokoj_add_go") {
            // nacist data
            $pokoj_new = $this->loadRequestParam($request, "pokoj", "post", null);
            //printr($pokoj_new);

            // kontrola, jestli pokoj s temito daty uz neexistuje
            $existuje = $pokoje->adminExistsPokojByParams($pokoj_new);

            // pokud neexistuje, tak pridat
            if ($existuje == false) {
                // mohu pridat
                $pokoj_id = $pokoje->adminInsertItem($pokoj_new);

                // prepnout na editaci pokoje
                $action = "pokoj_update_prepare";
            }
            else {

                // zatim chyba, ze uz existuje
                $result_ok = false;
                $result_msg = "Tento pokoj již existuje. Nemohu ho přidat.";
                $action = "pokoje_list_all";
            }
        }

        // formular pro vytvoreni noveho pokoje
        if ($action == "pokoj_add_prepare") {
            // parametry pro skript s obsahem - POZOR: nesmim je vynulovat, uz mam pripravenou cast
            $content_params["form_submit_url"] = $this->makeUrlByRoute($this->route);
            $content_params["form_action_insert"] = "pokoj_add_go";
            $content_params["skupiny_pokoju"] = $pokoje->loadAllSkupinyPokoju(true);
            $content_params["url_pokoje_list"] = $this->makeUrlByRoute($this->route, array("action" => "pokoje_list_all"));

            $content = $this->renderPhp(DS1_DIR_ADMIN_MODULES_FROM_ADMIN . "pokoje/templates/admin_pokoj_insert_form.inc.php", $content_params, true);
        }


        if ($action == "pokoj_update_go") {
            $pokoj_id = $this->loadRequestParam($request,"pokoj_id", "all", -1);
            $pokoj_new = $this->loadRequestParam($request, "pokoj", "post", null);

            if ($pokoj_id > 0 && $pokoj_new != null) {
                // mohu provest update
                //printr($obyvatel_new);

                // provest update
                $ok = $pokoje->adminUpdateItem($pokoj_id, $pokoj_new);

                if ($ok) {
                    $result_ok = true;
                    $result_msg = "Změny pokoje byly uloženy.";
                }
                else {
                    $result_ok = false;
                    $result_msg = "Změny pokoje se nepovedlo uložit.";
                }
            }

            // presun do detailu
            $action = "pokoj_detail_show";
        }

        if ($action == "pokoj_update_prepare") {
            if (!isset($pokoj_id)) {
                // pokud nemam pokoj, tak nactu z URL. Jinak uz ho MAM treba z INSERTu
                $pokoj_id = $this->loadRequestParam($request,"pokoj_id", "all", -1);
            }

            // nacist pokoj
            $pokoj_detail = $pokoje->adminGetItemByID($pokoj_id);

            if ($pokoj_id > 0 && $pokoj_detail != null) {
                // vypis
                // parametry pro skript s obsahem - POZOR: nesmim je vynulovat, uz mam pripravenou cast
                $content_params["pokoj_id"] = $pokoj_id;
                $content_params["pokoj"] = $pokoj_detail;
                $content_params["form_submit_url"] = $this->makeUrlByRoute($this->route);
                $content_params["form_action_update"] = "pokoj_update_go";
                $content_params["url_pokoje_list"] = $this->makeUrlByRoute($this->route, array("action" => "pokoje_list_all"));

                $content = $this->renderPhp(DS1_DIR_ADMIN_MODULES_FROM_ADMIN . "pokoje/templates/admin_pokoj_update_form.inc.php", $content_params, true);
            }
            else {
                $result_msg = "Pokoj nenalezen - ID nebylo získáno z URL nebo pokoj neexistuje.";
                $result_ok = false;

                $action = "pokoje_list_all";
            }
        }


        // DETAIL pokoje
        if ($action == "pokoj_detail_show") {
            if (!isset($pokoj_id)) {
                // pokud nemam pokoj, tak nactu z URL. Jinak uz ho MAM treba z INSERTu
                $pokoj_id = $this->loadRequestParam($request,"pokoj_id", "all", -1);
            }

            // nacist pokoj
            $pokoj = $pokoje->adminGetItemByID($pokoj_id);

            if ($pokoj_id > 0 && $pokoj != null) {
                // vypis
                // parametry pro skript s obsahem - POZOR: nesmim je vynulovat, uz mam pripravenou cast
                $content_params["pokoj_id"] = $pokoj_id;
                $content_params["pokoj"] = $pokoj;
                //$content_params["form_submit_url"] = $this->makeUrlByRoute($this->route);
                //$content_params["form_action_update_obyvatel"] = "obyvatel_update_go";
                $content_params["url_pokoje_list"] = $this->makeUrlByRoute($this->route, array("action" => "pokoje_list_all"));
                $content_params["url_pokoj_update"]  = $this->makeUrlByRoute($this->route, array("action" => "pokoj_update_prepare", "pokoj_id" => $pokoj_id));

                $content = $this->renderPhp(DS1_DIR_ADMIN_MODULES_FROM_ADMIN . "pokoje/templates/admin_pokoj_detail.inc.php", $content_params, true);
            }
            else {
                $result_msg = "Pokoj nenalezen - ID nebylo získáno z URL nebo pokoj neexistuje.";
                $result_ok = false;

                $action = "pokoje_list_all";
            }
        }

        if ($action == "pokoje_list_all") {
            // vypsat vsechny pokoje

            $count = 50;
            $where_array = array();
            // count_on_page a page se u prikazu count neuvazuje
            $total = $pokoje->adminLoadItems("count", 1, 1, $where_array);
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
            $content_params["pokoje_list_name"] = "všechny"; // dle filtru
            $content_params["pokoj_detail_action"] = "pokoj_detail_show";
            $content_params["pokoj_update_prepare_action"] = "pokoj_update_prepare";
            $content_params["pokoje_count"] = $count;
            $content_params["pokoje_total"] = $total;
            //$content_params["search_params"] = $search_params;
            $content_params["pokoje_list"] = $pokoje->adminLoadItems("data", $this->page_number, $count, $where_array, "nazev", "asc");
            $content_params["pagination_html"] = $pagination_html;

            // url pro vytvoreni pokoje
            $content_params["url_pokoj_add_prepare"] = $this->makeUrlByRoute($this->route, array("action" => "pokoj_add_prepare"));

            // vysledek nejake akce
            $content_params["result_msg"] = $result_msg;
            $content_params["result_ok"] = $result_ok;

            $content = $this->renderPhp(DS1_DIR_ADMIN_MODULES_FROM_ADMIN . "pokoje/templates/admin_pokoje_list.inc.php", $content_params, true);
        }

        // vypsat hlavni template
        $main_params = array();
        $main_params["content"] = $content;
        $main_params["result_msg"] = $result_msg;
        $main_params["result_ok"] = $result_ok;

        return $this->renderAdminTemplate($main_params);
        //return new Response("Controller pro pokoje.");
    }
}