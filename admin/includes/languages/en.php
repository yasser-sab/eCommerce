<?php 

	function lang($key) {
		static $lang = array(
			'HOME'=>'home',
			'CATEGORIES' => 'categories',
			'EDIT_PROFILE' => 'edit profile',
			'SETTINGS' => 'settings',
			'LOGOUT' => 'logout',

			//member page

			'EDIT_MEMBER'=>'edit member',
			'USER_NAME'=>'user name',
			'EDIT' => 'edit',
			'PASSWORD' => 'password',
		);
		return array_key_exists($key, $lang)?
		$lang[$key]:$key;
	}

?>