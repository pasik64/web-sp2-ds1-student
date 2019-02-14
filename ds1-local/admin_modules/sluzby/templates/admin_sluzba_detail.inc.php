<?php
/**
 *   Detail služby
 */
?>

<div class="container-fluid">
    <?php
    echo "<h3>Detail služby #$sluzba_id - pacient $pacient</h3><br/>";
    ?>
    <div class="pull-right">
        <a href="<?php echo $url_sluzby_list; ?>" class="btn btn-default">Zpět na seznam služeb</a>
    </div>

    <!-- start seznam zalozek  -->
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="typVykonu-tab" data-toggle="tab" href="#typVykonu" role="tab"
               aria-controls="typVykonu" aria-selected="true">Typ výkonu</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="planVykonu-tab" data-toggle="tab" href="#planVykonu" role="tab"
               aria-controls="planVykonu" aria-selected="false">Plány výkonu</a>
        </li>
    </ul>
    <!-- konec seznam zalozek  -->

    <!-- start panely pro zalozky  -->
    <div class="tab-content" id="myTabContent">

        <!-- start panel TYP VYKONU  -->
        <div class="tab-pane fade show active" id="typVykonu" role="tabpanel" aria-labelledby="typVykonu-tab">
            <div class="container-fluid">
                <div class="row">
                    <?php
                    $leky_typ_vykonu = array();
                    //$leky_typ_vykonu["id"] = "Id";
                    $leky_typ_vykonu["nazev"] = "Název";
                    $leky_typ_vykonu["popis"] = "Popis";
                    $leky_typ_vykonu["role_id"] = "Role";


                    // tridy pro konkretni polozky
                    $classes_for_columns = array();


                    // napoveda pro vse
                    $input_help_desc = array();


                    if ($leky_typ_vykonu != null && $typ != null) {

                        echo "<table class='table table-sm table-striped table-bordered' style='max-width: 500px;'>";

                        // zakladni texty vcetne datumu
                        foreach ($leky_typ_vykonu as $key => $value) {

                            echo "<tr>";
                            echo "<th class='w-20'>$value</th>";
                            echo "<td class='w-30'>";


                            echo "$typ[$key]";


                            echo "</td>";
                            echo "</tr>";
                        }

                        echo "</table>"; ?>
                        <div>
                            <a href="<?php echo $url_typ_vykonu_update_prepare; ?>" class="btn btn-default"">Upravit</a>
                        </div>
                        <?php
                    }
                    else {
                        echo "Typ výkonu není přidán k dané službě."; ?>
                        <div>
                            <a href="<?php echo $url_typ_vykonu_add_prepare; ?>" class="btn btn-default"">Přidat</a>
                        </div>

                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
        <!-- konec panel TYP VYKONU  -->


        <!-- start panel PLANY VYKONU -->
        <div class="tab-pane fade" id="planVykonu" role="tabpanel" aria-labelledby="planVykonu-tab">
            <div class="container-fluid">

                <div class="pull-right">
                    <a href="<?php echo $url_plan_vykonu_add_prepare; ?>">
                        <button class="btn btn-primary btn-sm"><i class="icon-plus"></i>Nový plán</button>
                    </a>

                </div>
                <br><br>

                <div class="row">
                    <?php


                    if ($plany != null) {

                        echo "<table class='table table-sm table-bordered table-striped table-hover'>";
                        echo "<tr>
                                    
                                    <th>Typ plánu</th>
                                    <th>Čas od</th>
                                    <th>Čas do</th>
                                    <th>&nbsp;</th>
                        </tr>";

                        foreach ($plany as $key => $value) {
                            $pom = $sluzby->translateTypPlanu($value['typ_planu']);

                            echo "<tr>";

//                            echo "<td>$value[id]</td>";
//                            echo "<td>$value[sluzba_id]</td>";
                            //echo "<td>$value[typ_planu]</td>";
                            echo "<td>$pom</td>";
                            echo "<td>$value[cas_od]</td>";
                            echo "<td>$value[cas_do]</td>";

                            echo "<td>
                                  
                                  <form method=\"post\" action='$url_sluzby_zaznam'>
                                      <button class='btn btn-primary btn-sm' type=\"submit\" name=\"plan_zaznam\" id=\"plan_zaznam\" value=\"$value[id]\"/><i class=\"icon-layers\"></i> Záznamy</button><br/>
                                  </form>
                                  
                                  <form method=\"post\" action='$url_plan_vykonu_update_prepare'>
                                      <button class='btn btn-primary btn-sm' type=\"submit\" name=\"plan_update\" id=\"plan_update\" value=\"$value[id]\"/><i class=\"icon-pencil\"></i></button><br/>
                                  </form>

                              </td>";

                            echo "</tr>";
                        }


                        echo "</table>";
                        echo "</div>";
                    }
                    else {
                        echo "Žádný plán výkonu není přidán k dané službě."; ?>
                        <div>
                            <a href="<?php echo $url_plan_vykonu_add_prepare; ?>" class="btn btn-default"">Pridat</a>
                        </div>

                        <?php
                    }
                    ?>
                </div>
            </div>
            <!-- konec panel PLANY VYKONU -->
        </div>
    </div>

    <!-- konec panely pro zalozky  -->


    <!-- jen dodatecna navigace pod obsahem -->
    <div class="row">
        <div class="col-md-12">
            <div class="pull-right">
                <a href="<?php echo $url_sluzby_list; ?>" class="btn btn-default">Zpět na seznam služeb</a>
            </div>
        </div>
    </div>
</div>

<br/>