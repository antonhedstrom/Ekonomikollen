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

$tpl->loadtemplate('content','templates/stats2.tpl.php');


//Ta fram alla kategorier
$resource = $db->query("SELECT * FROM kategori k WHERE userid=".$_SESSION['myid']." ORDER BY k.description ASC");

$counter = -1;
while($data = $db->format_output($resource)) {
	$counter++;
	$categories[$counter]['row_cat1_id'] = $data['id'];
	$categories[$counter]['row_cat1_desc'] = $data['description'];
	
	$categories_2[$counter]['row_cat2_id'] = $data['id'];
	$categories_2[$counter]['row_cat2_desc'] = $data['description'];
}


//Ta fram högsta id:t
$resource = $db->query("SELECT description FROM kategori WHERE userid=".$_SESSION['myid']."");
$kategorier = array();
$number_of_categories = 0;
while($data = $db->format_output($resource)){
	$kategorier[$number_of_categories] = $data['description'];
	$number_of_categories++;
}

//Data
$sql = "SELECT date_format(dateline, '%b-%y') AS datum, kategoriid, sum(price) AS summan
		FROM events 
		WHERE userid=".$_SESSION['myid']." 
		GROUP BY year(dateline), month(dateline), kategoriid 
		ORDER BY year(dateline) DESC, month(dateline) DESC";
		
$sql = "SELECT date_format(e.dateline, '%b-%y') AS datum, k.description as kategorinamn, sum(e.price) AS summan 
		FROM events e, kategori k 
		WHERE e.userid=".$_SESSION['myid']." AND e.kategoriid=k.id 
		GROUP BY year(e.dateline), month(e.dateline), e.kategoriid 
		ORDER BY year(e.dateline) DESC, month(e.dateline) DESC, k.description;";
		
$resource = $db->query($sql);

$count_month = -1;
$summa = array_fill(0, $number_of_categories, '0');
$month = '';
while($data = $db->format_output($resource)){
	if($month != $data['datum']){
		//Ny månad!
		$count_month++;
		$month = $data['datum'];
		
		if($count_month % 2 == 0)
			$rows[$count_month]['row_class'] = 'even';
		else 
			$rows[$count_month]['row_class'] = 'odd';
	}

	$rows[$count_month]['row_date'] = $data['datum'];
	$rows[$count_month]['row_date_sort_helper'] = DatePartFromDate('YmdHis', $data['datum']);
		
	for($i = 0; $i<=$number_of_categories; $i++){
		$rows[$count_month]['row_'.$i] = '0';

		$summa[$i] += round($data['summan'],0);
	
		$rows[$count_month]['row_'.$i] = round($data['summan'],0);
		$rows[$count_month]['row_summa'] += round($data['summan'],0);
	}
	
}

// Räknar ut summan
$sum_index = 0;
for($i = 0; $i<=$number_of_categories;$i++){
	$sum[$sum_index]['summa'] = $summa[$i];
	$sum[$sum_index]['row_summa'] += $summa[$i];
	$sum_index++;
}
/* Räknar ut summan
$sum_index = 0;
for($i = 0; $i<=$max_id;$i++){
	$sum[$sum_index]['row_'.$i] = $summa[$i];
	$sum[$sum_index]['row_summa'] += $summa[$i];
}
*/

$tpl->array_repeat('content','categories_1');
$tpl->array_repeat('content','categories_2');
$tpl->array_repeat('content','rows');
$tpl->array_repeat('content','sum');
$tpl->registervariable('content', 'menu, username');

$contents .= $tpl->parse('content');

require('footer.php');

?>