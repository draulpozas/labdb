<?php

require_once __DIR__."/../config/autoload.php";

class Lab{
    //class properties
    private $id;
    private $name;
    private $manager;
    private $manager_email;

    //construct method
    public function __construct($id = null){
        if ($id) {
            $data = Database::selectLab("WHERE id = $id");
            var_dump($data);
            if (!$data) {
                return false;
            }
            $params = $data[0];
            $this->id = $params['id'];
            $this->name($params['name']);
            $this->manager($params['manager']);
            $this->manager_email($params['manager_email']);
        }
    }

    //get-set methods
    public function id(){
        return $this->id;
    }

    public function name($name = null){
        if ($name)
            $this->name = $name;
        return $this->name;
    }

    public function manager($manager = null){
        if ($manager)
            $this->manager = $manager;
        return $this->manager;
    }

    public function manager_email($manager_email = null){
        if ($manager_email){
            $this->manager_email = $manager_email;
        }
        return $this->manager_email;
    }

    //basic methods
    public function save(){
        $params = [
            'name' => $this->name(),
            'manager' => $this->manager(),
            'manager_email' => $this->manager_email(),
        ];

        if ($this->id) {
            Database::updateLab($params, $this->id());
        }else{
            Database::insertLab($params);
        }
    }

    public function deleteLab(){
        Database::deleteLab($this->id());
    }

    // other methods
    public function addUser($user_id){
        if (new User($user_id)) {
            return Database::insertMemberOf($user_id, $this->id());
        } else {
            return false;
        }
    }

    public function removeUser($user_id){
        $usr = new User($user_id);
        if ($usr && $usr->belongsToLab($this->id())) {
            return Database::deleteMemberOf($user_id, $this->id());
        } else {
            return false;
        }
    }

    //static methods
    public static function getAll(){
        $data = Database::selectLab();

        $list = [];
        foreach ($data as $row) {
            array_push($list, new Lab($row['id']));
        }

        return $list;
    }

    public static function getByUser($user_id){
        $data = Database::selectMemberOf("WHERE user_id = '$user_id'");

        $list = [];
        foreach ($data as $row) {
            array_push($list, new Lab($row['id']));
        }

        return $list;
    }
}

 ?>