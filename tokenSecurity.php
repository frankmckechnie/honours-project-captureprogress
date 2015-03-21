<?php
header("Content-Type: application/json");
require_once 'core/init.php';
$myVariable = Token::generate();

echo json_encode(array(array("myVariable" => $myVariable)));