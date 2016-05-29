<?php 
/*   Groups 
Developers - -1
Admins - 0
Moderators - md
Clan members - 1
Vip - 2
Members - 3 (Gamers on website)
Pro player - 4
Special guests - 5
Top player - 6
Noob - 7
Worst - 8
*/

// scheduler, enter $rules_schedule[hour in 24h format 0-23][minute 0-59] = 'command';
// you can also enter more commands to the same timestamp in this format: $rules_schedule[hour][minute][] = 'say Welcome!';
$rules_schedule[6][0] = 'exec a__config_morning.cfg';
$rules_schedule[9][0] = 'exec a__config_day.cfg';
$rules_schedule[19][0] = 'exec a__config_night.cfg';

$chat_off = '0';  // 0 - ON CHAT FLOOD, SWEARING, AND SPAM PROTECTING , 1 - OFF protection
$mapfix = 'dm';  // zom - IF ZOMBIES MOD , dm - if simple mod

// Время между сообщениями
$flood_time = false; //bylo '1';  za sekundu nebolie 1 soobshenia
$flood_t_povtorki = "3";
$flood_t_povtorki10 = "2";

require 'connect_cfg.php';
require $linuxxx.'cfg/bans.php';
require $linuxxx.'cfg/all_cmds.php';
require $linuxxx.'cfg/badwords.php';
require $linuxxx.'cfg/spam.php';
require $linuxxx.'cfg/cryingkids.php';
require $linuxxx.'cfg/admins.php';
require $linuxxx.'cfg/messages.php';
$r_admi = array($ip1,$ip2,$ip3,$ip4,$ip5,$ip6,$ip7,$ip8,$ip9,$ip10,$ip11,$ip12);
$ipt = 0;
$rules_time_announce = array(0,5);	// everytime minute digits equal one of these, shows current time to all players;  // array() to disable

///////////////////////////////////////////////////////MAPS
function mpt($string) {
$string = str_replace("harbor", "mp_harbor", $string);
$string = str_replace("carentan", "mp_carentan", $string);
$string = str_replace("lg", "mp_logging_mill", $string);
$string = str_replace("lm", "mp_logging_mill", $string);
$string = str_replace("pavlov", "mp_pavlov", $string);
$string = str_replace("abbey", "^5abbey", $string);
$string = str_replace("eisberg", "mp_eisberg", $string);
$string = str_replace("wawa", "wawa_3dAim", $string);
$string = str_replace("railyard", "mp_railyard", $string);
$string = str_replace("standoff", "xp_standoff", $string);
$string = str_replace("rocket", "mp_rocket", $string);
$string = str_replace("brecourt", "mp_brecourt", $string);
$string = str_replace("chateau", "mp_chateau", $string);
$string = str_replace("hurtgen", "mp_hurtgen", $string);
$string = str_replace("stalingrad", "mp_stalingrad", $string);
$string = str_replace("depot", "mp_depot", $string);
$string = str_replace("powcamp", "mp_powcamp", $string);
$string = str_replace("dawnville", "mp_dawnville", $string);
$string = str_replace("ship", "mp_ship", $string);
$string = str_replace("valley", "x_valley", $string);
$string = str_replace("burgundy", "mp_burgundy", $string);
$string = str_replace("westwall", "mp_westwall", $string);
return $string;
}
///////////////////////////////////////////////////////Gametypes
function gtt($string) {
$string = str_replace("^3_actf", "^3_actf", $string);
$string = str_replace("^2tdm", "^2tdm", $string);
$string = str_replace("^1htf", "^1htf", $string);
$string = str_replace("^9old^5sd", "^9old^5sd", $string);
$string = str_replace("^5sd", "^5sd", $string);
$string = str_replace("dem", "dem", $string);
$string = str_replace("sd", "sd", $string);
$string = str_replace("gungame", "gungame", $string);
$string = str_replace("dm", "dm", $string);
$string = str_replace("rsd", "^5rsd", $string);
$string = str_replace("bash", "bash", $string);
$string = str_replace("osd", "^9old^5sd", $string);
$string = str_replace("gun", "^6gun", $string);
return $string;
}

// settings
$filename = "error.log";
$log_folder = "/media/DataBase/Game_Servers/RCM/COD1_1_1_SD/ReCodMod/x_logs";
$log_cash = "/media/DataBase/Game_Servers/RCM/COD1_1_1_SD/ReCodMod/x_cache";

