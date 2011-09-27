		
	<div id="menu">
		<ul>
			{menu}
		</ul>
	</div>
	
	<h1>{username}'s budget</h1>
	
	
	<div id="frame">


		<div id="stats">

		<h2 style="padding:0.4em;">Statistik:</h2>
		<table cellspacing="0" border="0" style="padding:2em 0.3em;" class="sortable">
			<thead>
				<tr>
					<th style="text-align: left;">Datum</th>
					<repeat name="categories_1">  
					<th>{row_cat1_desc}</th>
					</repeat name="categories_1">
					<th class="vertical_sum" style="width:70px;">Summa</th>
				</tr>
			</thead>
			<tbody>
			<repeat name="rows">
				<tr class="{row_class}"> 
					<td style="text-align:left;" sorttable_customkey="{row_date_sort_helper}">{row_date}</td>
					
				<repeat name="categories_2">
					<td>{row_{row_cat2_id}}</td>
				</repeat name="categories_2">

					<td class="vertical_sum">{row_summa}</td>
				</tr>
			</repeat name="rows">
			</tbody>
			
		</table>	
		</div>
