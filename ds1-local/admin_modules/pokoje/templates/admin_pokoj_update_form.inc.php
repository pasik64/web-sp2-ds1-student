<div class="container-fluid">

    <form method="post" action="<?php echo $form_submit_url; ?>">
        <input type="hidden" name="action" value="<?php echo $form_action_update; ?>"/>
        <input type="hidden" name="pokoj_id" value="<?php echo $pokoj_id; ?>" />

        <div class="row">

            <!-- START DETAIL OBYVATELE PRO UPDATE -->
            <div class="col-md-12">

                <div class="card">
                    <div class="card-header">
                        Editace pokoje #<?php echo $pokoj_id;?>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php
                            $text_columns = array();
                            $text_columns["nazev"] = "Název";
                            $text_columns["poschodi"] = "Poschodí";
                            $text_columns["socialni_zarizeni"] = "Sociální zařízení";
                            $text_columns["kapacita_osob"] = "Kapacita (počet osob)";


                            // definice, ktera pole jsou datumy
                            $dates_text_columns_keys = array();

                            // definice poli yes no
                            $yesno_text_columns_keys = array();
                            $yesno_text_columns_keys[] = "socialni_zarizeni";

                            // tridy pro konkretni polozky
                            $classes_for_columns = array();
                            //$classes_for_columns["prijmeni"] = array("tr" => "table-success");


                            // napoveda pro vse
                            $input_help_desc = array();
                            //$input_help_desc["rodne_cislo"] = "Rodné číslo ukládáme textově. Klidně s lomítkem.";
                            //$input_help_desc["pojistovna_zkratka"] = "Např. VZP. Zkratku je potřeba ukládat stále stejně.";

                            // popisy
                            $textarea_columns = array();
                            $textarea_columns["popis"] = "Popis";

                            // viditelne
                            $viditelne_pom = array(0 => "ne", 1 => "ano");


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

                                    if (in_array($key, $yesno_text_columns_keys)) {
                                        // je to datum
                                        $input_type = "yes-no";
                                    }

                                    if ($input_type == "text" || $input_type == "date") {
                                        echo "<input type=\"$input_type\" class=\"form-control\" name=\"pokoj[$key]\" value=\"".$pokoj[$key]."\" />";
                                    }
                                    else if ($input_type == "yes-no") {
                                        // select
                                        echo "<select name='pokoj[$key]' class=\"form-control col-md-3\">";

                                            foreach ($viditelne_pom as $viditelne_key => $viditelne_value) {

                                                $selected_pom = "";
                                                if ($viditelne_key == $pokoj[$key]) {
                                                    $selected_pom = "selected = \"selected\"";
                                                }

                                                echo "<option value=\"$viditelne_key\" $selected_pom>$viditelne_value</option>";
                                            }
                                        echo "</select>";
                                    }


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
                                                <textarea class=\"form-control\" name=\"pokoj[$key]\" rows='7'>$pokoj[$key]</textarea>
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
                                    <a href="<?php echo $url_pokoje_list;?>" class="btn btn-default btn-lg">Zpět na seznam pokojů</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div><!-- konec col-6 -->
            <!-- KONEC DETAIL OBYVATELE -->

        </div><!-- konec row-->

        <?php
        echo "Pro testovani:";
        printr($pokoj);
        ?>

    </form>
</div>
