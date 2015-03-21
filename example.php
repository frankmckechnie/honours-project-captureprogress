<?php
require_once 'core/init.php';

$user = DB::getInstance()->query("SELECT * FROM users");


if (!$user->count()){
	echo 'no users';
}else{
	foreach ($user->results() as $user) {
		echo $user->username, '<br>';
	}
}

$user = DB::getInstance()->get('users', array('username','=','jameslol'));


if (!$user->count()){
	echo 'no users';
}else{
	echo $user->first()->username;
}

$userInsert = DB::getInstance()->update('users', 8, array(
	'password' => 'newpassword',
	'name' => 'dave'
));

	print_r($this->_data);

		$data = $this->_db->get('users', array($field, '=', $user));