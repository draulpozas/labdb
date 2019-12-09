<?php

require_once __DIR__."/../config/autoload.php";

class Database{
    //connection property
    private static $connection;

    /**
    * Connection method. It will connect to the database and assign the new PDO object to the $connection property.
    */
    private static function connect(){
        try{
            $conn_data = json_decode(file_get_contents(__DIR__."/../config/connection.json"), true);
            self::$connection = new PDO($conn_data['CONN_STRING'], $conn_data['DB_USER'], $conn_data['DB_PASS']);
		} catch (PDOException $e){
			echo "Database error: ".$e->getMessage();
			die();
		}
    }

    /**
    * Query method. It will use the $connection property to communicate directly with the database.
    * It receives the .sql path and a replace array for replacing the correspondent values in the predefined query string.
    * It also allows to specify if the replace array fields should be sanitized or not.
    */
    private static function query($file, $replace, $sanitize = false){
		if (!self::$connection) {
			self::connect();
        }
        
        if ($sanitize) {
            $replace = self::sanitizeReplace($replace);
        }

        $query = file_get_contents(__DIR__."/sql/$file");
        $query = strtr($query, $replace);
        $stm = self::$connection->prepare($query);
		$stm->execute();
		return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
    * Basic sanitazing method. Escapes quotes and double quotes.
    */
    private static function sanitizeReplace($replace){
        $final = [];
        
        foreach ($replace as $field => $value) {
            $value = str_replace('\'', '\\\'', $value);
            $value = str_replace('\"', '\\\"', $value);
            // $value = str_replace('<', '\<', $value);
            // $value = str_replace('>', '\>', $value);
            $final[$field] = $value;
        }
        return $final;
    }

    //specific methods------------------
    #lab methods
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
        $file = 'deleteLab.sql';
        $replace = [
            '{{id}}' => $id,
        ];

        return self::query($file, $replace);
    }

    #reagent methods
    public static function insertReagent($params){
        $file = 'insertReagent.sql';
        $replace = [
            '{{lab_id}}' => $params['lab_id'],
            '{{name}}' => $params['name'],
            '{{formula}}' => $params['formula'],
            '{{cas}}' => $params['cas'],
            '{{location}}' => $params['location'],
            '{{private}}' => $params['private'],
            '{{secure}}' => $params['secure'],
        ];

        return self::query($file, $replace);
    }

    public static function updateReagent($params, $id){
        $file = 'updateReagent.sql';
        $replace = [
            '{{lab_id}}' => $params['lab_id'],
            '{{name}}' => $params['name'],
            '{{formula}}' => $params['formula'],
            '{{cas}}' => $params['cas'],
            '{{location}}' => $params['location'],
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

    public static function deleteReagent($id){
        $file = 'deleteReagent.sql';
        $replace = [
            '{{id}}' => $id,
        ];

        return self::query($file, $replace);
    }

    #user methods
    public static function insertUser($params){
        $file = 'insertUser.sql';
        $replace = [
            '{{username}}' => $params['username'],
            '{{passwd}}' => $params['passwd'],
            '{{role}}' => $params['role'],
            '{{lang}}' => $params['lang'],
        ];

        return self::query($file, $replace);
    }

    public static function updateUser($params, $id){
        $file = 'updateUser.sql';
        $replace = [
            '{{username}}' => $params['username'],
            '{{passwd}}' => $params['passwd'],
            '{{role}}' => $params['role'],
            '{{lang}}' => $params['lang'],
            '{{id}}' => $id,
        ];

        return self::query($file, $replace);
    }

    public static function selectUser($where = ''){
        $file = 'selectUser.sql';
        $replace = [
            '{{WHERE}}' => $where,
        ];

        return self::query($file, $replace);
    }

    public static function deleteUser($id){
        $file = 'deleteUser.sql';
        $replace = [
            '{{id}}' => $id,
        ];

        return self::query($file, $replace);
    }

    #history methods
    public static function insertHistory($params){
        $file = 'insertHistory.sql';
        $replace = [
            '{{user_id}}' => $params['user_id'],
            '{{search}}' => $params['search'],
        ];

        return self::query($file, $replace);
    }

    public static function selectHistory($where = ''){
        $file = 'selectHistory.sql';
        $replace = [
            '{{WHERE}}' => $where,
        ];

        return self::query($file, $replace);
    }

    public static function deleteHistory($search_id){
        $file = 'deleteHistory.sql';
        $replace = [
            '{{search_id}}' => $search_id,
        ];

        return self::query($file, $replace);
    }

    #member_of methods
    public static function insertMemberOf($user_id, $lab_id){
        $file = 'insertMemberOf.sql';
        $replace = [
            '{{user_id}}' => $user_id,
            '{{lab_id}}' => $lab_id,
        ];

        return self::query($file, $replace);
    }

    
    public static function selectMemberOf($where = ''){
        $file = 'selectMemberOf.sql';
        $replace = [
            '{{WHERE}}' => $where,
        ];

        return self::query($file, $replace);
    }

    public static function deleteMemberOf($user_id, $lab_id){
        $file = 'deleteMemberOf.sql';
        $replace = [
            '{{user_id}}' => $user_id,
            '{{lab_id}}' => $lab_id,
        ];

        return self::query($file, $replace);
    }

}

 ?>