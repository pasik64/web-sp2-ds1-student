<div class="container-fluid" ng-app="ds1">
    <div class="card">
        <div class="card-header">
            <div class="pull-left">
                <h3>Úprava role: <?php echo "$role[nazev]" ?></h3>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">

                <?php
                // vypis informací uživatelích systému
                if ($db_objekty != null)
                {
                    echo "<table class='table table-sm table-bordered table-striped table-hover'>";
                    echo "<tr>
                                    <th>#</th>
                                    <th>název</th>
                                    <th>read</th>
                                    <th>insert</th>
                                    <th>update</th>
                                    <th>delete</th>
                                    <th>upravit</th>
                                </tr>";
                    foreach($db_objekty as $objekt){
                        $route_params = array();
                        echo "<tr>";
                        echo "<td>$objekt[id]</td>";
                        echo "<td>$objekt[nazev]</td>";
                        $prideleno = false;
                        foreach ($role_prideleni_objektu as $prideleni) {
                            if ($prideleni["db_objekty_id"] == $objekt["id"]) {
                                if ($prideleni["read"] == 1) {
                                    echo "<td>ANO</td>";
                                } else echo "<td>NE</td>";
                                if ($prideleni["insert"] == 1) {
                                    echo "<td>ANO</td>";
                                } else echo "<td>NE</td>";
                                if ($prideleni["update"] == 1) {
                                    echo "<td>ANO</td>";
                                } else echo "<td>NE</td>";
                                if ($prideleni["delete"] == 1) {
                                    echo "<td>ANO</td>";
                                } else echo "<td>NE</td>";
                                $prideleno = true;
                                $route_params["prideleni"] = $prideleni;
                            }
                        }
                        if ($prideleno == false) {
                            echo "<td>NE</td>";
                            echo "<td>NE</td>";
                            echo "<td>NE</td>";
                            echo "<td>NE</td>";
                            $route_params["prideleni"] = null;
                        }
                        $route_params["action"] = $uprava_role_edit;
                        $route_params["role"] = $role;
                        $route_params["objekt"] = $objekt;
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
                               Žádné objekty nenalezeny
                         </div>";
                    echo "</div>";
                }
                // konec vypis dat
                ?>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="pull-right">
                        <a href="<?php echo $url_uprava_typu_roli;?>" class="btn btn-default btn-lg">Zpět do úpravy typů rolí</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>