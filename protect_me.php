
//Kollar s� man �r inloggad?

if (!isset($_SESSION["myid"])) {
	header('location: login.php');
	die();	
}