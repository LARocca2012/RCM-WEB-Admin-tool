<?php
usleep(998000);


//////////////////////////////============================	
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://recod.ru/forum/ip_check.php');
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$ip = curl_exec($ch);


$ip = '81.198.132.146';

echo ' Your-ip-adress '.$ip.' ';

curl_close($ch);
//////////////////////////////============================	

$date = date('Y.m.d H.i.s');
$db = sqlite_open("./ReCodMod/databases/database1.db", 0666, $sqliteerror);

sqlite_query($db, "DELETE FROM x_db_admins WHERE s_adm='$ip'"); 
ECHO 'Deleted';

/*
$sql = sqlite_query($db, "SELECT * FROM x_db_admins WHERE s_adm='$ip'"); 
if (sqlite_num_rows($sql) > 0) {
	echo '--------Please close this console-------';
	echo '--------Your adress created in db-------';
	sqlite_query($db, "UPDATE x_db_admins SET s_group='0' WHERE s_adm='$ip'"); 

	//exit;
	}else{
if (sqlite_query($db, "INSERT INTO x_db_admins (s_adm, s_dat, s_group) VALUES ('$ip', '$date', '111')") > 0) {
	echo 'Inserted in database'."\n";
	//exit;
	}	
}
*/
?>