<?php 

///////////////////////////////
$c_1  = $ixz.'balance on';
$c_2  = $ixz.'balance off';
$c_3  = $ixz.'cam on';
$c_4  = $ixz.'cam off';
$c_5  = $ixz.'ff on';
$c_6  = $ixz.'ff off';
$c_6x  = $ixz.'e sd';
$c_7  = $ixz.'dg 0';
$c_7x  = $ixz.'e td';
$c_8  = $ixz.'dg 1';
$c_9  = $ixz.'dg 2';
$c_10 = $ixz.'dh 0';
$c_11 = $ixz.'dh 1';
$c_12 = $ixz.'pt20 '; 
$c_13 = $ixz.'pt50 '; 
$c_13x = $ixz.'pt100 ';  
$c_14 = $ixz.'pt1000 ';
$c_15 = $ixz.'boom ';
$c_16 = $ixz.'fgod ';
$c_17 = $ixz.'crash ';
$c_18 = $ixz.'crush ';

/////////////////////////////////////////////////////////////////////////////////
$restart  = $ixz.'restart';
$rcx  = $ixz.'rc ';  //all cod cmds
$mapc  = $ixz.'map ';
$gts  = $ixz.'gt ';
$cmd0  = $ixz.'ban ';  
$cmd1  = $ixz.'kick ';
$cmd_a_ru  = $ixz.'ru '; // poslat za alboi po russki
$cmd_a_en  = $ixz.'en '; // poslat za alboi po english ;)
$cmd3  = $ixz.'tban ';  ///'.$ixz.'tban 5 55
$scr1 = $ixz.'get ';
$ptx = $ixz.'unban ';

$skill_test  = $ixz.'n ';

$mistake = '\/+[a-z]';  //regedit search for mistakes
$skill  = $ixz.'sk';
$stats  = $ixz.'stats';
$cmdx0 = $ixz.'b ';  //vote ban    false; //

$cmdyes = '+';  //yes vote    false; //
$cmdyes_gt = '=';   //no vote     false; //
$cmdnoo = '-';   //no vote     false; //

$vk = $ixz.'k ';  //vote kick    false; // 
$mapvote = $ixz.'xmap ';  //vote map    false; // 
$gtvote = $ixz.'xgt ';    //vote map    false; // 

$cmd2  = $ixz.'cmd';
$cmdadm  = $ixz.'a--';
$cmdffun  = $ixz.'fun';
$cmd4  = $ixz.'ip';
$cmdcb  = $ixz.'geo';
$cmdme  = $ixz.'guid';
$cmd6  = $ixz.'help';
$cmd7  = $ixz.'top';
$cmd8  = $ixz.'info';  
$cmd9  = $ixz.'worst';
$cmd10 = $ixz.'kills';
$t_ime  = $ixz.'time';
$grk = $ixz.'status';
$cmdw   = '3Welcome';

///////////////////Special words
$che1  = 'ty';
$che2  = 'thx';  
$che3  = 'gg';
$reportz  = $ixz.'report';
$che4  = 'admin';
$che5  = 'support';
$che6  = 'supo';  
$che7  = 'chea';
$chez2  = 'wallhack';
$chez1  = 'haker';
$alarm1 = 'hack';
$alarm2 = 'aimbot';

$che8  = 'sps';
$che9  = 'bb';
$tit  = 'tits';

$gxg  = 'hi';
$hiserver  = 'hi server';
$gxg1  = 'hello';
$fps  = 'fps';
$fps1  = 'ping';

$alba = 'alba';
$alba1 = 'albu';
$alba2 = 'alby';
$alba3 = 'antic';
$alba4 = 'anti4';
 

$cmdzv = "^3".$ixz."cmd  ^5[id+^7 ".$cmdx0." + - = ".$mapvote."^5]^3 ".$ixz."report ".$ixz."guid ".$ixz."info ".$ixz."ip ".$ixz."id ".$ixz."all ".$ixz."status ".$ixz."fun ^5[+id^7 ".$ixz."sk ".$ixz."top ".$ixz."geo^5]";

