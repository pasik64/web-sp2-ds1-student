<?php
/**
 *   Detail obyvatele $obyvatel_id
 */

    // definice, ktera pole jsou datumy
    $dates_text_columns_keys = array();
    $dates_text_columns_keys[] = "datum_narozeni";
    $dates_text_columns_keys[] = "op_platnost_do";

    // slozit cele jmeno obyvatele s tituly
    $obyvatel_cele_jmeno_tituly = "";

    if (trim($obyvatel["tituly_pred"]) != "") $obyvatel_cele_jmeno_tituly = trim($obyvatel["tituly_pred"])." ";
    $obyvatel_cele_jmeno_tituly .= trim($obyvatel["jmeno"])." ";
    $obyvatel_cele_jmeno_tituly .= trim($obyvatel["prijmeni"]);
    if (trim($obyvatel["tituly_za"]) != "") $obyvatel_cele_jmeno_tituly .= ", ".trim($obyvatel["tituly_za"]); // tituly za se oddeluji carkou od jmena

?>
<div class="container-fluid">

    <div class="row">
        <div class="col-md-3">
            <?php
            echo "<h3>$obyvatel[jmeno] $obyvatel[prijmeni]<br/> ("
                . $controller->helperFormatDate($obyvatel["datum_narozeni"])
                . ")</h3><br/>";
            ?>
        </div>
        <div class="col-md-1">
            <?php
                // fotka obyvatele - TODO melo by prijit z modelu
                $image_file_path = $base_url . "fotogalerie/obyvatele/3_test_dostal.jpg";
                echo "<img src=\"$image_file_path\" class=\"img-fluid\" alt=\"$obyvatel_cele_jmeno_tituly\" style='max-width: 100px;'>";
            ?>
        </div>

    </div>

    <div class="pull-right">
        <a href="<?php echo $url_obyvatele_list;?>" class="btn btn-default">Zpět na seznam obyvatel</a>
    </div>

    <!-- start seznam zalozek  -->
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <!-- class stretched-link roztahne link na celeho rodice, aby byl klikatelny -->
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

        <!-- JS podpora pro klikatelny radek nez najdu lepsi reseni - zabalovani a rozbalovani zalozek mozna -->
        <script>
            jQuery(document).ready(function($) {
                $(".clickable-row").click(function() {
                window.location = $(this).data("href");
                });
            });
        </script>


        <!-- start panel ZAKLAD  -->
        <div class="tab-pane fade show active" id="zaklad" role="tabpanel" aria-labelledby="zaklad-tab">
            <div class="container-fluid">
            <div class="row">
                <div class="col-md-10">

                    <?php
                        // automaticke acordiony k konfigurace sablony
                        if ($template_config != null) {
                            if (@$template_config["accordions_list1"] != null)
                                foreach ($template_config["accordions_list1"] as $accordion1_id => $accordion1_value) {

                                    // slozit accordion header text
                                    // $accordion1_value[value] = [prijmeni] [jmeno]
                                    $accordion_header_text = $controller->helperStringReplaceMarksWithDbValues($accordion1_value["value"], $obyvatel, array("keys_dates" => $dates_text_columns_keys));

                                    echo "<div id='$accordion1_id' role='tablist' class='obyvatele_detail_accordion1'>
                                            <div class='card mb-0'>
                                                <!-- klikatelny cely radek - jen pridat data-toggle=\"collapse\" data-target=\"#collapse_$accordion1_id\" -->
                                                <div class='card-header klikatelne' role='tab' data-toggle=\"collapse\" data-target=\"#collapse_$accordion1_id\">
                                                     <h5 class='mb-0' >
                                                        <!-- tento odkaz tady potreuji jednak pro jistotu a jednak kuli css - pridani sipky na konec radku-->
                                                        <a data-toggle=\"collapse\" href=\"#collapse_$accordion1_id\">
                                                                $accordion1_value[title] &nbsp; <div class=\"accordion_header_value only-collapsed\">$accordion_header_text</div>
                                                        </a>
                                                    </h5>
                                                </div><!-- card-header -->";

                                    // start card body
                                    echo "<div class='collapse show' id='collapse_$accordion1_id' role='tabpanel' data-parent='#$accordion1_id'>
                                                <div class='card-body'>";

                                    //printr($accordion1_value);

                                    // vnorene acordiony
                                    if (@$accordion1_value["subaccordions"] != null) {
                                        foreach ($accordion1_value["subaccordions"] as $accordion2_id => $accordion2_value) {

                                            // **************************************************************************
                                            // START VNORENY ACORDION

                                            // slozit popisek vnoreneho acordionu
                                            $accordion_header_text2 =  $controller->helperStringReplaceMarksWithDbValues($accordion2_value["value"], $obyvatel, array("keys_dates" => $dates_text_columns_keys));

                                            echo "<div id='$accordion2_id' role='tablist' class='obyvatele_detail_accordion2'>
                                            <div class='card mb-0'>
                                                <div class='card-header klikatelne' role='tab' data-toggle=\"collapse\" data-target=\"#collapse_$accordion2_id\">
                                                    <h5 class='mb-0'>
                                                        <!-- prihodi se class collapsed, pokud je to zabalene-->
                                                        <a data-toggle=\"collapse\" href=\"#collapse_$accordion2_id\" class='collapsed'>
                                                                $accordion2_value[title] &nbsp; <div class=\"accordion_header_value only-collapsed\">$accordion_header_text2</div>
                                                        </a>
                                                    </h5>
                                                </div><!-- card-header -->";

                                                // start card body - pridanim show se zobrazi
                                                echo "<div class='collapse' id='collapse_$accordion2_id' role='tabpanel' data-parent='#$accordion2_id'>
                                                    <div class='card-body'>";

                                                        if ($accordion2_value["fields"] != null)
                                                        {
                                                            echo "<table class='table table-sm'>";

                                                            foreach ($accordion2_value["fields"] as $field_key => $field_title) {

                                                                // fixace datumu
                                                                if (in_array($field_key, $dates_text_columns_keys)) {
                                                                    // datum
                                                                    $field_text_db = $controller->helperFormatDate($obyvatel[$field_key]);
                                                                } else {
                                                                    // text
                                                                    $field_text_db = $obyvatel[$field_key];
                                                                }

                                                                echo "<tr><th class='w-50'>$field_title</th><td>$field_text_db</td></tr>";
                                                            }

                                                            echo "</table>";
                                                        }


                                                    echo "</div></div>"; // konec card-body a .collapse
                                                    // konec card body

                                                    echo "</div>"; // konec .card
                                                    echo "</div><!-- #$accordion1_id -->"; // konec accordion
                                            // KONEC VNORENY ACORDION
                                            // **************************************************************************
                                        }
                                    }


                                    echo "</div></div>"; // konec card-body a .collapse
                                    // konec card body

                                    echo "</div>"; // konec .card
                                    echo "</div><!-- #$accordion1_id -->"; // konec accordion

                                    echo "<br/>"; // mezera mezi accordiony
                                }
                        }
                    ?>


                    <!-- POBYT  START  -->
                    <div id="accordion_pobyt" role="tablist" class='obyvatele_detail_accordion1'>
                        <div class="card mb-0">
                            <div class="card-header klikatelne" id="pobyt_headingOne" role="tab" data-toggle="collapse" data-target="#pobyt_collapseOne">
                                <h5 class="mb-0">

                                    <?php
                                        // hledat aktualni pobyt - maximalni datum od TEMP, FIXME pres nejakou lepsi metodu
                                        $aktualni_ubytovani_datum_od = "";
                                        $aktualni_ubytovani_pokoj = "";

                                        if ($obyvatel_na_pokojich != null)
                                        foreach ($obyvatel_na_pokojich as $ubytovani) {
                                            if ($ubytovani["datum_od"] > $aktualni_ubytovani_datum_od) {
                                                $aktualni_ubytovani_datum_od = $ubytovani["datum_od"];
                                                $aktualni_ubytovani_pokoj = $ubytovani["pokoj_nazev"];
                                            }
                                        }

                                       // echo "<td>".$controller->helperFormatDateAuto($ubytovani["datum_od"])."</td>";
                                    ?>

                                    <a data-toggle="collapse" href="#pobyt_collapseOne" class="collapsed">
                                        Pobyt
                                        <div class="accordion_header_value only-collapsed">
                                            <?php echo $aktualni_ubytovani_pokoj." <small>(od ".$controller->helperFormatDateAuto($aktualni_ubytovani_datum_od).")</small>"; ?>
                                        </div>
                                    </a>
                                </h5>
                            </div>
                            <div class="collapse" id="pobyt_collapseOne" role="tabpanel" data-parent="#accordion_pobyt" style="">
                                <div class="card-body">
                                    <?php
                                    //printr($obyvatel_na_pokojich);
                                    if ($obyvatel_na_pokojich != null){
                                        echo "<table class='table table-sm table-striped table-bordered' style='max-width: 500px;'>";
                                        echo "<tr><th>Datum od</th><th>Datum do</th><th>Název pokoje</th><th>Poschodí</th></tr>";

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
                            </div>
                        </div>
                    </div>
                    <!-- POBYT  KONEC  -->

                    <br/>


                    <div id="accordion_klicovy_zamestnanec" role="tablist"  class='obyvatele_detail_accordion1'>
                        <div class="card mb-0">
                            <!-- je to klikatelne uz primo pres ten header = zabalit a rozbalit -->
                            <div class="card-header klikatelne" id="klicovy_zamestnanec_headingOne" role="tab" data-toggle="collapse" href="#klicovy_collapseOne">
                                <h5 class="mb-0">
                                    <a data-toggle="collapse" href="#klicovy_collapseOne" class="collapsed" style="color: #000; font-size: 16px;">
                                        Klíčový zaměstnanec
                                    </a>
                                </h5>
                            </div>
                            <div class="collapse" id="klicovy_collapseOne" role="tabpanel" data-parent="#accordion_klicovy_zamestnanec" style="">
                                <div class="card-body">
                                    <form>
                                    <table class='table table-sm table-striped table-bordered' style='max-width: 500px;'>
                                        <?php
                                        echo "<tr><td class='w-20'>Jan Novák (ID: 5)</td><td class='w-30'>1.1.2018 - 10.1.2019</td>
                                                    <td><a href='#' class='btn btn-primary btn-sm'><i class=\"icon-pencil\"></i></a> &nbsp;
                                                        <a href='#' class='btn btn-danger btn-sm'><i class=\"icon-trash\"></i></a></td>                                                
                                                    </tr>";
                                        ?>
                                    </table>

                                    <strong>TODO tady tlačítko pro zobrazení následujícího formuláře nebo rovnou zobrazit?</strong>
                                    <h4>Přidat zaměstnance</h4>
                                    <table class="table table-bordered table-striped">
                                        <tr><td>Zaměstnanec:</td><td><select class="form-control"><option>Martin Dostal (3)</option></select></td></tr>
                                        <tr><td>od:</td>
                                            <td><input type="date" class="form-control" size="6"></td></tr>
                                        <tr><td>do</td><td><input type="date" class="form-control" size="6"></td></tr>
                                        <tr><td>&nbsp;</td><td><input type="submit" value="Přidat" class="btn btn-primary btn-sm"></td></tr>
                                    </table>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <br/><br/><br/>

    <?php
        // konfigurace sablony
        //echo "<h2>Konfigurace výpisu v .jsonu</h2>";
        //printr($template_config);

        /*
        // AUTOMATICKY VYPIS VSEHO pro testovani
                                // pro tabulkovy vypis
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
                                        echo "<th class='w-20'>$value [$key]</th>";
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
            */

    ?>

                </div>

                <!-- SLOUPEC VLEVO - napr pro tlacitka -->
                <div class="col-md-2">
                            <!-- TLACITKA -->
                            <div class="row">
                                <div class="col-md-12">
                                        <a href="<?php echo $url_obyvatel_update;?>" class="btn btn-primary"><i class="icon-pencil"></i> Upravit</a>
                                </div>
                            </div>
                </div>
            </div><!-- konec row -->

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
                        echo "<tr><th>Datum od</th><th>Datum do</th><th>Název pokoje</th><th>Poschodí</th></tr>";

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
