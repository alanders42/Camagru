<!DOCTYPE html>
<?php
include("config/setup.php");
include "functions/functions.php";
ini_set("display_errors", true);
session_start();
?>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="author" content="Alexan Anderson">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.8.0/css/bulma.min.css">
		<link rel="stylesheet" href="http://localhost:8080/Camagru/styles/index.css">
		<script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
		<title>Camagru</title>
	</head>
	<body style="background-image:url(background.jpg)">
		<header>
				<div class="navbar-brand">
					<?php
						get_menu();
					?>
				</div>
		</header>
		<main>
			<section class="section" style="margin-top:190px;margin-bottom:100px">
				<div class="container is-fluid">
					<?php
						get_gallery();
					?>
				</div>
			</section>
		</main>
	</body>
	<footer>
		<div id="footer">
			<h2 style="text-align:center; padding-top:30px;">&#169; alanders</h2>
		</div>
	</footer>
</html>
<?php
if (isset($_GET['session_status'])) {
	if ($_GET['session_status'] == "logout") {
		log_out("index");
	}
}
?>
