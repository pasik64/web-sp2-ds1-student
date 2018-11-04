<?php


namespace ds1\admin_modules\obyvatele;


class obyvatele extends \ds1\core\ds1_base_model
{

    // ************************************************************************************
    // *********   START ADMIN     ********************************************************
    // ************************************************************************************

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

    // ************************************************************************************
    // *********   KONEC ADMIN     ********************************************************
    // ************************************************************************************

}