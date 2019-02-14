<div class="container-fluid">

    <form method="post" action="<?php echo $form_submit_url; ?>">
        <input type="hidden" name="action" value="<?php echo $form_action_insert_zaznam_vykonu; ?>"/>

        <div class="row">

            <!-- START UPDATE -->
            <div class="col-md-12">

                <div class="card">
                    <div class="card-header">
                        Přidat záznam výkonu
                    </div>
                    <div class="card-body">
                        <div class="row">

                            <table class='table table-striped table-bordered'>

<!--                                <tr>-->
<!---->
<!--                                    <th class='w-25'>-->
<!--                                        Plan vykonu-->
<!--                                        <br>-->
<!--                                        <a href="--><?php //echo $url_plan_vykonu_add_prepare ?><!--">(Pridat)</a>-->
<!--                                    </th>-->
<!--                                    <td class="form-group">-->
<!--                                        <select class="form-control" name="zaznam_vykonu[plan_vykonu_id]" required>-->
<!--                                        </select>-->
<!--                                    </td>-->
<!--                                </tr>-->

                                <tr>

                                    <th class='w-25'>
                                        Uživatel
                                    </th>
                                    <td class="form-group">
                                        <select class="form-control" name="zaznam_vykonu[uzivatel_id]" required>
                                            <?php foreach($uzivatele as $uzivatel): ?>
                                                <option value="<?= $uzivatel['id']; ?>"><?= $uzivatel['jmeno']; ?> <?= $uzivatel['prijmeni']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                </tr>

                                <tr>
                                    <th class='w-25'>Čas začátek</th>
                                    <td class='w-75'>
                                        <input type="time" class="form-control" name="zaznam_vykonu[cas_zacatek]" />
                                    </td>
                                </tr>

                                <tr>
                                    <th class='w-25'>Čas konec</th>
                                    <td class='w-75'>
                                        <input type="time" class="form-control" name="zaznam_vykonu[cas_konec]" />
                                    </td>
                                </tr>

                                <tr>
                                    <th class='w-25'>Poznámka</th>
                                    <td class='w-75'>
                                        <input type="text" class="form-control" name="zaznam_vykonu[poznamka]" />
                                    </td>
                                </tr>



                            </table>

                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="pull-left">

                                    <input type="submit" class="btn btn-primary btn-lg" value="Vytvořit zaznam vykonu" />

                                </div>
                                <div class="pull-right">
                                    <a href="<?php echo $url_sluzby_list;?>" class="btn btn-default btn-lg">Zpět na seznam sluzeb</a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div><!-- card -->

            </div> <!--col-md-12-->
            <!-- KONEC DETAIL OBYVATELE -->

        </div><!-- konec row-->


    </form>
</div>