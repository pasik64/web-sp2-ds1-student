<?php

namespace ds1\admin_modules\pokoje;


class pokoje extends \ds1\core\ds1_base_model
{
    // ************************************************************************************
    // *********   START ADMIN     ********************************************************
    // ************************************************************************************

    /**
     * Existuje polozka dle ID? Pozor: kontrola pouze existence ID. Nebere se v uvahu VIDITELNOST.
     * @param $id
     * @return bool
     */
    public function adminExistsItemByID($id) {
        return $this->DBExistsItemByID(TABLE_POKOJE, $id);
    }

    /**
     * Test existence pokoje dle parametru.
     *
     * @param $params - pole hodnot pro hledani
     * @return bool
     */
    public function adminExistsPokojByParams($pokoj) {
        $table_name = TABLE_POKOJE;

        $where_array = array();

        // prihodit tam vsechny podminky
        if ($pokoj != null){
            foreach ($pokoj as $key => $value) {
                $where_array[] = $this->DBHelperGetWhereItem("$key", $pokoj[$key]);
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

        $table_name = TABLE_POKOJE;
        $where_array = array();
        $where_array[] = $this->DBHelperGetWhereItem("id", $id);
        $limit_pom = "limit 1";

        $row = $this->DBSelectOne($table_name, "*", $where_array, $limit_pom);
        //printr($row);

        return $row;
    }

    public function adminInsertItem($item) {
        $id = $this->DBInsert(TABLE_POKOJE, $item);
        return $id;
    }

    /**
     * TODO
     * @param $id
     */
    public function adminDeleteItemTODO($id) {
        $id += 0;

        $where_array = array();
        $where_array[] = array("column" => "id", "value" => $id, "symbol" => "=");


        //$ok = $this->DBDelete();
    }

    public function adminUpdateItem($id, $item) {

        $where_array = array();
        $where_array[] = array("column" => "id", "value" => $id, "symbol" => "=");

        $ok = $this->DBUpdate(TABLE_POKOJE, $where_array, $item, "limit 1");
        return $ok;
    }

    /**
     * Admin - nacist pokoje.
     *
     * @param string $type - data nebo count
     * @param int $page
     * @param int $count_on_page = -1 = vse
     * @param string $order_by
     * @return mixed
     */
    public function adminLoadItems($type = "data", $page = 1, $count_on_page = 12, $where_array = array(), $order_by = "id", $order_by_direction = "asc")
    {

        if ($type == "data") {
            $columns = "*";

            if ($page <= 1) $page = 1;
            $from = ($page - 1) * $count_on_page + 0;

            if ($count_on_page > 0) {
                $limit_pom = "limit $from, $count_on_page";
            }
            else {
                // limit se nepouzije, chci vsechno
                $limit_pom = "";
            }

        } else {
            $columns = "count(*)";
            $limit_pom = "";
        }

        $table_name = TABLE_POKOJE;
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

    public function loadAllSkupinyPokoju($use_id_as_key = false) {
        $where_array = array();
        $order_by = array();

        $items = $this->DBSelectAll(TABLE_SKUPINY_POKOJU, "*", $where_array, "", $order_by);
        //printr($items);

        if ($use_id_as_key) {
            $pom = $items;
            $items = array();

            if ($pom != null)
                foreach ($pom as $item) {
                    $items[$item["id"]] = $item["nazev"];
                }
        }

        return $items;
    }


    // ************************************************************************************
    // *********   KONEC ADMIN     ********************************************************
    // ************************************************************************************
}