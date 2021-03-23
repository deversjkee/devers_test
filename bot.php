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

//Погода
if ($message == 'Погода' || $message == 'погода'){
	//город. Можно и по-русски написать, например: Брянск
	$city = 'Chelyabinsk';
	//страна
	$country = 'RU';
	//в каком виде мы получим данные json или xml
	$mode = 'json';
	// Единицы измерения. metric или imperial
	$units = 'metric';
	// язык
	$lang = 'ru';
	// количество дней. Максимум 14 дней
	$countDay = 1;
	// APPID
	$appID = 'e2d566f8b8d8a128d7f482f813f93bbf';
	
	// формируем урл для запроса
	$weatherUrl = 'http://api.openweathermap.org/data/2.5/forecast?q='.$city.','.$country.'&cnt='.$countDay.'&lang='.$lang.'&units='.$units.'&appid='.$appID;
	
	$weather = $modx->runSnippet('ddMakeHttpRequest', array(
			'url' => $weatherUrl,
			'method' => 'post',
			'postData' => '',
			'headers' => 'application/json'
	));
	
	if($weather){
		// декодируем полученные данные
		$weatherJson = json_decode($weather);
		// получаем только нужные данные
		$weatherDays = $weatherJson->list;
		// выводим данные
		foreach($weatherDays as $oneDay){
			$sendData['text'] = 'temp:  '.$oneDay->main->temp.PHP_EOL.
				'feels like:  '.$oneDay->main->feels_like.PHP_EOL.
				'Влажность: '.$oneDay->main->humidity.PHP_EOL.
				'Давление:  '.$oneDay->main->pressure.PHP_EOL.
				'Погода:  '.$oneDay->weather[0]->description.PHP_EOL.
				'Ветер speed:  '.$oneDay->wind->speed.PHP_EOL.
				'timestamp:  '.$oneDay->dt_txt.PHP_EOL.
				PHP_EOL;
		}
		
		Send($url, $sendData);
	}else{
		$sendData['text'] = 'Сервер не доступен!';
			
		Send($url, $sendData);
	}
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