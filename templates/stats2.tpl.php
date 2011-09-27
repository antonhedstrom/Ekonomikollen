
		
	<div id="menu">
		<ul>
			{menu}
		</ul>
	</div>
	
	<h1 id="username"><a href="index.php">{username}'s budget</a></h1>
	
	
	<div id="frame">



		<div id="stats"><h2 style="padding:0.4em;">Statistik:</h2>
		<table cellspacing="0" border="0" style="padding:2em 0.3em;" class="sortable">
			<thead>
				<tr class="head_h3">
					<th>Datum</th>
					<repeat name="categories">  
							<th style="text-align:right; padding-right:0.4em;">{row_cat1_desc}</th>
					</repeat name="categories">
							<th style="width: 70px; text-align: right;">Summa</th>
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
			
			<tfoot>
			
					<tr class="summa"> 
						<td>Summa:</td>
					
					<repeat name="sum">
						<td>{summa}</td>
					</repeat name="sum">
					
					
	 	       		<td colspan="15" id="summa">Summa: {summa_tot}</td>
	 	       		
				</tr>
		    	
			</tfoot>
		
		</table>	
		</div>
