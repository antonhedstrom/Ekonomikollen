<!-- header.tpl.php -->

	<div id="menu">
		<ul>
			{menu}
		</ul>
	</div>
	
	<h1>Ny användare</h1>

	<div id="frame" style="padding:2em 1em;">
	
		<p>Välj ett användarnamn och lösenord.</p>
		<div id="login">
			<form action="adduser.php?do=add" method="post" name="login">
				<b>Användarnamn:</b><br/>	<input name="user" id="focus" length="25" style="border:1px #DDD solid;" /><br/><br/>
				<b>Lösenord:</b><br/>		<input name="pass1" length="25" type="password" style="border:1px #DDD solid;" /><br/>
				<b>Upprepa lösenord:</b><br/>		<input name="pass2" length="25" type="password" style="border:1px #DDD solid;" /><br/><br/>
				<button value="skicka" type="submit" />Skapa</button>
			</form>
		</div>
		
		{information}

<!-- footer.tpl.php -->
