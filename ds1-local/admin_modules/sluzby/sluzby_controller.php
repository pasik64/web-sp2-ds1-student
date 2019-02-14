<?php

namespace ds1\admin_modules\sluzby;

use ds1\admin_modules\obyvatele\obyvatele;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

use ds1\core\ds1_base_controller;

class sluzby_controller extends ds1_base_controller
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
        $sluzby = new sluzby();
        $sluzby->SetPDOConnection($this->ds1->GetPDOConnection());

        // objekt obyvatelu
        //$obyvatele = new obyvatele($this->ds1->GetPDOConnection());

        // AKCE
        // action - typ akce
        $action = $this->loadRequestParam($request,"action", "all", "sluzby_list_all");
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




        //
        // ====================== PLÁN VÝKONU START ======================

        if ($action == "plan_vykonu_add_prepare"){
            if (!isset($sluzba_id)) {
                $sluzba_id = $this->loadRequestParam($request,"sluzba_id", "all", -1);
            }
            //var_dump($sluzba_id);
            $content_params["form_submit_url"] = $this->makeUrlByRoute($this->route, array("action" => "plan_vykonu_add_go", "sluzba_id" => $sluzba_id));//$this->makeUrlByRoute($this->route);
            $content_params["form_action_insert_plan_vykonu"] = $this->makeUrlByRoute($this->route, array("action" => "plan_vykonu_add_go", "sluzba_id" => $sluzba_id));//"sluzba_add_go";
            $content_params["url_sluzby_list"] = $this->makeUrlByRoute($this->route, array("action" => "sluzby_list_all"));

            $content = $this->renderPhp(DS1_DIR_ADMIN_MODULES_FROM_ADMIN . "sluzby/templates/admin_plan_vykonu_insert_form.inc.php", $content_params, true);
        }


        if ($action == "plan_vykonu_add_go"){
            if (!isset($sluzba_id)) {
                $sluzba_id = $this->loadRequestParam($request,"sluzba_id", "all", -1);
            }

            $vysledek = -1;
            $radio = $this->loadRequestParam($request, "radio", "post", null);
            $selTyden = $this->loadRequestParam($request, "selTyden", "post", null);
            $selMesic = $this->loadRequestParam($request, "selMesic", "post", null);
            $selSpecial = $this->loadRequestParam($request, "selSpecial", "post", null);

            $muj_ok = true;

            if ($radio == "1"){
                if($selTyden == -1){
                    $result_msg = "Plán výkonu se nepovedlo vytvořit, pravděpodobně nebyl zvolen žádný den v týdnu.";
                    $result_ok = false;
                    $muj_ok = false;
                }
                $vysledek = $radio.$selTyden;
            }
            else if ($radio == "2"){
                if($selMesic == -1){
                    $result_msg = "Plán výkonu se nepovedlo vytvořit, pravděpodobně nebyl zvolen žádný den v měsíci.";
                    $result_ok = false;
                    $muj_ok = false;
                }
                $vysledek = $radio.$selMesic;
            }
            else if ($radio == "3"){
                if($selSpecial == -1){
                    $result_msg = "Plán výkonu se nepovedlo vytvořit.";
                    $result_ok = false;
                    $muj_ok = false;
                }
                $vysledek = $radio.$selSpecial;
            }

            if($muj_ok){
                $plan_vykonu_new = $this->loadRequestParam($request, "plan_vykonu", "post", null);
                $plan_vykonu_new["sluzba_id"] = $sluzba_id;
                $plan_vykonu_new["typ_planu"] = $vysledek;

                $plan_id = $sluzby->adminInsertPlanVykonuItem($plan_vykonu_new);
                $action = "plan_vykonu_update_prepare";
            }
            else {
                $action = "sluzby_list_all";
            }


//            var_dump($plan_vykonu_new);
//            var_dump($plan_vykonu_new);
//            echo "button: ".$but;
//            echo " den v tydnu: ".$selTyden;
//            echo " den v mesici: ".$selMesic;
//            echo " den special: ".$selSpecial;
//            echo " vysledek: ".$vysledek;

        }


        if($action == "plan_vykonu_update_prepare"){
            if (!isset($plan_id)) {
                $plan_id = $this->loadRequestParam($request,"plan_update", "all", -1);
            }
            if (!isset($sluzba_id)) {
                $sluzba_id = $this->loadRequestParam($request,"sluzba_id", "all", -1);
            }

            $plan_detail = $sluzby->adminGetPlanVykonuItemByID($plan_id);

            if ($plan_id > 0 && $plan_detail != null) {

                $content_params["plan_id"] = $plan_id;
                $content_params["plan_vykonu"] = $plan_detail;
                $content_params["form_submit_url"] = $this->makeUrlByRoute($this->route, array("action" => "plan_vykonu_update_go", "plan_id" => $plan_id, "sluzba_id" => $sluzba_id));//$this->makeUrlByRoute($this->route);
                $content_params["form_action_update_plan_vykonu"] = $this->makeUrlByRoute($this->route, array("action" => "plan_vykonu_update_go", "plan_id" => $plan_id, "sluzba_id" => $sluzba_id));
                $content_params["url_sluzby_list"] = $this->makeUrlByRoute($this->route, array("action" => "sluzby_list_all"));
                $content_params["url_sluzba_detail"] = $this->makeUrlByRoute($this->route, array("action" => "sluzba_detail_show", "sluzba_id" => $sluzba_id));

                $content = $this->renderPhp(DS1_DIR_ADMIN_MODULES_FROM_ADMIN . "sluzby/templates/admin_plan_vykonu_update_form.inc.php", $content_params, true);
            }
            else {
                $result_msg = "Nic nenalezeno pri planu vykonu update.";
                $result_ok = false;

                $action = "sluzby_list_all";
            }
        }

        if($action == "plan_vykonu_update_go"){
            if (!isset($plan_id)) {
                $plan_id = $this->loadRequestParam($request,"plan_id", "all", -1);
            }
            if (!isset($sluzba_id)) {
                $sluzba_id = $this->loadRequestParam($request,"sluzba_id", "all", -1);
            }

            $plan_new = $this->loadRequestParam($request, "plan_vykonu", "post", null);

            if ($plan_id > 0 && $plan_new != null) {

                // provest update
                $ok = $sluzby->adminUpdatePlanVykonuItem($plan_id, $plan_new);

                if ($ok) {
                    $result_ok = true;
                    $result_msg = "Změny planu byly uloženy.";
                }
                else {
                    $result_ok = false;
                    $result_msg = "Změny planu vykonu se nepovedlo uložit.";
                }
            }

            // presun do detailu
            $action = "sluzba_detail_show";

        }

        //
        // ========================= PLÁN VÝKONU KONEC ========================









        //
        // ================= ZÁZNAM VÝKONU START ==================


        if ($action == "zaznam_vykonu_add_prepare") {
            if (!isset($plan_id)) {
                $plan_id = $this->loadRequestParam($request,"plan_id", "all", -1);
            }
            if (!isset($sluzba_id)) {
                // pokud nemam pokoj, tak nactu z URL. Jinak uz ho MAM treba z INSERTu
                $sluzba_id = $this->loadRequestParam($request,"sluzba_id", "all", -1);
            }

            $content_params["form_submit_url"] = $this->makeUrlByRoute($this->route, array("action" => "zaznam_vykonu_add_go", "plan_id" => $plan_id));
            $content_params["form_action_insert_zaznam_vykonu"] = $this->makeUrlByRoute($this->route, array("action" => "zaznam_vykonu_add_go", "plan_id" => $plan_id));
            $content_params["url_sluzby_list"] = $this->makeUrlByRoute($this->route, array("action" => "sluzby_list_all"));
            $content_params["plany"] = $sluzby->adminLoadPlanVykonuItems();
            $content_params["sluzby"] = $sluzby;
            $content_params["uzivatele"] = $sluzby->adminLoadUzivateleItems();
            $content_params["url_zaznam_vykonu_add_prepare"] = $this->makeUrlByRoute($this->route, array("action" => "zaznam_vykonu_add_prepare"));
            $content_params["url_plan_vykonu_add_prepare"] = $this->makeUrlByRoute($this->route, array("action" => "plan_vykonu_add_prepare", "sluzba_id" => $sluzba_id));

            $content = $this->renderPhp(DS1_DIR_ADMIN_MODULES_FROM_ADMIN . "sluzby/templates/admin_zaznam_vykonu_insert_form.inc.php", $content_params, true);
        }

        if ($action == "zaznam_vykonu_add_go") {

            if (!isset($plan_id)) {
                $plan_id = $this->loadRequestParam($request,"plan_id", "all", -1);
            }
            $zaznam_vykonu_new = $this->loadRequestParam($request, "zaznam_vykonu", "post", null);
            $zaznam_vykonu_new["plan_vykonu_id"] = $plan_id;
            $zaznam_id = $sluzby->adminInsertZaznamVykonuItem($zaznam_vykonu_new);

            // prepnout na editaci typu
            $action = "zaznam_vykonu_update_prepare";
        }



        if ($action == "zaznam_vykonu_update_prepare") {

            if (!isset($zaznam_id)) {
                $zaznam_id = $this->loadRequestParam($request,"update", "all", -1);
            }

            if (!isset($sluzba_id)) {
                $sluzba_id = $this->loadRequestParam($request,"sluzba_id", "all", -1);
            }

            $zaznam_detail = $sluzby->adminGetZaznamVykonuItemByID($zaznam_id);

            if ($zaznam_id > 0 && $zaznam_detail != null) {

                $content_params["zaznam_id"] = $zaznam_id;
                $content_params["zaznam_vykonu"] = $zaznam_detail;
                $content_params["form_submit_url"] = $this->makeUrlByRoute($this->route, array("action" => "zaznam_vykonu_update_go", "zaznam_vykonu_id" => $zaznam_id, "sluzba_id" => $sluzba_id));//$this->makeUrlByRoute($this->route);
                $content_params["form_action_update_zaznam_vykonu"] = $this->makeUrlByRoute($this->route, array("action" => "zaznam_vykonu_update_go", "zaznam_vykonu_id" => $zaznam_id, "sluzba_id" => $sluzba_id));
                $content_params["url_sluzby_list"] = $this->makeUrlByRoute($this->route, array("action" => "sluzby_list_all"));
                $content_params["url_sluzba_detail"] = $this->makeUrlByRoute($this->route, array("action" => "sluzba_detail_show", "sluzba_id" => $sluzba_id));

                $content = $this->renderPhp(DS1_DIR_ADMIN_MODULES_FROM_ADMIN . "sluzby/templates/admin_zaznam_vykonu_update_form.inc.php", $content_params, true);
            }
            else {
                $result_msg = "Nic nenalezeno pri zaznamu vykonu update.";
                $result_ok = false;

                $action = "sluzby_list_all";
            }
        }


        if ($action == "zaznam_vykonu_update_go") {
            if (!isset($zaznam_vykonu_id)) {
                $zaznam_vykonu_id = $this->loadRequestParam($request,"zaznam_vykonu_id", "all", -1);
            }
//            if (!isset($sluzba_id)) {
//                $sluzba_id = $this->loadRequestParam($request,"sluzba_id", "all", -1);
//            }

            $zaznam_new = $this->loadRequestParam($request, "zaznam_vykonu", "post", null);

            if ($zaznam_vykonu_id > 0 && $zaznam_new != null) {

                // provest update
                $ok = $sluzby->adminUpdateZaznamVykonuItem($zaznam_vykonu_id, $zaznam_new);

                if ($ok) {
                    $result_ok = true;
                    $result_msg = "Změny zaznamu byly uloženy.";
                }
                else {
                    $result_ok = false;
                    $result_msg = "Změny zaznamu vykonu se nepovedlo uložit.";
                }
            }

            $action = "sluzby_list_all"; //TODO?
        }

        //
        // ================= ZÁZNAM VÝKONU KONEC ===================








        if($action == "sluzby_zaznam"){
            if(!isset($plan_id)){
                $plan_id = $this->loadRequestParam($request,"plan_zaznam", "all", -1);
            }


            if (!isset($sluzba_id)) {
                $sluzba_id = $this->loadRequestParam($request,"sluzba_id", "all", -1);
            }

            $zaznamy = $sluzby->adminGetZaznamVykonuItemsByPlanVykonuID($plan_id);
            $pacient = $sluzby->adminGetObyvatelNameBySluzbaID($sluzba_id);
            $plan_ciselne = $sluzby->adminGetPlanVykonuItemByID($plan_id)["typ_planu"];
            $plan_lidsky = $sluzby->translateTypPlanu($plan_ciselne);

            if ($plan_id > 0){
                $content_params["plan_lidsky"] = $plan_lidsky;
                $content_params["zaznamy"] = $zaznamy;
                $content_params["sluzba_id"] = $sluzba_id;
                $content_params["plan_id"] = $plan_id;
                $content_params["pacient"] = $pacient;
                $content_params["sluzby"] = $sluzby;

                $content_params["url_sluzby_list"] = $this->makeUrlByRoute($this->route, array("action" => "sluzby_list_all"));
                $content_params["url_sluzba_detail"] = $this->makeUrlByRoute($this->route, array("action" => "sluzba_detail_show", "sluzba_id" => $sluzba_id));
                $content_params["url_sluzby_podrobnosti_zaznam"] = $this->makeUrlByRoute($this->route, array("action" => "podrobnosti_zaznam", "sluzba_id" => $sluzba_id));
                $content_params["url_zaznam_vykonu_add_prepare"] = $this->makeUrlByRoute($this->route, array("action" => "zaznam_vykonu_add_prepare", "sluzba_id" => $sluzba_id, "plan_id" => $plan_id));
                $content_params["url_zaznam_vykonu_update_prepare"] = $this->makeUrlByRoute($this->route, array("action" => "zaznam_vykonu_update_prepare", "sluzba_id" => $sluzba_id));


                $content_params["sluzby_route_name"] = "sluzby";

                $content = $this->renderPhp(DS1_DIR_ADMIN_MODULES_FROM_ADMIN . "sluzby/templates/admin_sluzba_zaznamy.inc.php", $content_params, true);
            }
            else {
                $result_msg = "Nenalezeno 8";
                $result_ok = false;

                $action = "sluzba_detail_show";
            }

        }



        //
        // ============== PODROBNOSTI K ZÁZNAMU START ================

        if($action == "podrobnosti_zaznam"){
            $zaznam_id = $this->loadRequestParam($request,"zaznam_detail", "all", -1);

            if (!isset($sluzba_id)) {
                $sluzba_id = $this->loadRequestParam($request,"sluzba_id", "all", -1);
            }
//            if (!isset($zaznam_vykonu_id)) {
//                $zaznam_vykonu_id = $this->loadRequestParam($request,"zaznam_vykonu_id", "all", -1);
//            }

            //jmeno pacienta
            $pacient = $sluzby->adminGetObyvatelNameBySluzbaID($sluzba_id);

            $podrobnosti = $sluzby->adminGetZaznamVykonuDetailItemsByZaznamID($zaznam_id);

            //$uzivatel_jmeno = $sluzby->adminGetUzivatelItemByID($podrobnosti["uzivatel_id"]);


            if ($zaznam_id > 0 /*&& $typ != null && $plan != null && $zaznam != null*/) { //TODO
                // vypis
                $content_params["pacient"] = $pacient;
                $content_params["sluzba_id"] = $sluzba_id;
                $content_params["podrobnosti"] = $podrobnosti;
                $content_params["sluzby"] = $sluzby;
                //$content_params["uzivatel_jmeno"] = $uzivatel_jmeno;

                $content_params["url_sluzby_list"] = $this->makeUrlByRoute($this->route, array("action" => "sluzby_list_all"));
                $content_params["url_sluzba_detail"] = $this->makeUrlByRoute($this->route, array("action" => "sluzba_detail_show", "sluzba_id" => $sluzba_id));
                $content_params["url_podrobnosti_zaznam_add_prepare"]  = $this->makeUrlByRoute($this->route, array("action" => "podrobnosti_zaznam_add_prepare", "zaznam_id" => $zaznam_id));
                $content_params["url_podrobnosti_zaznam_update_prepare"]  = $this->makeUrlByRoute($this->route, array("action" => "podrobnosti_zaznam_update_prepare", "zaznam_id" => $zaznam_id, "sluzba_id" => $sluzba_id/*, "zaznam_vykonu_detail_id" => $podrobnosti["id"]*/));
                //$content_params["url_sluzby_podrobnosti_zaznam"]  = $this->makeUrlByRoute($this->route, array("action" => "url_sluzby_podrobnosti_zaznam", "sluzba_id" => $sluzba_id));


                $content_params["sluzby_route_name"] = "sluzby";

                $content = $this->renderPhp(DS1_DIR_ADMIN_MODULES_FROM_ADMIN . "sluzby/templates/admin_podrobnosti_zaznam.inc.php", $content_params, true);
            }
            else {
                $result_msg = "Žádný detail záznamu nenalezen";
                $result_ok = false;

                $action = "sluzba_detail_show";
            }
        }

        if($action == "podrobnosti_zaznam_add_prepare"){
            if (!isset($zaznam_id)) {
                $zaznam_id = $this->loadRequestParam($request,"zaznam_id", "all", -1);
            }

            $content_params["form_submit_url"] = $this->makeUrlByRoute($this->route, array("action" => "podrobnosti_zaznam_add_go", "zaznam_id" => $zaznam_id));
            $content_params["form_action_insert_podrobnosti_zaznam_vykonu"] = $this->makeUrlByRoute($this->route, array("action" => "podrobnosti_zaznam_add_go", "zaznam_id" => $zaznam_id));
            $content_params["url_sluzby_list"] = $this->makeUrlByRoute($this->route, array("action" => "sluzby_list_all"));
            $content_params["uzivatele"] = $sluzby->adminLoadUzivateleItems();


            $content = $this->renderPhp(DS1_DIR_ADMIN_MODULES_FROM_ADMIN . "sluzby/templates/admin_podrobnosti_zaznam_vykonu_insert_form.inc.php", $content_params, true);
        }

        if($action == "podrobnosti_zaznam_add_go"){
            if (!isset($zaznam_id)) {
                $zaznam_id = $this->loadRequestParam($request,"zaznam_id", "all", -1);
            }
            $zaznam_vykonu_detail_new = $this->loadRequestParam($request, "zaznam_vykonu_detail", "post", null);
            $zaznam_vykonu_detail_new["zaznam_vykonu_id"] = $zaznam_id;

            $zaznam_detail_id = $sluzby->adminInsertZaznamVykonuDetailItem($zaznam_vykonu_detail_new);

            $action = "sluzby_list_all";
        }


        if($action == "podrobnosti_zaznam_update_prepare"){
            if (!isset($zaznam_id)) {
                $zaznam_id = $this->loadRequestParam($request,"zaznam_id", "all", -1);
            }
            if (!isset($sluzba_id)) {
                $sluzba_id = $this->loadRequestParam($request,"sluzba_id", "all", -1);
            }
            if (!isset($zaznam_vykonu_detail_id)) {
                $zaznam_vykonu_detail_id = $this->loadRequestParam($request,"detail_zaznamu_upravit", "post", -1);
            }


            $zaznam_vykonu_detail = $sluzby->adminGetZaznamVykonuDetailItemByID($zaznam_vykonu_detail_id);//$sluzby->adminGetZaznamVykonuDetailItemByZaznamID($zaznam_id);

            if ($zaznam_id > 0 && $zaznam_vykonu_detail != null) {

                $content_params["zaznam_id"] = $zaznam_id;
                $content_params["zaznam_vykonu_detail"] = $zaznam_vykonu_detail;
                $content_params["form_submit_url"] = $this->makeUrlByRoute($this->route, array("action" => "podrobnosti_zaznam_update_go", "zaznam_vykonu_id" => $zaznam_id, "zaznam_vykonu_detail_id" => $zaznam_vykonu_detail_id));//$this->makeUrlByRoute($this->route);
                $content_params["form_action_update_zaznam_vykonu_detail"] = $this->makeUrlByRoute($this->route, array("action" => "podrobnosti_zaznam_update_go", "zaznam_vykonu_id" => $zaznam_id, "zaznam_vykonu_detail_id" => $zaznam_vykonu_detail_id));
                $content_params["url_sluzby_list"] = $this->makeUrlByRoute($this->route, array("action" => "sluzby_list_all"));
                $content_params["url_sluzba_detail"] = $this->makeUrlByRoute($this->route, array("action" => "sluzba_detail_show", "sluzba_id" => $sluzba_id));

                $content = $this->renderPhp(DS1_DIR_ADMIN_MODULES_FROM_ADMIN . "sluzby/templates/admin_podrobnosti_zaznam_vykonu_update_form.inc.php", $content_params, true);
            }
            else {
                $result_msg = "Nic nenalezeno pri detail zaznamu vykonu update.";
                $result_ok = false;

                $action = "sluzby_list_all";
            }
        }

        if($action == "podrobnosti_zaznam_update_go"){
            if (!isset($zaznam_vykonu_detail_id)) {
                $zaznam_vykonu_detail_id = $this->loadRequestParam($request,"zaznam_vykonu_detail_id", "all", -1);
            }
            $zaznam_detail_new = $this->loadRequestParam($request, "zaznam_vykonu_detail", "post", null);

            if ($zaznam_vykonu_detail_id > 0 && $zaznam_detail_new != null) {

                // provest update
                $ok = $sluzby->adminUpdateZaznamVykonuDetailItem($zaznam_vykonu_detail_id, $zaznam_detail_new);

                if ($ok) {
                    $result_ok = true;
                    $result_msg = "Změny detailu zaznamu byly uloženy.";
                }
                else {
                    $result_ok = false;
                    $result_msg = "Změny detailu zaznamu vykonu se nepovedlo uložit.";
                }
            }

            $action = "sluzby_list_all";
        }

        //
        // ============== PODROBNOSTI K ZÁZNAMU KONEC ================






        //
        //  ================== TYP VÝKONU START ====================

        if($action == "typ_vykonu_add_prepare"){
            if (!isset($typ_vykonu_id)) {
                $typ_vykonu_id = $this->loadRequestParam($request,"typ_vykonu_id", "all", -1);
            }
            if (!isset($sluzba_id)) {
                $sluzba_id = $this->loadRequestParam($request,"sluzba_id", "all", -1);
            }

            $content_params["form_submit_url"] = $this->makeUrlByRoute($this->route, array("action" => "typ_vykonu_add_go", "typ_vykonu_id" => $typ_vykonu_id, "sluzba_id" => $sluzba_id));//$this->makeUrlByRoute($this->route);
            $content_params["form_action_insert_typ_vykonu"] = $this->makeUrlByRoute($this->route, array("action" => "typ_vykonu_add_go", "typ_vykonu_id" => $typ_vykonu_id, "sluzba_id" => $sluzba_id));//"sluzba_add_go";
            $content_params["url_sluzby_list"] = $this->makeUrlByRoute($this->route, array("action" => "sluzby_list_all"));
            //$content_params["typy"] = $sluzby->adminLoadTypVykonuItems();

            $content = $this->renderPhp(DS1_DIR_ADMIN_MODULES_FROM_ADMIN . "sluzby/templates/admin_typ_vykonu_insert_form.inc.php", $content_params, true);
        }

        if ($action == "typ_vykonu_add_go") {
//            if (!isset($typ_vykonu_id)) {
//                $typ_vykonu_id = $this->loadRequestParam($request,"typ_vykonu_id", "all", -1);
//            }
            if (!isset($sluzba_id)) {
                $sluzba_id = $this->loadRequestParam($request,"sluzba_id", "all", -1);
            }

            // nacist data
            $typ_vykonu_new = $this->loadRequestParam($request, "typ_vykonu", "post", null);
            $typ_vykonu_id = $sluzby->adminInsertTypVykonuItem($typ_vykonu_new);

            $action = "typ_vykonu_update_prepare";
        }


        if($action == "typ_vykonu_update_prepare") {
            if (!isset($typ_vykonu_id)) {
                $typ_vykonu_id = $this->loadRequestParam($request,"typ_vykonu_id", "all", -1);
            }
            if (!isset($sluzba_id)) {
                $sluzba_id = $this->loadRequestParam($request,"sluzba_id", "all", -1);
            }

            $typ_detail = $sluzby->adminGetTypVykonuItemByID($typ_vykonu_id);

            if ($typ_vykonu_id > 0 && $typ_detail != null) {
                echo "tady jsem";
                $content_params["typ_id"] = $typ_vykonu_id;
                $content_params["typ_vykonu"] = $typ_detail;
                $content_params["form_submit_url"] = $this->makeUrlByRoute($this->route, array("action" => "typ_vykonu_update_go", "typ_vykonu_id" => $typ_vykonu_id, "sluzba_id" => $sluzba_id));//$this->makeUrlByRoute($this->route);
                $content_params["form_action_update_typ_vykonu"] = $this->makeUrlByRoute($this->route, array("action" => "typ_vykonu_update_go", "typ_vykonu_id" => $typ_vykonu_id, "sluzba_id" => $sluzba_id));
                $content_params["url_sluzby_list"] = $this->makeUrlByRoute($this->route, array("action" => "sluzby_list_all"));
                $content_params["url_sluzba_detail"] = $this->makeUrlByRoute($this->route, array("action" => "sluzba_detail_show", "sluzba_id" => $sluzba_id));

                $content = $this->renderPhp(DS1_DIR_ADMIN_MODULES_FROM_ADMIN . "sluzby/templates/admin_typ_vykonu_update_form.inc.php", $content_params, true);
            }
            else {
                $result_msg = "Nic nenalezeno pri typu vykonu update.";
                $result_ok = false;

                $action = "sluzby_list_all";
            }
        }


        if($action == "typ_vykonu_update_go"){
            if (!isset($typ_vykonu_id)) {
                $typ_vykonu_id = $this->loadRequestParam($request,"typ_vykonu_id", "all", -1);
            }
            if (!isset($sluzba_id)) {
                $sluzba_id = $this->loadRequestParam($request,"sluzba_id", "all", -1);
            }

            $typ_new = $this->loadRequestParam($request, "typ_vykonu", "post", null);

            if ($sluzba_id > 0 && $typ_vykonu_id > 0 && $typ_new != null) {

                // provest update
                $ok = $sluzby->adminUpdateTypVykonuItem($typ_vykonu_id, $typ_new);

                if ($ok) {
                    $result_ok = true;
                    $result_msg = "Změny typu výkonu byly uloženy.";
                }
                else {
                    $result_ok = false;
                    $result_msg = "Změny typu výkonu se nepodařilo uložit.";
                }
            }

            if($sluzba_id == -1){
                $action = "sluzby_list_all";
            }
            else{
                $action = "sluzba_detail_show";
            }


        }


        //
        // ===================== TYP VÝKONU KONEC =========================
















        //
        // ======================= SLUŽBA START =========================



        // formular pro vytvoreni nove sluzby
        if ($action == "sluzba_add_prepare") {
            $content_params["form_submit_url"] = $this->makeUrlByRoute($this->route, array("action" => "sluzba_add_go"));//$this->makeUrlByRoute($this->route);
            $content_params["form_action_insert_sluzba"] = $this->makeUrlByRoute($this->route, array("action" => "sluzba_add_go"));//"sluzba_add_go";
            $content_params["url_sluzby_list"] = $this->makeUrlByRoute($this->route, array("action" => "sluzby_list_all"));
            $content_params["typy"] = $sluzby->adminLoadTypVykonuItems();
            $content_params["obyvatele"] = $sluzby->adminLoadObyvateleItems();
            $content_params["url_typ_vykonu_add_prepare"] = $this->makeUrlByRoute($this->route, array("action" => "typ_vykonu_add_prepare"));

            $content = $this->renderPhp(DS1_DIR_ADMIN_MODULES_FROM_ADMIN . "sluzby/templates/admin_sluzba_insert_form.inc.php", $content_params, true);
        }



        if ($action == "sluzba_add_go") {
            $sluzba_new = $this->loadRequestParam($request, "sluzba", "post", null);

            $sluzba_id = $sluzby->adminInsertSluzbaItem($sluzba_new);

            $action = "sluzba_update_prepare";
        }




        if($action == "sluzba_update_go"){
            if (!isset($sluzba_id)) {
                $sluzba_id = $this->loadRequestParam($request,"sluzba_id", "all", -1);
            }

            $sluzba_new = $this->loadRequestParam($request, "sluzba", "post", null);

            if ($sluzba_id > 0 && $sluzba_new != null) {

                // provest update
                $ok = $sluzby->adminUpdateSluzbaItem($sluzba_id, $sluzba_new);

                if ($ok) {
                    $result_ok = true;
                    $result_msg = "Změny služby byly uloženy.";
                }
                else {
                    $result_ok = false;
                    $result_msg = "Změny služby se nepodařilo uložit.";
                }
            }

            // presun do detailu
            $action = "sluzba_detail_show";
        }



        if ($action == "sluzba_update_prepare") {
            if (!isset($sluzba_id)) {
                // pokud nemam sluzbu, tak nactu z URL. Jinak uz ji MAM treba z INSERTu
                $sluzba_id = $this->loadRequestParam($request,"sluzba_id", "all", -1);
            }

            // nacist sluzbu
            $sluzba_detail = $sluzby->adminGetSluzbaItemByID($sluzba_id);

            if ($sluzba_id > 0 && $sluzba_detail != null) {
                $content_params["sluzba_id"] = $sluzba_id;
                $content_params["sluzba"] = $sluzba_detail;
                $content_params["obyvatele"] = $sluzby->adminLoadObyvateleItems();
                $content_params["typy"] = $sluzby->adminLoadTypVykonuItems();
                $content_params["form_submit_url"] = $this->makeUrlByRoute($this->route, array("action" => "sluzba_update_go", "sluzba_id" => $sluzba_id));//$this->makeUrlByRoute($this->route);
                $content_params["form_action_update_sluzba"] = $this->makeUrlByRoute($this->route, array("action" => "sluzba_update_go", "sluzba_id" => $sluzba_id));
                $content_params["url_sluzby_list"] = $this->makeUrlByRoute($this->route, array("action" => "sluzby_list_all"));

                $content = $this->renderPhp(DS1_DIR_ADMIN_MODULES_FROM_ADMIN . "sluzby/templates/admin_sluzba_update_form.inc.php", $content_params, true);
            }
            else {
                $result_msg = "Služba nenalezena.";
                $result_ok = false;

                $action = "sluzby_list_all";
            }
        }

        //
        // ============================ SLUŽBA KONEC ===========================





        //
        // ======================= DETAIL START =============================

        // DETAIL
        if ($action == "sluzba_detail_show") {
            if (!isset($sluzba_id)) {
                // pokud nemam sluzbu, tak nactu z URL. Jinak uz ji MAM treba z INSERTu
                $sluzba_id = $this->loadRequestParam($request,"sluzba_id", "all", -1);
            }

            //jmeno pacienta podle sluzby
            $pacient = $sluzby->adminGetObyvatelNameBySluzbaID($sluzba_id);

            // typ vykonu, ktery sluzba ma
            $typ_vykonu_id = $sluzby->adminGetSluzbasTypVykonuID($sluzba_id);
            // plan vykonu, ktery sluzba ma
            $plan_id = $sluzby->adminGetPlansPlanVykonuID($sluzba_id);

            // radek z tabulky s danym id
            $typ = $sluzby->adminGetTypVykonuItemByID($typ_vykonu_id);

            // plan sluzby
            $plan = $sluzby->adminGetPlanVykonuItemBySluzbaID($sluzba_id);

            // vsechny zaznamy s danym planem
            $zaznamy = $sluzby->adminGetZaznamVykonuItemsByID($plan_id);

            // vsechny plany podle id sluzby
            $plany = $sluzby->adminLoadPlanVykonuItemsBySluzbaID($sluzba_id);

            if ($sluzba_id > 0) {
                // poslu si tam vsechno, co potrebuju
                $content_params["pacient"] = $pacient;
                $content_params["sluzba_id"] = $sluzba_id;
                $content_params["typ"] = $typ;
                $content_params["plan"] = $plan;
                $content_params["zaznamy"] = $zaznamy;
                $content_params["sluzby"] = $sluzby;
                $content_params["plany"] = $plany;
                $content_params["url_sluzby_list"] = $this->makeUrlByRoute($this->route, array("action" => "sluzby_list_all")); //todo muze se hodit
                $content_params["url_sluzby_zaznam"] = $this->makeUrlByRoute($this->route, array("action" => "sluzby_zaznam", "sluzba_id" => $sluzba_id));
                $content_params["url_typ_vykonu_update_prepare"] = $this->makeUrlByRoute($this->route, array("action" => "typ_vykonu_update_prepare", "typ_vykonu_id" => $typ_vykonu_id, "sluzba_id" => $sluzba_id));;
                $content_params["url_typ_vykonu_add_prepare"] = $this->makeUrlByRoute($this->route, array("action" => "typ_vykonu_add_prepare", "typ_vykonu_id" => $typ_vykonu_id, "sluzba_id" => $sluzba_id));
                $content_params["url_plan_vykonu_add_prepare"] = $this->makeUrlByRoute($this->route, array("action" => "plan_vykonu_add_prepare", "sluzba_id" => $sluzba_id));
                $content_params["url_plan_vykonu_update_prepare"] = $this->makeUrlByRoute($this->route, array("action" => "plan_vykonu_update_prepare", "sluzba_id" => $sluzba_id/*, "plan_vykonu_id" => $plan_id*/));
                $content_params["sluzby_route_name"] = "sluzby";

                $content = $this->renderPhp(DS1_DIR_ADMIN_MODULES_FROM_ADMIN . "sluzby/templates/admin_sluzba_detail.inc.php", $content_params, true);
            }
            else {
                $result_msg = "Nelze zobrazit detail služby, protože služba nebyla nalezena.";
                $result_ok = false;

                $action = "sluzby_list_all";
            }
        }

        //
        // ====================== DETAIL KONEC ==============================





        //
        // ======================== VŠECHNO START ==========================

        if ($action == "sluzby_list_all") {
            // vypsat vsechny sluzby

            $count = 50;
            $where_array = array();
            // count_on_page a page se u prikazu count neuvazuje
            $total = $sluzby->adminLoadSluzbaItems("count", 1, 1, $where_array);
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
            $content_params["sluzby"] = $sluzby;
            $content_params["sluzby_list_name"] = "všechny"; // dle filtru
            $content_params["sluzba_detail_action"] = "sluzba_detail_show";
            $content_params["sluzba_update_prepare_action"] = "sluzba_update_prepare";
            $content_params["sluzby_count"] = $count;
            $content_params["sluzby_total"] = $total;
            //$content_params["search_params"] = $search_params;
            $content_params["sluzby_list"] = $sluzby->adminLoadSluzbaItems("data", $this->page_number, $count, $where_array, "id", "asc");
            $content_params["pagination_html"] = $pagination_html;

            // url pro vytvoreni sluzby
            $content_params["url_sluzba_add_prepare"] = $this->makeUrlByRoute($this->route, array("action" => "sluzba_add_prepare"));

            // vysledek nejake akce
            $content_params["result_msg"] = $result_msg;
            $content_params["result_ok"] = $result_ok;

            $content = $this->renderPhp(DS1_DIR_ADMIN_MODULES_FROM_ADMIN . "sluzby/templates/admin_sluzby_list.inc.php", $content_params, true);
        }

        //
        // ========================= VŠECHNO KONEC ================================












        // vypsat hlavni template
        $main_params = array();
        $main_params["content"] = $content;
        $main_params["result_msg"] = $result_msg;
        $main_params["result_ok"] = $result_ok;

        return $this->renderAdminTemplate($main_params);
        //return new Response("Controller pro pokoje.");
    }



    //
    //  ------------------------------ STRANKY KONEC------------------------------
    //
}

