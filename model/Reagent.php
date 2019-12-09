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
            $params = Database::selectReagent("where id = $id")[0];
            if ($_SESSION['role'] == 'user' && $params[7] == 1) {
                return false;
            }
            $this->id = intval($id);
            $this->lab_id(intval($params[1]));
            $this->name($params[2]);
            $this->formula($params[3]);
            $this->cas($params[4]);
            if ($_SESSION['lab'] == $params[1] || $params[6] == 0) {
                $this->location($params[5]);
            }else{
                $this->location('private');
            }
            $this->private(intval($params[6]));
            $this->secure(intval($params[7]));
        }
    }

    //get-set methods
    public function id(){
        return $this->id;
    }

	public function lab_id($lab_id = null){
		if ($lab_id && is_numeric($lab_id)) {
			$this->lab_id = $lab_id;
		}
		return $this->lab_id;
    }
    
    public function name($name = null){
		if ($name) {
            $name = strtolower($name);
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
		if (isset($tinyint) && is_numeric($tinyint)) {
			$this->private = $tinyint > 0;
		}
		return $this->private?1:0;
    }
    
    public function secure($tinyint = null){
		if (isset($tinyint) && is_numeric($tinyint)) {
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

        if ($_SESSION['role'] == 'admin' && $_SESSION['lab'] == $this->lab_id()) {
            if ($this->id) {
                Database::updateReagent($params, $this->id());
            }else{
                Database::insertReagent($params);
            }
        }else{
            echo "Unable to edit row: no permissions.";
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

        /*
        if ($_SESSION['lab'] == $lab_id) {
            $where = "WHERE lab_id = $lab_id";
        }else{
            $where = "WHERE lab_id = $lab_id AND private = 0";
        }

        if ($_SESSION['role'] == 'admin') {
            $data = Database::selectReagent($where);
        }else{
            $data = Database::selectReagentFromSecureView($where);
        }
        */

        $list = [];

        for ($i=0; $i < count($data); $i++) { 
            $rgt = new Reagent($data[$i][0]);
            if ($rgt) {
                array_push($list, $rgt);
            }
        }

        return $list;
    }

    public static function getListByFormula($formula){
        $where = "WHERE formula = '$formula'";
        $data = Database::selectReagent($where);

        /*
        $lab = $_SESSION['lab'];
        $where = "WHERE (formula = '$formula' AND private = 0) OR (formula = '$formula' AND lab_id = $lab)";
        if ($_SESSION['role'] == 'admin') {
            $data = Database::selectReagent($where);
        }else{
            $data = Database::selectReagentFromSecureView($where);
        }
        */

        $list = [];

        for ($i=0; $i < count($data); $i++) { 
            $rgt = new Reagent($data[$i][0]);
            if ($rgt->id()) {
                array_push($list, $rgt);
            }
        }
        
        return $list;
    }

    public static function getListByCAS($cas){
        $where = "WHERE CAS = '$cas'";
        $data = Database::selectReagent($where);

        /*
        $lab = $_SESSION['lab'];
        $where = "WHERE (cas = '$cas' AND private = 0) OR (cas = '$cas' AND lab_id = $lab)";

        if ($_SESSION['role'] == 'admin') {
            $data = Database::selectReagent("$where");
        }else{
            $data = Database::selectReagentFromSecureView("$where");
        }
        */

        $list = [];

        for ($i=0; $i < count($data); $i++) { 
            $rgt = new Reagent($data[$i][0]);
            if ($rgt->id()) {
                array_push($list, $rgt);
            }
        }

        return $list;
    }
    
    public static function getListByKeyword($keyword){
        $keyword = strtolower($keyword);
        $where = "WHERE name LIKE '%$keyword%'";
        $data = Database::selectReagent($where);

        /*
        $lab = $_SESSION['lab'];
        $where = "WHERE (name_systematic LIKE '%$keyword%' OR name LIKE '%$keyword%') AND (private = 0 OR lab_id = $lab)";
        if ($_SESSION['role'] == 'admin') {
            $data = Database::selectReagent($where);
        }else{
            $data = Database::selectReagentFromSecureView($where);
        }
        */

        $list = [];

        for ($i=0; $i < count($data); $i++) { 
            $rgt = new Reagent($data[$i][0]);
            if ($rgt->id()) {
                array_push($list, $rgt);
            }
        }

        return $list;
    }
}

 ?>