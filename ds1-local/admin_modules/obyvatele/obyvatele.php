<?php

namespace ds1\admin_modules\obyvatele;


use DateTime;
use DateTimeZone;
use ds1\admin_modules\pokoje\pokoje;
use PDO;

class obyvatele extends \ds1\core\ds1_base_model
{


    /**
     * Spocita vek k aktualnimu dni nebo ke dni v minulosti.
     * @param $born_date
     * @param string $current_date - k jakemu dni urcit vek, nechat prazdne pro aktualni datum
     *
     * @return int|void
     */
    public function getAge($born_date, $current_date = "") {
        // pokud je prazdne, tak vratit
        if (trim($born_date) == "") return;

        $tz  = new DateTimeZone('Europe/Brussels');

        if ($current_date == "")
        {
            $current_date_datetime = new DateTime('now',$tz);
        }
        else {
            $current_date_datetime = DateTime::createFromFormat('Y-m-d', $current_date, $tz);
        }

        //echo "born:";
        $born_datetime = DateTime::createFromFormat('Y-m-d', $born_date, $tz);
        //printr($born_datetime);

        // kontrola objektu
        if (!is_object($current_date_datetime)) return;

        // vlastni vypocet
        $age = $born_datetime->diff($current_date_datetime)->y;
        //echo $age;

        return $age;
    }

    // ************************************************************************************
    // *********   START ADMIN     ********************************************************
    // ************************************************************************************


    /**
     * Existuje polozka dle ID? Pozor: kontrola pouze existence ID. Nebere se v uvahu VIDITELNOST.
     * @param $id
     * @return bool
     */
    public function adminExistsItemByID($id) {
        return $this->DBExistsItemByID(TABLE_OBYVATELE, $id);
    }

    /**
     * Test existence obyvatele specialne pro pridavani noveho obyvatele.
     *
     * @param $params - pole hodnot pro hledani
     * @return bool
     */
    public function adminExistsObyvatelByParams($obyvatel) {
        $table_name = TABLE_OBYVATELE;

        $where_array = array();

        // prihodit tam vsechny podminky
        if ($obyvatel != null){
            foreach ($obyvatel as $key => $value) {
                $where_array[] = $this->DBHelperGetWhereItem("$key", $obyvatel[$key]);
            }

            $limit_pom = "limit 1";
            $row = $this->DBSelectOne($table_name, "*", $where_array, $limit_pom);
            //echo "obyv by params";
            //printr($row);

            if ($row != null)
                return true;
            else
                return false;
        }

        return null;
    }

    public function adminGetItemByID($id) {
        $id += 0;

        $table_name = TABLE_OBYVATELE;
        $where_array = array();
        $where_array[] = $this->DBHelperGetWhereItem("id", $id);
        $limit_pom = "limit 1";

        $row = $this->DBSelectOne($table_name, "*", $where_array, $limit_pom);
        //printr($row);

        return $row;
    }

    public function adminInsertItem($item) {
        $id = $this->DBInsert(TABLE_OBYVATELE, $item);
        return $id;
    }

    public function adminUpdateItem($id, $item) {

        $where_array = array();
        $where_array[] = array("column" => "id", "value" => $id, "symbol" => "=");

        $ok = $this->DBUpdate(TABLE_OBYVATELE, $where_array, $item, "limit 1");
        return $ok;
    }


    /**
     * Doplnit informaci do seznamu obyvatel. Napr. vek a aktualni pokoj.
     * Tato metoda se musi volat pro rozumny pocet zaznamu, jinak bude trvat moc dlouho.
     */
    public function adminObyvateleAddedInformationToList($list) {
        // projit seznam a doplnit
        if ($list != null)
            foreach ($list as $index => $item) {
                // dopocitat vek
                $list[$index]["vek"] = $this->getAge($item["datum_narozeni"]);

                // zjistit aktualni pokoj = k dnesnimu dni
                $pokoj = $this->getAktualniPokojByObyvatelID($item["id"]);
                $list[$index]["pokoj"] = $pokoj;

                if (isset($pokoj["nazev"])) {
                    $list[$index]["pokoj_nazev"] = $pokoj["nazev"];
                }
                else {
                    $list[$index]["pokoj_nazev"] = "";
                }
            }

        //printr($list);
        return $list;
    }

    /**
     * Admin - nacist obyvatele.
     *
     * @param string $type - data nebo count
     * @param int $page
     * @param int $count_on_page
     * @param string $order_by
     * @return mixed
     */
    public function adminLoadItems($type = "data", $page = 1, $count_on_page = 12, $where_array = array(), $order_by = "id", $order_by_direction = "asc")
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

