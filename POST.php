<?php 
header("Content-Type: application/json"); // Укажем тип - JSON 
$data = json_decode(file_get_contents("php://input")); // отправляем в ответ строку с подтверждением 
$volunteer_specialization = json_encode($data->specializations); // Делаем строкой массив компетенций для отображения

// Получаем и расшифровываем строку, используя Get-параметр:
$get_string = $_GET['encrypted_string'];
// Декодируем base64:
$encrypted = base64_decode($get_string);
// Указываем данные для расшифровки:
$password2 = 'Psw_Procharity';
$key2 = $password2;
$method2 = 'aes-128-cbc';
$iv2 = "12345678";

// $encrypted = base64_decode("/4o7m/4fyJ4HinfAcXbysA==");

// И вызываем openssl_decrypt():
$decrypted_id_volunteer = openssl_decrypt(
    $encrypted,
    $method2,
    $key2,
    OPENSSL_RAW_DATA,
    $iv2,
);

// Выводим результат:
echo "Сервер получил следующие данные: 
<br>GET-параметр из строки c зашифрованным ID волонтёра  - $get_string,
<br>Расшифрованный ID волонтёра из переданной строки - $decrypted_id_volunteer,
<br>Данные из личного кабинета волонтёра:
<br>ID - $data->id,
<br>Имя — $data->name,
<br>E-mail — $data->email,
<br>Специализации — $volunteer_specialization"; 


$fd = fopen("TB - add_volunteer.log", 'a') or die("не удалось создать файл");
// $str = "Привет мир";

$date_connect = date("Y-m-d H:i:s");
$data_about_volunteer = "
$date_connect, 
GET-параметр из строки c зашифрованным ID волонтёра  - $get_string, 
Расшифрованный ID волонтёра из переданной строки - $decrypted_id_volunteer, 
Данные из личного кабинета волонтёра: 
ID - $data->id, 
Имя — $data->name, 
E-mail — $data->email, 
Специализации — $volunteer_specialization 
=======================================================================================\n";

fputs($fd, $data_about_volunteer);
fclose($fd);

?>
