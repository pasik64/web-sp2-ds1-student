<div class="container-fluid">

    <form method="post" action="<?php echo $form_submit_url; ?>">
        <input type="hidden" name="obyvatel_id" value="<?php echo $obyvatel_id; ?>"/>
        <input type="hidden" name="obyvatel[obyvatel_id]" value="<?php echo $obyvatel_id; ?>"/>
        <input type="hidden" name="action" value="<?php echo $form_action; ?>"/>

        <div class="row">

            <!-- START DETAIL OBYVATELE PRO UPDATE -->
            <div class="col-md-12">

                <div class="card">
                    <div class="card-header">
                        Přidat obyvatele <?php echo "<strong>$obyvatel[jmeno] $obyvatel[prijmeni]</strong>";?> na pokoj
                    </div>
                    <div class="card-body">
                        <div class="row">

                            <table class='table table-striped table-bordered'>
                                <tr>
                                    <th class='w-25'>Datum OD</th>
                                    <td class='w-75'>
                                        <input type="datetime-local" class="form-control" name="obyvatel[datum_od]" required />
                                    </td>
                                </tr>
                                <tr>
                                    <th class='w-25'>Datum DO</th>
                                    <td class='w-75'>
                                        <input type="datetime-local" class="form-control" name="obyvatel[datum_do]"/>
                                    </td>
                                </tr>
                                <tr>
                                    <th class='w-25'>Pokoj</th>
                                    <td class='w-75'>
                                        <?php
                                            // printr($pokoje_list);

                                            if ($pokoje_list != null) {
                                                echo "<select name=\"obyvatel[pokoj_id]\" required>";

                                                    foreach ($pokoje_list as $pokoj_item) {
                                                        echo "<option value=\"$pokoj_item[id]\">$pokoj_item[nazev] (poschodi: $pokoj_item[poschodi], kapacita: $pokoj_item[kapacita_osob] osob)</option>";
                                                    }

                                                echo "</select>";
                                            }
                                            else {
                                                echo "Chyba: pokoje nejsou k dispozici";
                                            }
                                        ?>
                                    </td>
                                </tr>
                            </table>

                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="pull-left">

                                    <input type="submit" class="btn btn-primary btn-lg" value="Přidat obyvatele na pokoj" />

                                </div>
                                <div class="pull-right">
                                    <a href="<?php echo $url_obyvatel_detail;?>" class="btn btn-default btn-lg">Zpět na detail obyvatele</a>
                                    <a href="<?php echo $url_obyvatele_list;?>" class="btn btn-default btn-lg">Zpět na seznam obyvatel</a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div><!-- card -->

            </div> <!--col-md-12-->


        </div><!-- konec row-->
    </form>


    <!-- INFO, KDE AKTUALNE BYDLI -->
    <div class="card">
        <div class="card-header">
            <?php echo "<strong>$obyvatel[jmeno] $obyvatel[prijmeni]</strong>";?> - záznamy o ubytování
        </div>
        <div class="card-body">
            <div class="row">

            <?php
            //printr($obyvatel_na_pokojich);
            if ($obyvatel_na_pokojich != null){
                echo "<table class='table table-sm table-striped table-bordered'>";
                echo "<tr><th>Datum od</th><th>Datum do</th><th>Název pokoje</th><th>Poschodí</th><th>Pokoj ID (#)</th><th>&nbsp;</th></tr>";

                foreach ($obyvatel_na_pokojich as $ubytovani) {

                    // url na detail pokoje
                    $url_pokoj_detail = $controller->makeUrlByRoute($pokoje_route_name,
                        array("action" => "pokoj_detail_show", "pokoj_id" => $ubytovani["pokoj_id"])
                    );

                    // smazat zaznam
                    $delete_record_params = array();
                    $delete_record_params["action"] = "obyvatel_na_pokoje_delete_go";
                    $delete_record_params["obyvatel_id"] = $obyvatel_id;
                    $delete_record_params["obyvatel_na_pokoji_id"] = $ubytovani["id"];

                    $url_delete_record = $controller->makeUrlByRoute($route, $delete_record_params);

                    echo "<tr>";
                    // toto bude navic datetime
                    echo "<td>".$controller->helperFormatDateAuto($ubytovani["datum_od"])."</td>";
                    echo "<td>".$controller->helperFormatDateAuto($ubytovani["datum_do"])."</td>";
                    echo "<td><a href='$url_pokoj_detail'>$ubytovani[pokoj_nazev]</a></td>";
                    echo "<td>$ubytovani[pokoj_poschodi]</td>";
                    echo "<td>$ubytovani[pokoj_id]</td>";
                    echo "<td><a href='$url_delete_record' class='btn btn-danger btn-sm'><i class=\"icon-trash\"></i></a></td>";
                    echo "</tr>";
                }
                echo "</table>";
            }
            ?>
            </div>
        </div><!-- konec card body -->
    </div><!-- card-->


</div>
