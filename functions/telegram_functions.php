<?php

// this is telegram functions that written by Morteza Ghasemi

// communication with telegram servers ... 

function bot($method,$data=[]) {
    $baseurl = 'https://api.telegram.org/bot' . API_KEY . '/' . $method;
    $ch = curl_init(); // create curl function
    curl_setopt($ch, CURLOPT_URL, $baseurl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $result = curl_exec($ch);
    
    if(curl_error($ch)) {
        var_dump(curl_error($ch));
    } else {
        return json_decode($result ,true);
    }
    curl_close($ch);
}

function sendMessage($chatId, $text, $parseMode, $replyMarkup, $replyToMessageId = ''){
	sendAction($chatId, 'typing');
    $data = bot('sendmessage', array(
        'chat_id' => $chatId,
        'text' => $text,
        'parse_mode' => $parseMode,
        'reply_markup' => $replyMarkup,
        'disable_notification' => false,
        'disable_web_page_preview' => false,
        'reply_to_message_id' => $replyToMessageId,
    ));
    return $data;

}
function sendAction($chatId, $action){
    bot('sendchataction', array(
        'chat_id' => $chatId,
        'action' => $action,
    ));
}
function forwardMessage($destination, $chatId, $messageId){
    bot('forwardMessage', array(
        'chat_id' => $destination,
        'from_chat_id' => $chatId,
        'message_id' => $messageId,
    ));
}
function answerCallbackQuery($callbackQueryId, $text, $showAlert, $cacheTime = 0){
    bot('answerCallbackQuery', array(
        'callback_query_id' => $callbackQueryId,
        'text' => $text,
        'show_alert' => $showAlert,
        'cache_time' => $cacheTime,
         
    ));
}
function editMessage($chatId, $messageId, $inlineMessageId, $text, $parseMode, $replyMarkup) {
    bot('editMessageText', array(
        'chat_id' => $chatId,
        'message_id' => $messageId,
        'text' => $text,
        'inline_message_id' => $inlineMessageId,
        'parse_mode' => $parseMode,
        'reply_markup' => $replyMarkup,
    ));
}
function editMessageReplyMarkup($chatId, $messageId, $inlineMessageId, $replyMarkup) {
    bot('editMessageReplyMarkup', array(
        'chat_id' => $chatId,
        'message_id' => $messageId,
        'inline_message_id' => $inlineMessageId,
        'reply_markup' => $replyMarkup,
    ));
}

function sendDocument($chatId,$document,$filename,$caption){
    bot('senddocument',[
        'chat_id'=>$chatId,
        'document'=>$document,
        'file_name'=>$filename,
        'caption'=>$caption,
    ]);
}

function sendPhoto($chatId, $photoFileId, $caption, $parseMode){
    return bot('sendphoto', array(
        'chat_id' => $chatId,
        'photo' => $photoFileId,
        'caption' => $caption,
        'parse_mode' => $parseMode,
    ));
}

function sendVideo($chatId, $videoFileId, $caption, $parseMode){
    bot('sendvideo', array(
        'chat_id' => $chatId,
        'video' => $videoFileId,
        'caption' => $caption,
        'parse_mode' => $parseMode,
    ));
}

function getChatMember($chatId,$channel) {
    $data = bot('getChatMember', array(
                    'chat_id' => $channel,
                    'user_id' => $chatId
                )
            );
    return $data;
}

function getUserProfilePhotos($chatId) {
    $data = bot('getUserProfilePhotos', array(
                'user_id' => $chatId,
            ));
    return $data;
}

function getFile($fileId) {
    $data = bot('getFile', array(
                'file_id' => $fileId,
            ));
    return $data;
}


function getPhotoUrl($api_key, $file_path) {
	return "https://api.telegram.org/file/bot" . $api_key . '/' . $file_path;
}

function saveTMP_photo($photo_telegram_json, $api_key) {
	$photoFileId = end($photo_telegram_json)['file_id'];

	$photoSRC = getPhotoUrl($api_key, getFile($photoFileId)['result']['file_path']);

	$photoAddr = "TEMP_PATH";

	file_put_contents($photoAddr . $photoFileId . '.jpg', file_get_contents($photoSRC));

	return "tmp/" . $photoFileId . '.jpg';
}
