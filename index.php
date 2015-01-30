<?php
require_once 'core/init.php';

$userInsert = DB::getInstance()->update('users', 8, array(
	'password' => 'newpassword',
	'name' => 'dave'
));


