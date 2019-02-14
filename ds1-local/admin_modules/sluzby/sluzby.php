<?php
namespace ds1\admin_modules\sluzby;


use ds1\admin_modules\pokoje\pokoje;
use PDO;

class sluzby extends \ds1\core\ds1_base_model
{

    //
    // ------------ POMOCNA FUNKCE START -----------
    //

    /**
     *
     * Funkce na prevod typu planu z ciselne podoby (z databaze) do 'lidske' reci
     *
     * Uchovavani dat v databazi:
     *  -> prvni cislo ∈ {1, 2, 3}, kde 1 znamena tydni opakovani, 2 mesicni, 3 specialni
     *    (specialni: zatim jenom sudy/lichy tyden)
     *  -> druhe (nebo druhe a treti) cislo znaci den v tydnu nebo v mesici
     *  -> pokud je prvni cislo tri, tak treti cislo znaci sudy/lichy tyden - 1 lichy, 0 sudy
     *
     *
     * @param $ciselne ciselna reprezentace typu planu
     * @return string
     */
    public function translateTypPlanu($ciselne){ // TODO chtelo by to lepsi kontrolu, ale ted by to melo stacit
        $ret_string = '';
        $arr = str_split($ciselne);
//        var_dump($arr);
//        if (array_key_exists(2, $arr)){
//            echo $arr[2];
//        }

        //tyden
        if ($arr[0] == 1){
            $ret_string = $ret_string."Každý týden v ";

            if($arr[1] == -1){
                $ret_string = "Každý týden";
            }
            else{
                switch ($arr[1]){
                    case 1: $ret_string = $ret_string."pondělí"; break;
                    case 2: $ret_string = $ret_string."úterý"; break;
                    case 3: $ret_string = $ret_string."středu"; break;
                    case 4: $ret_string = $ret_string."čtvrtek"; break;
                    case 5: $ret_string = $ret_string."pátek"; break;
                    case 6: $ret_string = $ret_string."sobotu"; break;
                    case 7: $ret_string = $ret_string."neděli"; break;
                    default: $ret_string = "Neznámý typ plánu";
                }
            }

        }
        // mesic
        else if ($arr[0] == 2){
            $ret_string = $ret_string."Každý ";

            if($arr[1] == -1){
                $ret_string = "Každý měsíc";
            }
            else{
                $ret_string = $ret_string.$arr[1];
                if (array_key_exists(2, $arr)){
                    $ret_string = $ret_string.$arr[2];
                }
                $ret_string = $ret_string.". den v měsíci";
            }
        }
        //special
        else if ($arr[0] == 3){

            $ret_string = $ret_string."Každý ";
            if ($arr[2] == 0){
                $ret_string = $ret_string."sudý ";
            }
            else if($arr[2] == 1){
                $ret_string = $ret_string."lichý ";
            }
            $ret_string = $ret_string."týden v ";

            switch ($arr[1]){
                case 1: $ret_string = $ret_string."pondělí"; break;
                case 2: $ret_string = $ret_string."úterý"; break;
                case 3: $ret_string = $ret_string."středu"; break;
                case 4: $ret_string = $ret_string."čtvrtek"; break;
                case 5: $ret_string = $ret_string."pátek"; break;
                case 6: $ret_string = $ret_string."sobotu"; break;
                case 7: $ret_string = $ret_string."neděli"; break;
            }
        }
        else {
            $ret_string = "Neznamy typ planu (".$ciselne.")";
        }

        return $ret_string;
    }

    //
    // ------------ POMOCNA FUNKCE KONEC -----------
    //



















    //
    //   ------------- SLUZBA START ----------------------
    //

    /**
     * Vlozi novy zaznam do tabulky SLUZBA
     * @param $sluzba_new
     * @return int
     */
    public function adminInsertSluzbaItem($sluzba_new){
        $id = $this->DBInsert(TABLE_SLUZBA, $sluzba_new);
        return $id;
    }


