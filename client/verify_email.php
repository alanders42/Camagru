<!DOCTYPE html>
<?php
// include("config/setup.php");
include '../includes/connect.php';
include '../config/database.php';
include '../functions/functions.php';
ini_set("display_errors", true);
session_start();
    // include("functions/functions.php");
?>
<html lang="en" dir="ltr">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="author" content="Alexan Anderson">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.8.0/css/bulma.min.css">
		<script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
		<link rel="stylesheet" href="http://localhost:8080/Camagru/styles/index.css">
		<title>Camagru - Email Verified</title>
	</head>
	<body>
		<header>
			<div class="navbar">
				<div class="navbar-brand">
					<a href="index.php">
					</a>
					<?php
						get_menu();
					?>
				</div>
			</div>
		</header>
			<section class="section"  style="margin-top:150px;margin-bottom:100px">
				<div class="container">
					<?php
			//checks if user is already verified or if user exists. Redundancy can be safer.
						if (isset($_GET['ver_key'])) {
							$get_user = $con->prepare("SELECT * FROM users WHERE token=?");
							$get_user->execute([$_GET['ver_key']]);
							$user = $get_user->fetch();
							if ($user['verified'] == 1) {
								$_SESSION['user_name'] = $user['user_name'];
								$_SESSION['user_email'] = $user['user_email'];
								$_SESSION['user_id'] = $user['user_id'];
								$_SESSION['notif'] = $user['notify'];
								echo "<p class='title'>You are already a verified user. <a href='my_account.php'>here</a>.</p>";
							} else {
								$u_email = $user['user_email'];
								$ver_key = $user['token'];

								$updt_usr = $con->prepare("UPDATE users SET verified=1 WHERE user_email=?");
								$updt_usr->execute([$u_email]);

								$_SESSION['user_name'] = $user['user_name'];
								$_SESSION['user_email'] = $user['user_email'];
								$_SESSION['user_id'] = $user['user_id'];
								$_SESSION['notif'] = $user['notify'];
								echo "<p class='title'>Your account is verified. Click here <a href='my_account.php?'>here</a>.</p>";
							}
						}
						else {
							echo "<script>window.alert('Bye');window.open('../index.php', '_self')</script>";
						}

					?>
				</div>
			</section>
		</body>
		<footer>
			<div id="footer">
				<h2 style="text-align:center; padding-top:30px;">alanders</h2>
			</div>
		</footer>

</html>
