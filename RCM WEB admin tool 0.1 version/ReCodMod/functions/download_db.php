<?php

$userAgent = 'Googlebot/2.1 (http://www.googlebot.com/bot.html)';
$ch = curl_init($target_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
$output = curl_exec($ch);
$fh = fopen("database1.db", 'w+');
fwrite($fh, $output);
fclose($fh);

?>