    /**
     * Updatuje zaznam na indexu sluzba_id v tabulce SLUZBA
     * @param $sluzba_id
     * @param $sluzba_new
     * @return bool
     */
    public function adminUpdateSluzbaItem($sluzba_id, $sluzba_new){
        $where_array = array();
        $where_array[] = array("column" => "id", "value" => $sluzba_id, "symbol" => "=");

        $ok = $this->DBUpdate(TABLE_SLUZBA, $where_array, $sluzba_new, "limit 1");
        return $ok;
    }


    /**
     * Vrati zaznam z tabulky SLUZBA podle id
     * @param $id
     * @return mixed
     */
    public function adminGetSluzbaItemByID($id) {
        $id += 0;

        $table_name = TABLE_SLUZBA;
        $where_array = array();
        $where_array[] = $this->DBHelperGetWhereItem("id", $id);
        $limit_pom = "limit 1";

        $row = $this->DBSelectOne($table_name, "*", $where_array, $limit_pom);
        //printr($row);

        return $row;
    }


    /**
     * Vrati vsechny zaznamy z tabulky SLUZBA
     *
     * @param string $type
     * @param int $page
     * @param int $count_on_page
     * @param array $where_array
     * @param string $order_by
     * @param string $order_by_direction
     * @return array|int
     */
    public function adminLoadSluzbaItems($type = "data", $page = 1, $count_on_page = 12, $where_array = array(), $order_by = "id", $order_by_direction = "asc")
    {

        if ($type == "data") {
            $columns = "*";

            if ($page <= 1) $page = 1;
            $from = ($page - 1) * $count_on_page + 0;
            $limit_pom = "limit $from, $count_on_page";
        } else {
            $columns = "count(*)";
            $limit_pom = "";
        }

        $table_name = TABLE_SLUZBA;
        $order_by = $this->DBHelperFixColumnName($order_by);

        $order_by_pom = array();
        $order_by_pom[] = array("column" => $order_by, "sort" => $order_by_direction);
        $where_array = array();

        $rows = $this->DBSelectAll($table_name, $columns, $where_array, $limit_pom, $order_by_pom);
        //printr($rows);

        if ($type == "data") {
            // chci data - vratit data
            return $rows;
        } else {
            // chci jen count
            $count = @$rows[0]["count(*)"] + 0;
            //echo $count;
            return $count;
        }
    }

    //
    //   ------------------ SLUZBA KONEC ----------------------
    //









    //
    // ---------------------- TYP VÝKONU START-------------------
    //

    /**
     * Updatuje typ vykonu zaznam na indexu typ_id
     * @param $typ_id
     * @param $typ_new - nova data
     * @return bool
     */
    public function adminUpdateTypVykonuItem($typ_id, $typ_new){
        $where_array = array();
        $where_array[] = array("column" => "id", "value" => $typ_id, "symbol" => "=");

        $ok = $this->DBUpdate(TABLE_TYP_VYKONU, $where_array, $typ_new, "limit 1");
        return $ok;
    }

    /**
     * Vlozi novy zaznam do tabulky TYP VYKONU
     * @param $typ_vykonu_new
     * @return int
     */
    public function adminInsertTypVykonuItem($typ_vykonu_new){
        $id = $this->DBInsert(TABLE_TYP_VYKONU, $typ_vykonu_new);
        return $id;
    }


    /**
     * Vrati zaznam z tabulky TYP VYKONU podle id
     *
     * @param $id
     * @return mixed
     */
    public function adminGetTypVykonuItemByID($id) {
        $id += 0;

        $table_name = TABLE_TYP_VYKONU;
        $where_array = array();
        $where_array[] = $this->DBHelperGetWhereItem("id", $id);
        $limit_pom = "limit 1";

        $row = $this->DBSelectOne($table_name, "*", $where_array, $limit_pom);
        //printr($row);

        return $row;
    }

