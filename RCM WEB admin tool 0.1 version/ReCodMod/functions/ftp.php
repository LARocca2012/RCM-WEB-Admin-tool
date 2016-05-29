<?php

if ($ftp_log_read == "1"){

// установка соединения
$conn_id = ftp_connect($ftp_server); 

// вход с именем пользователя и паролем
$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass); 

// проверка соединения
if ((!$conn_id) || (!$login_result)) { 
        echo "can not connect to serever!";
        echo "try connect to $ftp_server with user $ftp_user_name!";
        exit; 
    } else {
        echo "Connected to $ftp_server with user $ftp_user_name";
    }

// закачивание файла
$upload = ftp_put($conn_id, $destination_file, $source_file, FTP_BINARY); 

// проверка результата
if (!$upload) { 
        echo "Can not download file!";
    } else {
        echo "File $source_file downloaded to $ftp_server with name $destination_file";
    }

// закрытие соединения
ftp_close($conn_id);

$mplogfile = "./databases/parsed_db.db"; 

}else{

}


if ($ftp_log_read == "1"){


$local_file = 'local.zip';
$server_file = 'server.zip';


$conn_id = ftp_connect($ftp_server);
$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);

// попытка скачать $server_file и сохранить в $local_file
if (ftp_get($conn_id, $local_file, $server_file, FTP_BINARY)) {
    echo "Произведена запись в $local_file\n";
} else {
    echo "Не удалось завершить операцию\n";
}

// закрытие соединения
ftp_close($conn_id);

?>