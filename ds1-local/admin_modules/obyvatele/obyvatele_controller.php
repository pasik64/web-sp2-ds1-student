<?php
namespace ds1\admin_modules\obyvatele;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

use ds1\core\ds1_base_controller;

// TODO konstaty presunout do konfigurace
define("TABLE_OBYVATELE", "ds1_obyvatele");


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

        $content = "";

        // AKCE - VYPISY
        if ($action == "obyvatel_detail_show") {
            /*
            $user_id = $this->loadRequestParam($request,"user_id", "all", -1);
            $user = $this->ds1->user_manager->getUserById($user_id);

            if ($user_id > 0 && $user != null) {
                // vypis
                $content_params["user"] = $user;
                $content = $this->renderPhp("admin/user_manager/admin_user_detail_show.inc.php", $content_params, true);
            }
            else {
                $result_msg = "Uživatel nenalezen - ID nebylo získáno z URL.";
                $action = "obyvatele_list_all";
            }
            */
        }

        if ($action == "obyvatel_update_go") {
            /*
            $user_id = $this->loadRequestParam($request,"user_id", "all", -1);
            $user = $this->ds1->user_manager->getUserById($user_id);

            if ($user_id > 0 && $user != null) {
                // vypis
                $content_params["user"] = $user;
                $content = $this->renderPhp("admin/user_manager/admin_user_detail_show.inc.php", $content_params, true);
            }
            else {
                $result_msg = "Uživatel nenalezen - ID nebylo získáno z URL.";
                $action = "users_list_all";
            }
            */
        }

        if ($action == "obyvatel_update_prepare") {
            /*
            $user_id = $this->loadRequestParam($request,"user_id", "all", -1);
            $user = $this->ds1->user_manager->getUserById($user_id);

            if ($user_id > 0 && $user != null) {
                // vypis
                $content_params["user"] = $user;
                $content = $this->renderPhp("admin/user_manager/admin_user_detail_show.inc.php", $content_params, true);
            }
            else {
                $result_msg = "Uživatel nenalezen - ID nebylo získáno z URL.";
                $action = "users_list_all";
            }
            */
        }

        if ($action == "obyvatele_list_all") {
            // vypsat vsechny obyvatele

            $count = 50;
            $where_array = array();
            // count_on_page a page se u prikazu count neuvazuje
            $total = $obyvatele->adminLoadItems("count", 1, 1, $where_array);
            //echo "total: $total"; exit;

            // vygenerovat strankovani - obecna metoda
            $pagination_params["page_number"] = $this->page_number;
            $pagination_params["count"] = $count;
            $pagination_params["total"] = $total;
            $pagination_params["route"] = $this->route;
            $pagination_params["route_params"] = array();
            $pagination_html = $this->renderPhp("admin/partials/admin_pagination.inc.php", $pagination_params, true);
            // echo $pagination_html; exit;

            // parametry pro skript s obsahem
            $content_params["users_list_name"] = "všichni"; // dle filtru
            $content_params["user_detail_action"] = "obyvatel_detail_show";
            $content_params["obyvatele_count"] = $count;
            $content_params["obyvatele_total"] = $total;
            //$content_params["search_params"] = $search_params;
            $content_params["users_list"] = $obyvatele->adminLoadItems("data", $this->page_number, $count, $where_array, "prijmeni", "asc");
            $content_params["pagination_html"] = $pagination_html;

            $content = $this->renderPhp("../../ds1-local/admin_modules/obyvatele/templates/admin_obyvatele_list.inc.php", $content_params, true);
        }

        // vypsat hlavni template
        $main_params = array();
        $main_params["content"] = $content;

        return $this->renderAdminTemplate($main_params);
        //return new Response("Controller pro obyvatele.");
    }
}