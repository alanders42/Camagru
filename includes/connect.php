<?php
try {
	$con = new PDO("mysql:host=localhost;dbname=db_camagru", "root", "root");
	$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e) {
	echo "ERROR: ".$e->getMessage();
}
?>
