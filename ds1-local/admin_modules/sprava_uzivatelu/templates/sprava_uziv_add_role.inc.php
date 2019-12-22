<div class="container-fluid">
    <form method="post" action="<?php echo $form_submit_url; ?>">
        <input type="hidden" name="action" value="<?php echo $form_action_save_role; ?>"/>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <?php echo "<h3>Přidání nové role</h3><br/>"; ?>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <table class='table table-striped table-bordered'>
                                <tr>
                                    <th class='w-25'>Nová role</th>
                                    <td class='w-75'>
                                        <input type="text" name="nova_role">
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="pull-left">
                                    <input type="submit" class="btn btn-primary btn-lg" value="Uložit novou roli" />
                                </div>
                                <div class="pull-right">
                                    <a href="<?php echo $url_seznam_roli;?>" class="btn btn-default btn-lg">Zpět na seznam rolí</a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
