<!-- header.tpl.php -->

	<h1>Logga in</h1>

	<div id="frame" style="padding:2em 1em;">
	
		<div id="login">
			<form action="login.php?do=login" method="post" name="login">
				<b>Anv�ndarnamn:</b><br/>	<input name="user" id="focus" length="25" style="border:1px #DDD solid;" /><br/>
				<b>L�senord:</b><br/>		<input name="pass" length="25" type="password" style="border:1px #DDD solid;" /><br/><br/>
				<button value="skicka" type="submit" />Logga in</button>
			</form>
		</div>
		
		{information}

<!-- footer.tpl.php -->