$cmdzv2 = "^3".$ixz."n ".$ixz."stats ".$ixz."worst ".$ixz."rank ".$ixz."grenades ".$ixz."top ".$ixz."toprank ".$ixz."kills ".$ixz."bash ".$ixz."suicides ".$ixz."headshots ";



$cmdfun = "^3[^7hi gg gl ty thx sps mc ny afk fps stfu bl vdk ch dr xxx xl tits rabbit ^3] ^6[^7 ping admin ^6] ";
$cmdadmin = "^3[^7".$ixz."status - ^1Admin -> ^7".$ixz."balance ".$ixz."cam ".$ixz."dg ".$ixz."pt ".$ixz."list ".$ixz."kick ".$ixz."tban ".$ixz."ban ".$ixz."unban ".$ixz."map ".$ixz."gt ".$ixz."restart ".$ixz."get ^2".$ixz."rc^2x2 ^3]";

$grdl = '  ^1H^7appy ^1N^7ew ^1Y^7ear ^12016    ^3*<(:{D';
$albai = '^6 ^3[^1Alba Anticheat Client  ^2http:^2/^2/'.$website.'/alba ^7(ver. 7.20 & 7.61) ^3or ^2alba.ucoz.net ^7(only 7.61) ^3]';

$tit1  = '^6 ^3( o ) ( o ) ^7';
$gxg2  = '^6 CAM I"OBHO';
$fps3  = '^6 ^5 /cg_drawFPS  1    ^7&^5    /com_maxfps 125    ^7&^5    /cg_lagometer 1 ^7  ^7&^5  /seta cl_maxpackets 30 (min 20 max 100)';
$cmdz = " ^3[^6You find cheater? ^1Report cheater nickname ^3(Sample ^7".$ixz."report Kashtanka^3 , or ^2http:^2/^2/".$website." ^3]";
$cmdzx = " ^3[^6Admin needed? ^1Report here ^3(Sample ^7".$ixz."report admin i have big dick^3 , or ^2http:^2/^2/".$website." ^3]";


/// FOR _start.php command searching
$commands = array( $cmd0, $cmd1, $cmd_a_ru, $cmd_a_en, $cmd2, $cmdadm, $cmdffun, 
$cmd3, $cmd4, $scr1, $cmd6, $t_ime, $cmd7, $cmd8, $cmd9, $ptx, $mapc, 
$restart, $cmdcb, $cmdme, $gts, $che4, $reportz, $che5, $che6, $che7, $che8, $chez1, 
$cmd3, $chez2, $tit, $gxg1, $fps, $fps1, $alba, $alba1, $alba2, $alba3, $alba4, $alarm1, $alarm2,
$skill_test, $grk, $che1, $che2, $che3, $gxg, $hiserver, $che9, $mistake, $cmd10,
'xxx', 'xl', 'ch', 'vdk', 'dr', 'govn', 'gavn', 'bl', 'gl', 'stfu', 'afk', $skill, $stats,
$ixz.'status', $ixz.'i', $ixz.'id', $ixz.'all', $ixz.'rank', $ixz.'range', $ixz.'toprank', $ixz.'list', $ixz.'nade', $ixz.'grenade', $ixz.'heads', $ixz.'mellee', $ixz.'suicides',$ixz.'suicid', $ixz.'bash', $ixz.'headshots', $ixz.'banlist', $ixz.'ulist', $ixz.'kick', 'ny', 'mc', '#fp');

/// FOR _start.php admin command searching
$admin_com = array( $rcx,$c_1,$c_2,$c_3,$c_4,$c_5,$c_6,$c_7,$c_6x,$c_7x,$c_8,$c_9,$c_10,$c_11,$c_12,
$c_13,$c_13x,$c_14,$c_15,$c_16);

?>
