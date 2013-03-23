<?php

/*
+----------------------------------------------------+
|   ENCRYPT YOUR e107_config.php
|   a.php
|
|   Copyright 2010 Kevin Zoll
|   Zoll Technologies
|   http://zolltech.com
|   kzoll@zolltech.com
|
|   Released under the terms and conditions of the
|   GNU General Public License (http://gnu.org).
+----------------------------------------------------+
*/

if (file_exists('secureconfig.php'))
	{
	echo 'YOU HAVE TO DELETE secureconfig.php';
	exit;
	}

$sdr = $_SERVER['DOCUMENT_ROOT'];
$sdr = strtoupper(str_replace('\\','/',$sdr));

$mySQLserver = ECH0('{mySQLserver}',$sdr);
$mySQLuser = ECH0('{mySQLuser}',$sdr);
$mySQLpassword = ECH0('{mySQLpassword}',$sdr);
$mySQLdefaultdb = ECH0('{mySQLdefaultdb}',$sdr);
$mySQLprefix = ECH0('{mySQLprefix}',$sdr);

function ECH0($str,$key)
	{
	$letter = -1;
	$lenpath = strlen($key);
	$newpass = '';
	
	$str = base64_decode($str);
	for ($i = 0; $i < strlen($str); $i++)
		{
		$letter++;
		if ($letter >= $lenpath)
			{
			$letter = 0;
			}
		$neword = ord($str{$i})-ord($key{$letter});
		if ($neword <= 0)
			{
			$neword += 256;
			}
		$newpass .= chr($neword);
		}
	// echo $newpass;
	return $newpass;
	}
	
?>