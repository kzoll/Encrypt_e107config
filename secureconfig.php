<?php

/*
+----------------------------------------------------+
|   ENCRYPT YOUR e107_config.php
|   secureconfig.php
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

echo "<html><body style='font-family:courier new'>";

if (!$aphp = @file_get_contents('a.php'))
	{
	echo '<br /><br />Sorry, could not read "a.php". Please, correct the problem and try again.';
	exit;
	}

if (!$handle = @fopen('a.php','w'))
	{
	echo '<br /><br />Sorry, could not open "a.php" for writing. Please, correct the problem and try again.';
	exit;
	}

$sdr = $_SERVER['DOCUMENT_ROOT'];
include('e107_config.php');
$sdr = strtoupper(str_replace('\\','/',$sdr));

if (!$sdr)
	{
	echo "<br />Sorry, you cannot secure your Database Password";
	exit;
	}
echo '<br />Your Server Document Root is: '.$sdr;
echo '<br />Important: Whenever (in the future) you change this path (for example if you move with your forum to another server) you need to rerun this program';
echo '<br /><br />';

//$sdr = chr(199);

function encode($str,$key)
	{
	$letter = -1;
	$lenpath = strlen($key);
	
	for ($i = 0; $i < strlen($str); $i++)
		{
		$letter++;
		if ($letter >= $lenpath)
			{
			$letter = 0;
			}
		$neword = ord($str{$i})+ord($key{$letter});
		if ($neword >= 256)
			{
			$neword -= 256;
			}
		$newpass .= chr($neword);
		}
	
	return base64_encode($newpass);
	}


echo "<br />A file called 'a.php' has been created. Now do the following:";
echo "<br />1) Open 'e107_config.php'\n";
echo "<br />2) Replace the 5 lines: ";
echo "<div style='border: 2px solid red'>";
echo '   $mySQLserver = \''.$mySQLserver.'\';';
echo '<br />   $mySQLuser = \''.$mySQLuser.'\';';
echo '<br />   $mySQLpassword = \''.$mySQLpassword.'\';';
echo '<br />   $mySQLdefaultdb = \''.$mySQLdefaultdb.'\';';
echo '<br />   $mySQLprefix = \''.$mySQLprefix.'\';';
echo "</div>";
echo "<br />   by the following 6 lines:";
$mySQLserver = encode($mySQLserver,$sdr);
$mySQLpassword = encode($mySQLpassword,$sdr);
$mySQLuser = encode($mySQLuser,$sdr);
$mySQLdefaultdb = encode($mySQLdefaultdb,$sdr);
$mySQLprefix = encode($mySQLprefix,$sdr);
echo "<div style='border: 2px solid green'>";
echo '   $mySQLserver = \''.randPass(strlen($mySQLserver)).'\';';
echo '<br />   $mySQLuser = \''.randPass(strlen($mySQLuser)).'\';';
echo '<br />   $mySQLpassword = \''.randPass(strlen($mySQLpassword)).'\';';
echo '<br />   $mySQLdefaultdb = \''.randPass(strlen($mySQLdefaultdb)).'\';';
echo '<br />   $mySQLprefix = \''.randPass(strlen($mySQLprefix)).'_\';';
echo "<br />   include_once('a.php');";
echo "</div>";
echo "(or any other random sequence of characters)";

echo "<br />";
echo "<br />Now delete the file 'secureconfig.php'!";

$fin[] = '{mySQLserver}';
$fin[] = '{mySQLuser}';
$fin[] = '{mySQLpassword}';
$fin[] = '{mySQLdefaultdb}';
$fin[] = '{mySQLprefix}';
$rep[] = $mySQLserver;
$rep[] = $mySQLuser;
$rep[] = $mySQLpassword;
$rep[] = $mySQLdefaultdb;
$rep[] = $mySQLprefix;

if (@fwrite($handle,str_replace($fin,$rep,$aphp)) === FALSE)
	{
	echo '<br /><br />Sorry, could not write to "a.php". Please, correct the problem and try again.';
	}

fclose($handle);

function randPass($len) 
	{ 
	for ($i=0; $i < $len; $i++)
		{
		$r = rand(1,3);
		if (($i == 0) && ($r == 1))
			{
			$r = 3; // No digit as first character
			}
		switch($r)
			{
			case 1: $pw .= chr(rand(48,57));  break; //0-9
			case 2: $pw .= chr(rand(65,90));  break; //A-Z
			case 3: $pw .= chr(rand(97,122)); break; //a-z
			}
		}
	return $pw;
	}

echo '</body></html>';

?>