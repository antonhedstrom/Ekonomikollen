<?php
session_start();
require('includes/settings.php');
require('includes/functions.php');
require('classes/templateclass.php');
require('classes/databaseclass.php');
require('classes/imagegraphclass.php');
$db = new Database();
$tpl = new template();
$contents = '';
$individual_color = false;

if ( $protect_me ) {
	
	// Kollar så man är inloggad?
	if (!isset($_SESSION["budget_id"])) {
		header('location: logout.php');
		die();	
	}
	
	// Om det är en admin sida så måste man vara admin
	if ( $admin_page ) {
	  if (!$_SESSION["budget_admin"]) {
		  header('location: logout.php');
		  die();	
		}
	}
	
	$color = "AA";
	if (strlen($_SESSION["budget_color"]) == 6 OR strlen($_SESSION["budget_color"]) == 3 ) {
    $color = $_SESSION["budget_color"];
	  $individual_color = true;
	}
	$tpl->registervariable('header','username, menu, color');

}

$tpl->loadtemplate('header','templates/header.tpl.php');

$tpl->parse_if('header', 'protect_me');
$tpl->parse_if('header', 'individual_color');
	
$contents .= $tpl->parse('header');

?>