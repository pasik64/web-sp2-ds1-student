<div class="container-fluid" ng-app="ds1">
    <?php
    echo "<h3>Úprava práv - dokončeno"
        . "</h3><br/>";
    echo "Informace byly úspěšně zapsány do databáze - můžete upravit další práva nebo se vrátit zpět na seznam všech rolí."
        . "<br/>";
    ; ?>
    <div style="text-align: center">
        <a href="<?php echo $url_uprava_role;?>" class="btn btn-primary btn-bg"><i class="icon-plus"></i> Upravit další práva</a>
        <a href="<?php echo $url_uprava_typu_roli;?>" class="btn btn-primary btn-bg"><i class="icon-minus"></i> Zpět na seznam všech rolí</a>
    </div>
</div>