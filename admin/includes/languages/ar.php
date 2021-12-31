<?php 

	function lang($key) {
		static $lang = array(
			'HOME'=>'الرئيسية',
			'CATEGORIES' => 'التصنيفات',
			'EDIT_PROFILE' => 'تعديل الملف الشخصي',
			'SETTINGS' => 'الإعدادات',
			'LOGOUT' => 'الخروج',
			'yasser'=>'ياسر',
			//index page

			'NAME' => 'الإسم',
			'PASSWORD'=>'كلمه السر',
			'LOGIN'=>'تسجيل الدخول',
			'DASHBOARD'=>'لوحة القيادة',

			//member page

			'EDIT_MEMBER'=>'تعديل العضو',
			'USER_NAME'=>'اسم االمستخدم',
			'EDIT' => 'تعديل',
			'ADD_MEMBER'=>'إضافة عضو',
		);
		return array_key_exists($key, $lang)?
		$lang[$key]:$key;
	}

?>