<?php
require('header.php');

$tpl->loadtemplate('content','templates/login.tpl.php');
$information = "";


if ($_GET['do'] == "login") { //Anv�ndaren har fyllt i b�de anv�ndarnamn och l�senord => Testa mot databasen
	$error = false;
	$myuser=addslashes($_POST['user']);
	$mypass=md5($_POST['pass'] . substr(strtolower(strrev($myuser)),1,3)); //MD5-hash med dynamiskt salt.

	$resource = $db->query("SELECT * FROM user WHERE LOWER(name)=LOWER('".$myuser."') and pwd='".$mypass."'");

	if($db->num_rows($resource)==1) {
		$data = $db->format_output($resource);
		$_SESSION["budget_username"] = $data['name'];
		$_SESSION["budget_id"] = $data['id'];
		$_SESSION["budget_admin"] = $data['admin'];
		$_SESSION["budget_color"] = addslashes($data['color']);

		$db->close_database();
		header("location: index.php");
		die();
	}
	else {
		$information = '<p style="color:#966;"><b>Anv�ndarnamn och l�senord st�mde inte �verens.</b></p>'; //meddelar att anv�ndare&l�senord inte st�mde �verens
	}
	$db->close_database();
}

$tpl->registervariable('content','information');
$contents .= $tpl->parse('content');

require('footer.php');
?>

