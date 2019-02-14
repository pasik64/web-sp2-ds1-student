<div class="container-fluid">

    <form method="post" action="<?php echo $form_submit_url; ?>">
        <input type="hidden" name="action" value="<?php echo $form_action_update_typ_vykonu; ?>"/>
        <input type="hidden" name="typ_id" value="<?php echo $typ_id; ?>" />

        <div class="row">

            <!-- START DETAIL SLUZBY PRO UPDATE -->
            <div class="col-md-12">

                <div class="card">
                    <div class="card-header">
                        Editace typu výkonu #<?php echo $typ_id;?>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php
                            $text_columns = array();
                            //$text_columns["id"] = "Id";
                            $text_columns["nazev"] = "Název";
                            $text_columns["popis"] = "Popis";

                            $text_columns["role_id"] = "Role";

                            // definice, ktera pole jsou datumy
                            $dates_text_columns_keys = array();

                            // tridy pro konkretni polozky
                            $classes_for_columns = array();
                            //$classes_for_columns["prijmeni"] = array("tr" => "table-success");


                            // napoveda pro vse
                            $input_help_desc = array();
                            $input_help_desc["nazev"] = "Název typu výkonu - např. podání léků";
                            $input_help_desc["popis"] = "Stručný popis typu";

                            // popisy
                            $textarea_columns = array();

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
                                    if (in_array($key, $dates_text_columns_keys)) {
                                        // je to datum
                                        $input_type = "date";
                                    }

                                    echo "<input type=\"$input_type\" class=\"form-control\" name=\"typ_vykonu[$key]\" value=\"".$typ_vykonu[$key]."\" />";
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
                                                <textarea class=\"form-control\" name=\"typ_vykonu[$key]\" rows='7'>$typ_vykonu[$key]</textarea>
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
                                    <i class="icon-info"></i>
                                    Uloží změny pro všechny pacienty s daným typem výkonu.

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