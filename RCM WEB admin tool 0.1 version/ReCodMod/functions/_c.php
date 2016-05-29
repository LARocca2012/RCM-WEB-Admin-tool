<?php 
$z_rcm = "RCM[v.x2.2]";
$dtx = "^7[20_05_2016]^5";

error_reporting(E_ALL);
ini_set("display_errors", 1);

//////////////////////////////============================	
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://recod.ru/forum/rcm.php');
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$zxxm = curl_exec($ch);

if($z_rcm == $zxxm){
$z_rcm = $zxxm;
}else{ $dtx = date('Y.m.d');
	   $z_rcm = 'New ADMINTOOL updates available RCM'; $spps = 1555555;}
	
$z_set =  $z_rcm." Admintool";
$z_ver = " ".$z_rcm." ".$dtx."";

$mapfix = 'dm';  // zom - IF ZOMBIES MOD , dm - if simple mod

$v_time_gtx = 100;   
$v_time_map = 100; 
$v_time_ban = 100;  
$v_time_kick = 100; 

$ax1 = 'admin';
$ax2 = 'moderator';
$ax3 = 'specialguests';
$ax4 = 'vip';

/*   Groups 
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
$web_con = '1';   
//require $cpath.'connect_cfg.php';
//********************************************************
/// Register from website sync. // if login from website use 1, no from website login = 0 - this line supporting only with special RCM web plugins
$registerx = '0';              
//********************************************************
//***********************************************\ dont change it after \***********************************************
//======================================================================= Alba supported only
$game_ac = '0';                    /// With anticheat alba (1 - yes, 0 - without)
$game_ac_status = '0';             /// Kick without alba and good skill (1 - yes, 0 - without)
$game_ptch = '---';                /// /1.2/ codx - on   another off if us codam
$game_mod = 'NONE';                /// for /codam/ mods awe 1.2 only
//======================================================================= Alba supported only
$sleep_rcon = 529000;              ///Rcon get pause time   
$target_url = "http://recod.ru/forum/db/database1.db";
$codecon = 'http://recod.ru/forum/RECODMOD_CONNECTOR.txt';  
//***********************************************\ dont change it before\***********************************************
require $cpath.'cfg/bans.php';
require $cpath.'cfg/all_cmds.php';
require $cpath.'cfg/badwords.php';
require $cpath.'cfg/spam.php';
require $cpath.'cfg/cryingkids.php';
require $cpath.'cfg/admins.php';
require $cpath.'cfg/messages.php';
require $cpath.'cfg/maps_gametypes.php';

$admin_code1 = $ax1.$admin_code; 
$moderator_code1 = $ax2.$moderator_code; 
$specialguests_code1 = $ax3.$specialguests_code; 
$vip_code1 = $ax4.$vip_code; 
	
?>

