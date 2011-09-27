
	<div id="menu">
		<ul>
			{menu}
		</ul>
	</div>
	
	<h1>{username}'s budget</h1>
	
	
	<div id="frame">


		<div id="add">
			<div id="add_toggle" title="Klicka för att lägga till utgift">
				<h2>Lägg till</h2>
			</div>
			<div id="add_form">
			<table cellspacing="0" border="0" style="width:100%;">
        		<thead>
        			<tr>
						<th>Pris?</th>
						<th>När?</th>
						<th>Var?</th>
						<th>Kommentar?</th>
					</tr>
				</thead>
				<tbody>
				<tr class="add"> 
					<form action="?add" name="add" method="post"> <!-- Formuläret för att lägga till poster -->
	  					<td rowspan="2"><input type="text" name="price" size="3" tabindex="1" /></td>
	  					<td rowspan="2"><input type="text" name="dateline" size="6" tabindex="2" id="add_date" value="{dateline}" /></td>
	  					<td rowspan="2">

					<repeat name="categories">  
							<input type="radio" name="where" value="{row_id}" tabindex="3" id="id_{row_id}" />
							<label for="id_{row_id}">{row_desc}</label><br />
					</repeat name="categories">
							<input type="radio" name="where" value="_new" id="radio_new_cat" /><input type="text" size="8" name="new_category" onfocus="javascript: document.getElementById('radio_new_cat').checked = true;" /><br />
						</td>
	  					<td align="center">
							<textarea rows="5" style="width:95%;" name="comment" tabindex="4"></textarea><br />
							<button type="submit" tabindex="5"  />Spara</button>
						</td>
					</form>
				</tr>
				</tbody>
			</table>
			</div>
		</div>
		
	    <div id="history"> 
	   
			<div id="icons">
				<!--<a href="?transaction" onclick="return confirm('Vill du verkligen arkivera nuvarande nuffror?');"><img src="layout/gfx/icon_add.png" alt="arkivera" title="Arkivera!" /></a>-->
				<a href="stats.php"><img src="layout/gfx/icon_stats.png" alt="statistik" title="Visa statistik" /></a>
			</div>
				
				
	    	<h2>Aktuellt</h2>
	    	
			<table cellspacing="0" border="0" style="width:100%;" class="sortable" id="arkiv">
		 	    <thead>   	
		 	       	<tr class="head_h3">
						<th>Datum</th>
						<th class="sorttable_group">Kategori</th>
						<th>Kommentar</th>
						<th style="text-align:right;">Pris</th>
						<th></th>
		 	       	</tr>
		 	    </thead>
		 	    <tbody>
			<repeat name="rows">
		 	       	<tr class="{row_css_class}"> 
					<td class="date" sorttable_customkey="{row_date_sort_helper}">{row_date}</td>
					<td class="category">{row_where}</td>
					<td class="comment">{row_comment}</td>
					<td class="price"><b>{row_price}</b></td>
					<td><b style="font-size:0.7em;vertical-align:top;">{row_decimal}</b></td>
		 	       	</tr>
			</repeat name="rows">
				</tbody>
				<tfoot>		
		 	       	<tr> 
		 	       		<td colspan="5" id="summa">Summa: {summa_tot}</td>
		 	       	</tr>
				 </tfoot>
			</table>
		</div>
		
		<div style="text-align:center;">	
			<img src="{imageURL}" alt="graf" /><br /><br />
		</div>

		<div id="archive">
			<div id="archive_toggle" title="Klicka för att lägga till utgift">
				<h2>Arkiv</h2>
			</div>
			<div id="archive_display">
			
			<repeat name="archive">
				<a href="?tid={arc_tid}"><font style="color:#999;">från</font> {arc_from} <font style="color:#999;">till</font> {arc_to}</a><br />
			</repeat name="archive">
			
			</div>
		</div>
			
		<br style="clear:both;" />