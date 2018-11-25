<?php

namespace ds1\admin_modules\obyvatele;


class obyvatele extends \ds1\core\ds1_base_model
{

    // ************************************************************************************
    // *********   START ADMIN     ********************************************************
    // ************************************************************************************


    /**
     * Existuje polozka dle ID? Pozor: kontrola pouze existence ID. Nebere se v uvahu VIDITELNOST zbozi.
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
     * Admin - nacist obyvatele.
     *
     * @param string $type - data nebo count
     * @param int $page
     * @param int $count_on_page
     * @param array $search_params_sql - primo do tvaru pro sql
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
        $count_on_page += 0;
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


    /*
    public function adminSearchItems($search = "", $type = "data", $page = 1, $count_on_page = 50, $order_by_field = "nazev") {
        $table_name = TABLE_GOODS;
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

        // podminka
        $where_pom = "";
        if ($search != "") {
            $where_pom .= "where ";

            // pomocne
            $search_cislo = $search + 0;

            // FIXME pres bindovani to nechodi
            //$where_pom .= "`nazev` like \"%:search%\" ";

            $where_pom .= "`nazev` like \"%$search%\" ";        // nazev

            if ($search_cislo > 0) {
                $where_pom .= "or `id` = \"$search\" ";             // id
                $where_pom .= "or `cena_s_dph` = \"$search\" ";     // cena s dph

                // navic test na ISBN
                if ($search_cislo > 10000) {
                    // muze to byt isbn
                    $where_pom .= "or `isbn` like \"%$search%\" ";      // isbn
                }
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

        // FIXME bind parametr - uz nepotrebuju, musel jsem dat rucne
        // nechodi
        //$statement->bindValue(1, $search);
        //printr($statement);
        //bindValue(':calories', $calories, PDO::PARAM_INT);

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
    */



    /**
     * Pomocna metoda pro ziskani dat ze stare tabulky z puvodniho eshopu.
     * @return mixed
     */
/*  public function adminLoadAllItemsFromTable($table_name) {
        return $this->DBSelectAll($table_name, "*", array(), "");
    }
*/

    // ************************************************************************************
    // *********   KONEC ADMIN     ********************************************************
    // ************************************************************************************

}