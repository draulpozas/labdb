<?php

require_once __DIR__."./../config/autoload.php";

class ReagentController{
    public static function pageNew(){
        if ($_SESSION['role'] != 'admin') {
            echo "<script>window.alert(\"No puedes añadir reactivos: no eres administrador.\")</script><a href=\"./\">Volver</a>";
        }else if ($_POST) {
            $rgt = new Reagent();
            $rgt->lab_id($_SESSION['lab']);
            $rgt->name_common($_POST['name_common']);
            $rgt->formula($_POST['formula']);
            $rgt->cas($_POST['cas']);
            $rgt->locations($_POST['locations']);
            $rgt->isPrivate(isset($_POST['private'])?1:0);
            $rgt->isSecure(isset($_POST['secure'])?1:0);
            $rgt->save();

            echo '<div class="main-container">Guardado. <a href="./">Volver</a></div>';
        }else{
            View::createReagent();
        }
    }
    
    public static function pageSearch(){
        if ($_POST) {
            $results;

            if ($_POST['b'] == 'Buscar por fórmula') {
                $results = Reagent::getListByFormula($_POST['formula']);
            }
            elseif ($_POST['b'] == 'Buscar por palabra clave') {
                $results = Reagent::getListByKeyword($_POST['keyword']);
            }
            elseif ($_POST['b'] == 'Buscar por código CAS') {
                $results = Reagent::getListByCAS($_POST['cas']);
            }

            $items_str = '';
            for ($i=0; $i < count($results); $i++) { 
                $rgt = $results[$i];
                $id = $rgt->id();
                $name_common = $rgt->name_common();
                $formula = $rgt->formula();
                $lab_name = (new Lab($rgt->lab_id()))->name();
                $locations = $rgt->locations();
                $cas = trim($rgt->cas());
                if ($_SESSION['lab'] == $rgt->lab_id()) {
                    $items_str .= "<li><a href=\"edit.php?id=$id\">$name_common ($formula)</a>, en $lab_name ($locations). <a href=\"https://pubchem.ncbi.nlm.nih.gov/#query=$cas\" target=\"blank\">Ver referencia online.</a><div class=\"structuralDiagram\"><img src=\"http://www.commonchemistry.org/images/structuralDiagrams/$cas.png\"></div></li>";
                }else{
                    $items_str .= "<li>$name_common ($formula), en $lab_name ($locations). <a href=\"https://pubchem.ncbi.nlm.nih.gov/#query=$cas\" target=\"blank\">Ver referencia online.</a><div class=\"structuralDiagram\"><img src=\"http://www.commonchemistry.org/images/structuralDiagrams/$cas.png\"></div></li>";
                }
            }
            View::searchReagent($items_str);
        }else{
            View::searchReagent();
        }
    }

    public static function pageEdit($id){
        $rgt = new Reagent($_GET['id']);
        if ($_SESSION['role'] != 'admin') {
            echo "<script>window.alert(\"No puedes editar reactivos: no eres administrador.\")</script><a href=\"./\">Volver</a>";
        }else if ($_SESSION['lab'] != $rgt->lab_id()) {
            echo "<script>window.alert(\"No puedes editar reactivos ajenos.\")</script><a href=\"./\">Volver</a>";
        }else if ($_POST) {
            if ($_POST['submit'] == 'Guardar') {
                $rgt = new Reagent($_GET['id']);
                $rgt->lab_id(intval($_SESSION['lab']));
                $rgt->name_common($_POST['name_common']);
                $rgt->formula($_POST['formula']);
                $rgt->cas($_POST['cas']);
                $rgt->locations($_POST['locations']);
                $rgt->isPrivate(isset($_POST['private'])?1:0);
                $rgt->isSecure(isset($_POST['secure'])?1:0);
                $rgt->save();
                echo 'Guardado. <a href="./">Volver</a>';
            }elseif ($_POST['submit'] == 'Eliminar registro') {
                $rgt = new Reagent($_GET['id']);
                $rgt->delete();
                echo 'Eliminado. <a href="./">Volver</a>';
            }
        }else{
            $rgt = new Reagent($id);
            $replace = [
                '{{name_common}}' => $rgt->name_common(),
                '{{cas}}' => $rgt->cas(),
                '{{formula}}' => $rgt->formula(),
                '{{locations}}' => $rgt->locations(),
                '{{private_checked}}' => ($rgt->isPrivate()? 'checked="checked"': ''),
                '{{private_disabled}}' => ($rgt->lab_id() == $_SESSION['lab']? '': 'disabled=""'),
                '{{secure_checked}}' => ($rgt->isSecure()? 'checked="checked"': ''),
            ];

            View::editReagent($replace);
        }
    }
}

?>