    /**
     * Vrati, jaky typ vykonu (id) nalezi sluzbe s id sluzba_id
     *
     * @param $sluzba_id
     * @return mixed
     */
    public function adminGetSluzbasTypVykonuID($sluzba_id){

        $table_name = TABLE_SLUZBA;
        $where_array = array();
        $where_array[] = $this->DBHelperGetWhereItem("id", $sluzba_id);
        $limit_pom = "limit 1";

        $row = $this->DBSelectOne($table_name, "*", $where_array, $limit_pom);

        return $row["typ_vykonu_id"];
    }


    /**
     * Vrati vsechny zaznamy z tabulky TYP VYKONU
     *
     * @param string $type
     * @param int $page
     * @param int $count_on_page
     * @param array $where_array
     * @param string $order_by
     * @param string $order_by_direction
     * @return array|int
     */
    public function adminLoadTypVykonuItems($type = "data", $page = 1, $count_on_page = 12, $where_array = array(), $order_by = "id", $order_by_direction = "asc")
    {

        if ($type == "data") {
            $columns = "*";

            if ($page <= 1) $page = 1;
            $from = ($page - 1) * $count_on_page + 0;
            $limit_pom = "limit $from, $count_on_page";
        } else {
            $columns = "count(*)";
            $limit_pom = "";
        }

        $table_name = TABLE_TYP_VYKONU;
        $order_by = $this->DBHelperFixColumnName($order_by);

        $order_by_pom = array();
        $order_by_pom[] = array("column" => $order_by, "sort" => $order_by_direction);
        $where_array = array();

        $rows = $this->DBSelectAll($table_name, $columns, $where_array, $limit_pom, $order_by_pom);
        //printr($rows);

        if ($type == "data") {
            // chci data - vratit data
            return $rows;
        } else {
            // chci jen count
            $count = @$rows[0]["count(*)"] + 0;
            //echo $count;
            return $count;
        }
    }



    //
    // -------------------- TYP VÝKONU KONEC -------------------
    //













    //
    // ---------------------- PLÁN VÝKONU START-------------------
    //

    /**
     * Vrati id zaznamu z tabulky PLAN VYKONU takovy, ktery ma sluzbu_id rovnou parametru
     *
     * @param $sluzba_id
     * @return mixed
     */
    public function adminGetPlansPlanVykonuID($sluzba_id){
        $table_name = TABLE_PLAN_VYKONU;
        $where_array = array();
        $where_array[] = $this->DBHelperGetWhereItem("sluzba_id", $sluzba_id);
        $limit_pom = "limit 1";

        $row = $this->DBSelectOne($table_name, "*", $where_array, $limit_pom);

        return $row["id"];
    }

    /**
     * Vlozi novy zaznam do tabulky PLAN VYKONU
     *
     * @param $plan_vykonu_new
     * @return int
     */
    public function adminInsertPlanVykonuItem($plan_vykonu_new){
        $id = $this->DBInsert(TABLE_PLAN_VYKONU, $plan_vykonu_new);
        return $id;
    }

    /**
     * Updatuje dany zaznam v tabulce PLAN VYKONU
     *
     * @param $plan_id
     * @param $plan_new
     * @return bool
     */
    public function adminUpdatePlanVykonuItem($plan_id, $plan_new){
        $where_array = array();
        $where_array[] = array("column" => "id", "value" => $plan_id, "symbol" => "=");

        $ok = $this->DBUpdate(TABLE_PLAN_VYKONU, $where_array, $plan_new, "limit 1");
        return $ok;
    }

    /**
     * Vrati radek z tabulky PLAN VYKONU podle daneho sluzba_id
     *
     * @param $sluzba_id
     * @return mixed
     */
    public function adminGetPlanVykonuItemBySluzbaID($sluzba_id) {
        //$sluzba_id += 0;

        $table_name = TABLE_PLAN_VYKONU;
        $where_array = array();
        $where_array[] = $this->DBHelperGetWhereItem("sluzba_id", $sluzba_id);
        $limit_pom = "limit 1";

        $row = $this->DBSelectOne($table_name, "*", $where_array, $limit_pom);
        //printr($row);

        return $row;
    }

