<?php

define('API_KEY', '');

require 'functions/flintstone/config.php';
require 'functions/telegram_getdata.php';
require 'functions/telegram_functions.php';

connect_to_db('user_status');

function convertFaNum2EnNum($fa_num) {
    $en = array("0","1","2","3","4","5","6","7","8","9");
    $fa = array("۰","۱","۲","۳","۴","۵","۶","۷","۸","۹");
    return str_replace($fa, $en, $fa_num);
    // if(!is_numeric($fa_num) || empty($fa_num)) return false;
}

if(isset($json_decode['callback_query'])) {
	$chatId = $json_decode['callback_query']['from']['id'];
	$inlineData = $json_decode['callback_query']['data'];

	if($inlineData == 'edit-fullname') {
		$answer = "لطفا نام و نام خانوادگی جدید را وارد نمایید!";
		sendMessage($chatId, $answer, 'Markdown', '');
	    $user_status->set($chatId, 'edit-fullname');

	} elseif($inlineData == 'edit-phonenumber') {
		$answer = "لطفا شماره همراه جدید را وارد نمایید!";
		sendMessage($chatId, $answer, 'Markdown', '');
	    $user_status->set($chatId, 'edit-phonenumber');

	}

	return false;
}


$RKB = [
    'register' => '📝 ثبت نام',
    'change_information' => '✏️ ویرایش مشخصات',
    'send_photo' => '🖼 ارسال تصویر جدید',
    'about_us' => '👨‍💻 درباره سازنده'
];

