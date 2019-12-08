<?php
require_once __DIR__."/../config/autoload.php";
session_start();
if (!isset($_SESSION['role']) || !isset($_SESSION['lab'])) {    
    Login::main();
}else{
    View::init();
}
?>
