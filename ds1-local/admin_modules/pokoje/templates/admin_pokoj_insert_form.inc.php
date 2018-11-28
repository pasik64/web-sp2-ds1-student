<div class="container-fluid">

    <form method="post" action="<?php echo $form_submit_url; ?>">
        <input type="hidden" name="action" value="<?php echo $form_action_insert; ?>"/>

        <div class="row">

            <!-- START DETAIL OBYVATELE PRO UPDATE -->
            <div class="col-md-12">

                <div class="card">
                    <div class="card-header">
                        Přidat pokoj
                    </div>
                    <div class="card-body">
                        <div class="row">

                            <table class='table table-striped table-bordered'>
                                <tr>
                                    <th class='w-25'>Název</th>
                                    <td class='w-75'>
                                        <input type="text" class="form-control" name="pokoj[nazev]" required />
                                    </td>
                                </tr>
                                <tr>
                                    <th class='w-25'>Poschodí</th>
                                    <td class='w-75'>
                                        <input type="text" class="form-control" name="pokoj[poschodi]" required />
                                    </td>
                                </tr>
                                <tr>
                                    <th class='w-25'>Sociální zařízení</th>
                                    <td class='w-75'>
                                        <select name="pokoj[socialni_zarizeni]">
                                            <option value="1">ano</option>
                                            <option value="0">ne</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th class='w-25'>Zařadit do skupiny pokojů</th>
                                    <td class='w-75'>
                                        <select name="pokoj[skupina_pokoju_id]">

                                            <?php
                                                // vypis skupin
                                                //printr($skupiny_pokoju);
                                                if ($skupiny_pokoju != null)
                                                    foreach ($skupiny_pokoju as $skupina_id => $skupina_nazev) {
                                                        echo "<option value=\"$skupina_id\">$skupina_nazev</option>";
                                                    }
                                            ?>

                                        </select>
                                    </td>
                                </tr>
                            </table>

                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="pull-left">

                                    <input type="submit" class="btn btn-primary btn-lg" value="Vytvořit pokoj" />

                                </div>
                                <div class="pull-right">
                                    <a href="<?php echo $url_pokoje_list;?>" class="btn btn-default btn-lg">Zpět na seznam pokojů</a>
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
