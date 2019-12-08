<?php

require_once __DIR__."./../config/autoload.php";

class View{
    public static function init(){
        $html = file_get_contents("./index.html");

        $replace = [
            '{{role}}' => $_SESSION['role'],
            '{{lab}}' => (new Lab($_SESSION['lab']))->name(),
        ];

        echo strtr($html, $replace);
    }

    public static function createReagent(){
        $html = file_get_contents(__DIR__."./templates/newReagentPage.html");
        $html = self::addHeader($html);

        echo $html;
    }

    public static function searchReagent($items_str = null){
        $html = file_get_contents(__DIR__."./templates/searchReagentPage.html");
        $html = self::addHeader($html);

        if ($items_str) {
            $replace = ['{{items}}' => $items_str,];
        }
        else{
            $replace = ['{{items}}' => "-",];
        }
        echo strtr($html, $replace);
    }

    public static function editReagent($replace){
        $html = file_get_contents(__DIR__."./templates/editReagentPage.html");
        $html = self::addHeader($html);

        echo strtr($html, $replace);
    }

    private static function addHeader($html){
        $header = file_get_contents(__DIR__."./templates/header.html");
        $header = strtr($header, ['{{role}}' => $_SESSION['role'], '{{lab}}' => (new Lab($_SESSION['lab']))->name(),]);

        return strtr($html, ['{{header}}' => $header]);
    }
}
 ?>