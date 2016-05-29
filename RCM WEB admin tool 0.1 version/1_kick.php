 <?php

 
// PHP COD2 Rules Checker                                    http://ashus.ashus.net/
// Created by Ashus in 2007 - 2009
// version 1.5
// This script should be launched via crontab - every minute; try this line, change your url:
//* * * * * root nice -n 4 lynx -dump http://127.0.0.1/cod2/rcon-rules.php > /dev/null 2>&1 &
//* * * * * root nice -n 4 php /var/www/cod2/rcon-rules.php > /dev/null 2>&1 &

include("zzz_add_here_server_configurations.php"); 

if ($_GET["whoo"] != $admintool_user){
echo 'hacking atemp'; exit;}

$whooo = $_GET["whoo"];
$x_name = $_GET["plrname"];
$x_ip = $_GET["plrip"];
$x_id = $_GET["plrid"];

if (empty($x_id)){
echo 'Error
<center><H1>Error!</H1>
<H1><a title="Settings" href="index.php">return BACK!</a></H1></center>';

exit;
}
if (empty($x_ip)){
echo 'Error 
<center><H1>Error!</H1>
<H1><a title="Settings" href="index.php">return BACK!</a></H1></center>';
exit;
}


include("zzz_add_here_server_configurations.php"); 


$rules_log_file = 'kicked.log';     // path to a file containing kicked players; must be writeable




////////////////////////////////////////////////////////////////
// do not modify below here
 
$server_addr = "udp://" . $server_ip;
@$connect = fsockopen($server_addr, $server_port, $re, $errstr, 30);
if (! $connect) { die('Can\'t connect to COD gameserver.'); }
socket_set_timeout ($connect, 2);
$send = "\xff\xff\xff\xff" . 'rcon "' . $server_rconpass . '" status';
fwrite($connect, $send);
$output = fread ($connect, 1);
if (! empty ($output)) {
do {
$status_pre = socket_get_status ($connect);
$output = $output . fread ($connect, 1024);
$status_post = socket_get_status ($connect);
} while ($status_pre['unread_bytes'] != $status_post['unread_bytes']);
};

$output = explode ("\xff\xff\xff\xffprint\n", $output);
$output = implode ('!', $output);
$output = explode ("\n",$output);

function rcon($s, $replace='')
	{
	global $connect, $server_rconpass;
	fwrite($connect, "\xff\xff\xff\xff" . 'rcon "' . $server_rconpass . '" '.strtr($s,array('%s'=>$replace)));
	$output = fread ($connect, 512);
    usleep(500*1000);
    return $output;
	}

// var_dump(rcon('pb_sv_ver'));
	
function AddToLog($s)
	{
	global $rules_log_file;
	$fp = fopen($rules_log_file, 'a');
    fwrite($fp, $s."\n");
    fclose($fp);
	}

function stringrpos($haystack,$needle,$offset=NULL)
	{
	   if (strpos($haystack,$needle,$offset) === FALSE)
	     return FALSE;

	   return strlen($haystack)
	           - strpos( strrev($haystack) , strrev($needle) , $offset)
	           - strlen($needle);
	}

function GetPlainName($name) // copied from original - IPluginCOD.php
	{

		$repname = $name;

		for($y=0; $y < 2; $y++) // Loop around a few time in case we have embedded colors!
		{
			for($x=0; $x < 10; $x++)
			{
				$repname = str_replace("^$x","",$repname);
			}
		}
		return $repname;
	}

function GetRealIp()
{
 if (!empty($_SERVER['HTTP_CLIENT_IP']))
 {
   $ip=$_SERVER['HTTP_CLIENT_IP'];
 }
 else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
 {
  $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
 }
 else
 {
   $ip=$_SERVER['REMOTE_ADDR'];
 }
 return $ip;
}

 $hhh = getRealIp();

// oznamovani casu v urcene minuty

if (count($rules_time_announce)>0)
	{
	if (in_array( (int) date('i'), $rules_time_announce, true))
		{
		rcon('say "'.$rules_time_msg.'"', date('G:i'));
		}
	}

// nacitani cache varovanych pred kopnutim

if (is_file($rules_warned_cache_file))
	{$ping_cache = file($rules_warned_cache_file);} else
	{$ping_cache = array();}
	
$ping_cache_new = array();
$datetime = date('Y-m-d H:i:s');

$player_cnt = count($output);
$rec_ok = ($player_cnt>3);

// pokud se nevratil uspesne seznam hracu, chcipneme
if (!$rec_ok)
	{exit;}


// smazeme z vystupu nepotrebne radky
unset($output[0],$output[1],$output[2],$output[$player_cnt-2],$output[$player_cnt-1]);
$output = array_values($output);
$player_cnt = count($output);


if ($rules_iplog_interval>0)
	{
	$iplog = '--- '.$datetime." ---\n";
	}

// zkoumame hrace jestli nekdo neporusuje pravidla

for ($i=0; $i<1; $i++)
	{
	$i_rcon_string = $output[$i];
	if ($i_rcon_string != '')
		{
		$pat[0] = "/^\s+/";
		$pat[1] = "/\s{2,}/";
		$pat[2] = "/\s+\$/";
		$rep[0] = "";
		$rep[1] = " ";
		$rep[2] = "";
		$i_rcon_string = preg_replace($pat,$rep,$i_rcon_string);

		unset($tmp2);
       if (preg_match("/[[:space:]][0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}/",  $i_rcon_string, $tmp2))
            {
            $i_ip = substr($tmp2[0],1);
			} else $i_ip = '';

		$i_rcon_string = explode(' ', $i_rcon_string, 5);
        $i_id = $i_rcon_string[0];
		$i_ping = $i_rcon_string[2];
		$i_name = substr($output[$i], 22);
		$i_name = preg_replace($pat,$rep,$i_name);

		$vyk = ':';
		$tmp = stringrpos($i_name, $vyk);
		if ($tmp === false)
		    {
			$vyk = '!';
			$tmp = stringrpos($i_name, $vyk);
			if ($tmp === false)
				{Continue;}
			}

	    $i_name = substr($i_name, 0, $tmp);
	    $i_name = substr($i_name, 0, stringrpos($i_name, ' '));
	    $tmp2 = stringrpos($i_name, ' ');
	    if ($tmp2 !== false)
	    	{$i_name = substr($i_name, 0, $tmp2);}
        $i_name = trim(GetPlainName($i_name));

		}
		
	


	$valid_id = is_numeric($i_id);
	$valid_ping = is_numeric($i_ping);
	if ((! $valid_id) || (! $valid_ping)) Continue;  // this one was broken while receiving data from cod2 server. there is a bug in the game.
								// one random character is replaced by data header if there are more than ca. 13 players present.
								// this protects other players, if this bug occurs.



usleep(320000);
rcon('say ^7'.$x_name.' ^3kicked by ^1'.$whooo.'^3!', '');		
usleep(120000);
rcon('clientkick '. $x_id, '');
usleep(370000);
rcon('clientkick '. $x_id, '');
		AddToLog('['.$datetime.'] Kicked: ' . $x_ip . ' (' . $x_name . ') by ('.$whooo.') ('.$hhh.') reason: KICK');

 
	}

fclose($connect);

?>
<center><H1>Player kicked!</H1>
<H1><a title="Settings" href="index.php">return BACK!</a></H1></center>
