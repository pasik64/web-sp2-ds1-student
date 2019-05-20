<?php

namespace ds1\admin_modules\api_zaznam_vykonu;


use PDO;

class zaznam_vykonu extends \ds1\core\ds1_base_model
{
    public function getAktualniZaznamyByUzivatelId($uzivatel_id){
        
        $table_record_name = "ds1_zaznam_vykonu";//TABLE_ZAZNAM_VYKONU;
        $table_resident_name = "ds1_obyvatele";
        // slozit query
        $query = "SELECT $table_record_name.datum_od, $table_record_name.datum_do, $table_record_name.poznamka, 
                         $table_resident_name.jmeno, $table_resident_name.prijmeni, $table_resident_name.datum_narozeni
                  FROM `$table_record_name`
                  INNER JOIN $table_resident_name ON $table_record_name.obyvatel_id=$table_resident_name.id
                  WHERE DATE($table_record_name.datum_od) = CURDATE() and $table_record_name.uzivatel_id=:uzivatel_id order by $table_record_name.datum_od ASC";

        // echo $query;
        $statement = $this->PrepareStatement($query);

        // bind parametru
        $statement->bindValue(":uzivatel_id", $uzivatel_id);

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
            $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

                // doplnit chybejici info
            return $rows;
            
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
    
    public function updateRecord($id, $item) {

        $where_array = array();
        $where_array[] = array("column" => "id", "value" => $id, "symbol" => "=");

        $updated = $this->DBUpdate("ds1_zaznam_vykonu", $where_array, $item, "limit 1");
        return $updated;
    }
    
    public function insertDetailObecny($item) {
        $id = $this->DBInsert("ds1_zaznam_vykonu_detail_obecny", $item);
        return $id;
    }

    public function insertDetailLeky($item) {
        $id = $this->DBInsert("ds1_zaznam_vykonu_detail_leky", $item);
        return $id;
    }

    public function getDetailOptionsByDetailId($detail_id) {
        $where_array = array();
        $where_array[] = $this->DBHelperGetWhereItem("detaily_vykonu_id", $detail_id);
        $rows = $this->DBSelectAll("ds1_detaily_vykonu_moznosti", "*", $where_array, "", "asc");
        return $rows;
    }

    public function getDetailList() {
        $rows = $this->DBSelectAll("ds1_detaily_vykonu", "*", array(), "", "asc");
        return $rows;
    }

}