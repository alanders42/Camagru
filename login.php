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
		<title>Camagru - Login</title>
	</head>
	<body>
		<header>
<!-- navigation bar -->
			<div class="navbar">
				<div class="navbar-brand">
					<?php
						get_menu();
					?>
				</div>
			</div>
		</header>
		<section class="section" style="margin-top:150px;margin-bottom:105px">
<!--content wrapper starts-->
			<div class="container">
	<!-- login form -->
				<form method="POST" action="login.php">
					<div class="field">
						<label class="label">Log In</label>
						<p class="control has-icons-left">
							<input class="input is-medium" type="email" name="user_email" placeholder="email" required/>
							<span class="icon is-small is-left">
								<i class="fas fa-envelope"></i>
							</span>
						</p>
					</div>
					<div class="field">
						<p class="control has-icons-left">
							<input class="input is-medium" type="password" name="user_passwd" placeholder="password" required/>
							<span class="icon is-small is-left">
								<i class="fas fa-lock"></i>
							</span>
						</p>
					</div>
					<div class="columns">
						<div class="column">
							<div class="field">
								<p class="control">
									<input class="button is-success" type="submit" name="login" value="Login">
								</p>
							</div>
						</div>
				</form>
<!-- Forgot password? You pleb. xD -->
						<div class="column is-one-fifth">
							<a href="client/forgot_passwd.php">Forgot Password?</a>
						</div>
					</div>
				<?php
					if (isset($_POST['login'])) {
						log_in();
					}
				?>
			</div>
		</section>
	</body>
		<!--footer starts-->
		<footer>
			<div id="footer">
				<h2 style="text-align:center; padding-top:30px;">&#169; alanders</h2>
			</div>
		</footer>
		<!--footer ends-->
</html>

