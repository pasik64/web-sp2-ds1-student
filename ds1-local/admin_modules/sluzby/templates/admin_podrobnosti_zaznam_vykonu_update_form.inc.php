<div class="container-fluid">

    <form method="post" action="<?php echo $form_submit_url; ?>">
        <input type="hidden" name="action" value="<?php echo $form_action_update_zaznam_vykonu_detail; ?>"/>

        <div class="row">

            <div class="col-md-12">

                <div class="card">
                    <div class="card-header">
                        Editace detailu záznamu výkonu
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php
                            $text_columns = array();
                            //$text_columns["id"] = "Id";
                            //$text_columns["uzivatel_id"] = "uzivatel";
                            $text_columns["nazev"] = "nazev";
                            $text_columns["popis"] = "popis";
                            $text_columns["lecivo"] = "lecivo";
                            $text_columns["mnozstvi_ml"] = "ml";
                            $text_columns["mnozsvi_mg"] = "mnozstvi mg";
                            $text_columns["mnozstvi_text"] = "text mnozstvi";
                            $text_columns["datum_vytvoreni"] = "datum vytvoreni";

                            // definice, ktera pole jsou datumy
                            $datetimes_text_columns_keys = array();
                            $datetimes_text_columns_keys["datum_vytvoreni"] = "datum_vytvoreni";

                            $numbers_columns_keys = array();
                            $numbers_columns_keys["mnozstvi_ml"] = "mnozstvi_ml";
                            $numbers_columns_keys["mnozsvi_mg"] = "mnozsvi_mg";

                            // tridy pro konkretni polozky
                            $classes_for_columns = array();
                            //$classes_for_columns["prijmeni"] = array("tr" => "table-success");


                            // napoveda pro vse
                            $input_help_desc = array();
                            $input_help_desc["mnozstvi_text"] = "mnozstvi leku napsane slovy";

                            // popisy
                            $textarea_columns = array();
//                           $textarea_columns["popis"] = "popis";
                           //$textarea_columns["obsah"] = "Obsah";

                           // viditelne
                           //$viditelne_pom = array(0 => "ne", 1 => "ano");


                            if ($text_columns != null) {

                                echo "<table class='table table-striped table-bordered'>";


                                // zakladni texty vcetne datumu
                                foreach ($text_columns as $key => $value) {

                                    // tridy
                                    $tr_class_pom = "";

                                    if (array_key_exists($key, $classes_for_columns)) {
                                        if (array_key_exists("tr", $classes_for_columns[$key])) {
                                            $tr_class_pom = "class=\"".$classes_for_columns[$key]["tr"]."\"";
                                        }
                                    }
                                    // konec tridy

                                    echo "<tr $tr_class_pom>";
                                    echo "<th class='w-30'>$value</th>";
                                    echo "<td class='w-40'>";

                                    $input_type = "text";
                                    if (in_array($key, $datetimes_text_columns_keys)) {
                                        // je to datum
                                        $input_type = "datetime";
                                    }
                                    if (in_array($key, $numbers_columns_keys)) {
                                        // je to datum
                                        $input_type = "number";
                                    }

                                    echo "<input type=\"$input_type\" class=\"form-control\" name=\"zaznam_vykonu_detail[$key]\" value=\"".$zaznam_vykonu_detail[$key]."\" />";
                                    echo "</td>";
                                    echo "<td class='w-30'>";
                                    // napoveda
                                    if (array_key_exists($key, $input_help_desc)) {
                                        // vypsat napovedu
                                        echo $input_help_desc[$key];
                                    }
                                    echo "</td>";
                                    echo "</tr>";
                                }

                                // prihodit popisy
                                foreach ($textarea_columns as $key => $value) {
                                    echo "<tr>";
                                    echo "<th class='w-25'>$value</th>";
                                    echo "<td class='w-75'>
                                                <textarea class=\"form-control\" name=\"zaznam_vykonu_detail[$key]\" rows='7'>$zaznam_vykonu_detail[$key]</textarea>
                                              </td>";
                                    echo "</tr>";
                                }

                                echo "</table>";
                            }
                            ?>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="pull-left">

                                    <input type="submit" class="btn btn-primary btn-lg" value="Uložit změny" />

                                </div>
                                <div class="pull-right">
                                    <a href="<?php echo $url_sluzba_detail;?>" class="btn btn-default btn-lg">Zpět na detail služby</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div><!-- konec col-6 -->
            <!-- KONEC DETAIL OBYVATELE -->

        </div><!-- konec row-->

    </form>
</div>