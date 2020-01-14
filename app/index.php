<?php

require_once __DIR__."/../config/autoload.php";

session_start();
$_SESSION['id'] = 1;
ReagentController::pageNew();

 ?>