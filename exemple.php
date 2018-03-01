<?php

define('BOT_TOKEN', '');
define('API_URL', 'https://api.telegram.org/bot'.BOT_TOKEN.'/');

function apiRequest($method, $parameters) {
	if(!is_string($method)) {
		return false;
	}
	
	if(!$parameters) {
		$parameters = array();
	} elseif(!is_array($parameters)) {
		return false;
	}
	
	$url = API_URL.$method.'?'.http_build_query($parameters);
	
	$opts = array('http' =>
		array(
			'timeout' => 5
		)
	);
	
	$context  = stream_context_create($opts);
	
	$result = file_get_contents($url, false, $context);
	
}

function processMessage($message) {
	$message_id = $message['message_id'];
	$chat_id = $message['chat']['id'];
	
	if (isset($message['text'])) {
		$text = $message['text'];
		
		apiRequest("sendMessage", array('chat_id' => $chat_id, "reply_to_message_id" => $message_id, "text" => $text));
		exit;
  
	} else {
		apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => 'I understand only text messages'));
		exit;
	}
	
}	

$content = file_get_contents("php://input");
$update = json_decode($content, true);
if(!$update) {
	exit;
}

if(isset($update["message"])) {
	processMessage($update["message"]);
}
