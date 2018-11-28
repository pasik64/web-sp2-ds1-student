<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <div class="pull-left">
                Seznam pokojů - <?php echo $pokoje_list_name; ?>
            </div>
            <div class="pull-right">
                <!-- odkaz pro pridani obyvatele -->
                <a href="<?php echo $url_pokoj_add_prepare;?>" class="btn btn-primary btn-sm"><i class="icon-plus"></i> Přidat pokoj</a>
            </div>
        </div>
        <div class="card-body">

            <?php
            // vypis obyvatel
            if ($pokoje_list != null) {
                // printr($pokoje_list); exit;

                // table-sm - zmensi odsazeni bunek
                echo "<table class='table table-sm table-bordered table-striped table-hover'>";
                echo "<tr>
                                    <th>#</th>
                                    <th>název</th>
                                    <th>poschodí</th>
                                    <th>sociální zařízení</th>
                                    <th>kapacita (osob)</th>
                                    <th>&nbsp;</th>
                                </tr>";

                foreach ($pokoje_list as $pokoj) {
                    // detail uzivatele
                    $route_params = array();
                    $route_params["action"] = $pokoj_detail_action;
                    $route_params["pokoj_id"] = $pokoj["id"];
                    $url_detail = $this->makeUrlByRoute($route, $route_params);

                    // update prepare
                    $route_params = array();
                    $route_params["action"] = $pokoj_update_prepare_action;
                    $route_params["pokoj_id"] = $pokoj["id"];
                    $url_update_prepare = $this->makeUrlByRoute($route, $route_params);

                    echo "<tr>";

                        echo "<td>$pokoj[id]</td>";
                        echo "<td>$pokoj[nazev]</td>";
                        echo "<td>$pokoj[poschodi]</td>";

                        $soc = "ne";
                        if ($pokoj["socialni_zarizeni"] == 1) $soc = "ano";
                        echo "<td>$soc</td>";

                        echo "<td>$pokoj[kapacita_osob]</td>";

                        echo "<td>
                                  <a href=\"$url_detail\" class='btn btn-primary btn-sm'><i class=\"icon-layers\"></i></a>
                                  &nbsp;&nbsp;
                                  <a href=\"$url_update_prepare\" class='btn btn-primary btn-sm'><i class=\"icon-pencil\"></i></a>
                        </td>";

                    echo "</tr>";
                }

                echo "</table>";
            }
            else {
                echo "Žádné pokoje nenalezeny.";
            }


            // stranovani
            echo $pagination_html;
            ?>
        </div>
    </div>
</div>