<?php

require_once __DIR__."/../config/autoload.php";

class User{
    // class properties
    private $id;
    private $username;
    private $passwd;
    private $role;
    private $lang;

    // constructor
    public function __construct($id = null){
        if ($id) {
            $data = Database::selectUser("WHERE id = $id");
            if (count($data) < 1) {
                return false;
            }
            $params = $data[0];
            $this->id = $params['id'];
            $this->username = $params['username'];
            $this->passwd = $params['passwd'];
            $this->role = $params['role'];
            $this->lang = $params['lang'];
        }
    }

    // get-set methods
    public function id(){
        return $this->id;
    }

    public function username($username = null){
        if ($username){
            $this->username = $username;
        }
        return $this->username;
    }

    public function passwd($passwd = null){
        if ($passwd){
            $this->passwd = password_hash($passwd, PASSWORD_DEFAULT);
        }
        return $this->passwd;
    }

    public function role($role = null){
        if ($role){
            $this->role = $role;
        }
        return $this->role;
    }

    public function lang($lang = null){
        if ($lang){
            $this->lang = $lang;
        }
        return $this->lang;
    }

    //basic methods
    /**
    * Save method. Collects the values of the object's properties, then it uses the Database class to save the information in the database.
    * It inserts or updates depending on whether or not an id has been set. This is because the id is only generated when the row is inserted in the database;
    * thus, if id is not defined, the object is considered a new row yet to be inserted in the database; if it has an id, it represents data loaded from a database record.
    */
    public function save(){
        $params = [
            'username' => $this->username(),
            'passwd' => $this->passwd(),
            'role' => $this->role(),
            'lang' => $this->lang(),
        ];

        if ($this->id()) {
            return Database::updateUser($params, $this->id());
        } else {
            return Database::insertUser($params);
        }
    }

    /**
    * Deletes the row from the database table "chat" where the id is equal to the id of the current object.
    */
    public function delete(){
        Database::deleteUser($this->id());
    }

    // other methods
    public function getLabs(){
        $data = Database::selectMemberOf("WHERE user_id = '". $this->id() ."'");

        $labs = [];
        foreach ($data as $row) {
            array_push($labs, (new Lab($row['lab_id'])));
        }

        return $labs;
    }

    public function belongsToLab($lab_id){
        $labs = $this->getLabs();

        foreach ($labs as $lab) {
            if ($lab_id = $lab->id()){
                return true;
            }
        }

        return false;
    }

    public function verify($username, $passwd){
        if ($username == $this->username() && password_verify($passwd, $this->passwd())){
            return $this->id();
        } else {
            return false;
        }
    }

    // static methods
    public static function getAll(){
        $data = Database::selectUser();

        $usrs = [];
        foreach ($data as $row) {
            array_push($usrs, (new User($row['id'])));
        }

        return $usrs;
    }

    /**
     * Checks whether or not the credentials given as a parameter are valid for any existing user.
     * If they are, it returns that user's id. If they are not, it returns false.
     */
    public static function checkLogin($username, $passwd){
        $usr = User::getFromUsername($username);
        if (!$usr) {
            return false;
        } else {
            return $usr->verify($username, $passwd);
        }
    }

    /**
     * Returns a User object where the username is equal to the one given as a parameter.
     * If no user is found for the provided username, it returns false.
     */
    public static function getFromUsername($username){
        $data = Database::selectUser("WHERE username = '$username'");
        if ($data) {
            $usr = new User($data[0]['id']);
            return $usr;
        }
        return false;
    }
}

 ?>