<?php

require_once __DIR__."/../config/autoload.php";

class View{
    public static function infoPage($title, $text){
        $html = file_get_contents(__DIR__.'/templates/infoPage.html');
        $html = strtr($html, ['{{title}}' => $title, '{{text}}' => $text]);
        $html = self::addHeader($html);
        // $html = strtr($html, self::lang());
        echo $html;
    }

    public static function newReagent($replace){
        $html = file_get_contents(__DIR__.'/templates/newReagent.html');
        $html = strtr($html, $replace);
        $html = self::addHeader($html);
        // $html = strtr($html, self::lang());
        echo $html;
    }

    public static function searchReagent($replace){
        $html = file_get_contents(__DIR__.'/templates/searchReagent.html');
        $html = strtr($html, $replace);
        $html = self::addHeader($html);
        $html = strtr($html, self::lang());
        echo $html;
    }

    public static function editReagent($replace){
        $html = file_get_contents(__DIR__.'/templates/editReagent.html');
        $html = strtr($html, $replace);
        $html = self::addHeader($html);
        $html = strtr($html, self::lang());
        echo $html;
    }

    private static function addHeader($html){
        return strtr($html, ['{{header}}' => file_get_contents(__DIR__.'/templates/header.html')]);
    }

    private static function lang(){
        $lang;
        if (!isset($_SESSION['id'])) {
            $lang = json_decode(file_get_contents(__DIR__.'/lang/_en_.json'), true);
        } else {
            $usr = new User($_SESSION['id']);
            switch ($usr->lang()) {
                case 'es':
                    $lang = json_decode(file_get_contents(__DIR__.'/lang/_es_.json'), true);
                    break;
                default:
                    $lang = json_decode(file_get_contents(__DIR__.'/lang/_en_.json'), true);
                    break;
            }
        }

        return $lang;
    }
}
 ?>