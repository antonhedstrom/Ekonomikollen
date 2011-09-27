<?php
session_start();
session_unset(); //frees all session variables currently registered. 
session_destroy(); //destroys all of the data associated with the current session.
header('Location: login.php');
?>