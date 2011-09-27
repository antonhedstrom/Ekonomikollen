<?php
/*
* listusers.php
*/
$protect_me = true;
$admin_page = true;
require('header.php');

$tpl->loadtemplate('content','templates/listusers.tpl.php');

$menu = '';
$menu .= '<li><a href="logout.php">Logga ut</a></li>';
$menu .= '<li><a href="index.php">Hem</a></li>';
$username = 'Unknown';
if (isset($_SESSION["budget_username"])) {
	$username = $_SESSION["budget_username"];

	if ($_SESSION["budget_admin"]) {
		$menu .= '<li><a href="adduser.php">Skapa användare</a></li>';
	}
}

//Ta fram alla kategorier
$resource = $db->query("SELECT u.id, u.name, count(e.id) as countevents FROM user u LEFT JOIN events e ON u.id = e.userid GROUP BY u.name ORDER BY countevents DESC");

$counter = -1;
while($data = $db->format_output($resource)) {
	$counter++;
	$users[$counter]['row_userid'] = $data['id'];
	$users[$counter]['row_name'] = $data['name'];
	$users[$counter]['row_usage'] = $data['countevents'];

	if($counter % 2 == 0)
		$users[$counter]['row_class'] = 'odd';
	else 
		$users[$counter]['row_class'] = 'even';
}

$tpl->array_repeat('content','users');
$tpl->registervariable('content', 'menu, username');

$contents .= $tpl->parse('content');

require('footer.php');

?>