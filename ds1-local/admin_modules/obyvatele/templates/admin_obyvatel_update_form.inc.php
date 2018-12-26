<div class="container-fluid">

    <form method="post" action="<?php echo $form_submit_url; ?>">
        <input type="hidden" name="action" value="<?php echo $form_action_update_obyvatel; ?>"/>
        <input type="hidden" name="obyvatel_id" value="<?php echo $obyvatel_id; ?>" />

        <div class="row">

            <!-- START DETAIL OBYVATELE PRO UPDATE -->
            <div class="col-md-12">

                <div class="card">
                    <div class="card-header">
                        Editace obyvatele #<?php echo $obyvatel_id;?>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php
                            $text_columns = array();
                            $text_columns["jmeno"] = "Jméno";
                            $text_columns["prijmeni"] = "Příjmení";
                            $text_columns["rodne_prijmeni"] = "Rodné příjmení";

                            $text_columns["datum_narozeni"] = "Datum narození";
                            $text_columns["tituly_pred"] = "Tituly před";
                            $text_columns["tituly_za"] = "Tituly za";
                            $text_columns["rodne_cislo"] = "Rodné číslo";
                            $text_columns["misto_narozeni"] = "Místo narození";

                            $text_columns["pojistovna_zkratka"] = "Pojišťovna zkratka";
                            $text_columns["cislo_pojistence"] = "Číslo pojištěnce";

                            $text_columns["adresa_ulice"] = "Adresa - ulice";
                            $text_columns["adresa_cp"] = "Adresa - čp";
                            $text_columns["adresa_mesto"] = "Adresa - město";
                            $text_columns["op"] = "OP";
                            $text_columns["op_platnost_do"] = "OP platnost do";

                            // definice, ktera pole jsou datumy
                            $dates_text_columns_keys = array();
                            $dates_text_columns_keys[] = "datum_narozeni";
                            $dates_text_columns_keys[] = "op_platnost_do";

                            // tridy pro konkretni polozky
                            $classes_for_columns = array();
                            //$classes_for_columns["prijmeni"] = array("tr" => "table-success");


                            // napoveda pro vse
                            $input_help_desc = array();
                            $input_help_desc["rodne_cislo"] = "Rodné číslo ukládáme textově. Klidně s lomítkem.";
                            $input_help_desc["pojistovna_zkratka"] = "Např. VZP. Zkratku je potřeba ukládat stále stejně.";

                            // popisy
                            $textarea_columns = array();
                            /*
                           $textarea_columns["minipopis"] = "Mini popis";
                           $textarea_columns["popis"] = "Popis";
                           $textarea_columns["obsah"] = "Obsah";

                           // viditelne
                           $viditelne_pom = array(0 => "ne", 1 => "ano");
                            */

                            if ($text_columns != null) {

                                echo "<table class='table table-striped table-bordered'>";

                                /*
                                // viditelne
                                echo "<tr><th>Viditelné na webu</th><td><select name='obyvatel[viditelne]' class=\"form-control col-md-3\">";
                                foreach ($viditelne_pom as $key => $value) {

                                    $selected_pom = "";
                                    if ($key == $obyvatel["viditelne"]) {
                                        $selected_pom = "selected = \"selected\"";
                                    }

                                    echo "<option value=\"$key\" $selected_pom>$value</option>";
                                }
                                echo "</select></td></tr>";
                                */


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

                                                echo "<input type=\"$input_type\" class=\"form-control\" name=\"obyvatel[$key]\" value=\"".$obyvatel[$key]."\" />";
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
                                                <textarea class=\"form-control\" name=\"obyvatel[$key]\" rows='7'>$obyvatel[$key]</textarea>
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
                                    <a href="<?php echo $url_obyvatele_list;?>" class="btn btn-default btn-lg">Zpět na seznam obyvatel</a>
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
            printr($obyvatel);
        ?>

    </form>
</div>
