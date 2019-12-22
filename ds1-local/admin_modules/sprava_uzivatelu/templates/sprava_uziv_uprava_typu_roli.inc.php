<div class="container-fluid" ng-app="ds1">
    <div class="card">
        <div class="card-header">
            <div class="pull-left">
                <h3>Úprava typů rolí</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">

                <?php
                // vypis informací uživatelích systému
                if ($vsechny_role != null)
                {
                    echo "<table class='table table-sm table-bordered table-striped table-hover'>";
                    echo "<tr>
                                    <th>#</th>
                                    <th>název</th>
                                    <th>úprava role</th>
                                </tr>";
                    foreach($vsechny_role as $role){
                        echo "<tr>";
                        echo "<td>$role[id]</td>";
                        echo "<td>$role[nazev]</td>";
                        $route_params = array();
                        $route_params["action"] = $role_edit;
                        $route_params["role"] = $role;
                        $url_detail = $this->makeUrlByRoute($route, $route_params);

                        echo "<td><a href=\"$url_detail\" class='btn btn-primary btn-sm'><i class=\"icon-pencil\"></i></a></td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                    echo "</div>";
                    echo "<div class=\"row\">
                       <div class=\"col-md-8 offset-md-2 \">";
                    echo "</div></div>";
                }
                else {
                    echo "<div class=\"col-md-12\">";
                    echo "<div class=\"alert alert-danger fade show\" role=\"alert\">
                               Žádné role nenalezeny
                         </div>";
                    echo "</div>";
                }
                ?>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="pull-right">
                        <a href="<?php echo $url_uzivatele_list;?>" class="btn btn-default btn-lg">Zpět na výpis uživatelů</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>