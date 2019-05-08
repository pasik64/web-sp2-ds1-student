<?php
namespace ds1\admin_modules\api_obyvatele;

use ds1\admin_modules\pokoje\pokoje;
use ds1\admin_modules\obyvatele\obyvatele;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

use ds1\core\ds1_base_controller;

class api_obyvatele_controller extends ds1_base_controller
{
    // timto rikam, ze je NUTNE PRIHLASENI ADMINA
    protected $admin_secured_forced = true; // vynuceno pro jistotu, ale mel by stacit kontext admin


    /**
     * Hlavni metoda pro vstup
     * @param Request $request
     * @return JsonResponse|Response
     */
    public function apiIndexAction(Request $request)
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
        /*
        $field = @$post_data["field"];
        $search_string = @$post_data["search"];
        $base_url = @$post_data["base_url"];
        */
        //echo "field: $field, search: $search_string <br/>";

        // vratit json response
        //return new JsonResponse($data_for_response);

        // pro testovani pres URL
        return new Response("Controller pro obyvatele API - 5/2019.");
    }


    /**
     * Metoda pro testovani API.
     * @param Request $request
     * @return Response
     */
    public function apiTestAction(Request $request)
    {
        // zavolat metodu rodice, ktera provede obecne hlavni kroky a nacte parametry
        parent::indexAction($request, "");

        // KONTROLA ZABEZPECENI - pro jistotu
        // test, jestli je uzivatel prihlasen, pokud NE, tak redirect na LOGIN
        $this->checkAdminLogged("api");

        // objekt pro praci s obyvateli
        $obyvatele = new obyvatele();
        $obyvatele->SetPDOConnection($this->ds1->GetPDOConnection());

        // DATA - v postu dostanu field a search

        // POZOR: data jsou ve formatu application/json a neni mozne je prijmout $_POST, musi se to takto:
        $json_data = file_get_contents("php://input");
        $post_data = (array) json_decode($json_data);   // zaroven pretypovat na pole
        //printr($post_data);

        // vratit json response
        $post_data["pokus"] = "test";
        $data_for_response = json_encode($post_data);
        return new JsonResponse($data_for_response);

        // pro testovani pres URL
        //return new Response("Test action - 5/2019.");
    }
}