<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <div class="pull-left">
                Seznam obyvatel - <?php echo $obyvatele_list_name; ?>
            </div>
            <div class="pull-right">
                <!-- odkaz pro pridani obyvatele -->
                <a href="<?php echo $url_obyvatel_add_prepare;?>" class="btn btn-primary btn-sm"><i class="icon-plus"></i> Přidat obyvatele</a>
            </div>
        </div>
        <div class="card-body">

            <?php
            // vypis obyvatel
            if ($obyvatele_list != null) {
                //printr($users_list); exit;

                // table-sm - zmensi odsazeni bunek
                echo "<table class='table table-sm table-bordered table-striped table-hover'>";
                echo "<tr>
                                    <th>#</th>
                                    <th>příjmení</th>
                                    <th>jméno</th>
                                    <th>datum narození</th>
                                    <th>zkratka pojišťovny</th>
                                    <th>OP platnost DO</th>
                                    <th>&nbsp;</th>
                                </tr>";

                foreach ($obyvatele_list as $obyvatel) {
                    // detail uzivatele
                    $route_params = array();
                    $route_params["action"] = $obyvatel_detail_action;
                    $route_params["obyvatel_id"] = $obyvatel["id"];
                    $url_detail = $this->makeUrlByRoute($route, $route_params);

                    // update prepare
                    $route_params = array();
                    $route_params["action"] = $obyvatel_update_prepare_action;
                    $route_params["obyvatel_id"] = $obyvatel["id"];
                    $url_update_prepare = $this->makeUrlByRoute($route, $route_params);

                    echo "<tr>";

                    echo "<td>$obyvatel[id]</td>";
                    echo "<td>$obyvatel[prijmeni]</td>";
                    echo "<td>$obyvatel[jmeno]</td>";

                    // toto mi prevede datum do spravneho formatu pro CR
                    echo "<td>".$controller->helperFormatDate($obyvatel["datum_narozeni"])."</td>";
                    echo "<td>$obyvatel[pojistovna_zkratka]</td>";
                    echo "<td>".$controller->helperFormatDate($obyvatel["op_platnost_do"])."</td>";
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
                echo "Žádní uživatelé nenalezeni.";
            }


            // stranovani
            echo $pagination_html;
            ?>
        </div>
    </div>
</div>