<?php

namespace ds1\admin_modules\sprava_uzivatelu;

use PDO;
use ds1\core\ds1_base_model;

class sprava_uzivatelu extends ds1_base_model
{
    /**
     * Funkce vrátí údaje uživatele, který má požadovaný (zadaný) login.
     * @param $login login uživatele
     * @return mixed údaje uživatele z DB
     */
    public function getUzivatelByLogin($login){
        $table_name = TABLE_USERS_ADMIN;
        $where_array = array();
        $where_array[] = $this->DBHelperGetWhereItem("login", $login);
        $limit_pom = "limit 1";
        $uzivatel_data = $this->DBSelectOne($table_name, "*", $where_array, $limit_pom);
        return $uzivatel_data;
    }

    public function getUzivatelskeRole() {
        $where_array = array();
        $rows = $this->DBSelectAll("ds1_uzivatelske_role", "*", $where_array, "", "");
        return $rows;
    }

    /**
     * Metoda vrátí z DB řádek obsahující informace o rolích požadovaného uživatele
     * @param $id_uzivatel id požadovaného uživatele v tabulce uživatelů
     * @return mixed řádek z DB obsahující informace o roli požadovaného uživatele
     */
    public function getRoleUzivatelByIDUzivatel($id_uzivatel){
        $table_name = TABLE_USERS_ADMIN;
        $where_array = array();
        $where_array[] = $this->DBHelperGetWhereItem("id", $id_uzivatel);
        $limit_pom = "limit 1";
        $uzivatel_data = $this->DBSelectOne($table_name, "*", $where_array, $limit_pom);

        $table_name = TABLE_UZIVATELE_PRIDELENI_ROLI;
        $where_array = array();
        $where_array[] = $this->DBHelperGetWhereItem("uzivatele_id", $uzivatel_data["id"]);
        $limit_pom = "";
        $prideleni_roli_data = $this->DBSelectAll($table_name, "*", $where_array, $limit_pom);

        $table_name = TABLE_UZIVATELSKE_ROLE;

        $konkretni_role_data = array();
        foreach ($prideleni_roli_data as $role_data) {
            if ($role_data["uzivatele_role_id"] != "-1") {
                $where_array = array();
                $where_array[] = $this->DBHelperGetWhereItem("id", $role_data["uzivatele_role_id"]);
                $limit_pom = "limit 1";
                $data = $this->DBSelectOne($table_name, "*", $where_array, $limit_pom);
                array_push($konkretni_role_data, $data);
            }
        }

        return $konkretni_role_data;
    }

    /**
     * Metoda vrátí z DB řádky obsahující informace o typech dokumentace, ke kterým má přístup role s požadovaným názvem
     * @param $role_nazev název role, o níž chci informace zjistit
     * @return array řádky DB obsahující informace o typech dokumentace, ke kterým má přístup role s požadovaným názvem
     */
    public function getDruhyZapisuPristupByNazevRole($role_nazev){
        $where_array = array();
        $where_array[] = $this->DBHelperGetWhereItem("nazev", $role_nazev);
        $limit_pom = "limit 1";
        $id_role = $this->DBSelectOne(TABLE_UZIVATELSKE_ROLE, "*", $where_array, $limit_pom);

        $where_array = array();
        $where_array[] = $this->DBHelperGetWhereItem("uzivatelske_role_id", $id_role["id"]);
        $druhy_zapisu_pristup_id = $this->DBSelectAll(TABLE_DRUH_ZAPISU_UZIVATELSKE_ROLE, "*", $where_array, "", "");

        $druhy_zapisu_pristup = array();
        foreach($druhy_zapisu_pristup_id as $id_pristup){
            $where_array = array();
            $where_array[] = $this->DBHelperGetWhereItem("id", $id_pristup["dokumentace_druh_zapisu_id"]);
            $druhy_zapisu_pristup_id_jedno = $this->DBSelectAll(TABLE_DOKUMENTACE_DRUH_ZAPISU, "*", $where_array, "", "");

            //nechceme pole v poli = foreach
            foreach($druhy_zapisu_pristup_id_jedno as $id_pristup){
                array_push($druhy_zapisu_pristup, $id_pristup);
            }
        }

        return $druhy_zapisu_pristup;
    }

    public function getDbObjektyPrideleniByIdRole($role_id){
        $where_array = array();
        $where_array[] = $this->DBHelperGetWhereItem("uzivatelske_role_id", $role_id);
        $objekty_prideleni = $this->DBSelectAll(TABLE_UZIVATELSKE_ROLE_DB_OBJEKTY, "*", $where_array, "", "");

        return $objekty_prideleni;
    }

    public function getDbObjekty(){
        $where_array = array();
        $objekty_prideleni = $this->DBSelectAll(TABLE_OBJEKTY, "*", $where_array, "", "");

        return $objekty_prideleni;
    }

