<?php
namespace ds1\admin_modules\api_zaznam_vykonu;

use ds1\admin_modules\sprava_uzivatelu\sprava_uzivatelu;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

use ds1\core\ds1_base_controller;

class api_zaznam_vykonu_controller extends ds1_base_controller
{
    
    // timto rikam, ze je NUTNE PRIHLASENI ADMINA
    protected $admin_secured_forced = true; // vynuceno pro jistotu, ale mel by stacit kontext admin

    public function getActualRecords(Request $request)
    {
        // zavolat metodu rodice, ktera provede obecne hlavni kroky a nacte parametry
        parent::indexAction($request, "");

        // KONTROLA ZABEZPECENI - pro jistotu
        // test, jestli je uzivatel prihlasen, pokud NE, tak redirect na LOGIN
        $this->checkAdminLogged();
        // objekt pro praci s obyvateli
        $zaznam_vykonu = new zaznam_vykonu();
        $zaznam_vykonu->SetPDOConnection($this->ds1->GetPDOConnection());
        
        $uzivatel_info = $this->ds1->user_admin->getAdminUserFromSession();
        $sprava_uzivatelu = new sprava_uzivatelu();
        $sprava_uzivatelu->SetPDOConnection($this->ds1->GetPDOConnection());
        
        $uzivatel_info = $sprava_uzivatelu->getUzivatelByLogin($uzivatel_info["login"]);
        $data_for_response = $zaznam_vykonu->getAktualniZaznamyByUzivatelId($uzivatel_info["id"]);

        // vratit json response
        return new JsonResponse($data_for_response);
    }

    public function getDetailOptions(Request $request){
        // zavolat metodu rodice, ktera provede obecne hlavni kroky a nacte parametry
        parent::indexAction($request, "");

        // KONTROLA ZABEZPECENI - pro jistotu
        // test, jestli je uzivatel prihlasen, pokud NE, tak redirect na LOGIN
        $this->checkAdminLogged();

        // objekt pro praci s obyvateli
        $zaznam_vykonu = new zaznam_vykonu();
        $zaznam_vykonu->SetPDOConnection($this->ds1->GetPDOConnection());
        
        $detailList = $zaznam_vykonu->getDetailList();
        foreach ($detailList as &$detail) {
            $detail["moznosti"]=$zaznam_vykonu->getDetailOptionsByDetailId($detail["id"]);
            
        }
        // vratit json response
        return new JsonResponse($detailList);
    }

    public function confirmRecord(Request $request){
        parent::indexAction($request, "");

        // KONTROLA ZABEZPECENI - pro jistotu
        // test, jestli je uzivatel prihlasen, pokud NE, tak redirect na LOGIN
        $this->checkAdminLogged();

        $zaznam_vykonu = new zaznam_vykonu();
        $zaznam_vykonu->SetPDOConnection($this->ds1->GetPDOConnection());

        $json_data = file_get_contents("php://input");
        $post_data = (array) json_decode($json_data);   // zaroven pretypovat na pole
        $item = array();
        $item["stav"] = 1;
        $updatedRecord=$zaznam_vykonu->updateRecord($post_data["id"], $item);
        return new JsonResponse($updatedRecord);
    }
    /**
     * 
     */
    public function addDetailGeneral(Request $request){
        
        parent::indexAction($request, "");

        // KONTROLA ZABEZPECENI - pro jistotu
        // test, jestli je uzivatel prihlasen, pokud NE, tak redirect na LOGIN
        $this->checkAdminLogged();
        
        $user_id = $this->getLoginUserId();

        $zaznam_vykonu = new zaznam_vykonu();
        $zaznam_vykonu->SetPDOConnection($this->ds1->GetPDOConnection());

        $json_data = file_get_contents("php://input");
        $post_data = (array) json_decode($json_data);   // zaroven pretypovat na pole
        
        $post_data["uzivatel_id"] = $user_id;
        $id = $zaznam_vykonu->insertDetailObecny($post_data);

        if($id != -1){
            return new JsonResponse("ok");
        }else{
            return new JsonResponse("error");
        }
    }

    /**
     * Metoda pro pridani detailu s lekem 
     */
    public function addDetailMedicines(Request $request){
        
        parent::indexAction($request, "");

        // KONTROLA ZABEZPECENI - pro jistotu
        // test, jestli je uzivatel prihlasen, pokud NE, tak redirect na LOGIN
        $this->checkAdminLogged();
        
        $user_id = $this->getLoginUserId();

        $zaznam_vykonu = new zaznam_vykonu();
        $zaznam_vykonu->SetPDOConnection($this->ds1->GetPDOConnection());

        $json_data = file_get_contents("php://input");
        $post_data = (array) json_decode($json_data);   // zaroven pretypovat na pole
        
        $post_data["uzivatel_id"] = $user_id;
        $id = $zaznam_vykonu->insertDetailLeky($post_data);
        
        if($id != -1){
            return new JsonResponse("ok");
        }else{
            return new JsonResponse("error");
        }
        
    }
    /**
     * Metoda pro zjisteni id prihlaseneho uzivatele
     */
    private function getLoginUserId(){
        $user_info = $this->ds1->user_admin->getAdminUserFromSession();
        $sprava_uzivatelu = new sprava_uzivatelu();
        $sprava_uzivatelu->SetPDOConnection($this->ds1->GetPDOConnection());
            
        $user_info = $sprava_uzivatelu->getUzivatelByLogin($user_info["login"]);
        return $user_info["id"];
    }
}