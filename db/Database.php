<?php

require_once __DIR__."/../config/autoload.php";

class Database{
    //connection property
    private static $connection;

    //connection method
    private static function connect(){
        try{
			self::$connection = new PDO(CONN_STRING, DB_USER, DB_PASS);
		} catch (PDOException $e){
			echo "Error al conectar a la base de datos: ".$e->getMessage();
			die();
		}
    }

    //query method
    private static function query($file, $replace){
		if (!self::$connection) {
			self::connect();
		}

		$query = file_get_contents(__DIR__."/sql/$file");
        $query = strtr($query, $replace);
        $stm = self::$connection->prepare($query);
		$stm->execute();
		return $stm->fetchAll();
    }

    //specific methods------------------
    #laboratory methods
    public static function insertLab($params){
        $file = 'insertLab.sql';
        $replace = [
            '{{name}}' => $params['name'],
            '{{manager}}' => $params['manager'],
            '{{manager_email}}' => $params['manager_email'],
        ];

        return self::query($file, $replace);
    }

    public static function updateLab($params, $id){
        $file = 'updateLab.sql';
        $replace = [
            '{{name}}' => $params['name'],
            '{{manager}}' => $params['manager'],
            '{{manager_email}}' => $params['manager_email'],
            '{{id}}' => $id,
        ];

        return self::query($file, $replace);
    }

    public static function selectLab($where = ''){
        $file = 'selectLab.sql';
        $replace = [
            '{{where}}' => $where,
        ];

        return self::query($file, $replace);
    }

    public static function deleteLab($id){
        if (!self::$connection) {
			self::connect();
        }
        $query = file_get_contents(__DIR__."/sql/_deleteLab.sql");
        $stm = self::$connection->prepare($query);
        $stm->bindParam(1, $id);

        try{
            $stm->execute();
        }catch(PDOException $e){
            echo "Database error - could not delete: " . $e->getMessage();
        }
    }

    #reagent methods
    public static function insertReagent($params){
        $file = 'insertReagent.sql';
        $replace = [
            '{{lab_id}}' => $params['lab_id'],
            '{{name_common}}' => $params['name_common'],
            '{{formula}}' => $params['formula'],
            '{{CAS}}' => $params['cas'],
            '{{locations}}' => $params['locations'],
            '{{private}}' => $params['private'],
            '{{secure}}' => $params['secure'],
        ];

        return self::query($file, $replace);
    }

    public static function updateReagent($params, $id){
        $file = 'updateReagent.sql';
        $replace = [
            '{{lab_id}}' => $params['lab_id'],
            '{{name_common}}' => $params['name_common'],
            '{{formula}}' => $params['formula'],
            '{{CAS}}' => $params['cas'],
            '{{locations}}' => $params['locations'],
            '{{private}}' => $params['private'],
            '{{secure}}' => $params['secure'],
            '{{id}}' => $id,
        ];

        self::query($file, $replace);
    }

    public static function selectReagent($where = ''){
        $file = 'selectReagent.sql';
        $replace = [
            '{{WHERE}}' => $where,
        ];

        return self::query($file, $replace);
    }

    public static function selectReagentFromSecureView($where = ''){
        $file = 'selectReagentFromSecureView.sql';
        $replace = [
            '{{WHERE}}' => $where,
        ];

        return self::query($file, $replace);
    }

    public static function selectReagentFromPrivateView($where = ''){
        $file = 'selectReagentFromPrivateView.sql';
        $replace = [
            '{{WHERE}}' => $where,
        ];

        return self::query($file, $replace);
    }

    public static function deleteReagent($id){
        if (!self::$connection) {
			self::connect();
        }
        $query = file_get_contents(__DIR__."/sql/_deleteReagent.sql");
        $stm = self::$connection->prepare($query);
        $stm->bindParam(1, $id);

        try{
            $stm->execute();
        }catch(PDOException $e){
            echo "Database error - could not delete: " . $e->getMessage();
        }
    }
}

 ?>