    public function addNewRole($role_nazev){

        $item = array();
        $item["nazev"] = $role_nazev;
        $this -> DBInsert(TABLE_UZIVATELSKE_ROLE, $item);
    }

    public function saveRolePravaToObject($role_id, $objekt_id, $prava) {

        $where_array = array();
        $where_array[] = $this->DBHelperGetWhereItem("uzivatelske_role_id", $role_id);
        $where_array[] = $this->DBHelperGetWhereItem("db_objekty_id", $objekt_id);
        $prirazeni = $this->DBSelectOne(TABLE_UZIVATELSKE_ROLE_DB_OBJEKTY, "*", $where_array, "limit 1");
        $item = array();
        $read_used = false;
        $insert_used = false;
        $update_used = false;
        $delete_used = false;
        if ($prirazeni != null) {
            if ($prava != null) {
                foreach ($prava as $pravo) {
                    if ($pravo == "read") {
                        $item["`read`"] = 1;
                        $read_used = true;
                    }
                    if ($pravo == "insert") {
                        $item["`insert`"] = 1;
                        $insert_used = true;
                    }
                    if ($pravo == "update") {
                        $item["`update`"] = 1;
                        $update_used = true;
                    }
                    if ($pravo == "delete") {
                        $item["`delete`"] = 1;
                        $delete_used = true;
                    }
                }
            }
            if (!$read_used) {
                $item["`read`"] = 0;
            }
            if (!$insert_used) {
                $item["`insert`"] = 0;
            }
            if (!$update_used) {
                $item["`update`"] = 0;
            }
            if (!$delete_used) {
                $item["`delete`"] = 0;
            }
            $this->DBUpdate(TABLE_UZIVATELSKE_ROLE_DB_OBJEKTY, $where_array, $item, "");
        } else {
            if ($prava != null) {
                foreach ($prava as $pravo) {
                    if ($pravo == "read") {
                        $item["read"] = 1;
                        $read_used = true;
                    }
                    if ($pravo == "insert") {
                        $item["insert"] = 1;
                        $insert_used = true;
                    }
                    if ($pravo == "update") {
                        $item["update"] = 1;
                        $update_used = true;
                    }
                    if ($pravo == "delete") {
                        $item["delete"] = 1;
                        $delete_used = true;
                    }
                }
            }
            if (!$read_used) {
                $item["read"] = 0;
            }
            if (!$insert_used) {
                $item["insert"] = 0;
            }
            if (!$update_used) {
                $item["update"] = 0;
            }
            if (!$delete_used) {
                $item["delete"] = 0;
            }
            $item["uzivatelske_role_id"] = $role_id;
            $item["db_objekty_id"] = $objekt_id;
            $this->DBInsert(TABLE_UZIVATELSKE_ROLE_DB_OBJEKTY, $item);
        }

    }

    /**
     * Funkce přidělí uživateli s předaným id novou roli (také specifikovanou id)
     * @param $uzivatel_id id uživatele, kterému chceme přidat / upravit roli
     * @param $role_id id nové role, které chceme uživateli přidělit
     */
    public function saveAdminPridelRoleDB($uzivatel_id, $role_id){
        //nejdříve si zjistím, jestli uživatel již má přidělenou nějakou roli
        $predchozi_role_existuje = false;

        $where_array = array();
        $where_array[] = $this->DBHelperGetWhereItem("uzivatele_id", $uzivatel_id);
        $id_role = $this->DBSelectAll(TABLE_UZIVATELE_PRIDELENI_ROLI, "*", $where_array, "");

        if(sizeof($id_role) > 0){
            $predchozi_role_existuje = true;
        }else{
            $predchozi_role_existuje = false;
        }

        if(!$predchozi_role_existuje) { //pokud neexistuje předchozí role, pak vytvořím nový záznam
            foreach ($role_id as $role_jedna) {

                $item = array();
                $item["uzivatele_id"] = $uzivatel_id;
                $item["uzivatele_role_id"] = $role_jedna;
                $this->DBInsert(TABLE_UZIVATELE_PRIDELENI_ROLI, $item);
            }
        }else{ //předchozí role existuje, jenom updatuji existující záznam
            $item = array();
            $item["uzivatele_role_id"] = -1;
            $this->DBUpdate(TABLE_UZIVATELE_PRIDELENI_ROLI, $where_array, $item, "");
            $reused_roles = 0;

            foreach ($role_id as $role_jedna) {
                if ($reused_roles < sizeof($id_role)) {
                    $item = array();
                    $item["uzivatele_id"] = $uzivatel_id;
                    $item["uzivatele_role_id"] = $role_jedna;
                    $where_array = array();
                    $where_array[] = array("column" => "uzivatele_id", "value" => $uzivatel_id, "symbol" => "=");
                    $where_array[] = array("column" => "uzivatele_role_id", "value" => "-1", "symbol" => "=");
                    $this->DBUpdate(TABLE_UZIVATELE_PRIDELENI_ROLI, $where_array, $item, "limit 1");
                    $reused_roles++;
                } else {
                    $item = array();
                    $item["uzivatele_id"] = $uzivatel_id;
                    $item["uzivatele_role_id"] = $role_jedna;
                    $this->DBInsert(TABLE_UZIVATELE_PRIDELENI_ROLI, $item);
                }
            }
        }
    }

