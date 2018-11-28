<?php
/**
 *   Detail pokoje
 */
?>
<div class="container-fluid">
    <?php
    echo "<h3>Detail pokoje #$pokoj_id - $pokoj[nazev]</h3><br/>";
    ?>
    <div class="pull-right">
        <a href="<?php echo $url_pokoje_list;?>" class="btn btn-default">Zpět na seznam pokojů</a>
    </div>

    <!-- start seznam zalozek  -->
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="zaklad-tab" data-toggle="tab" href="#zaklad" role="tab" aria-controls="zaklad" aria-selected="true">Základní údaje</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="obyvatele-tab" data-toggle="tab" href="#obyvatele" role="tab" aria-controls="obyvatele" aria-selected="false">Obyvatelé v pokoji</a>
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
                    $text_columns["nazev"] = "Název";
                    $text_columns["poschodi"] = "Poschodí";
                    $text_columns["popis"] = "Popis";

                    // definice, ktera pole jsou datumy
                    $dates_text_columns_keys = array();


                    // tridy pro konkretni polozky
                    $classes_for_columns = array();
                    //$classes_for_columns["prijmeni"] = array("tr" => "table-success");


                    // napoveda pro vse
                    $input_help_desc = array();
                    //$input_help_desc["rodne_cislo"] = "Rodné číslo ukládáme textově. Klidně s lomítkem.";
                    //$input_help_desc["pojistovna_zkratka"] = "Např. VZP. Zkratku je potřeba ukládat stále stejně.";


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
                                echo $controller->helperFormatDate($pokoj[$key]);
                            } else {
                                // text
                                echo "$pokoj[$key]";
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
                            <a href="<?php echo $url_pokoj_update;?>" class="btn btn-primary">Upravit</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- konec panel ZAKLAD  -->

        <!-- start panel OBYVATELE na pokoji  -->
        <div class="tab-pane fade" id="obyvatele" role="tabpanel" aria-labelledby="obyvatele-tab">Obyvatelé na pokoji</div>
        <!-- konec panel UBYTOVANI   -->
    </div>
    <!-- konec panely pro zalozky  -->


    <!-- jen dodatecna navigace pod obsahem -->
    <div class="row">
        <div class="col-md-12">
            <div class="pull-right">
                <a href="<?php echo $url_pokoje_list;?>" class="btn btn-default">Zpět na seznam pokojů</a>
            </div>
        </div>
    </div>

</div>

<br/>
