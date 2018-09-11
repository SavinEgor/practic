<?php
//проверка параметров запуска скрипта
//если скрипт вызван через консоль
if (PHP_SAPI == 'cli') {
	//вывод справки
	echo "Скрипт выполняет подключение к электронному почтовому ящику ".
		"для сбора информации об входящих и отправленных электронных письмах ".
		"(тема письма, email отправителя, email получателя, X-Event, дата получения). ".
		"Эта информация будет записана в .csv файл, имя файла составляет дата и время запуска скрипта. \n".
		"Скрипт имеет следующие необязательные параметры запуска (критерии поиска писем): \n".
		"date_from - дата начала периода поиска \n".
		"date_to - дата окончания периода поиска \n".
		"from - email отправителя \n".
		"to - email получателя \n".
		"Для успешной работы скрипта необходимо указать хотя бы один параметр. \n".
		"Примеры запуска: \n".
		"php parser.php date_from=01.01.2018 date_to=01.05.2018 from=manager@uchitel-izd.ru to=admin@uchitel-izd.ru ".
		"поиск писем, дата отправления которых находится в периоде между 01.01.2018 и 01.05.2018,".
		"email отправителя которых - manager@uchitel-izd.ru, а получателя - admin@uchitel-izd.ru. \n".
		"php parser.php date_from=01.01.2018 from=manager@uchitel-izd.ru ".
		"поиск писем, дата отправления которых находится в периоде, начиная от 01.01.2018,".
		"email отправителя которых - manager@uchitel-izd.ru. \n".
		"php parser.php date_to=01.05.2018 to=admin@uchitel-izd.ru ".
		"поиск писем, дата отправления которых находится в периоде до 01.05.2018,".
		"email получателя которых - admin@uchitel-izd.ru. \n".
		"php parser.php from=manager@uchitel-izd.ru ".
		"поиск писем с любой датой отправления, email отправителя которых - manager@uchitel-izd.ru. \n"
		"php parser.php ".
		"запуск скрипта некорректен, так как отсутствуют входные параметры. \n"
	//если количество параметров меньше двух (так как один есть всегда)
	if ($argc < 2) {
		//прекращаем выполнение скрипта и выводим сообщение
		die("Введите параметры фильтрации.");
	}
	//перебираем все параметры запуска, кроме первого
	for ($i = 1; $i < $argc; $i++) {
		//поиск символа "=" в параметре, возвращаемое значение - строка, содержащая текст параметра до символа "=" включая его
		$inputArgument=strstr($argv[$i], "=", TRUE);
		//если символ найден
		if ($inputArgument) {
			//убираем символ "=" из строки
			$inputArgument=str_replace("=", "", $inputArgument);
			//если полученная строка - это текст "date_from"
			if ($inputArgument == "date_from") {
				//берем часть параметра справа от символа "=", включая его
				$inputArgument=strstr($argv[$i], "=", FALSE);
				//убираем символ "=" из строки
				$dateFrom=str_replace("=", "", $inputArgument);
			}
			//если полученная строка - это текст "date_to"
			if ($inputArgument == "date_to") {
				//берем часть параметра справа от символа "=", включая его
				$inputArgument=strstr($argv[$i], "=", FALSE);
				//убираем символ "=" из строки
				$dateTo=str_replace("=", "", $inputArgument);
			}
			//если полученная строка - это текст "from"
			if ($inputArgument == "from") {
				//берем часть параметра справа от символа "=", включая его
				$inputArgument=strstr($argv[$i], "=", FALSE);
				//убираем символ "=" из строки
				$fromEmail=str_replace("=", "", $inputArgument);
			}
			//если полученная строка - это текст "to"
			if ($inputArgument == "to") {
				//берем часть параметра справа от символа "=", включая его
				$inputArgument=strstr($argv[$i], "=", FALSE);
				//убираем символ "=" из строки
				$toEmail=str_replace("=", "", $inputArgument);
			}
		}
	}
} else {//если запуск скрипта осуществлен через http
	//вывод справки
	echo "Скрипт выполняет подключение к электронному почтовому ящику ".
		"для сбора информации об входящих и отправленных электронных письмах ".
		"(тема письма, email отправителя, email получателя, X-Event, дата получения). ".
		"Эта информация будет записана в .csv файл, имя файла составляет дата и время запуска скрипта. <br>".
		"Скрипт имеет следующие необязательные параметры запуска (критерии поиска писем): <br>".
		"date_from - дата начала периода поиска <br>".
		"date_to - дата окончания периода поиска <br>".
		"from - email отправителя <br>".
		"to - email получателя <br>".
		"Для успешной работы скрипта необходимо указать хотя бы один параметр. <br>".
		"Примеры запуска: <br>".
		"parser.php?date_from=01.01.2018&date_to=01.05.2018&from=manager@uchitel-izd.ru&to=admin@uchitel-izd.ru ".
		"поиск писем, дата отправления которых находится в периоде между 01.01.2018 и 01.05.2018,".
		"email отправителя которых - manager@uchitel-izd.ru, а получателя - admin@uchitel-izd.ru. <br>".
		"parser.php?date_from=01.01.2018&from=manager@uchitel-izd.ru ".
		"поиск писем, дата отправления которых находится в периоде, начиная от 01.01.2018,".
		"email отправителя которых - manager@uchitel-izd.ru. <br>".
		"parser.php?date_to=01.05.2018&to=admin@uchitel-izd.ru ".
		"поиск писем, дата отправления которых находится в периоде до 01.05.2018,".
		"email получателя которых - admin@uchitel-izd.ru. <br>".
		"parser.php?from=manager@uchitel-izd.ru ".
		"поиск писем с любой датой отправления, email отправителя которых - manager@uchitel-izd.ru. <br>"
		"parser.php ".
		"запуск скрипта некорректен, так как отсутствуют входные параметры. <br>"
	//если отсутствуют необходимые параметры
	if (($_GET["to"] === null)&&($_GET["from"] === null)&&($_GET["date_to"] === null)&&($_GET["date_from"] === null)) {
		//прекращаем выполнение скрипта и выводим сообщение
		die("Введите параметры фильтрации.");
	}
	//осуществляем get-запрос (поиск параметра по ключу-названию из переданных скрипту)
	$dateFrom=$_GET['date_from'];
	//осуществляем get-запрос (поиск параметра по ключу-названию из переданных скрипту)
	$dateTo=$_GET['date_to'];
	//осуществляем get-запрос (поиск параметра по ключу-названию из переданных скрипту)
	$fromEmail=$_GET['from'];
	//осуществляем get-запрос (поиск параметра по ключу-названию из переданных скрипту)
	$toEmail=$_GET['to'];
}
//часть программного кода, вынесенная в отдельную функцию, которая осуществляет подключение к почтовому ящику, аргумент функции - название папки в почтовом ящике, например "Отправленные"
function GetMailbox($mailFolder) {
	//подключаемся к почтовому ящику
	$mailbox = @imap_open("{imap.gmail.com:993/imap/ssl}".imap_utf8_to_mutf7($mailFolder), "login", "password");
	//если подключение к почтовому ящику удалось
	if ($mailbox) {
		//возвращаемое значение - поток почтового ящика
		return $mailbox;
	} else { //иначе - подключение к почтовому ящику не удалось
		//прекращаем выполнение скрипта и выводим сообщение
		die("Ошибка подключения к почтовому ящику.");
	}
}
//часть программного кода, вынесенная в отдельную функцию, которая осуществляет сбор необходимой информации о письме, 
//аргументы функции: $mailbox - поток к почтовому ящику, $letterNumber - номер письма в ящике, $outputFileStream - поток к выходному файлу для записи информации о письме
function GetLetterInformation($mailbox, $letterNumber, $outputFileStream) {
	//считываем заголовок письма
	$letterHeader = imap_header($mailbox, $letterNumber);
	//декодируем тему письма
	$letterSubject = imap_mime_header_decode($letterHeader->subject);
	//записываем все части (если их несколько) темы письма в одну строку
	for ($i = 0; $i < count($letterSubject); $i++) {
		$subject .= $letterSubject[$i]->text;
	}
	//вытаскиваем отправителя
	$letterSender = $letterHeader->from;
	//выводим почту отправителя
	foreach ($letterSender as $element) {
		$sender = ($element->mailbox)."@".($element->host);
	}
	//вытаскиваем получателя
	$letterReceiver = $letterHeader->to;
	//выводим почту получателя
	foreach ($letterReceiver as $element) {
		$receiver = ($element->mailbox)."@".($element->host);
	}
	//считываем дату в формате udate
	$date = $letterHeader->udate;
	//выводим дату в формате день.месяц.год час:минута:секунда
	$date = date("j.m.Y H:i:s",$date);
	//проверяем X-EVENT
	//считываем полный заголовок письма
	$letterHeader = imap_fetchheader($mailbox, $letterNumber);
	//ищем в заголовке строку "X-EVENT"
	$position = strpos($letterHeader, "X-EVENT");
	//если такая строка нашлась
	if ($position) {
		//считываем текст после "X-EVENT_NAME: " до конца строки
		$xEventName = substr($letterHeader, $position+strlen("X-EVENT_NAME: "));
	}
	//записываем строку (дата получения, email отправителя, email получателя, тема письма, X-EVENT_NAME) в выходной файл
	fputcsv($outputFileStream, array($date,iconv("utf-8" ,"windows-1251", $sender), iconv("utf-8" ,"windows-1251", $receiver), iconv("utf-8" ,"windows-1251", $subject), iconv("utf-8" ,"windows-1251", $x_event)),";");
}
//заводим строку для хранения параметров поиска
$criteriaOfSearch = '';
//если параметр "to" присутствовал при запуске скрипта
if ($toEmail != null) {
	//добавляем его значение в строку
	$criteriaOfSearch.=' TO '.$toEmail;
}
//если параметр "from" присутствовал при запуске скрипта
if ($fromEmail != null) {
	//добавляем его значение в строку
	$criteriaOfSearch .= ' FROM '.$fromEmail;
}
//если параметр "date_from" присутствовал при запуске скрипта
if ($dateFrom != null) {
	//добавляем его значение в строку
	$criteriaOfSearch.=' SINCE "'.date("j F Y",strtotime($dateFrom)).'"';
}
//если параметр "date_to" присутствовал при запуске скрипта
if ($dateTo != null) {
	//добавляем его значение в строку
	$criteriaOfSearch.=' BEFORE "'.date("j F Y",strtotime($dateTo)).'"';
}
//задаем имя выходного файла, используя дату исполнения скрипта
$outputFileName = date('j.m.Y H-i-s', time()).".csv";
//открываем файл для записи
$outputFileStream = fopen($outputFileName, 'w');
//если открыть файл не удалось
if (!$outputFileStream) {
	//прекращаем выполнение скрипта и выводим сообщение
	die("Ошибка записи в файл. ");
}
//записываем шапку файла
fputcsv($outputFileStream, array(iconv("utf-8" ,"windows-1251", "Дата получения"),iconv("utf-8" ,"windows-1251", "Email отправителя"), iconv("utf-8" ,"windows-1251", "Email получателя"), iconv("utf-8" ,"windows-1251", "Тема письма"), iconv("utf-8" ,"windows-1251", "X-EVENT_NAME")),";");
//закрываем выходной файл
fclose($outputFileStream);
//подключение к почтовому ящику и открытие папки "Отправленные"
$mailbox = GetMailbox('Отправленные');
//поиск писем в папке по заданным критериям поиска
$lettersArray = imap_search ( $mailbox , $criteriaOfSearch );
//если нашлось хотя бы одно письмо
if (!empty($lettersArray)) {
	//выводим сообщение о результатах поиска
	echo "По заданным параметрам найдено отправленных писем: ".count($lettersArray).". ";
	//открываем файл для записи
	$outputFileStream = @fopen($outputFileName, 'a');
	//если открыть файл не удалось
	if (!$outputFileStream) {
		//прекращаем выполнение скрипта и выводим сообщение
		die("Ошибка записи в файл. ");
	}
	//перебираем найденные письма
	foreach ($lettersArray as $letterNumber) {
		//вызываем функцию для обработки письма
		GetLetterInformation($mailbox, $letterNumber, $outputFileStream);
	}
	//закрываем выходной файл
	fclose($outputFileStream);
} else {//иначе - писем не найдено
	//выводим сообщение
	echo "Отправленных писем по заданным параметрам не найдено. ";
}
//закрываем поток к почтовому ящику
imap_close($mailbox);
//подключение к почтовому ящику в папке по умолчанию ("Входящие")
$mailbox = GetMailbox('');
//поиск писем в папке по заданным критериям поиска
$lettersArray = imap_search ( $mailbox , $criteriaOfSearch );
//если нашлось хотя бы одно письмо
if (!empty($lettersArray)) {
	//выводим сообщение о результатах поиска
	echo "По заданным параметрам найдено входящих писем: ".count($lettersArray).". ";
	//открываем файл для записи
	$outputFileStream = @fopen($outputFileName, 'a');
	//если открыть файл не удалось
	if (!$outputFileStream) {
		//прекращаем выполнение скрипта и выводим сообщение
		die("Ошибка записи в файл. ");
	}
	//перебираем найденные письма
	foreach ($lettersArray as $letterNumber) {
		//вызываем функцию для обработки письма
	GetLetterInformation($mailbox, $letterNumber, $outputFileStream);
	}
	//закрываем выходной файл
	fclose($outputFileStream);
}
//иначе - писем не найдено
else 
{
	//выводим сообщение
	echo "Входящих писем по заданным параметрам не найдено. ";
}
//закрываем поток к почтовому ящику
imap_close($mailbox);
?>
