#!/bin/php
<?php
/*/ ///******************  |||           \\\	
/*/ ///******************  |||         ///\ROCCA 
/*/ ///******************  |||        ///  \\\ (c) http://recod.ru/
/*/ ///******************  |||       ///    \\\
/*/ ///******************  |||      ///\\\*RecodMod V.15.0
/*/ ///******************  ||||||| ///        \\\          skype: larocca2012
/*/ ///******************/ 
include("zzz_add_here_server_configurations.php"); 

$sleep_rcon = 120000;

function stringrpos($haystack,$needle,$offset=NULL)
	{
	   if (strpos($haystack,$needle,$offset) === FALSE)
	     return FALSE;

	   return strlen($haystack)
	           - strpos( strrev($haystack) , strrev($needle) , $offset)
	           - strlen($needle);
	}

function rcon($s, $replace='')
	{
	global $connect, $server_rconpass;
	fwrite($connect, "\xff\xff\xff\xff" . 'rcon "' . $server_rconpass . '" '.strtr($s,array('%s'=>$replace)));
	$output = fread ($connect, 512);   //512
    //usleep(500*1000);
	usleep(200);
    return $output;
	}

function GetPlainName($name) // copied from original - IPluginCOD.php
	{

		$repname = $name;

		for($y=0; $y < 2; $y++) // Loop around a few time in case we have embedded colors!
		{
			for($x=0; $x < 10; $x++)
			{
				$repname = str_replace("^$x","",$repname);
				//$repname = str_replace("^^$x","",$repname);
			}
		}
		return $repname;
	}
	
////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////
echo " \n ================= \n ";
function hx($sc)
{
    $sc = str_replace(array(
        "w.php"
    ), '', $sc);
    return $sc . "";
}
$x_ff = 0;
$cpath = hx(__FILE__);
 
 
 
while (true) {
  usleep(1500000);  
  $x_loops      = 0;

 echo "."; 
							require $cpath . 'ReCodMod/functions/inc_functions2.php';
                            for ($i = 0; $i < $player_cnt; $i++) {
                                require $cpath . 'ReCodMod/functions/inc_functions3.php';						
								
try
  {	
  
$db2 = new PDO('sqlite:'.$database_folder.'db2.sqlite');
    $result = $db2->query("SELECT * FROM bans WHERE ip='$i_ip'");
    foreach($result as $row)
    {		
		$ipuuu = $row['ip'];
		$playername1 = $row['playername'];
		$reason  = 	$row['reason'];		  			
echo $i_id;
usleep($sleep_rcon*7);
rcon('say  ' . $playername1 . '  " ^1BANNED ^7Reason:^1" "'.$reason.'"', '');
usleep($sleep_rcon*7);
rcon('clientkick '.$i_id, '');
		  exit;	
	}			



  $result = $db2->query("SELECT * FROM bans");
    foreach($result as $row)
    {
		$playername = $row['playername'];
		$reason  = 	$row['reason'];
	
	      $plnm = trim($playername);
          $i_nn = trim($i_name);
		  
	 if (($plnm == $i_nn) && ($i_name != ''))
		{			
		  usleep($sleep_rcon*7); 
          rcon('clientkick '. $i_id, '');
          exit;		  
	} 
}


$dat = '.';
$x_addr = explode(".", $i_ip);
$result = $db2->query("SELECT * FROM x_ranges WHERE ip_ranges='$x_addr[0].$dat.$x_addr[1]'");
    foreach($result as $row)
    {	
		$ip_r = $row['ip_ranges'];	
		  usleep($sleep_rcon*7);
  rcon('clientkick '. $i_id, '');
 exit;  
	}




  }
  catch(PDOException $e)
  {
    print 'Exception : '.$e->getMessage();
  }								
								
}	 
	 
	 
}

/*/ ///******************  |||           \\\	
/*/ ///******************  |||         ///\ROCCA  
/*/ ///******************  |||        ///  \\\ (c) http://recod.ru
/*/ ///******************  |||       ///    \\\
/*/ ///******************  |||      ///\\\*RecodMod PLUGINS
/*/ ///******************  ||||||| ///        \\\      skype: larocca2012
?>

