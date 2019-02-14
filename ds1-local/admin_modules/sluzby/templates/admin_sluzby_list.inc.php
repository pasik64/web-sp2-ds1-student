<div class="container-fluid" ng-app="ds1">
    <div class="card">
        <div class="card-header">
            <div class="pull-left">
                Seznam služeb
            </div>
            <div class="pull-right">
                <!-- odkaz pro pridani sluzby -->
                  <a href="<?php echo $url_sluzba_add_prepare;?>" class="btn btn-primary btn-sm"><i class="icon-plus"></i>Přidat službu</a>
            </div>
        </div>
        <div class="card-body">

            <div class="row">
                <div class="col-md-12">
                    <!-- TODO search form ?-->

                </div>
            </div>
            <br/>

            <div class="row">
                <div class="col-md-12">
                    <?php
                    // vypis sluzeb
                    if ($sluzby_list != null) {

                        // table-sm - zmensi odsazeni bunek
                        echo "<table class='table table-sm table-bordered table-striped table-hover'>";
                        echo "<tr>";
//                                    <th>id</th>
//                                    <th>obyvatel id</th>
//                                    <th>typ vykonu id</th>
                         echo"      <th>Obyvatel</th>
                                    <th>Datum od</th>
                                    <th>Datum do</th>
                                    <th>Poznámka</th>
                                    <th>&nbsp;</th>
                        </tr>";

                        foreach ($sluzby_list as $sluzba) {
                            // detail sluzby
                            $route_params = array();
                            $route_params["action"] = $sluzba_detail_action;
                            $route_params["sluzba_id"] = $sluzba["id"];
                            $url_detail = $this->makeUrlByRoute($route, $route_params);

                            // update prepare
                            $route_params = array();
                            $route_params["action"] = $sluzba_update_prepare_action;
                            $route_params["sluzba_id"] = $sluzba["id"];
                            $url_update_prepare = $this->makeUrlByRoute($route, $route_params);

                            $pom = $sluzba["id"];
                            $pom2 = $sluzby->adminGetObyvatelNameBySluzbaID($pom);
                            echo "<tr>";

                            //echo "<td>$sluzba[id]</td>";
                            //echo "<td>$sluzba[obyvatel_id]</td>";
                            echo "<td>$pom2</td>";
                            //echo "<td>$sluzba[typ_vykonu_id]</td>";

                            // toto mi prevede datum do spravneho formatu pro CR
                            echo "<td>".$controller->helperFormatDate($sluzba["datum_od"])."</td>";
                            echo "<td>".$controller->helperFormatDate($sluzba["datum_do"])."</td>";
                            echo "<td>$sluzba[poznamka]</td>";
                            echo "<td>
                                  <a href=\"$url_detail\" class='btn btn-primary btn-sm'><i class=\"icon-layers\"></i> Detail</a>
                                  &nbsp;&nbsp;
                                  <a href=\"$url_update_prepare\" class='btn btn-primary btn-sm'><i class=\"icon-pencil\"></i></a>
                              </td>";

                            echo "</tr>";
                        }

                        echo "</table>";
                        echo "</div>";

                        // stranovani
                        echo "<div class=\"row\">
                       <div class=\"col-md-8 offset-md-2 \">";

                        echo $pagination_html;

                        echo "</div></div>";
                        // konec strankovani
                    }
                    else {
                        var_dump($sluzby_list);
                        echo "<div class=\"col-md-12\">";

                        echo "<div class=\"alert alert-danger fade show\" role=\"alert\">
                                Žádné služby nenalezeny.
                         </div>";

                        echo "</div>";
                    }


                    ?>
                </div>
            </div>
        </div>
    </div>
</div>