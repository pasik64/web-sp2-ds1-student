<?php
/**
 *   Detail služby
 */
?>
<div class="container-fluid">
    <?php
    echo "<h3>Záznamy o vykonání služby s typem plánu $plan_lidsky pacienta $pacient</h3><br/>";
    ?>
    <div class="pull-right">
        <a href="<?php echo $url_sluzba_detail; ?>" class="btn btn-default">Zpět na detail služby</a>
    </div>

    <!-- start seznam zalozek  -->
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="zaznamVykonu-tab" data-toggle="tab" href="#zaznamVykonu" role="tab"
               aria-controls="zaznamVykonu" aria-selected="true">Záznamy</a>
        </li>
    </ul>
    <!-- konec seznam zalozek  -->

    <!-- start panely pro zalozky  -->
    <div class="tab-content" id="myTabContent">

        <!-- start panel ZAZNAM VYKONU -->
        <div class="tab-pane fade show active" id="zaznamVykonu" role="tabpanel" aria-labelledby="zaznamVykonu-tab">
            <div class="container-fluid">

                <div class="pull-right">
                    <a href="<?php echo $url_zaznam_vykonu_add_prepare; ?>">
                        <button class="btn btn-primary btn-sm"><i class="icon-plus"></i>Nový záznam</button>
                    </a>

                </div>
                <br><br>

                <div class="row">
                    <?php
                    // vypis sluzeb
                    if ($zaznamy != null) {

                        echo "<table class='table table-sm table-bordered table-striped table-hover'>";
                        echo "<tr>
                                    
                                    <th>Čas začátek</th>
                                    <th>Čas konec</th>
                                    <th>Poznámka</th>
                                    
                                    <th>Léčivo</th>
                                    <th>Množství ml</th>
                                    <th>Množství mg</th>
                                    <th>Datum vytvoření</th>
                                    <th>&nbsp;</th>
                        </tr>";


                        // pridani indexu "datum" do asoc. pole z tabulky detailu
                        foreach ($zaznamy as $key => $value) {
                            $pom = $value["id"];
                            $pom2 = $sluzby->adminGetZaznamVykonuDetailItemByZaznamID($pom);
                            //$datum = $controller->helperFormatDateAuto($pom2['datum_vytvoreni']);

                            $zaznamy[$key] += ["datum" => $pom2['datum_vytvoreni']];
                            //var_dump($value);
                        }

                        //var_dump($zaznamy);


                        function cmp($a, $b)
                        {
                            if ($a["datum"] == $b["datum"]) {
                                return 0;
                            }
                            return ($a["datum"] < $b["datum"]) ? 1 : -1;
                        }

                        // serazeni od nejnovejsiho po nejstarsi - predpoklad je ze pokud je vic detailu k
                        // jdenomu zaznamu, tak ze datumy budou stejny
                        usort($zaznamy,"cmp");



                        foreach ($zaznamy as $key => $value) {

                            $pom = $value["id"];
                            //$pom2 = $sluzby->adminGetZaznamVykonuDetailItemByZaznamID($pom);
                            //$datum = $controller->helperFormatDateAuto($pom2['datum_vytvoreni']);

                            $pom3 = $sluzby->adminGetZaznamVykonuDetailItemsByZaznamID($pom);
                            //var_dump($pom3);

                            echo "<tr>";

//                            echo "<td>$value[id]</td>";
//                            echo "<td>$value[plan_vykonu_id]</td>";
//                            echo "<td>$value[uzivatel_id]</td>";
                            echo "<td>$value[cas_zacatek]</td>";
                            echo "<td>$value[cas_konec]</td>";
                            echo "<td>$value[poznamka]</td>";



                        // vypsani vsech informaci z tabulky s detailama, ktere je potreba mit po ruce
                            echo "<td>";
                            for ($i = 0; $i < count($pom3); $i++){
                                echo "
                                <div>";
                                    echo $pom3[$i]["lecivo"];
                                echo "</div>";
                                if ($i != count($pom3)-1){
                                    echo "<hr>";
                                }
                            }
                            echo "</td>";
                            echo "<td>";
                            for ($i = 0; $i < count($pom3); $i++){
                                echo "
                                <div>";
                                echo $pom3[$i]["mnozstvi_ml"];
                                echo "</div>";
                                if ($i != count($pom3)-1){
                                    echo "<hr>";
                                }
                            }
                            echo "</td>";
                            echo "<td>";
                            for ($i = 0; $i < count($pom3); $i++){
                                echo "
                                <div>";
                                echo $pom3[$i]["mnozsvi_mg"];
                                echo "</div>";
                                if ($i != count($pom3)-1){
                                    echo "<hr>";
                                }
                            }
                            echo "</td>";
                            echo "<td>";
                            for ($i = 0; $i < count($pom3); $i++){
                                echo "
                                <div>";
                                echo $controller->helperFormatDateAuto($pom3[$i]["datum_vytvoreni"]);
                                echo "</div>";
                                if ($i != count($pom3)-1){
                                    echo "<hr>";
                                }
                            }
                            echo "</td>";
                            echo "<td>
                                            
                                  <form method=\"post\" action='$url_sluzby_podrobnosti_zaznam'>
                                      <button class='btn btn-primary btn-sm' type=\"submit\" name=\"zaznam_detail\" id=\"zaznam_detail\" value=\"$value[id]\"/><i class=\"icon-layers\"></i> Podrobnosti</button><br/>
                                  </form>
                                  
                                  <form method=\"post\" action='$url_zaznam_vykonu_update_prepare'>
                                      <button class='btn btn-primary btn-sm' type=\"submit\" name=\"update\" id=\"update\" value=\"$value[id]\"/><i class=\"icon-pencil\"></i></button><br/>
                                  </form>        
                                  
                              </td>";

                            echo "</tr>";
                        }


                        echo "</table>";
                        echo "</div>";

                        // stranovani
                        echo "<div class=\"row\">
                       <div class=\"col-md-8 offset-md-2 \">";
                    } else {
                        echo
                        "<div>
                            Žádný záznam k dané službě a k danému plánu výkonu neexistuje. Nový záznam můžete přidat pomocí tlačítka 'Nový záznam', které je výše.
                        </div>";
                    }
                    ?>

                </div>

            </div>
        </div>
        <!-- konec panel ZAZNAM  -->


    </div>
</div>
<!-- konec panely pro zalozky  -->


<!-- jen dodatecna navigace pod obsahem -->
<div class="row">
    <div class="col-md-12">
        <div class="pull-right">
            <a href="<?php echo $url_sluzba_detail; ?>" class="btn btn-default">Zpět na detail služby</a>
        </div>
    </div>
</div>
<br/>