    /**
     * Vrati radek z tabulky PLAN VYKONU podle id
     *
     * @param $plan_id
     * @return mixed
     */
    public function adminGetPlanVykonuItemByID($plan_id){
        $table_name = TABLE_PLAN_VYKONU;
        $where_array = array();
        $where_array[] = $this->DBHelperGetWhereItem("id", $plan_id);
        $limit_pom = "limit 1";

        $row = $this->DBSelectOne($table_name, "*", $where_array, $limit_pom);
        //printr($row);

        return $row;
    }


    /**
     * Vrati vsechny zaznamy z tabulky PLAN VYKONU
     *
     * @param string $type
     * @param int $page
     * @param int $count_on_page
     * @param array $where_array
     * @param string $order_by
     * @param string $order_by_direction
     * @return array|int
     */
    public function adminLoadPlanVykonuItems($type = "data", $page = 1, $count_on_page = 12, $where_array = array(), $order_by = "id", $order_by_direction = "asc")
    {

        if ($type == "data") {
            $columns = "*";

            if ($page <= 1) $page = 1;
            $from = ($page - 1) * $count_on_page + 0;
            $limit_pom = "limit $from, $count_on_page";
        } else {
            $columns = "count(*)";
            $limit_pom = "";
        }

        $table_name = TABLE_PLAN_VYKONU;
        $order_by = $this->DBHelperFixColumnName($order_by);

        $order_by_pom = array();
        $order_by_pom[] = array("column" => $order_by, "sort" => $order_by_direction);
        $where_array = array();

        $rows = $this->DBSelectAll($table_name, $columns, $where_array, $limit_pom, $order_by_pom);
        //printr($rows);

        if ($type == "data") {
            // chci data - vratit data
            return $rows;
        } else {
            // chci jen count
            $count = @$rows[0]["count(*)"] + 0;
            //echo $count;
            return $count;
        }
    }

    /**
     * Vrati vsechny zaznamy z tabulky PLAN VYKONU, ktere maji sluzba_id rovno parametru
     *
     * @param $sluzba_id
     * @return array
     */
    public function adminLoadPlanVykonuItemsBySluzbaID($sluzba_id){
        $sluzba_id += 0;

        $table_name = TABLE_PLAN_VYKONU;
        $where_array = array();
        $where_array[] = $this->DBHelperGetWhereItem("sluzba_id", $sluzba_id);
        $limit_pom = "limit 1";

        $rows = $this->DBSelectAll($table_name, "*", $where_array);
        //printr($row);

        return $rows;
    }


    //
    // ---------------------- PLÁN VÝKONU KONEC -------------------
    //






    //
    // ---------------------- ZAZNAM VÝKONU START-------------------
    //


    public function adminInsertZaznamVykonuItem($zaznam_new){
        $id = $this->DBInsert(TABLE_ZAZNAM_VYKONU, $zaznam_new);
        return $id;
    }

    public function adminUpdateZaznamVykonuItem($zaznam_id, $zaznam_new){
        $where_array = array();
        $where_array[] = array("column" => "id", "value" => $zaznam_id, "symbol" => "=");

        $ok = $this->DBUpdate(TABLE_ZAZNAM_VYKONU, $where_array, $zaznam_new, "limit 1");
        return $ok;
    }


    public function adminExistsZaznamVykonuItemByID($id) {
        return $this->DBExistsItemByID(TABLE_ZAZNAM_VYKONU, $id);
    }


    public function adminGetZaznamVykonuItemsByID($plan_id){
        $plan_id += 0;

        $table_name = TABLE_ZAZNAM_VYKONU;
        $where_array = array();
        $where_array[] = $this->DBHelperGetWhereItem("plan_vykonu_id", $plan_id);
        $limit_pom = "limit 1";

        $rows = $this->DBSelectAll($table_name, "*", $where_array);
        //printr($row);

        return $rows;
    }

