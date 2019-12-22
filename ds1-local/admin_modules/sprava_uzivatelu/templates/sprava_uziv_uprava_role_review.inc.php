<div class="container-fluid" ng-app="ds1">
    <div class="card-header">
        <?php echo "<h3>Úprava práv k objektu - kontrola informací"
            . "</h3><br/>"; ?>
    </div>
    <?php

    echo "<table class='table table-sm table-striped table-bordered' style='max-width: 800px;'>";
    echo "<tr>
                                    <td><b>název role</b></td>
                                    <td>$role_nazev</td>
                                    </tr>
                                    <tr>
                                    <td><b>název objektu</b></td>
                                    <td>$objekt_nazev</td>
                                    </tr>
                                    <tr>     
                                    <td><b>současná práva</b></td>
                                    <td>$prideleni_soucasne</td>
                                    </tr>                
                                    <tr>
                                    <td><b>nová práva</b></td>
                                    <td>$prideleni_nove</td>
                                    </tr>                                  
                                </tr>";
    echo
    "</table>";
    $_SESSION["zadana_prava"] = $zadana_prava;
    $_SESSION["role_id"] = $role_id;
    $_SESSION["objekt_id"] = $objekt_id;
    ?>
    <div style="text-align: center">
        <a href="<?php echo $url_add_result;?>" class="btn btn-primary btn-bg"><i class="icon-plus"></i> ANO, přidělit novou roli</a>
        <a href="<?php echo $url_uprava_role_edit;?>" class="btn btn-primary btn-bg"><i class="icon-minus"></i> NE, zrušit akci a vrátit se zpět</a>
    </div>
</div>