if ($text == '/start') {
    sendAction($chatId, 'typing');

    $replyKeyboard = [
        'keyboard' => [
            [$RKB['change_information'], $RKB['register']],
            [$RKB['about_us'], $RKB['send_photo']],
          ],
          'resize_keyboard' => true,
          'one_time_keyboard' => false,
    ];

    $answer = "خوش آمدید\n \n لطفاً با انتخاب گزینه ثبت نام، مشخصات خود را ارسال نمایید. \nدر صورتی که ثبت نام کرده اید می توانید مشخصات خود را ویرایش نموده یا تصویر جدیدی ارسال کنید.";
    sendMessage($chatId, $answer, 'Markdown', json_encode($replyKeyboard));

    // Add user
    addUser($chatId, $firstName, $lastName, $userName);

    $user_status->set($chatId, 'start');

} elseif ($text == $RKB['register']) {
	$user = getUserByTelegramId($chatId);
	// sendMessage($chatId, json_encode($user),'','');
	if(!empty($user['fullname'])) {
		$answer = "شما قبلا ثبت نام کرده اید!\nبا استفاده از گزینه ویرایش مشخصات می توانید اطلاعات خود را تغییر دهید";
		sendMessage($chatId, $answer, 'Markdown', '');

		return false;
	}


    $answer = 'نام و نام خانوادگی خود را ارسال نمایید';
    sendMessage($chatId, $answer, 'Markdown', '');
    $user_status->set($chatId, 'save-fullname');

} elseif ($text == $RKB['change_information']) {
	$user = getUserByTelegramId($chatId);
	if(empty($user['fullname'])) {
		$answer = "لطفا ابتدا ثبت نام کنید!";
		sendMessage($chatId, $answer, 'Markdown', '');

		return false;
	}

	$inlineKeyboard = [
		'inline_keyboard'=>
			[
				[
					['text'=>' تغییر نام و نام خانوادگی','callback_data'=>'edit-fullname' ],

				],
				[
					['text'=>'تغییر شماره همراه','callback_data'=>'edit-phonenumber' ],
				]
			]
	];

	$answer = "نام و نام خانوادگی: $user[fullname]\nشماره همراه: $user[phone_number]";
	sendMessage($chatId, $answer, 'Markdown', json_encode($inlineKeyboard));

} elseif ($text == $RKB['send_photo']) {
    $answer = "تصویر خود را ارسال کنید";
    sendMessage($chatId, $answer, 'Markdown', '');
    $user_status->set($chatId, 'proccess-photo');

} elseif ($text == $RKB['about_us']) {
	// $answer = "همه با سدک همراه میشویم تا با در خانه ماندن کرونا را شکست دهیم \n آدرس کانال: @sadakssu\nآدرس گروه: @sadak_ssu \n\n";
    $answer = "ساخته شده با ❤️ به وسیله [مرتضی قاسمی](https://t.me/mortezagh98) \n https://t.me/mortezagh98";
    sendMessage($chatId, $answer, 'Markdown', '');
    $user_status->set($chatId, 'start');

} elseif ($user_status->get($chatId) == 'save-fullname') {
	updateUserFullname($chatId, convertFaNum2EnNum($text));

    $answer = "شماره موبایل خود را ارسال کنید";
    sendMessage($chatId, $answer, 'Markdown', '');
    $user_status->set($chatId, 'save-phonenumber');

} elseif ($user_status->get($chatId) == 'save-phonenumber') {
	updateUserPhoneNumber($chatId, convertFaNum2EnNum($text));
	
    $answer = "تصویر خود را ارسال کنید";
    sendMessage($chatId, $answer, 'Markdown', '');
    $user_status->set($chatId, 'proccess-photo');

} elseif ($user_status->get($chatId) == 'proccess-photo') {
	$user = getUserByTelegramId($chatId);
	if(empty($user['fullname'])) {
		$answer = "لطفا ابتدا ثبت نام کنید!";
		sendMessage($chatId, $answer, 'Markdown', '');

		return false;
	}

	if(!isset($photo)) {
		$answer = "لطفا یک تصویر ارسال نمایید!";
		sendMessage($chatId, $answer, 'Markdown', '');

		return false;
	}

    $answer = "تصویر با موفقیت دریافت شد. \nدر حال پردازش پوستر شما...";
    sendMessage($chatId, $answer, 'Markdown', '');

	$user = getUserByTelegramId($chatId);
	$photo_path = saveTMP_photo($photo, API_KEY);

	$msg = sendPhoto($chatId, "URL/photo_mixer_module.php?access_key=ACCESS_KEY&name=$user[fullname]&file_src=$photo_path", '', '');
    $user_status->set($chatId, 'start');

	unlink($photo_path);

	$answer = " ✅با تشکر از پیوستن شما به پویش در خانه میمانم؛🏠 \n \n🔹همه با سدک همراه میشویم تا با در خانه ماندن کرونا را شکست دهیم. \n \n 🔶شما میتوانید این تصویر را با دوستان خود به اشتراک بگذارید و ما در تلگرام و اینستاگرام و توئیتر به نشانی زیر دنبال کنید: @SadakSSU \n \n ⭕️ همچنین به وبسایت ما حتما سر بزنید؛ \n \n https://sadakssu.ir/";
    sendMessage($chatId, $answer, 'Markdown', '');

	sleep(0.1);

	forwardMessage(CHANNEL_TELEGRAM_ID, $chatId, $msg['result']['message_id']);
	sendMessage(CHANNEL_TELEGRAM_ID, "☝️☝️☝️☝️" . "\n" . $user['telegram_id'] . "\n" . $user['fullname'] . "\n" . $user['phone_number'], 'Markdown', '');

} elseif ($user_status->get($chatId) == 'edit-fullname') {
	updateUserFullname($chatId, convertFaNum2EnNum($text));
	
    $answer = "نام و نام خانوادگی شما با موفقیت تغییر کرد!";
    sendMessage($chatId, $answer, 'Markdown', '');
    $user_status->set($chatId, '');

} elseif ($user_status->get($chatId) == 'edit-phonenumber') {
	updateUserPhoneNumber($chatId, convertFaNum2EnNum($text));
	
    $answer = "شماره همراه شما با موفقیت تغییر کرد!";
    sendMessage($chatId, $answer, 'Markdown', '');
    $user_status->set($chatId, '');

}



