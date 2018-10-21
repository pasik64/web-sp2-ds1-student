<?php

namespace ds1\controllers_web;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use ds1\core\ds1_base_controller;


class homepage_controller extends ds1_base_controller
{
    public function indexAction(Request $request, $page = "")
    {
        // zavolat metodu rodice, ktera provede obecne hlavni kroky
        parent::indexAction($request, $page);


        // renderovat hlavni sablonu
        $main_params = array();
        $main_params["content"] = $content;
        $main_params["title"] = "";
        $main_params["meta_description"] = "";
        $main_params["meta_keywords"] = "";

        // poznamka <script type="text/javascript"> - ten type uz se tam davat nemusi a radeji ani nema u JS
        $main_params["js_add"] = "";

        // vypsat hlavni template
        return $this->renderMainTemplate($main_params);
    }


    /**
     * Sem to muze prijit v pripade: 404, nebo treba obrazku
     * @param Request $request
     * @param $action tady je obsazeno url
     * @return Response
     */
    public function odpadAction(Request $request, $action) {

        // echo "$action";
        //printr($request);

        // 404
        return new Response("Chyba 404 - homepgae odpad action");
    }
}