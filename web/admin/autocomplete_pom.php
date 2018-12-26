<?php
    // nacist si post parameter field

    // POZOR: data jsou ve formatu application/json a neni mozne je prijmout $_POST, musi se to takto:
    $json_data = file_get_contents("php://input");
    $post_data = (array) json_decode($json_data);   // zaroven pretypovat na pole

    // pro kontrolni vypis zpet do Angularu - zobrazit se to v konzoli:
    // print_r($post_data);

    // nacist vstupni data: field = napr. klicove_slovo, search: vstup od uživatele, např. lyž
    $field = @$post_data["field"];
    $search = @$post_data["search"];
    $base_url = @$post_data["base_url"];

    /*
     * popis výstupu:
     * - kořenovým prvkem je autocomplete_results - toto je nastaveno v direktivě v index.html pro všechny autocomplete
     * - klicove_slovo - tento field se vybira u kazdeho autocomplete
     * - autocomplete_desc - opet nastaveno u direktivy, je to nejaky nepovinny popis dane hodnoty. Napr. tam jde vlozit ID: cislo. */
?>

<?php
    if ($field == "obyvatele") {

        //$search

        $obyvatele = array();
        $obyvatele[] = array("jmeno" => "Beneš Edvard", "id" => 4);
        $obyvatele[] = array("jmeno" => "Dostal Martin", "id" => 3);
        $obyvatele[] = array("jmeno" => "Havel Václav", "id" => 1);
        $obyvatele[] = array("jmeno" => "Masaryk Tomáš", "id" => 2);

        $res = array();
        $res["autocomplete_results"] = array();

        if ($obyvatele)
            foreach ($obyvatele as $ob) {

                // pridat do vysledku
                if (mb_strpos(
                        mb_convert_case($ob["jmeno"], MB_CASE_LOWER),
                        mb_convert_case($search, MB_CASE_LOWER)
                    ) !== false) {

                    // pridat do vysledku
                    $ob["id_klicove_slovo"] = $ob["id"];
                    $ob["klicove_slovo"] = $search;
                    $ob["autocomplete_desc"] = $ob["jmeno"];

                    $ob["url"] = $base_url."index.php/plugin/obyvatele?action=obyvatel_detail_show&obyvatel_id=".$ob["id"];

                    $res["autocomplete_results"][] = $ob;
                }
            }

            echo json_encode($res);

        /*
        ?>
        {
        "autocomplete_results" : [
            {"id_klicove_slovo" : 1, "klicove_slovo" : "", "autocomplete_desc" : "Beneš Edvard", "url" : "http://localhost/github_web-sp2-ds1-student/web/admin/index.php/plugin/obyvatele?action=obyvatel_detail_show&obyvatel_id=4"},
            {"id_klicove_slovo" : 2, "klicove_slovo" : "", "autocomplete_desc" : "Dostal Martin", "url" : "http://localhost/github_web-sp2-ds1-student/web/admin/index.php/plugin/obyvatele?action=obyvatel_detail_show&obyvatel_id=3"},
            {"id_klicove_slovo" : 3, "klicove_slovo" : "", "autocomplete_desc" : "Havel Václav", "url" : "http://localhost/github_web-sp2-ds1-student/web/admin/index.php/plugin/obyvatele?action=obyvatel_detail_show&obyvatel_id=1"},
            {"id_klicove_slovo" : 4, "klicove_slovo" : "", "autocomplete_desc" : "Masaryk Tomáš", "url" : "http://localhost/github_web-sp2-ds1-student/web/admin/index.php/plugin/obyvatele?action=obyvatel_detail_show&obyvatel_id=2"}
        ]
        }
        <?php
        */
    }
?>
