<div class="container-fluid">

    <form method="post" action="<?php echo $form_submit_url; ?>">
        <input type="hidden" name="action" value="<?php echo $form_action_insert_plan_vykonu; ?>"/>

        <div class="row">

            <div class="col-md-12">

                <div class="card">
                    <div class="card-header">
                        Přidat plán výkonu
                    </div>
                    <div class="card-body">
                        <div class="row">

                            <table class='table table-striped table-bordered'>

                                <tr>
                                    <th class='w-25'>Typ Plánu</th>
                                    <td class='w-75'>

                                        <div class="radio">
                                            <label><input type="radio" name="radio" id="tydne" value="1" required>Týdně</label>
                                        </div>
                                        <div class="radio">
                                            <label><input type="radio" name="radio" id="mesicne" value="2" required>Měsíčně</label>
                                        </div>
                                        <div class="radio">
                                            <label><input type="radio" name="radio" id="special" value="3" required>Jinak</label>
                                        </div>



                                        <div class="form-group" id="tydni" style="display: none">
                                            <label for="sel1">Jak:</label>
                                            <select class="form-control" id="sel1" name="selTyden">
                                                <option value="-1">-- Vyberte den v týdnu --</option>
                                                <option value="1">Každé pondělí</option>
                                                <option value="2">Každé úterý</option>
                                                <option value="3">Každou středu</option>
                                                <option value="4">Každý čtvrtek</option>
                                                <option value="5">Každý pátek</option>
                                                <option value="6">Každou sobotu</option>
                                                <option value="7">Každou neděli</option>
                                            </select>
                                        </div>

                                        <div class="form-group" id="mesicni" style="display: none">
                                            <label for="sel1">Jak:</label>
                                            <select class="form-control" id="sel1" name="selMesic">
                                                <option value="-1" selected="selected">-- Vyberte den v měsíci --</option>
                                                <?php
                                                    for ($i = 1; $i <= 28; $i++){
                                                        // dny v měsíci jenom do 28. kvuli unoru - TODO chytrejsi zadavani?
                                                        echo "
                                                            <option value=\"$i\">Každý $i. den v měsíci</option>
                                                        ";
                                                    }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="form-group" id="specialni" style="display: none">
                                            <label for="sel1">Jak:</label>
                                            <select class="form-control" id="sel1" name="selSpecial">
                                                <option value="-1">-- Vyberte --</option>
                                                <option value="10">Každé ponělí v sudé týdny</option>
                                                <option value="20">Každé úterý v sudé týdny</option>
                                                <option value="30">Každou středu v sudé týdny</option>
                                                <option value="40">Každý čtvrtek v sudé týdny</option>
                                                <option value="50">Každý pátek v sudé týdny</option>
                                                <option value="60">Každou sobotu v sudé týdny</option>
                                                <option value="70">Každou neděli v sudé týdny</option>

                                                <option value="11">Každé ponělí v liché týdny</option>
                                                <option value="21">Každé úterý v liché týdny</option>
                                                <option value="31">Každou středu v liché týdny</option>
                                                <option value="41">Každý čtvrtek v liché týdny</option>
                                                <option value="51">Každý pátek v liché týdny</option>
                                                <option value="61">Každou sobotu v liché týdny</option>
                                                <option value="71">Každou neděli v liché týdny</option>
                                            </select>
                                        </div>


                                    </td>
                                </tr>

                                <tr>
                                    <th class='w-25'>Čas od</th>
                                    <td class="w-75">
                                        <input type="time" class="form-control" name="plan_vykonu[cas_od]" required/>
                                    </td>
                                </tr>

                                <tr>
                                    <th class='w-25'>Čas do</th>
                                    <td class='w-75'>
                                        <input type="time" class="form-control" name="plan_vykonu[cas_do]" required/>
                                    </td>
                                </tr>
                            </table>

                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="pull-left">

                                    <input type="submit" class="btn btn-primary btn-lg" value="Vytvořit plan vykonu" />

                                </div>
                                <div class="pull-right">
                                    <a href="<?php echo $url_sluzby_list;?>" class="btn btn-default btn-lg">Zpět na seznam služeb</a>
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




<!-- skript na skryvani/ukazovani selectu dni podle toho, jestli
je zaskrtnuty tydni/mesicni/specialni plan vykonu -->

<script type="text/javascript">
    window.onload = function() {

        var ex1 = document.getElementById('tydne');
        var ex2 = document.getElementById('mesicne');
        var ex3 = document.getElementById('special');

        ex1.onclick = handler;
        ex2.onclick = handler;
        ex3.onclick = handler;

    }

    function handler() {
        if(document.getElementById('tydne').checked){
            document.getElementById("tydni").style.display="block";
            document.getElementById("mesicni").style.display="none";
            document.getElementById("specialni").style.display="none";
        }
        else if(document.getElementById('mesicne').checked){
            document.getElementById("tydni").style.display="none";
            document.getElementById("mesicni").style.display="block";
            document.getElementById("specialni").style.display="none";
        }
        else if(document.getElementById('special').checked){
            document.getElementById("tydni").style.display="none";
            document.getElementById("mesicni").style.display="none";
            document.getElementById("specialni").style.display="block";
        }
    }
</script>



