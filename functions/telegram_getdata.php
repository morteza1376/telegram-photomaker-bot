<?php

$json_decode = json_decode(file_get_contents('php://input'), true);

    $messageId = $json_decode['message']['message_id'];
    // from info
        $fromId = $json_decode['message']['from']['id'];
        $isBot = $json_decode['message']['from']['is_bot'];
        $firstName = $json_decode['message']['from']['first_name'];
        $lastName = $json_decode['message']['from']['last_name'];
        $userName = $json_decode['message']['from']['username'];
        $languageCode = $json_decode['message']['from']['language_code'];
    // chat info 
        $chatId = $json_decode['message']['chat']['id'];
        $chatTitle = $json_decode['message']['chat']['title'];
        $type = $json_decode['message']['chat']['type']; // same
        $chatType = $json_decode['message']['chat']['type']; // same

    // date 
        $date = $json_decode['message']['date'];
    // text 
        $text = $json_decode['message']['text'];
    // photo
        $photo = $json_decode['message']['photo'];
    // contact
        $contact = $json_decode['message']['contact'];
    // caption
        $caption = $json_decode['message']['caption'];
    // reply to message
        $replied = $json_decode['message']['reply_to_message'];
    // callback
        $callbackId = $json_decode['callback_query']['id'];
        $callbackText = $json_decode['callback_query']['message']['text'];
        $callbackData = $json_decode['callback_query']['data'];
        $callbackFromId = $json_decode['callback_query']['from']['id'];
        $callbackMessageId = $json_decode['callback_query']['message']['message_id'];
    // entities
        $entities = $json_decode['message']['entities'];
        $captionEntities = $json_decode['message']['caption_entities'];


    // channel
        $channelPostType = $json_decode['channel_post']['chat']['type'];
        $channelId = $json_decode['channel_post']['chat']['id'];
        $channelTitle = $json_decode['channel_post']['chat']['title'];
        
        $author = $json_decode['channel_post']['author_signature'];

        $channelEntities = $json_decode['channel_post']['entities'];