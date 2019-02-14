<div class="container-fluid">

    <form method="post" action="<?php echo $form_submit_url; ?>">
        <input type="hidden" name="action" value="<?php echo $form_action_insert_sluzba; ?>"/>

        <div class="row">


            <div class="col-md-12">

                <div class="card">
                    <div class="card-header">
                        Přidat službu
                    </div>
                    <div class="card-body">
                        <div class="row">

                            <table class='table table-striped table-bordered'>

<!--                                <tr>-->
<!--                                    <th class='w-25'>Id</th>-->
<!--                                    <td class='w-75'>-->
<!--                                        <input type="number" class="form-control" name="sluzba[id]" required />-->
<!--                                    </td>-->
<!--                                </tr>-->

                                <tr>
                                    <th class='w-25'>
                                        Obyvatel
                                    </th>
                                    <td class="form-group">
                                        <select class="form-control" name="sluzba[obyvatel_id]" required>
                                            <?php foreach($obyvatele as $obyvatel): ?>
                                                <option value="<?= $obyvatel['id']; ?>"><?= $obyvatel['jmeno']; ?> <?= $obyvatel['prijmeni']; ?> <?= $controller->helperFormatDate($obyvatel['datum_narozeni']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
<!--                                    <td class='w-75'>-->
<!--                                        <input type="number" class="form-control" name="sluzba[obyvatel_id]" required />-->
<!--                                    </td>-->
                                </tr>

                                <tr>
                                    <th class='w-25'>
                                        Typ výkonu
                                        <br>
                                        <a class="btn" href=<?php echo $url_typ_vykonu_add_prepare?>>(Přidat)</a>
                                    </th>
                                    <td class="form-group">
                                        <select class="form-control" name="sluzba[typ_vykonu_id]">
                                            <?php foreach($typy as $typ): ?>
                                                <option value="<?= $typ['id']; ?>"><?= $typ['nazev']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
<!--                                    <td class='w-75'>-->
<!--                                        <input type="select" class="form-control" name="sluzba[typ_vykonu_id]" />-->
<!--                                    </td>-->
                                </tr>

                                <tr>
                                    <th class='w-25'>Datum od</th>
                                    <td class='w-75'>
                                        <input type="date" class="form-control" name="sluzba[datum_od]" required />
                                    </td>
                                </tr>
                                <tr>
                                    <th class='w-25'>Datum do</th>
                                    <td class='w-75'>
                                        <input type="date" class="form-control" name="sluzba[datum_do]" required />
                                    </td>
                                </tr>
                                <tr>
                                    <th class='w-25'>Poznámka</th>
                                    <td class='w-75'>
                                        <input type="text" class="form-control" name="sluzba[poznamka]" required />
                                    </td>
                                </tr>
                            </table>

                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="pull-left">

                                    <input type="submit" class="btn btn-primary btn-lg" value="Vytvořit sluzbu" />

                                </div>
                                <div class="pull-right">
                                    <a href="<?php echo $url_sluzby_list;?>" class="btn btn-default btn-lg">Zpět na seznam služeb</a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

        </div>


    </form>
</div>