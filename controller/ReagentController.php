<?php

require_once __DIR__."./../config/autoload.php";

class ReagentController{
    public static function pageNew(){
        $usr = new User($_SESSION['id']);
        if ($usr->role() != 'admin') {
            View::infoPage('{{unavailable}}', '{{insufficientpermissions}}');
        }else if ($_POST) {
            $rgt = new Reagent();
            $rgt->lab_id($_SESSION['lab']);
            $rgt->name($_POST['name']);
            $rgt->formula($_POST['formula']);
            $rgt->cas($_POST['cas']);
            $rgt->location($_POST['location']);
            $rgt->private(isset($_POST['private'])?1:0);
            $rgt->secure(isset($_POST['secure'])?1:0);
            $rgt->save();

            View::infoPage('{{saved}}', $rgt->getHtml());
        }else{
            View::newReagent();
        }
    }
    
    public static function pageSearch(){
        if ($_POST) {
            $rgts = [];
            if (isset($_POST['formula_search'])) {
                $rgts = Reagent::getListByFormula($_POST['formula']);
            } else if (isset($_POST['keyword_search'])) {
                $rgts = Reagent::getListByKeyword($_POST['keyword']);
            } else if (isset($_POST['cas_search'])) {
                $rgts = Reagent::getByCAS($_POST['cas']);
            }

            $list = [];
            foreach ($rgts as $rgt) {
                $name = $rgt->name();
                $formula = $rgt->formula();
                $location = '';
                if ($rgt->isAvailableToUser($_SESSION['id'])) {
                    array_push($list, '<div class="reagentLine">' . $name .'('. $formula .'): '. $location .'</div>');
                } else {
                    array_push($list, '<div class="reagentLine">' . $name .'('. $formula .'): {{cantaccesslocation}}</div>');
                }
            }

            $replace = [
                '{{results}}' => $list,
            ];

            View::searchReagent($replace);
        } else {
            View::searchReagent(['{{results}}' => '']);
        }
    }

    public static function pageEdit($id){
        $rgt = new Reagent($id);
        $usr = new User($_SESSION['id']);
        if ($_POST) {
            if ($usr->role() == 'admin' && $rgt->isAvailableToUser($usr->id())) {
                $rgt->lab_id($_POST['lab_id']);
                $rgt->name($_POST['name']);
                $rgt->formula($_POST['formula']);
                $rgt->cas($_POST['cas']);
                $rgt->location($_POST['location']);
                $rgt->private(isset($_POST['private'])?1:0);
                $rgt->secure(isset($_POST['secure'])?1:0);
                $rgt->save();
                View::infoPage();
            } else {
                View::infoPage('{{unavailable}}', '{{insufficientpermissions}}');
            }
        } else {
            if ($usr->role() == 'admin' && $rgt->isAvailableToUser($usr->id())) {
                $replace = [
                    '{{lab_id}}' => $rgt->lab_id(),
                    '{{name}}' => $rgt->name(),
                    '{{formula}}' => $rgt->formula(),
                    '{{cas}}' => $rgt->cas(),
                    '{{location}}' => $rgt->location(),
                    '{{privatecheck}}' => $rgt->private()?'checked="checked"':'',
                    '{{securecheck}}' => $rgt->secure()?'checked="checked"':'',
                ];
                View::editReagent($replace);
            } else {
                View::infoPage('{{unavailable}}', '{{insufficientpermissions}}');
            }
        }
    }
}

?>