    /**
     * Funkce vrátí id role specifikované názvem
     * @param $nazev_role název role, jejíž id chceme získat
     * @return mixed id specifikované role
     */
    public function getRoleIDByNazevRole($nazev_role){
        $where_array = array();
        $where_array[] = $this->DBHelperGetWhereItem("nazev", $nazev_role);
        $limit_pom = "limit 1";
        $id_role = $this->DBSelectOne(TABLE_UZIVATELSKE_ROLE, "id", $where_array, $limit_pom);
        $id_role = $id_role["id"]; //odstraním pole

        return $id_role;
    }

    /**
     * Funkce vrátí název role specifikované pomocí id
     * @param $id_role id role, jejíž název chceme získat
     * @return název specifikované role
     */
    public function getRoleNazevByIdRole($id_role){
        $where_array = array();
        $where_array[] = $this->DBHelperGetWhereItem("id", $id_role);
        $limit_pom = "limit 1";
        $nazev_role = $this->DBSelectOne(TABLE_UZIVATELSKE_ROLE, "nazev", $where_array, $limit_pom);
        $nazev_role = $nazev_role["nazev"];

        return $nazev_role;
    }

    /**
     * Funkce vrátí název objektu specifikovaného pomocí id
     * @param $id_objekt id objektu, jehož název chceme získat
     * @return název specifikovaného objektu
     */
    public function getObjektNazevByIdObjektu($id_objekt){
        $where_array = array();
        $where_array[] = $this->DBHelperGetWhereItem("id", $id_objekt);
        $limit_pom = "limit 1";
        $nazev_objektu = $this->DBSelectOne(TABLE_OBJEKTY, "nazev", $where_array, $limit_pom);
        $nazev_objektu = $nazev_objektu["nazev"];

        return $nazev_objektu;
    }

    public function getPrideleniPravByIdObjektuAndIdRole($id_objekt, $id_role){
        $where_array = array();
        $where_array[] = $this->DBHelperGetWhereItem("db_objekty_id", $id_objekt);
        $where_array[] = $this->DBHelperGetWhereItem("uzivatelske_role_id", $id_role);
        $limit_pom = "limit 1";
        $prideleni = $this->DBSelectOne(TABLE_UZIVATELSKE_ROLE_DB_OBJEKTY, "*", $where_array, $limit_pom);

        return $prideleni;
    }

    /**
     * Funkce vrátí veškerá možná příjmení z tabulky uživatlů (může být využito například jako nápověda při vyhledávání).
     * @return array pole obsahující příjmení uživatelů systému
     */
    public function getVsechnaPrijmeniUzivatele(){
        // $napoveda_prijmeni_uzivatelu
        $where_array = array();
        $limit_pom = "";
        $uzivatele_prijmeni = $this->DBSelectAll(TABLE_USERS_ADMIN, "prijmeni", $where_array, $limit_pom);

        $pole_vraceni = array();
        //odstraním pole v poli (chci jenom samotná příjmení)
        foreach($uzivatele_prijmeni as $prijmeni){
            if(!in_array($prijmeni["prijmeni"], $pole_vraceni)) {
                array_push($pole_vraceni, $prijmeni["prijmeni"]);
            }
        }

        return $pole_vraceni;
    }

    /**
     * Metoda vrátí data všech uživatelů z tabulky uživatelů
     * @return array pole obsahující informace o všech uživatelů
     */
    public function getDataVsechUzivatelu(){
        $where_array = array();
        $limit_pom = "";
        $data_uzivatelu = $this->DBSelectAll(TABLE_USERS_ADMIN, "*", $where_array, $limit_pom, "");

        return $data_uzivatelu;
    }

    /**
     * Funkce vrátí data všech uživatelů, které mají specifikované příjmení z tabulky uživatelů
     * @param $prijmeni příjmení uživatelů, které chceme vypsat
     * @return array pole obsahující informace o uživatelích se zadaných příjmením
     */
    public function getDataUzivateluPrijmeni($prijmeni){
        // $napoveda_prijmeni_uzivatelu
        $where_array = array();
        $where_array[] = $this->DBHelperGetWhereItem("prijmeni", $prijmeni);
        $limit_pom = "";
        $uzivatele_prijmeni = $this->DBSelectAll(TABLE_USERS_ADMIN, "*", $where_array, $limit_pom);

        return $uzivatele_prijmeni;
    }
}