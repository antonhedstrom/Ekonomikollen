<?php
/*
* stats.php
*/
$protect_me = true;
require('header.php');


$menu = '';
$menu .= '<li><a href="index.php">Hem</a></li>';
$username = 'Unknown';
if (isset($_SESSION["budget_username"])) {
	$username = $_SESSION["budget_username"];

	if ($_SESSION["budget_admin"]) {
	}
}
$menu .= '<li><a href="logout.php">Logga ut</a></li>';

$tpl->loadtemplate('content','templates/stats.tpl.php');

//Ta fram alla kategorier
$resource = $db->query("SELECT * FROM kategori k WHERE userid=".$_SESSION['budget_id']." ORDER BY k.description ASC");

$counter = -1;
while($data = $db->format_output($resource)) {
	$counter++;
	$categories_1[$counter]['row_cat1_id'] = $data['id'];
	$categories_1[$counter]['row_cat1_desc'] = $data['description'];
	$categories_2[$counter]['row_cat2_id'] = $data['id'];
	$categories_2[$counter]['row_cat2_desc'] = $data['description'];
}


//Ta fram högsta id:t
$result = $db->format_output($db->query("SELECT max(id) max_id FROM kategori WHERE userid=".$_SESSION['budget_id'].""));
$max_id = $result['max_id'];


//Data
$sql = "SELECT date_format(dateline, '%b-%y') AS datum, kategoriid, sum(price) AS pris
		FROM events 
		WHERE userid=".$_SESSION['budget_id']." 
		GROUP BY year(dateline), month(dateline), kategoriid 
		ORDER BY year(dateline) DESC, month(dateline) DESC";
$resource = $db->query($sql);

$count_month = -1;
$summa = array();
$month = '';
while($data = $db->format_output($resource)){
	if($month != $data['datum']){
		//Ny månad!
		$count_month++;
		$month = $data['datum'];
		for($i = 0; $i<=$max_id;$i++){
			$rows[$count_month]['row_'.$i] = '0';
			$summa[$i] += '0';
		}
	}
	if($count_month % 2 == 0)
		$rows[$count_month]['row_class'] = 'odd';
	else 
		$rows[$count_month]['row_class'] = 'even';

	$rows[$count_month]['row_'.$data['kategoriid']] = round($data['pris'],0);
	$summa[$data['kategoriid']] += round($data['pris'],0);
	$rows[$count_month]['row_summa'] += round($data['pris'],0);
	$rows[$count_month]['row_date'] = $data['datum'];
	$rows[$count_month]['row_date_sort_helper'] = DatePartFromDate('YmdHis', $data['datum']);

}
$count_month++;
for($i = 0; $i<=$max_id;$i++){
	$rows[$count_month]['row_'.$i] = $summa[$i];
	$rows[$count_month]['row_summa'] += $summa[$i];
}	
$rows[$count_month]['row_color'] = '#fff';
$rows[$count_month]['row_date'] = 'Summa:';
$rows[$count_month]['row_class'] = 'summa';

$tpl->array_repeat('content','categories_1');
$tpl->array_repeat('content','categories_2');
$tpl->array_repeat('content','rows');
$tpl->registervariable('content', 'menu, username');

$contents .= $tpl->parse('content');

require('footer.php');

?>