    public function adminGetZaznamVykonuItemByID($zaznam_id){
        $zaznam_id += 0;

        $table_name = TABLE_ZAZNAM_VYKONU;
        $where_array = array();
        $where_array[] = $this->DBHelperGetWhereItem("id", $zaznam_id);
        $limit_pom = "limit 1";

        $row = $this->DBSelectOne($table_name, "*", $where_array, $limit_pom);
        //printr($row);

        return $row;
    }

    public function adminGetZaznamVykonuItemsByPlanVykonuID($plan_id) {
        $plan_id += 0;

        $table_name = TABLE_ZAZNAM_VYKONU;
        $where_array = array();
        $where_array[] = $this->DBHelperGetWhereItem("plan_vykonu_id", $plan_id);
        $limit_pom = "limit 1";

        $rows = $this->DBSelectAll($table_name, "*", $where_array);
        //printr($row);

        return $rows;
    }


    public function adminLoadZaznamVykonuItems($type = "data", $page = 1, $count_on_page = 12, $where_array = array(), $order_by = "id", $order_by_direction = "asc")
    {

        if ($type == "data") {
            $columns = "*";

            if ($page <= 1) $page = 1;
            $from = ($page - 1) * $count_on_page + 0;
            $limit_pom = "limit $from, $count_on_page";
        } else {
            $columns = "count(*)";
            $limit_pom = "";
        }

        $table_name = TABLE_ZAZNAM_VYKONU;
        $order_by = $this->DBHelperFixColumnName($order_by);

        $order_by_pom = array();
        $order_by_pom[] = array("column" => $order_by, "sort" => $order_by_direction);
        $where_array = array();

        $rows = $this->DBSelectAll($table_name, $columns, $where_array, $limit_pom, $order_by_pom);
        //printr($rows);

        if ($type == "data") {
            // chci data - vratit data
            return $rows;
        } else {
            // chci jen count
            $count = @$rows[0]["count(*)"] + 0;
            //echo $count;
            return $count;
        }
    }


    //
    // ---------------------- ZAZNAM VÝKONU KONEC -------------------












    //
    // -------------------- DETAIL ZAZNAMU VYKONU START ---------------------
    //

    public function adminGetZaznamVykonuDetailItemByID($zaznam_detail_id){

        $table_name = TABLE_ZAZNAM_VYKONU_DETAIL;
        $where_array = array();
        $where_array[] = $this->DBHelperGetWhereItem("id", $zaznam_detail_id);
        $limit_pom = "limit 1";

        $row = $this->DBSelectOne($table_name, "*", $where_array, $limit_pom);
//        echo "row:";
//        var_dump($row);
        return $row;

    }

    public function adminGetZaznamVykonuDetailItemByZaznamID($zaznam_id){

        $table_name = TABLE_ZAZNAM_VYKONU_DETAIL;
        $where_array = array();
        $where_array[] = $this->DBHelperGetWhereItem("zaznam_vykonu_id", $zaznam_id);
        $limit_pom = "limit 1";

        $row = $this->DBSelectOne($table_name, "*", $where_array, $limit_pom);
//        echo "row:";
//        var_dump($row);
        return $row;

    }


    public function adminGetZaznamVykonuDetailItemsByZaznamID($zaznam_id){

        $table_name = TABLE_ZAZNAM_VYKONU_DETAIL;
        $where_array = array();
        $where_array[] = $this->DBHelperGetWhereItem("zaznam_vykonu_id", $zaznam_id);
        //$limit_pom = "limit 1";

        $rows = $this->DBSelectAll($table_name, "*", $where_array);
//        echo "row:";
//        var_dump($row);
        return $rows;

    }


    public function adminUpdateZaznamVykonuDetailItem($zaznam_detail_id, $zaznam_detail_new){
        $where_array = array();
        $where_array[] = array("column" => "id", "value" => $zaznam_detail_id, "symbol" => "=");

        $ok = $this->DBUpdate(TABLE_ZAZNAM_VYKONU_DETAIL, $where_array, $zaznam_detail_new, "limit 1");
        return $ok;
    }

