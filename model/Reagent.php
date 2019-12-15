<?php

require_once __DIR__."/../config/autoload.php";

class Reagent{
    //class properties
    private $id;
    private $lab_id;
    private $name;
    private $formula;
    private $cas;
    private $location;
    private $private;
    private $secure;

    //construct method. will return false if reagent is unreadable due to nonexistent id or insufficient privileges
    public function __construct($id = null){
        if ($id) {
            $data = Database::selectReagent("WHERE id = $id");
            if (!$data) {
                return false;
            }
            $params = $data[0];
            $this->id = $params['id'];
            $this->lab_id($params['lab_id']);
            $this->name($params['name']);
            $this->formula($params['formula']);
            $this->cas($params['cas']);
            $this->location($params['location']);
            $this->private($params['private']);
            $this->secure($params['secure']);
        }
    }

    //get-set methods
    public function id(){
        return $this->id;
    }

	public function lab_id($lab_id = null){
		if ($lab_id) {
			$this->lab_id = $lab_id;
		}
		return $this->lab_id;
    }
    
    public function name($name = null){
		if ($name) {
            $name = strtolower($name);
            $name = trim($name);
			$this->name = $name;
		}
		return $this->name;
    }
    
    public function formula($formula = null){
		if ($formula) {
			$this->formula = $formula;
		}
		return $this->formula;
    }
    
    public function cas($cas = null){
		if ($cas) {
			$this->cas = $cas;
		}
		return $this->cas;
    }
    
    public function location($location = null){
		if ($location) {
			$this->location = $location;
		}
		return $this->location;
    }
    
    public function private($tinyint = null){
		if (isset($tinyint)) {
			$this->private = $tinyint > 0;
		}
		return $this->private?1:0;
    }
    
    public function secure($tinyint = null){
		if (isset($tinyint)) {
			$this->secure = $tinyint > 0;
		}
		return $this->secure?1:0;
    }
    
    //basic methods
    public function save(){
        $params = [
            'lab_id' => $this->lab_id(),
            'name' => $this->name(),
            'formula' => $this->formula(),
            'cas' => $this->cas(),
            'location' => $this->location(),
            'private' => $this->private(),
            'secure' => $this->secure(),
        ];

        if ($this->id()) {
            Database::updateReagent($params, $this->id());
        }else{
            Database::insertReagent($params);
        }
    }

    public function delete(){
        if ($_SESSION['role'] == 'admin' && $_SESSION['lab'] == $this->lab_id()) {
            Database::deleteReagent($this->id());
        }else{
            echo "Unable to edit row: no permissions.";
        }
    }

    //static methods
    public static function getListByLab($lab_id){
        $where = "WHERE lab_id = $lab_id";
        $data = Database::selectReagent($where);

        $list = [];
        foreach ($data as $row) {
            array_push($list, new Reagent($row['id']));
        }

        return $list;
    }

    public static function getListByFormula($formula){
        $where = "WHERE formula = '$formula'";
        $data = Database::selectReagent($where);

        $list = [];
        foreach ($data as $row) {
            array_push($list, new Reagent($row['id']));
        }
        
        return $list;
    }

    public static function getByCAS($cas){
        $where = "WHERE CAS = '$cas'";
        $data = Database::selectReagent($where);

        if (!data) {
            return false;
        } else {
            return new Reagent($data[0]['id]']);
        }
    }
    
    public static function getListByKeyword($keyword){
        $keyword = strtolower($keyword);
        $where = "WHERE name LIKE '%$keyword%' OR formula LIKE '%$keyword%'";           // not sure about this but it was like that when i got here so i didn't change anything
        $data = Database::selectReagent($where);

        $list = [];
        foreach ($data as $row) {
            array_push(new Reagent($row['id']));
        }

        return $list;
    }
}

 ?>