$rules_log_file1 = $log_folder.'/rcon-rules_commands.log'; 
$log_chat = $log_folder.'/rcon-p_chat.log'; 
$cnt_pl = $log_folder.'/rcon-players_time_stats.log'; 
$rules_log_file = $log_folder.'/rcon-r_kick_ban.log';     // path to a file containing kicked players; must be writeable
$rules_log_vote = $log_cash.'/z-vote.log';
$rules_log_aalba = $log_cash.'/z-alba.log';

$rules_log_vote_gt = $log_cash.'/z-vote-gametype.log';
$rules_log_vote_map = $log_cash.'/z-vote-map.log';
$info_log_file = $log_folder.'/rcon-info_players.log';
$info_log_reggg = $log_folder.'/z_unregistered.log';
$info_log_regGUID = $log_folder.'/z_REG_GUIDS.log';
$cheater_help = $log_folder.'/rcon-p_cheataers_help.log'; 
//////////////////////////////////////////////////////////////
$rules_warned_cache_file = $linuxxx.'ReCodMod/x_cache/warned-cache';     // path to a file containing warned players; must be writeable
$rules_vote_cache_file = '/voter'; 
$rules_iplog_interval = 0;      // this is a number of minutes the log is updated in ; 0 = disable
$rules_iplog_file = 'rcon-iplog.log';     // path to a file containing log of ip addresses; must be writeable

$rules_kick_bad_named = true;	// kick unknown soldiers, unnamed players and those who have only spaces in nick [those mentioned below]
$rules_kick_ip_super_range = true;
$rules_kick_ping = true;		// kick for pings
$rules_max_ping = 700;			// if ping is larger, player gets warned (annoying)
$rules_max_ping_kick = 900;		// if player has been warned in previous scan, and has ping larger than this, he gets kicked

$rules_time_announce = array(0,30);	// everytime minute digits equal one of these, shows current time to all players;  // array() to disable

$rules_empty_set_gametype = 'dm';	// if set, when player count is low, this gametype is set on the server (and map is restarted immediately)  // leave empty to disable
$rules_empty_set_gametype_players_min = 0;  // the server is considered empty with minimum of n present players
$rules_empty_set_gametype_players_max = 1;  // the server is considered empty with maximum of n present players

$ban_name = "^6 ^3You Banned by Admins ^2www.recod.ru";
$ban_name_all = "^6 ^1Banned ^7|| ^3RMod ".$z_ver." bantool";
$ban_ip = "^6 ^3You Banned by Admins ^2www.recod.ru";
$ban_ip_all = "^6 ^1Banned ^7|| ^3RMod ".$z_ver." bantool";
$c_unban = "^6 ^2UnBanned ^7|| ^3RMod ".$z_ver." bantool";

//$rules_vip[] = 'ffffffffffffffffffffffffff';

// use %s at the position you want the values to appear at
$rules_bad_name_msg = "^6 ^6||| ^3Rename - you're breaking the rules";

$rules_bad_name_msg_chat = "^6 ^6||| ^Error - breaking the rules ^7|| bantool(recod.ru)";
$rules_bad_name_msg_chatgg = "^6 ^6Breaking rules ^1[Censored] ^7|| ^3bantool(recod.ru)";

$rules_empty_set_gametype_msg = "^5Not enough players on the server for this gametype. Switching to %s."; // gametype is uppercased here
$rules_ping_warn_msg = "^6 ^3Your ping is %s - so high!";
$rules_ping_warn_vip_msg = "^6 ^3Ping = %s";   //shorter - not so much annoying
$rules_msgtoall_kicked_enable = true;   // notify others, that someone has been kicked
$rules_msgtoall_kicked_bad_name = "Player %s has bug name.";
$rules_msgtoall_kicked_ping = "^3Player %s was kicked for high ping.";
//$screeng = "^3Possible Cheater!?!";
//$screeng1 = "^1Unlock block or auto screen deleter! Stop Block AC[Alba]!";
//$screeng2 = "^1Where is screenshot?!";
//$screeng3 = "^1Admin Reported!";
$i_zzzzzzz = "^4Virus";
$chat_protect = '1';
$corrupt_replacement = '=======ERROR=======';
$welcome_x = "^6 ^3Welcome^7";
$welcome_x2 = "^6 ^3Welcome Back^7";	
////////////////////////////////////////////////////////////////
?>
