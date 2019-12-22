<div class="container-fluid" ng-app="ds1">
    <?php
    echo "<h3>Přidání role - dokončeno"
        . "</h3><br/>";
    echo "Role se zadaným názvem byla vytvořena. Můžete vytvořit jinou s jiným názvem nebo se vrátit zpět na seznam rolí."
        . "<br/>";
    ; ?>
    <div style="text-align: center">
        <a href="<?php echo $url_add_role;?>" class="btn btn-primary btn-bg"><i class="icon-plus"></i>Vytvořit jinou roli</a>
        <a href="<?php echo $url_seznam_roli;?>" class="btn btn-primary btn-bg"><i class="icon-minus"></i> Zpět na seznam všech rolí</a>
    </div>
</div>