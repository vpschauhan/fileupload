<?php
ini_set('display_errors', 'On');
date_default_timezone_set('Asia/kolkata');	
$connection = mysqli_connect("localhost", "lisacade_ezrtris", "!Ezrtris@");
$db = mysqli_select_db($connection, "lisacade_ezr_crm");
?>
