<div class="container-fluid">

    <form method="post" action="<?php echo $form_submit_url; ?>">
        <input type="hidden" name="action" value="<?php echo $form_action_insert_obyvatel; ?>"/>

        <div class="row">

            <!-- START DETAIL OBYVATELE PRO UPDATE -->
            <div class="col-md-12">

                <div class="card">
                    <div class="card-header">
                        Přidat obyvatele
                    </div>
                    <div class="card-body">
                        <div class="row">

                            <table class='table table-striped table-bordered'>
                            <tr>
                                <th class='w-25'>Jméno</th>
                                <td class='w-75'>
                                    <input type="text" class="form-control" name="obyvatel[jmeno]" required />
                                </td>
                            </tr>
                            <tr>
                                <th class='w-25'>Příjmení</th>
                                <td class='w-75'>
                                    <input type="text" class="form-control" name="obyvatel[prijmeni]" required />
                                </td>
                            </tr>
                            <tr>
                                <th class='w-25'>Datum narození</th>
                                <td class='w-75'>
                                    <input type="date" class="form-control" name="obyvatel[datum_narozeni]" required />
                                </td>
                            </tr>
                            </table>

                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="pull-left">

                                    <input type="submit" class="btn btn-primary btn-lg" value="Vytvořit obyvatele" />

                                </div>
                                <div class="pull-right">
                                    <a href="<?php echo $url_obyvatele_list;?>" class="btn btn-default btn-lg">Zpět na seznam obyvatel</a>
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
