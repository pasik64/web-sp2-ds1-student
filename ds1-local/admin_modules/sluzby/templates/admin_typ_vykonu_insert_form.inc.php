<div class="container-fluid">

    <form method="post" action="<?php echo $form_submit_url; ?>">
        <input type="hidden" name="action" value="<?php echo $form_action_insert_typ_vykonu; ?>"/>

        <div class="row">

            <!-- START UPDATE -->
            <div class="col-md-12">

                <div class="card">
                    <div class="card-header">
                        Přidat typ vykonu
                    </div>
                    <div class="card-body">
                        <div class="row">

                            <table class='table table-striped table-bordered'>

<!--                                <tr>-->
<!--                                    <th class='w-25'>Id</th>-->
<!--                                    <td class='w-75'>-->
<!--                                        <input type="number" class="form-control" name="typ_vykonu[id]" required />-->
<!--                                    </td>-->
<!--                                </tr>-->

                                <tr>
                                    <th class='w-25'>Název</th>
                                    <td class='w-75'>
                                        <input type="text" class="form-control" name="typ_vykonu[nazev]" />
                                    </td>
                                </tr>

                                <tr>
                                    <th class='w-25'>Popis</th>
                                    <td class="w-75">
                                        <input type="text" class="form-control" name="typ_vykonu[popis]" />
                                    </td>
                                </tr>

                                <tr>
                                    <th class='w-25'>Role</th>
                                    <td class='w-75'>
                                        <input type="text" class="form-control" name="typ_vykonu[role_id]" />
                                    </td>
                                </tr>
                            </table>

                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="pull-left">

                                    <input type="submit" class="btn btn-primary btn-lg" value="Vytvořit typ vykonu" />

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