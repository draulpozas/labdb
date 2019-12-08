<?php
require_once __DIR__."./../config/autoload.php";
session_start();
ReagentController::pageEdit($_GET['id']);