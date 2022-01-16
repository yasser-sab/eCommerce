<?php 

	function lang($key) {
		static $lang = array(
			// navigation

			'HOME'=>'الرئيسية',
			'CATEGORIES' => 'التصنيفات',
			'EDIT_PROFILE' => 'تعديل الملف الشخصي',
			'SETTINGS' => 'الإعدادات',
			'LOGOUT' => 'الخروج',
			'yasser'=>'ياسر',

			//index page

			'ADMIN_LOGIN'=>'دخول المشرف',
			'NAME' => 'الإسم',
			'PASSWORD'=>'كلمه السر',
			'LOGIN'=>'تسجيل الدخول',
			'DASHBOARD'=>'لوحة القيادة',

			//member page

			'EDIT_USERS'=>'تعديل العضو',
			'USER_NAME'=>'اسم االمستخدم',
			'EDIT' => 'تعديل',
			'ADD_USERS'=>'إضافة عضو',
				// edit section

			'USERID'=>'معرف المستخدم',
			'USERNAME'=>'اسم االمستخدم',
			'PASSWORD'=>'كلمه السر',
			'EMAIL'=>'البريد الإلكتروني',
				//add section

			'ADD_USER'=>'إضافة مستخدم',
			'INSERT'=>'إضافة',

			//categories page
				//add section
			
			'ADD_CATEGORIES'=>'إضافة فئة',
			'DESCRIPTION'=>'وصف',
			'ORDERING'=>'ترتيب',
			'VISIBILITY'=>'الرؤية',
			'ALLOW_COMMENT'=>'السماح بالتعليقات',
			'ALLOW_ADS'=>'السماح بالإعلانات',
				//edit section

			'EDIT_CATEGORIES'=>'تعديل الفئة',
		);
		return array_key_exists(strtoupper($key), $lang)?
		$lang[strtoupper($key)]:$key;
	}

?>