            // doplnit chybejici info
            $rows = $this->adminObyvateleAddedInformationToList($rows);
            return $rows;
        } else {
            // chci jen count
            $count = @$rows[0]["count(*)"] + 0;
            //echo $count;
            return $count;
        }
    }


    /**
     * Hledani obyvatel.
     *
     * @param string $search
     * @param string $type
     * @param int $page
     * @param int $count_on_page
     * @param string $order_by_field
     * @return int
     *
     */
    public function adminSearchItems($search = "", $type = "data", $page = 1, $count_on_page = 50, $order_by_field = "prijmeni") {
        $table_name = TABLE_OBYVATELE;
        $count_on_page += 0;
        $search = trim($search);

        if ($type == "data") {
            $columns = "*";

            if ($page <= 1) $page = 1;
            $from = ($page - 1) * $count_on_page + 0;
            $limit_pom = "limit $from, $count_on_page";
        }
        else {
            $columns = "count(*)";
            $limit_pom = "";
        }

        // podminka - musim BINDOVAT
        $where_pom = "";
        if ($search != "") {
            $where_pom .= "where ";

            // pomocne
            $search_cislo = $search + 0;

            // POZOR: musi to byt bez uvozovek, jinak to nechodi!!!
            // spravne: `prijmeni` like :search_like
            // spatne: `prijmeni` like %:search_like% NEBO `prijmeni` like "%:search_like%"
            $where_pom .= "`prijmeni` like :search_like ";        // prijmeni
            $where_pom .= "or `jmeno` like :search_like ";        // prijmeni
            $where_pom .= "or `pojistovna_zkratka` = :search ";     // pojistovna


            if ($search_cislo > 0) {
                $where_pom .= "or `id` = :search ";             // id

                // navic test na vek TODO
                /*
                if ($search_cislo > 10000) {
                    // muze to byt isbn
                    $where_pom .= "or `isbn` like :search_like ";      // isbn
                }
                */
            }
        }
        // konec podminka

        $order_by_field = $this->DBHelperFixColumnName($order_by_field);
        $order_by_pom = "order by `$order_by_field` ASC";

        // slozit query
        $query = "select $columns from `$table_name` $where_pom $order_by_pom $limit_pom";
        //echo $query;

        // echo $query;
        $statement = $this->PrepareStatement($query);

        // bind parametru
        if (!$statement->bindValue(":search_like", "%{$search}%")) {
            //echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        if (!$statement->bindValue(":search", $search)) {
            //echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        // nebo takto v execute:
        // $statement->execute(array(":search_like" => "%{$search}%", ":search" => $search));

        // provest dotaz
        $statement->execute();

        // kontrola chyb
        $errors = $statement->errorInfo();
        //printr($errors);

        $mysql_pdo_error = false;
        if ($errors[0] + 0 > 0)
        {
            // nalezena chyba
            $mysql_pdo_error = true;
        }

        // nacist data a vratit
        if ($mysql_pdo_error == false)
        {
            if ($type == "data") {
                $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

                // doplnit chybejici info
                $rows = $this->adminObyvateleAddedInformationToList($rows);
                return $rows;
            } else {
                // count
                $row = $statement->fetch(PDO::FETCH_ASSOC);
                return $row["count(*)"] + 0;
            }

        }
        else
        {
            // show debug info if it is allowed
            if (DB_SHOW_DEBUG_INFO)
            {
                echo "Error in query - PDOStatement::errorInfo(): ";
                printr($errors);
                echo "SQL: $query";
            }
        }
    }



    /**
     * Pomocna metoda pro ziskani dat ze stare tabulky z puvodniho eshopu.
     * @return mixed
     */
/*  public function adminLoadAllItemsFromTable($table_name) {
        return $this->DBSelectAll($table_name, "*", array(), "");
    }
*/


    // *****************  UBYTOVANI ***********************

    /**
     * Vrati aktualni pokoj pro obyvatele k dnesnimu dni.
     * @param $obyvatel_id
     *
     * @return array
     */
    public function getAktualniPokojByObyvatelID($obyvatel_id)
    {
        $obyvatel_id += 0;

        if ($obyvatel_id == 0) return;

        // select bohuzel obsahuje subselect, coz neni uplne idealni varianta z duvodu vykonosti, ale lepe to ted nejde
        // v dotazu neni treba resit [or datum_do = ""], melo by to byt null nebo nejaky datum
        /*
         * select p.*,op.datum_od, op.datum_do from ds1_obyvatele_na_pokojich op, ds1_pokoje p
            where
            op.obyvatel_id = 1 and
            op.pokoj_id = p.id and
            op.datum_od =
            (
            select max(datum_od) from ds1_obyvatele_na_pokojich
            where obyvatel_id = 1 and datum_od <= now() and (datum_do > now() or datum_do is null)
            )
         */

        // slozit query
        $query = "select p.*,op.datum_od, op.datum_do from `".TABLE_OBYVATELE_NA_POKOJICH."` op, `".TABLE_POKOJE."` p
                    where
                        op.obyvatel_id = :obyvatel_id and
                        op.pokoj_id = p.id and
                        op.datum_od = ( select max(datum_od) from `".TABLE_OBYVATELE_NA_POKOJICH."`
                                        where 
                                            obyvatel_id = :obyvatel_id and 
                                            datum_od <= now() and
                                            (datum_do > now() or datum_do is null) 
                                       )
                    limit 1";
        //echo $query;
        $statement = $this->PrepareStatement($query);

        // bind parametru
        if (!$statement->bindValue(":obyvatel_id", "{$obyvatel_id}")) {

        }

        // provest dotaz
        $statement->execute();

        // kontrola chyb
        $errors = $statement->errorInfo();
        //printr($errors);

        $mysql_pdo_error = false;
        if ($errors[0] + 0 > 0)
        {
            // nalezena chyba
            $mysql_pdo_error = true;
        }

        // nacist data a vratit
        if ($mysql_pdo_error == false)
        {

            $row = $statement->fetch(PDO::FETCH_ASSOC);
            return $row;
        }
        else
        {
            // show debug info if it is allowed
            if (DB_SHOW_DEBUG_INFO)
            {
                echo "Error in query - PDOStatement::errorInfo(): ";
                printr($errors);
                echo "SQL: $query";
            }
        }
    }

    public function adminInsertUbytovaniObyvatele($item) {
        //printr($item);

        if (!isset($item["obyvatel_id"])) {
            return false;
        } else {
            $item["obyvatel_id"] += 0;
        }

        // pokud neni datum do zadan, tak vyhodim, aby bylo null
        if (trim($item["datum_do"]) == "") {
            unset($item["datum_do"]);
        }

        $id = $this->DBInsert(TABLE_OBYVATELE_NA_POKOJICH, $item);
        return $id;
    }

    /**
     * @param $id - id v tabulce obyvatele_na_pokojich
     * @param $obyvatel_id
     * @return bool
     */
    public function adminDeleteUbytovaniObyvatele($id, $obyvatel_id) {
        $id += 0;
        $obyvatel_id += 0;

        if ($id > 0 && $obyvatel_id > 0) {
            // mohu smazat
            $where_array = array();
            $where_array[] = $this->DBHelperGetWhereItem("id", $id);
            $where_array[] = $this->DBHelperGetWhereItem("obyvatel_id", $obyvatel_id);

            $limit_pom = "limit 1";

            $ok = $this->DBDelete(TABLE_OBYVATELE_NA_POKOJICH, $where_array, $limit_pom);
            return $ok;
        }
        else
            return false;
    }

    /**
     * Vytahne data z tabulky obyvatele_na_pokojich
     * TODO u teto metody by sel zvednout vykon, pokud by bylo treba
     */
    public function adminLoadAllUbytovaniObyvatelu($obyvatel_id, $pokoj_id = -1) {
        $obyvatel_id += 0;
        $pokoj_id += 0;

        // vytvorit pomocny objekt pro praci s pokoji
        // staci mi to lokalne, globalne to zatim nepotrebuji
        $pokoje = new pokoje($this->GetPDOConnection());

        $table_name = TABLE_OBYVATELE_NA_POKOJICH;

        // razeni
        $order_by_pom = array();
        $order_by_pom[] = array("column" => "datum_od", "sort" => "desc");

        // podminka
        $where_array = array();

        // podminka na obyvatele, pokud je
        if ($obyvatel_id > 0){
            $where_array[] = $this->DBHelperGetWhereItem("obyvatel_id", $obyvatel_id);
        }

        // podminka na pokoj, pokud je
        if ($pokoj_id > 0){
            $where_array[] = $this->DBHelperGetWhereItem("pokoj_id", $pokoj_id);
        }

        // bez limitu na pocet
        $limit_pom = "";

        $rows = $this->DBSelectAll($table_name, "*", $where_array, $limit_pom, $order_by_pom);
        //printr($rows);

        // projit zaznamy a doplnit informace o pokojich a obyvatelich FIXME - sla by zvysit vykonnost
        if ($rows != null) {
            foreach ($rows as $rows_index => $row){
                //printr($row);

                // pridat info o pokoji
                $pokoj_detail = $pokoje->adminGetItemByID($row["pokoj_id"]);
                //printr($pokoj_detail);

                $rows[$rows_index]["pokoj"] = $pokoj_detail;
                $rows[$rows_index]["pokoj_nazev"] = $pokoj_detail["nazev"];
                $rows[$rows_index]["pokoj_poschodi"] = $pokoj_detail["poschodi"];

                // pridat info o obyvateli
                $obyvatel_detail = $this->adminGetItemByID($row["obyvatel_id"]);
                //printr($obyvatel_detail);
                $rows[$rows_index]["obyvatel"] = $obyvatel_detail;
            }
        }

        return $rows;
    }






    // ************************************************************************************
    // *********   KONEC ADMIN     ********************************************************
    // ************************************************************************************

}