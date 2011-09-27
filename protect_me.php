
//Kollar så man är inloggad?

if (!isset($_SESSION["myid"])) {
	header('location: login.php');
	die();	
}