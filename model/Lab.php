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
            $params = Database::selectLab("where id = $id")[0];
            $this->name = $params[1];
            $this->manager = $params[2];
            $this->manager_email = $params[3];
            $this->id = $params[0];
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
        if ($manager_email && filter_var($manager_email, FILTER_VALIDATE_EMAIL)){
            $manager_email = filter_var($manager_email, FILTER_SANITIZE_EMAIL);
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

    //static methods
    public static function getList($where = ''){
        $data = Database::selectLab($where);;
        $list = [];

        for ($i=0; $i < count($data); $i++) { 
            $lab = new Lab($data[$i][0]);
            array_push($list, $lab);
        }

        return $list;
    }
}

 ?>