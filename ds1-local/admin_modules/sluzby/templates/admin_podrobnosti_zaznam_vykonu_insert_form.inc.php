<div class="container-fluid">

    <form method="post" action="<?php echo $form_submit_url; ?>">
        <input type="hidden" name="action" value="<?php echo $form_action_insert_podrobnosti_zaznam_vykonu; ?>"/>

        <div class="row">
            <div class="col-md-12">

                <div class="card">
                    <div class="card-header">
                        Přidat detail záznamu výkonu
                    </div>
                    <div class="card-body">
                        <div class="row">

                            <table class='table table-striped table-bordered'>

                                <tr>
                                    <th class='w-25'>
                                        Uživatel
                                    </th>
                                    <td class="form-group">
                                        <select class="form-control" name="zaznam_vykonu_detail[uzivatel_id]" required>
                                            <?php foreach($uzivatele as $uzivatel): ?>
                                                <option value="<?= $uzivatel['id']; ?>"><?= $uzivatel['jmeno']; ?> <?= $uzivatel['prijmeni']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                </tr>

                                <tr>
                                    <th class='w-25'>
                                        Název
                                    </th>
                                    <td class='w-75'>
                                        <input type="text" class="form-control" name="zaznam_vykonu_detail[nazev]" />
                                    </td>
                                </tr>

                                <tr>
                                    <th class='w-25'>Popis</th>
                                    <td class='w-75'>
                                        <input type="text" class="form-control" name="zaznam_vykonu_detail[popis]" />
                                    </td>
                                </tr>
                                <tr>
                                    <th class='w-25'>Léčivo</th>
                                    <td class='w-75'>
                                        <input type="text" class="form-control" name="zaznam_vykonu_detail[lecivo]" />
                                    </td>
                                </tr>
                                <tr>
                                    <th class='w-25'>Množství ml</th>
                                    <td class='w-75'>
                                        <input type="number" class="form-control" name="zaznam_vykonu_detail[mnozstvi_ml]" />
                                    </td>
                                </tr>
                                <tr>
                                    <th class='w-25'>Množství mg</th>
                                    <td class='w-75'>
                                        <input type="number" class="form-control" name="zaznam_vykonu_detail[mnozsvi_mg]" />
                                    </td>
                                </tr>
                                <tr>
                                    <th class='w-25'>Množství text</th>
                                    <td class='w-75'>
                                        <input type="text" class="form-control" name="zaznam_vykonu_detail[mnozstvi_text]" />
                                    </td>
                                </tr>
                                <tr>
                                    <th class='w-25'>Datum vytvoření</th>
                                    <td class='w-75'>
                                        <input type="date" class="form-control" name="zaznam_vykonu_detail[datum_vytvoreni]" />
                                    </td>
                                </tr>
                            </table>

                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="pull-left">

                                    <input type="submit" class="btn btn-primary btn-lg" value="Vytvořit detail zaznamu" />

                                </div>
                                <div class="pull-right">
                                    <a href="<?php echo $url_sluzby_list;?>" class="btn btn-default btn-lg">Zpět na seznam sluzeb</a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div><!-- card -->

            </div> <!--col-md-12-->

        </div><!-- konec row-->


    </form>
</div>