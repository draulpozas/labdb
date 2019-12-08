<?php
require_once __DIR__."./../config/autoload.php";

class Login{
    public static function main(){
        if ($_POST) {
            $_SESSION['role'] = $_POST['role'];
            $_SESSION['lab'] = $_POST['lab'];
            header("Location: ./index.php");
        }
        $html = file_get_contents ("../view/templates/login.html");
        $list = Lab::getList();
        $labs = '';
        for ($i=0; $i < count($list); $i++) { 
            $lab_id = $list[$i]->id();
            $lab_name = $list[$i]->name();
            $labs .= "<option value=\"$lab_id\">$lab_name</option>";
        }

        $replace = [
            '{{labs}}' => $labs,
        ];
        echo strtr($html, $replace);
    }
}

 ?>