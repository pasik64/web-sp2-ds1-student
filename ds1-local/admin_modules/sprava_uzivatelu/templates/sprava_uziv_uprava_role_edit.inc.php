<div class="container-fluid">
    <form method="post" action="<?php echo $form_submit_url; ?>">
        <input type="hidden" name="action" value="<?php echo $form_action_save_prava; ?>"/>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <?php echo "<h3>Úprava práv pro roli ".$role['nazev']." k objektu ".$objekt['nazev']."</h3><br/>"; ?>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <table class='table table-striped table-bordered'>
                                <tr>
                                    <th class='w-25'>Práva</th>
                                    <input type="hidden" name="role" value="<?php echo $role['id'] ?>">
                                    <input type="hidden" name="objekt" value="<?php echo $objekt['id']?>">
                                    <td class='w-75'>
                                        <select id="selectBox" multiple hidden id="selectbox" name="zadanaPrava[]" class="form-control">
                                            <?php
                                            if ($role_prideleni != null) {?>
                                            <option id="1" value="read" <?php if ($role_prideleni["read"] == 1) { echo "selected='true'";} ?>name="read">read</option>
                                            <option id="2" value="insert" <?php if ($role_prideleni["insert"] == 1) { echo "selected='true'";} ?>name="insert">insert</option>
                                            <option id="3" value="update" <?php if ($role_prideleni["update"] == 1) { echo "selected='true'";} ?>name="update">update</option>
                                            <option id="4" value="delete" <?php if ($role_prideleni["delete"] == 1) { echo "selected='true'";} ?>name="delete">delete</option>
                                                <?php
                                            } else {?>
                                                <option id="1" value="read" name="read">read</option>
                                                <option id="1" value="insert" name="insert">insert</option>
                                                <option id="1" value="update" name="update">update</option>
                                                <option id="1" value="delete" name="delete">delete</option>
                                            <?php } ?>
                                        </select>
                                        <!--                                        <input type="text" class="form-control" name="zadano[role]" list="vsechny_role" required />-->
                                        <!--                                        <datalist id="vsechny_role">-->

                                        <!--                                        </datalist>-->
                                        <!--                                            <div ng-dropdown-multiselect="" options="myDropdownOptions" selected-model="myDropdownModel" extra-settings="myDropdownSettings"></div>-->
                                        <div ng-controller="adminDropdownMultiselectPrava">
                                            <div ng-dropdown-multiselect="" options="optionsList" selected-model="selectedOptions" extra-settings="myDropdownSettings" translation-texts="projectText" events="myEvent"></div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="pull-left">
                                    <input type="submit" class="btn btn-primary btn-lg" value="Uložit práva" />
                                </div>
                                <div class="pull-right">
                                    <a href="<?php echo $url_uprava_role;?>" class="btn btn-default btn-lg">Zpět na úpravu role</a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
