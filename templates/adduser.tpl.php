<!-- header.tpl.php -->

	<div id="menu">
		<ul>
			{menu}
		</ul>
	</div>
	
	<h1>Ny anv�ndare</h1>

	<div id="frame" style="padding:2em 1em;">
	
		<p>V�lj ett anv�ndarnamn och l�senord.</p>
		<div id="login">
			<form action="adduser.php?do=add" method="post" name="login">
				<b>Anv�ndarnamn:</b><br/>	<input name="user" id="focus" length="25" style="border:1px #DDD solid;" /><br/><br/>
				<b>L�senord:</b><br/>		<input name="pass1" length="25" type="password" style="border:1px #DDD solid;" /><br/>
				<b>Upprepa l�senord:</b><br/>		<input name="pass2" length="25" type="password" style="border:1px #DDD solid;" /><br/><br/>
				<button value="skicka" type="submit" />Skapa</button>
			</form>
		</div>
		
		{information}

<!-- footer.tpl.php -->
