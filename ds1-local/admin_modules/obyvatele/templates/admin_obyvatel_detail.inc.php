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


        // slozit cele jmeno obyvatele s tituly
        $obyvatel_cele_jmeno_tituly = "";

        if (trim($obyvatel["tituly_pred"]) != "") $obyvatel_cele_jmeno_tituly = trim($obyvatel["tituly_pred"])." ";
        $obyvatel_cele_jmeno_tituly .= trim($obyvatel["jmeno"])." ";
        $obyvatel_cele_jmeno_tituly .= trim($obyvatel["prijmeni"]);
        if (trim($obyvatel["tituly_za"]) != "") $obyvatel_cele_jmeno_tituly .= ", ".trim($obyvatel["tituly_za"]); // tituly za se oddeluji carkou od jmena
    ?>
    <div class="pull-right">
        <a href="<?php echo $url_obyvatele_list;?>" class="btn btn-default">Zpět na seznam obyvatel</a>
    </div>

    <!-- start seznam zalozek  -->
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="zaklad-tab" data-toggle="tab" href="#zaklad" role="tab" aria-controls="zaklad" aria-selected="true">Základní údaje (v1)</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="zaklad2-tab" data-toggle="tab" href="#zaklad2" role="tab" aria-controls="zaklad2" aria-selected="true">Základní údaje (v2)</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="zaklad3-tab" data-toggle="tab" href="#zaklad3" role="tab" aria-controls="zaklad3" aria-selected="true">Základní údaje (v3)</a>
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
                <div class="col-md-8">


                    <div id="accordion" role="tablist" style='max-width: 550px;'>
                        <div class="card mb-0">
                            <div class="card-header" id="headingOne" role="tab">
                                <h5 class="mb-0">
                                    <style>
                                        #headingOne a:before {
                                            content: "\f107";

                                            font-family: "FontAwesome";
                                            font-weight: 900;
                                            width: 55px;
                                            height: 100%;
                                            text-align: center;
                                            line-height: 50px;
                                            //border-left: 2px solid #D11149;
                                            position: absolute;
                                            top: 0;
                                            right: 0;
                                        }

                                        #headingOne a.collapsed:before {
                                            content: "\f106";

                                    </style>

                                    <a data-toggle="collapse" href="#collapseOne" onclick='console.log($("#collapseOne").hasClass("show"));' aria-expanded="true" aria-controls="collapseOne" style="color: #000; font-size: 16px;">
                                        Základní údaje &nbsp;<span ng-hide='$("#collapseOne").hasClass("show")' class="pull-right" style="padding-right: 50px; color: gray; font-size: 14px;"><?php echo "$obyvatel[cislo_pojistence] ($obyvatel[pojistovna_zkratka])"; ?></span>
                                    </a>
                                </h5>
                            </div>
                            <div class="collapse show" id="collapseOne" role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion" style="">
                                <div class="card-body">


                                    <div id="accordion_zakladni2" role="tablist" style='max-width: 550px;'>
                                        <div class="card mb-0">
                                            <div class="card-header" id="klicovy_zamestnanec_headingOne" role="tab">
                                                <h5 class="mb-0">
                                                    <a data-toggle="collapse" href="#zakladni2_body1" class="collapsed" style="color: #000; font-size: 16px;">
                                                        Identifikace &nbsp;<span class="pull-right" style="color: gray; font-size: 14px;"><?php echo "$obyvatel[rodne_cislo] (RČ)"; ?></span>
                                                    </a>
                                                </h5>
                                            </div>
                                            <div class="collapse" id="zakladni2_body1" role="tabpanel" data-parent="#accordion_zakladni2" style="">
                                                <div class="card-body">
                                                    <table class='table table-sm table-striped table-bordered' style='max-width: 500px;'>
                                                        <?php
                                                        echo "<tr><th class='w-20'>Rodné číslo</th><td class='w-30'>$obyvatel[rodne_cislo]</td></tr>";
                                                        echo "<tr><th class='w-20'>Adresa</th><td class='w-30'>$obyvatel[adresa_ulice] $obyvatel[adresa_cp], $obyvatel[adresa_mesto]</td></tr>";
                                                        echo "<tr><th class='w-20'>Číslo pojištěnce (pojišťovna)</th><td class='w-30'>$obyvatel[cislo_pojistence] ($obyvatel[pojistovna_zkratka])</td></tr>";
                                                        ?>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card mb-0">
                                            <div class="card-header" id="klicovy_zamestnanec_headingTwo" role="tab">
                                                <h5 class="mb-0">
                                                    <a data-toggle="collapse" href="#zakladni2_body2" class="collapsed" style="color: #000; font-size: 16px;">
                                                        Adresa &nbsp;<span class="pull-right" style="color: gray; font-size: 14px;"><?php echo "$obyvatel[adresa_ulice] $obyvatel[adresa_cp], $obyvatel[adresa_mesto]"; ?></span>
                                                    </a>
                                                </h5>
                                            </div>
                                            <div class="collapse" id="zakladni2_body2" role="tabpanel" data-parent="#accordion_zakladni2" style="">
                                                <div class="card-body">
                                                    <table class='table table-sm table-striped table-bordered' style='max-width: 500px;'>
                                                        <?php
                                                        echo "<tr><th class='w-20'>Adresa - ulice</th><td class='w-30'>$obyvatel[adresa_ulice]</td></tr>";
                                                        echo "<tr><th class='w-20'>Adresa - čp</th><td class='w-30'>$obyvatel[adresa_cp]</td></tr>";
                                                        echo "<tr><th class='w-20'>Adresa - město</th><td class='w-30'>$obyvatel[adresa_mesto]</td></tr>";
                                                        ?>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                        <!--
                        <div class="card mb-0">
                            <div class="card-header" id="headingTwo" role="tab">
                            <h5 class="mb-0">
                            <a class="collapsed" data-toggle="collapse" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">Collapsible Group Item #2</a>
                            </h5>
                            </div>
                            <div class="collapse" id="collapseTwo" role="tabpanel" aria-labelledby="headingTwo" data-parent="#accordion" style="">
                            <div class="card-body">Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt
                            aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat
                            craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.</div>
                            </div>
                        </div>
                        <div class="card mb-0">
                                <div class="card-header" id="headingThree" role="tab">
                                <h5 class="mb-0">
                                <a class="collapsed" data-toggle="collapse" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">Collapsible Group Item #3</a>
                                </h5>
                                </div>
                                <div class="collapse" id="collapseThree" role="tabpanel" aria-labelledby="headingThree" data-parent="#accordion" style="">
                                <div class="card-body">Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt
                                aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat
                                craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.</div>
                                </div>
                        </div>
                        -->
                    </div><!-- konec accordion -->

                    <br/>

                    <div id="accordion_pobyt" role="tablist" style='max-width: 550px;'>
                        <div class="card mb-0">
                            <div class="card-header" id="pobyt_headingOne" role="tab">
                                <h5 class="mb-0">
                                    <a data-toggle="collapse" href="#pobyt_collapseOne" class="collapsed" style="color: #000; font-size: 16px;">
                                        Pobyt &nbsp;<span class="pull-right" style="color: gray; font-size: 14px;">TODO vytáhnout extra</span>
                                    </a>
                                </h5>
                            </div>
                            <div class="collapse" id="pobyt_collapseOne" role="tabpanel" data-parent="#accordion_pobyt" style="">
                                <div class="card-body">
                                    <table class='table table-sm table-striped table-bordered' style='max-width: 500px;'>
                                        <?php
                                        echo "<tr><th class='w-20'>Pokoj</th><td class='w-30'>TODO pokoj</td></tr>";
                                        ?>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <br/>


                    <div id="accordion_klicovy_zamestnanec" role="tablist" style='max-width: 550px;'>
                        <div class="card mb-0">
                            <div class="card-header" id="klicovy_zamestnanec_headingOne" role="tab">
                                <h5 class="mb-0">
                                    <a data-toggle="collapse" href="#klicovy_collapseOne" class="collapsed" style="color: #000; font-size: 16px;">
                                        Klíčový zaměstnanec &nbsp;<span class="pull-right" style="color: gray; font-size: 14px;"><?php echo "$obyvatel[cislo_pojistence] ($obyvatel[pojistovna_zkratka])"; ?></span>
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
                                // AUTOMATICKY VYPIS VSEHO
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
                <div class="col-md-4">
                                <?php
                                // fotka obyvatele - TODO melo by prijit z modelu
                                $image_file_path = $base_url . "fotogalerie/obyvatele/3_test_dostal.jpg";
                                echo "<img src=\"$image_file_path\" class=\"img-fluid\" alt=\"$obyvatel_cele_jmeno_tituly\">";
                                ?>
                                Poznámka: jen ilustrační foto. Fotky jsme ještě nenapojili.
                </div>
            </div><!-- konec row -->

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

        <!-- start panel ZAKLAD 2  -->
        <div class="tab-pane fade" id="zaklad2" role="tabpanel" aria-labelledby="zaklad2-tab">
            <div class="row">
                <div class="col-md-8">
                    <?php
                    echo "<h2>$obyvatel_cele_jmeno_tituly</h2>";
                    echo "* ".$controller->helperFormatDate($obyvatel["datum_narozeni"])."<br/>";

                    // IDENTIFIKACE A ADRESA vedle sebe
                    echo "<div class=\"row\">";
                        echo "<div class=\"col-md-6\">";

                            echo "<br/><br/><h4>Identifikace</h4>";
                            echo "RČ: $obyvatel[rodne_cislo] <br/>";
                            echo "Č. poj.: $obyvatel[cislo_pojistence] ($obyvatel[pojistovna_zkratka]) <br/>";
                            echo "OP: $obyvatel[op]<br/>
                                    <small>(OP platnost do "
                                .$controller->helperFormatDate($obyvatel["op_platnost_do"])
                                .")</small><br/>";

                    echo "</div>";
                        echo "<div class=\"col-md-6\">";

                            echo "<br/><br/><h4>Adresa</h4>";
                            echo "$obyvatel[adresa_ulice] $obyvatel[adresa_cp]<br/>";
                            echo "$obyvatel[adresa_mesto]<br/>";

                        echo "</div>";
                    echo "</div>";
                    // KONEC IDENTIFIKACE A ADRESA

                    echo "<br/><br/><h4>Ostatní</h4>";
                    echo "místo narození: $obyvatel[misto_narozeni]<br/>";
                    if (trim($obyvatel["rodne_prijmeni"]) != "") {
                        // vypsat rodne prijmeni
                        echo "rozený/á: $obyvatel[rodne_prijmeni]";
                    }

                    //printr($obyvatel);
                    ?>
                </div>
                <div class="col-md-4">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="pull-right">
                                <a href="<?php echo $url_obyvatel_update;?>" class="btn btn-primary btn-sm"><i class="icon-pencil"></i> Upravit</a><br/><br/>
                            </div>
                        </div>
                    </div>
                    <?php
                        // fotka obyvatele - TODO melo by prijit z modelu
                        $image_file_path = $base_url . "fotogalerie/obyvatele/3_test_dostal.jpg";
                        echo "<img src=\"$image_file_path\" class=\"img-fluid\" alt=\"$obyvatel_cele_jmeno_tituly\">";
                    ?>
                </div>
            </div>
        </div>
        <!-- konec panel  ZAKLAD 2 -->

        <!-- start panel ZAKLAD 3  -->
        <div class="tab-pane fade" id="zaklad3" role="tabpanel" aria-labelledby="zaklad3-tab">

            <?php
                echo "<h2>$obyvatel_cele_jmeno_tituly";
                echo " <small>(*".$controller->helperFormatDate($obyvatel["datum_narozeni"]).")</small></h2><br/>";
            ?>

            <div class="card-deck mb-3">

                <div class="card mb-4 box-shadow" style="width: 18rem;">
                    <?php
                    // fotka obyvatele - TODO melo by prijit z modelu
                    $image_file_path = $base_url . "fotogalerie/obyvatele/3_test_dostal.jpg";
                    echo "<img src=\"$image_file_path\" class=\"card-img-top\" alt=\"$obyvatel_cele_jmeno_tituly\">";
                    ?>

                    <div class="card-body">
                        <p class="card-text">Libovolná textová informace</p>
                    </div>
                </div>


                <div class="card mb-4 box-shadow">
                    <div class="card-header text-center">
                        <h4 class="my-0 font-weight-normal">Základní údaje</h4>
                    </div>
                    <div class="card-body">

                        <h4 class="card-title">Identifikace</h4>

                        <p class="card-text">
                        <?php
                        echo "RČ: $obyvatel[rodne_cislo] <br/>";
                        echo "Č. poj.: $obyvatel[cislo_pojistence] ($obyvatel[pojistovna_zkratka]) <br/>";
                        echo "OP: $obyvatel[op]<br/>
                        <small>(OP platnost do "
                            .$controller->helperFormatDate($obyvatel["op_platnost_do"])
                            .")</small><br/>";
                        ?>
                        </p>

                        <br/>
                        <h4 class="card-title">Adresa</h4>
                        <p class="card-text">
                        <?php
                        echo "$obyvatel[adresa_ulice] $obyvatel[adresa_cp]<br/>";
                        echo "$obyvatel[adresa_mesto]<br/>";
                        ?>
                        </p>

                    </div>
                </div>
                <div class="card mb-4 box-shadow">
                    <div class="card-header text-center">
                        <h4 class="my-0 font-weight-normal">Ostatní</h4>
                    </div>
                    <div class="card-body">

                        <?php
                        echo "místo narození: $obyvatel[misto_narozeni]<br/>";
                        if (trim($obyvatel["rodne_prijmeni"]) != "") {
                        // vypsat rodne prijmeni
                        echo "rozený/á: $obyvatel[rodne_prijmeni]";
                        }
                        ?>

                    </div>
                </div>
            </div>



        </div>
        <!-- konec panel ZAKLAD 3  -->

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