    public function adminInsertZaznamVykonuDetailItem($zaznam_detail_new){
        $id = $this->DBInsert(TABLE_ZAZNAM_VYKONU_DETAIL, $zaznam_detail_new);
        return $id;
    }


    //
    // -------------------- DETAIL ZAZNAMU VYKONU KONEC ---------------------
    //



    //
    // -------------------- UZIVATELE START ---------------------
    //


    public function adminGetUzivatelItemByID($uzivatel_id){
        $uzivatel_id += 0;

        $table_name = TABLE_USERS_ADMIN;
        $where_array = array();
        $where_array[] = $this->DBHelperGetWhereItem("id", $uzivatel_id);
        $limit_pom = "limit 1";

        $row = $this->DBSelectOne($table_name, "*", $where_array, $limit_pom);
        //printr($row);

        return $row["jmeno"]." ".$row["prijmeni"];
    }

    public function adminLoadUzivateleItems($type = "data", $page = 1, $count_on_page = 12, $where_array = array(), $order_by = "id", $order_by_direction = "asc"){
        if ($type == "data") {
            $columns = "*";

            if ($page <= 1) $page = 1;
            $from = ($page - 1) * $count_on_page + 0;
            $limit_pom = "limit $from, $count_on_page";
        } else {
            $columns = "count(*)";
            $limit_pom = "";
        }

        $table_name = TABLE_USERS_ADMIN;
        $order_by = $this->DBHelperFixColumnName($order_by);

        $order_by_pom = array();
        $order_by_pom[] = array("column" => $order_by, "sort" => $order_by_direction);
        $where_array = array();

        $rows = $this->DBSelectAll($table_name, $columns, $where_array, $limit_pom, $order_by_pom);
        //printr($rows);

        if ($type == "data") {
            // chci data - vratit data
            return $rows;
        } else {
            // chci jen count
            $count = @$rows[0]["count(*)"] + 0;
            //echo $count;
            return $count;
        }
    }


    //
    // -------------------- UZIVATELE KONEC ---------------------
    //





    //
    // -------------------- OBYVATELE START ---------------------
    //


    public function adminGetObyvatelNameBySluzbaID($sluzba_id){
        //$sluzba_id += 0;

        $table_name = TABLE_SLUZBA;
        $where_array = array();
        $where_array[] = $this->DBHelperGetWhereItem("id", $sluzba_id);
        $limit_pom = "limit 1";

        $row = $this->DBSelectOne($table_name, "*", $where_array, $limit_pom);
        //printr($row);

        $obyvatel_id = $row["obyvatel_id"];

        $table_name = TABLE_OBYVATELE;
        $where_array = array();
        $where_array[] = $this->DBHelperGetWhereItem("id", $obyvatel_id);
        $limit_pom = "limit 1";
        $row = $this->DBSelectOne($table_name, "*", $where_array, $limit_pom);

        return $row["jmeno"]." ".$row["prijmeni"];
    }



    public function adminLoadObyvateleItems($type = "data", $page = 1, $count_on_page = 12, $where_array = array(), $order_by = "id", $order_by_direction = "asc")
    {

        if ($type == "data") {
            $columns = "*";

            if ($page <= 1) $page = 1;
            $from = ($page - 1) * $count_on_page + 0;
            $limit_pom = "limit $from, $count_on_page";
        } else {
            $columns = "count(*)";
            $limit_pom = "";
        }

        $table_name = TABLE_OBYVATELE;
        $order_by = $this->DBHelperFixColumnName($order_by);

        $order_by_pom = array();
        $order_by_pom[] = array("column" => $order_by, "sort" => $order_by_direction);
        $where_array = array();

        $rows = $this->DBSelectAll($table_name, $columns, $where_array, $limit_pom, $order_by_pom);
        //printr($rows);

        if ($type == "data") {
            // chci data - vratit data
            return $rows;
        } else {
            // chci jen count
            $count = @$rows[0]["count(*)"] + 0;
            //echo $count;
            return $count;
        }
    }

    //
    // -------------------- OBYVATELE KONEC ---------------------
    //
}