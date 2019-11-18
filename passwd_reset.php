<!DOCTYPE html>
<?php
// include ("config/setup.php");
include 'includes/connect.php';
include 'functions/functions.php';
ini_set("display_errors", true);
session_start();
?>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="author" content="Alexan Anderson">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.8.0/css/bulma.min.css">
		<script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
		<link rel="stylesheet" href="http://localhost:8080/Camagru/styles/index.css">
		<title>Camagru - Reset Password</title>
	</head>
	<body>
		<header>
			<div class="navbar">
				<div class="navbar-brand">
					<?php get_menu(); ?>
				</div>
			</div>
		</header>
		<section class="section" style="margin-top:150px;margin-bottom:100px">
			<div class="container">
	<!-- login form -->
				<form method="POST" action="">
					<div class="field">
						<label class="label">Change Password</label>
						<p class="control has-icons-left">
							<input class="input is-medium" type="email" name="user_email" placeholder="email"/>
							<span class="icon is-small is-left">
								<i class="fas fa-envelope"></i>
							</span>
						</p>
					</div>
					<div class="field">
						<label class="label"></label>
						<p class="control has-icons-left">
							<input class="input is-medium" type="password" name="new_passwd" placeholder="new password"/>
							<span class="icon is-small is-left">
								<i class="fas fa-lock"></i>
							</span>
						</p>
					</div>
					<div class="field">
						<p class="control">
							<input class="button is-success" type="submit" name="reset_pw" value="Reset Password">
						</p>
					</div>
				</form>
				<?php
					if (isset($_POST['reset_pw'])) {
						$ver_key = $_GET['ver_key'];
						$new_passwd = hash('whirlpool', $_POST['new_passwd']);
						if (replace_passwd($new_passwd, $ver_key)) {
							echo "<script>window.alert('Your password has been changed.')</script>";
							echo "<script>window.open('login.php', '_self')</script>";
						}
					}
				?>
			</div>
	</body>
			<!--footer starts-->
	<footer>
		<div id="footer">
			<h2 style="text-align:center; padding-top:30px;">&#169; alanders</h2>
		</div>
	</footer>
		<!--footer ends-->
</html>

