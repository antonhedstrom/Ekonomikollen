		
	<div id="menu">
		<ul>
			{menu}
		</ul>
	</div>
	
	<h1>Registrerade användare</h1>
	
	
	<div id="frame">


		<div id="stats">

		<h2 style="padding:0.4em;">Användare:</h2>
		<table cellspacing="0" border="0" style="padding:2em 0.3em; width:100%;" class="sortable">
			<thead>
				<tr>
					<th style="text-align: left;">Namn</th>
					<th>Antal poster</th>
				</tr>
			</thead>
			<tbody>
			<repeat name="users">
				<tr class="{row_class}"> 
					<td style="text-align:left;">{row_name}</td>
					<td>{row_usage}</td>
				</tr>
			</repeat name="users">
			</tbody>
			
		</table>	
		</div>
