<?php 

$messages=array(
"^5Welcome to our server and website ^2http:/^2/".$website."",
"^5Use ".$ixz."report for cheater report & ".$ixz."support contact to admin",
"^5Use ".$ixz."cmd !",
"^7Watching us ^3".$z_ver." ^6[^7".$ixz."info^6]"
);


$text=array();$slovam=1;
for($i=0;$i<$slovam;$i++){$ran = rand(0,count($messages)-1);
if(!in_array($messages[$ran],$text)){$text[]=$messages[$ran];}else{$i--;}}
foreach($text as $message) {
  // echo $message . "\n";
}
?>