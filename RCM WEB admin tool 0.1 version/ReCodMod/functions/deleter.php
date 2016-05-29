<?php
/* - Load configurations - */ require 'cfg/configs.php';
/* - Load functions - */ require_once 'ReCodMod/functions/inc_functions.php';
/* - Load functions - */ require_once ("ReCodMod/functions/functions.php");
date_default_timezone_set( 'Europe/Kaliningrad' );   ///Europe/Moscow  +1

ini_set("log_errors", "1");
ini_set("error_log", "./ReCodMod/x_errors/$filename");
$logging = new log("./ReCodMod/x_errors/$filename");
set_error_handler("error_handler");
$datetime = date('Y.m.d H:i:s');
$dtx2 = date('Y-m-d H:i:s');

if(!file_exists($mplogfile)){echo "\n CACHE CAN'T RESET - ADD GAME SERVER LOGFILE - (fix it in connect_cfg.php) "; sleep(3); exit;}

$ha0 = fopen($mplogfile, "w");
fclose($ha0);

$handlePos=fopen("./ReCodMod/x_cache/pos.txt" ,"w+");
fwrite($handlePos, "1");
fclose($handlePos);

AddToLog1("[".$datetime."]<font color='green'> Server :</font> <font color='silver'> LogFile game_mp.log starting auto reset! </font> "); 
echo "\n Cache logfiles restarted......";
  
  
  
?>