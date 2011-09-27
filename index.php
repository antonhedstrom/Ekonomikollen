<?php
/*
* index.php
*/
$protect_me = true;
require('header.php');


$menu = '';
$menu .= '<li><a href="logout.php">Logga ut</a></li>';
$username = 'Unknown';
if (isset($_SESSION["budget_username"])) {
	$username = $_SESSION["budget_username"];

	if ($_SESSION["budget_admin"]) {
		$menu .= '<a href="listusers.php"><li>Lista användare</li></a>';
	}
}

$menu .= '<li><a href="?transaction" onclick="return confirm(\'Vill du verkligen arkivera nuvarande nuffror?\');">Arkivera siffror</a></li>';

$tpl->loadtemplate('content','templates/index.tpl.php');

// Förbereder bilden
$im = new ImageGraph(490, 230);

if (!is_null($_SESSION["budget_color"]))
  $im->setColor($_SESSION["budget_color"]);

//Arkivering?
if(isset($_GET['transaction'])){
	$db->query('INSERT INTO transactions (dateline, userid) VALUES (now(), '.$_SESSION["budget_id"].')');
	$new_tid = $db->last_inserted_id();
	$db->query("UPDATE events SET transactionid = $new_tid 
				WHERE transactionid = 0 AND userid = " . $_SESSION['budget_id'] . "");
	
	//Döper om grafen
	rename(	$im->getDir() . "history_user-".$_SESSION['budget_id']."_tid-0.png", 
			$im->getDir() . "history_user-".$_SESSION['budget_id']."_tid-".$new_tid.".png");
	
	header('location: index.php');
}

//Formuläret
$dateline = date('ymd');
$tpl->registervariable('content','dateline');

//Gammal transaktion -> tid != 0
if(isset($_GET['tid']) and is_numeric($_GET['tid']))
	$tid = $_GET['tid'];
else
	$tid = 0;

if(isset($_GET['add'])) {
	//formuläret är postat.
	// Kolla om ny kategori
	if ($_POST['where'] == "_new") {
		$sql = "INSERT INTO kategori (description, userid)
				VALUES ('".$db->slashes($_POST['new_category'])."', ".$_SESSION['budget_id'].")";
		
		$db->query($sql);	
		$_POST['where'] = $db->last_inserted_id();
	}
	
	// Spara 
	$sql = "INSERT INTO events (dateline,price,kategoriid,comment,userid) VALUES (
		'".$db->slashes($_POST['dateline'])."',
		'".$db->slashes(str_replace(',','.',$_POST['price']))."',
		".$_POST['where'].",
		'".$db->slashes($_POST['comment'])."',
		".$_SESSION["budget_id"]."
		)";
	$db->query($sql);
	
	$im->setYlabel("Kronor");
	$im->setXlabel("Kategori");
	$resource = $db->query("SELECT k.description, sum(e.price) AS sum 
							FROM kategori k, events e 
							WHERE e.kategoriid = k.id 
								AND e.transactionid = $tid
								AND k.userid = ".$_SESSION['budget_id']."
								AND e.userid = ".$_SESSION['budget_id']."
							GROUP BY k.description
							ORDER BY k.description;");
	while($data = $db->format_output($resource)){
		$im->addValue($data['description'], $data['sum']);
	}
	
	$im->saveImage("history_user-".$_SESSION['budget_id']."_tid-".$tid.".png"); // Skriv över gammal bild
	
	header('location: ?act_id='.$db->last_inserted_id());
}

//Ta fram alla kategorier
$resource = $db->query("SELECT * FROM kategori k WHERE k.userid = ".$_SESSION['budget_id']." ORDER BY k.description ASC;");

$counter = -1;
while($data = $db->format_output($resource)) {
	$counter++;
	$categories[$counter]['row_id'] = $data['id'];
	$categories[$counter]['row_desc'] = $data['description'];
}

$act_event = 0;
if(isset($_GET['act_id']))
	$act_event = $_GET['act_id'];	
	
$resource = $db->query("SELECT e.id AS eventid, dateline, price, k.description, k.id, comment 
						FROM events AS e, kategori AS k
						WHERE transactionid = $tid 
							AND k.id = e.kategoriid
							AND e.userid = ".$_SESSION['budget_id']."
						ORDER BY dateline DESC");
$counter = -1;
while($data = $db->format_output($resource)) {
	$counter++;
	if($counter % 2 == 0)
		$rows[$counter]['row_css_class'] = 'even';
	else 
		$rows[$counter]['row_css_class'] = 'odd';
		
	if($data['eventid'] == $act_event)
		$rows[$counter]['row_css_class'] .= ' current';
		
	$month = DatePartFromDate('m',$data['dateline']) - 1;
	$rows[$counter]['row_date_sort_helper'] = DatePartFromDate('YmdHis', $data['dateline']);
	$rows[$counter]['row_date'] = DatePartFromDate('d',$data['dateline']) .' '. $months[$month];
	$rows[$counter]['row_price'] = number_format(intval($data['price']),0,'',' ');
	if(($data['price'] - intval($data['price'])) == 0)
		$rows[$counter]['row_decimal'] = '&nbsp;&nbsp;&nbsp;';
	else 
		$rows[$counter]['row_decimal'] = abs(($data['price'] * 100) % 100);
	$rows[$counter]['row_comment'] = nl2br(htmlentities($data['comment']));
	$rows[$counter]['row_where'] = htmlentities($data['description']);
	
	$summa_tot += $data['price'];
}

$summa_tot = number_format($summa_tot,2,',',' ');    


//Arkivmenyn
$resource = $db->query("SELECT id, dateline FROM transactions 
						WHERE userid = ".$_SESSION['budget_id']." 
						ORDER BY dateline DESC");
$counter = 0;
$archive[$counter]['arc_tid'] = 0;
$archive[$counter]['arc_to'] = '?';

while($data = $db->format_output($resource)){
	$archive[$counter]['arc_from'] = DatePartFromDate('Y-m-d',$data['dateline']);
	$counter++;
	$archive[$counter]['arc_tid'] = $data['id'];
	$archive[$counter]['arc_to'] = DatePartFromDate('Y-m-d',$data['dateline']);
}
$archive[$counter]['arc_from'] = '?';

$imageURL = $im->getURL("history_user-".$_SESSION['budget_id']."_tid-".$tid.".png");
$imageURL .= '?' . time(); 

$tpl->array_repeat('content','categories');
$tpl->array_repeat('content','rows');
$tpl->array_repeat('content','archive');
$tpl->registervariable('content','summa_tot,tid,imageURL,ordering,menu,username');
$contents .= $tpl->parse('content');

require('footer.php');

?>
