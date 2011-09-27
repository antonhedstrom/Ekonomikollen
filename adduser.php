<?php
/*
* adduser.php
*/
$protect_me = true;
$admin_page = true;
require('header.php');

$tpl->loadtemplate('content','templates/adduser.tpl.php');

$menu = '';
$menu .= '<li><a href="logout.php">Logga ut</a></li>';
$menu .= '<li><a href="index.php">Hem</a></li>';
$username = 'Unknown';
if (isset($_SESSION["myusername"])) {
	$username = $_SESSION["myusername"];

	if ($_SESSION["budget_admin"]) {
		$menu .= '<li><a href="listusers.php">Lista anv�ndare</a></li>';
	}
}


$information = "";


if ($_GET['do'] == "add") { //Anv�ndaren har fyllt i b�de anv�ndarnamn och l�senord => Testa mot databasen
	$error = false;
	$myuser=addslashes($_POST['user']);
	$mypass=md5($_POST['pass1'] . substr(strtolower(strrev($myuser)),1,3)); //MD5-hash med dynamiskt salt.
	
	if ( $_POST['user'] == '' ) {
		$information = '<p style="color:#966;"><b>Ange anv�ndarnamn.</b></p>'; 
	}
	else if ( $_POST['pass1'] == '' ) {
		$information = '<p style="color:#966;"><b>Ange l�senord.</b></p>'; 
	}
	else if ($_POST['pass1'] != $_POST['pass2']) {
		$information = '<p style="color:#966;"><b>L�senorden st�mde inte �verens</b></p>'; 
	}
	else {
		$resource = $db->query("SELECT * FROM user WHERE LOWER(name)=LOWER('".$myuser."')");

		if($db->num_rows($resource)==0) {
			$data = $db->format_output($resource);
			
			$resource = $db->query("INSERT INTO user (name, pwd) VALUES ('".$myuser."', '".$mypass."') ");

			$db->close_database();
			header("location: login.php");
			die();
		}
		else {
			$information = '<p style="color:#966;"><b>Anv�ndarnamnet �r upptaget.<br/>F�rs�k med ett annat.</b></p>'; //meddelar att anv�ndaren finns.
		}
		
		$db->close_database();
	}
	
}


$tpl->registervariable('content','information, menu');
$contents .= $tpl->parse('content');


require('footer.php');


?>

