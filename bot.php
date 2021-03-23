<?php
/**
 * Telegram bot RulonOboev
 * @version 0.2 (2021-03-22)
 * 
 */

global $modx;

$botToken = "1769533570:AAHSaIDb4vSRv_EbndsO95OFxegWK-3WpW8";
$chat_id = "2767782";
//Действие
$action = "/sendMessage";
$url = 'https://api.telegram.org/bot'. $botToken.$action;

//Получим данные
$data = file_get_contents('php://input');
$data = json_decode($data, true);

//Имя написавшего
$fromName = $data['message']['from']['first_name'];

//Текст пришедшего сообщения
$message = $data['message']['text'];


//Отправляемое
$sendData = [
	'chat_id' => $chat_id,
	'text' => 'Default'
];

//shrug
if($message == 'shrug'){
	$sendText = '¯\_(ツ)_/¯';
	
	$sendData['text'] = $sendText;
	
	Send($url, $sendData);
};


//Поздороваемся в ответ
if ($message == 'Привет' || $message == 'привет'){
	$sendText = 'Ну привет, '.$fromName;
	
	$sendData['text'] = $sendText;
	
	Send($url, $sendData);
};

//Отправка простого сообщения
function Send($url, $sendData){
	
	global $modx;
	$result = $modx->runSnippet('ddMakeHttpRequest', array(
			'url' => $url,
			'method' => 'post',
			'postData' => json_encode($sendData),
			'headers' => 'application/json'
	));
};

?>