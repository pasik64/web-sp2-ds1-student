<?php
/**
 *   Detail obyvatele
 */
?>
<div class="container-fluid">
    <?php
        echo "<h3>Detail obyvatele #$obyvatel_id - $obyvatel[jmeno] $obyvatel[prijmeni] ("
            . $controller->helperFormatDate($obyvatel["datum_narozeni"])
            . ")</h3><br/>";
    ?>
    <div class="pull-right">
        <a href="<?php echo $url_obyvatele_list;?>" class="btn btn-default">Zpět na seznam obyvatel</a>
    </div>

    <!-- start seznam zalozek  -->
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="zaklad-tab" data-toggle="tab" href="#zaklad" role="tab" aria-controls="zaklad" aria-selected="true">Základní údaje</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="ubytovani-tab" data-toggle="tab" href="#ubytovani" role="tab" aria-controls="ubytovani" aria-selected="false">Ubytování</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="ostatni-tab" data-toggle="tab" href="#ostatni" role="tab" aria-controls="ostatni" aria-selected="false">Ostatní</a>
        </li>
    </ul>
    <!-- konec seznam zalozek  -->

    <!-- start panely pro zalozky  -->
    <div class="tab-content" id="myTabContent">

        <!-- start panel ZAKLAD  -->
        <div class="tab-pane fade show active" id="zaklad" role="tabpanel" aria-labelledby="zaklad-tab">
            <div class="container-fluid">
            <div class="row">
                                <?php
                                $text_columns = array();
                                $text_columns["jmeno"] = "Jméno";
                                $text_columns["prijmeni"] = "Příjmení";
                                $text_columns["rodne_prijmeni"] = "Rodné příjmení";

                                $text_columns["datum_narozeni"] = "Datum narození";
                                $text_columns["tituly_pred"] = "Tituly před";
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


                                if ($text_columns != null) {

                                    echo "<table class='table table-sm table-striped table-bordered' style='max-width: 500px;'>";

                                    // zakladni texty vcetne datumu
                                    foreach ($text_columns as $key => $value) {

                                        echo "<tr>";
                                        echo "<th class='w-20'>$value</th>";
                                        echo "<td class='w-30'>";

                                        // je to text nebo datum
                                        if (in_array($key, $dates_text_columns_keys)) {
                                            // datum
                                            echo $controller->helperFormatDate($obyvatel[$key]);
                                        } else {
                                            // text
                                            echo "$obyvatel[$key]";
                                        }

                                        echo "</td>";
                                        echo "</tr>";
                                    }

                                    echo "</table>";
                                }
                                ?>
            </div>

            <div class="row">
                                <div class="col-md-12">
                                    <div class="pull-left">
                                        <a href="<?php echo $url_obyvatel_update;?>" class="btn btn-primary btn-sm"><i class="icon-pencil"></i> Upravit</a>
                                    </div>
                                </div>
            </div>
            </div>
        </div>
        <!-- konec panel ZAKLAD  -->

        <!-- start panel UBYTOVANI  -->
        <div class="tab-pane fade" id="ubytovani" role="tabpanel" aria-labelledby="ubytovani-tab">
            <h2>Ubytování</h2>

            <?php
                //printr($obyvatel_na_pokojich);
                if ($obyvatel_na_pokojich != null){
                    echo "<table class='table table-sm table-striped table-bordered' style='max-width: 500px;'>";
                        echo "<tr><th>Datum od</th><th>Datum do</th><th>Název pokoje</th><th>Poschodí</th><th>Pokoj ID (#)</th></tr>";

                    foreach ($obyvatel_na_pokojich as $ubytovani) {

                        // url na detail pokoje
                        $url_pokoj_detail = $controller->makeUrlByRoute($pokoje_route_name,
                                array("action" => "pokoj_detail_show", "pokoj_id" => $ubytovani["pokoj_id"])
                        );

                        echo "<tr>";
                            // toto bude navic datetime
                            echo "<td>".$controller->helperFormatDateAuto($ubytovani["datum_od"])."</td>";
                            echo "<td>".$controller->helperFormatDateAuto($ubytovani["datum_do"])."</td>";
                            echo "<td><a href='$url_pokoj_detail'>$ubytovani[pokoj_nazev]</a></td>";
                            echo "<td>$ubytovani[pokoj_poschodi]</td>";
                            echo "<td>$ubytovani[pokoj_id]</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                }

            ?>

            <div>
                <!-- odkaz pro pridani obyvatele do pokoje -->
                <a href="<?php echo $url_obyvatele_na_pokoje_add_prepare;?>" class="btn btn-primary btn-sm"><i class="icon-pencil"></i> Změnit ubytování</a>
            </div>
        </div>
        <!-- konec panel UBYTOVANI   -->

        <!-- start panel OSTATNI  -->
        <div class="tab-pane fade" id="ostatni" role="tabpanel" aria-labelledby="ostatni-tab">Záložka ostatní</div>
        <!-- konec panel OSTATNI  -->
    </div>
    <!-- konec panely pro zalozky  -->


    <!-- jen dodatecna navigace pod obsahem -->
    <div class="row">
        <div class="col-md-12">
            <div class="pull-right">
                <a href="<?php echo $url_obyvatele_list;?>" class="btn btn-default">Zpět na seznam obyvatel</a>
            </div>
        </div>
    </div>

</div>

<br/>
