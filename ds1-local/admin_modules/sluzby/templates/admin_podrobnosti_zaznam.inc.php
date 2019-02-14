<?php
/**
 *   Detail služby
 */
?>
<div class="container-fluid">
    <?php
    echo "<h3>Podrobnosti záznamu vykonání služby u pacienta $pacient</h3><br/>";
    ?>
    <div class="pull-right">
        <a href="<?php echo $url_sluzba_detail;?>" class="btn btn-default">Zpět na detail služby</a>
    </div>

    <!-- start seznam zalozek  -->
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="podrobnosti-tab" data-toggle="tab" href="#podrobnosti" role="tab" aria-controls="podrobnosti" aria-selected="true">Podrobnosti</a>
        </li>
    </ul>
    <!-- konec seznam zalozek  -->

    <!-- start panely pro zalozky  -->
    <div class="tab-content" id="myTabContent">

        <!-- start panel ZAZNAM VYKONU  -->
        <div class="tab-pane fade show active" id="podrobnosti" role="tabpanel" aria-labelledby="podrobnosti-tab">
            <div class="container-fluid">

                <div class="pull-right">
                    <a href="<?php echo $url_podrobnosti_zaznam_add_prepare; ?>">
                        <button class="btn btn-primary btn-sm"><i class="icon-plus"></i>Nový detail záznamu</button>
                    </a>

                </div>
                <br><br>

                <div class="row">

                    <?php

                    /*$sluzba_podrobnosti = array();
//                    $sluzba_podrobnosti["id"] = "Id";
//                    $sluzba_podrobnosti["zaznam_vykonu_id"] = "zaznam vykonu id";
                    $sluzba_podrobnosti["uzivatel_id"] = "Uživatel";
                    $sluzba_podrobnosti["nazev"] = "Název";
                    $sluzba_podrobnosti["popis"] = "Popis";
                    $sluzba_podrobnosti["lecivo"] = "Léčivo";
                    $sluzba_podrobnosti["mnozstvi_ml"] = "Množství ml";
                    $sluzba_podrobnosti["mnozsvi_mg"] = "Množství mg"; //preklep v db
                    $sluzba_podrobnosti["mnozstvi_text"] = "Množství text";
                    $sluzba_podrobnosti["datum_vytvoreni"] = "Datum vytvoření";


                    if ($sluzba_podrobnosti != null && $podrobnosti != null) {

                        echo "<table class='table table-sm table-striped table-bordered' style='max-width: 500px;'>";

                        $dates_text_columns_keys = array();
                        $dates_text_columns_keys["datum_vytvoreni"] = "datum_vytvoreni";

                        // zakladni texty vcetne datumu
                        foreach ($sluzba_podrobnosti as $key => $value) {

                            echo "<tr>";
                            echo "<th class='w-20'>$value</th>";
                            echo "<td class='w-30'>";

                            // je to text nebo datum
                            if (in_array($key, $dates_text_columns_keys)) {
                                // datum
                                echo $controller->helperFormatDateAuto($podrobnosti[$key]);
                            }
                            else if($key == "uzivatel_id"){
                                echo $uzivatel_jmeno;
                            }
                            else {
                                // text
                                echo "$podrobnosti[$key]";
                            }

                            echo "</td>";
                            echo "</tr>";
                        }

                        echo "</table>";*/






















                    if ($podrobnosti != null) {

                        echo "<table class='table table-sm table-bordered table-striped table-hover'>";
                        echo "<tr>
                                    
                                    
                                    <th>Uživatel</th>
                                    <th>Název</th>
                                    <th>Popis</th>
                                    <th>Léčivo</th>
                                    <th>Množství ml</th>
                                    <th>Množství mg</th>
                                    <th>Množství text</th>
                                    <th>Datum vytvoření</th>
                                    <th>&nbsp;</th>
                        </tr>";

                        //var_dump($podrobnosti);
                        foreach (/*$i = 0; $i < count($podrobnosti); $i++*/$podrobnosti as $key => $value) {
                            /*echo "key: ".$key;
                            echo "value: ".$value;*/

                            echo "<tr>";

//                            echo "<td>$value[id]</td>";
//                            echo "<td>$value[zaznam_vykonu_id]</td>";
                            echo "<td>"; echo $sluzby->adminGetUzivatelItemByID($value["uzivatel_id"]); echo"</td>";
                            echo "<td>$value[nazev]</td>";
                            echo "<td>$value[popis]</td>";
                            echo "<td>$value[lecivo]</td>";
                            echo "<td>$value[mnozstvi_ml]</td>";
                            echo "<td>$value[mnozsvi_mg]</td>";
                            echo "<td>$value[mnozstvi_text]</td>";
                            echo "<td>"; echo $controller->helperFormatDateAuto($value["datum_vytvoreni"]); echo"</td>";

                            echo "<td>
                                  
                                  <form method=\"post\" action=$url_podrobnosti_zaznam_update_prepare>
                                      <button class='btn btn-primary btn-sm' type=\"submit\" name=\"detail_zaznamu_upravit\" id=\"detail_zaznamu_upravit\" value=\"$value[id]\"/><i class=\"icon-pencil\"></i> Upravit</button><br/>
                                  </form>

                              </td>";

                            echo "</tr>";
                        }


                        echo "</table>";
                        echo "</div>";






                        ?>
<!--                        Pokud existuje, muzeme upravit -->
                        <!--<div class="row">
                            <div class="col-md-12">
                                <div class="pull-left">
                                    <a class="btn" href="<?php /*echo $url_podrobnosti_zaznam_update_prepare;*/?>" >Upravit</a>
                                </div>
                            </div>
                        </div>-->

                        <?php
                    }
                    else{
//                        pokud neexistuje, muzeme pridat
                        ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="pull-left">
                                    Detail daného záznamu není vytvořený.
                                    <a class="btn" href="<?php echo $url_podrobnosti_zaznam_add_prepare;?>">Přidat</a>
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>
        <!-- konec panel ZAZNAM VYKONU  -->
        </div>

    <!-- konec panely pro zalozky  -->


    <!-- jen dodatecna navigace pod obsahem -->
    <div class="row">
        <div class="col-md-12">
            <div class="pull-right">
                <a href="<?php echo $url_sluzba_detail;?>" class="btn btn-default">Zpět na detail služby</a>
            </div>
        </div>
    </div>

</div>
<br/>