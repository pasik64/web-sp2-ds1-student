<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            Seznam obyvatel - <?php echo $obyvatele_list_name; ?>
        </div>
        <div class="card-body">

            <?php

            if ($obyvatele_list != null) {
                //printr($users_list); exit;

                // table-sm - zmensi odsazeni bunek
                echo "<table class='table table-sm table-bordered table-striped table-hover'>";
                echo "<tr>
                                    <th>#</th>
                                    <th>příjmení</th>
                                    <th>jméno</th>
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