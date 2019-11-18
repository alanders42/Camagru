<!DOCTYPE html>
<?php
include ("includes/connect.php");
include ("functions/functions.php");
ini_set("display_errors", true);
session_start();
?>
<html lang="en" dir="ltr">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="author" content="Alexan Anderson">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.8.0/css/bulma.min.css">
		<script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
		<link rel="stylesheet" href="http://localhost:8080/Camagru/styles/index.css">
		<title>Camagru - Register</title>
	</head>
	<body>
		<header>
			<div class="navbar">
				<div class="navbar-brand">
					<?php
						get_menu();
					?>
				</div>
			</div>
		</header>
		<section class="section" style="margin-top:150px;margin-bottom:100px">
			<div class="container">
				<p class="title">Register</p>
				<form action="register.php" method="POST" enctype="multipart/form-data">
					<div class="field">
						<label class="label">Name</label>
						<p>
							<input class="input is-medium" type="text" name="u_name" placeholder="Username" required/>
						</p>
					</div>
					<div class="field">
						<label class="label">Email</label>
						<p>
							<input class="input is-medium" type="email" name="u_email" placeholder="Enter an email address" required/>
						</p>
					</div>
					<div class="field">
						<label class="label">Password</label>
						<p>
							<input class="input is-medium" type="password" name="u_passwd" placeholder="Password" required/>
						</p>
					</div>
					<div class="field">
						<label class="label">Profile Picture</label>
						<div class="file has-name">
							<label class="file-label">
								<input class="input is-medium" type="file" name="u_image" placeholder="" required/>
							</label>
						</div>
					</div>
					<div class="field">
						<p class="control">
							<input class="button is-success" type="submit" name="register" value="Register">
						</p>
					</div>
				</form>
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
<?php
//check if register button clicked
	if (isset($_POST['register'])) {
//get registration data
		$u_name = $_POST['u_name'];

		validate_name($u_name);

		$u_email = $_POST['u_email'];

		validate_email($u_email);

		$u_passwd = hash('whirlpool', $_POST['u_passwd']);
		validate_password($_POST['u_passwd']);

		$u_image = $_FILES['u_image']['name'];
		$u_image_tmp = base64_encode(file_get_contents($_FILES['u_image']['tmp_name']));
		// move_uploaded_file($u_image_tmp, "client/client_images/$u_image");

		$ver_code = hash('whirlpool', time().$u_email);
//check if user email exists in db
		$check = $con->prepare("SELECT * FROM users WHERE user_email=?");
		$check->execute([$u_email]);
		$ret = $check->fetch();
		if ($ret) {
			echo "<script>window.alert('This user exists!')</script>";
		}
		else {
//execute insert query  ///make seperate function
			$sql = "INSERT INTO users (`user_name`, `user_passwd`, `user_email`, `user_image`, `token`) VALUES (:u_name, :u_passwd, :u_email, :u_image, :ver_code)";
			$insert_data = $con->prepare($sql);
			$insert_data->execute(array(':u_name'=>$u_name, ':u_passwd'=>$u_passwd,':u_email'=>$u_email,':u_image'=>$u_image_tmp,':ver_code'=>$ver_code));

//save session vars for later use   ///make seperate funct
			$_SESSION['user_email'] = $u_email;
			// $_SESSION['user_id'] = $u_id['user_id'];
			$_SESSION['user_name'] = $u_name;

//send email to user_email for verification
			if (verif_email($u_email, $ver_code)) {
				echo "<script>window.alert('An email has been sent to ".$u_email.". Check your email to verify your account.')</script>";
				echo "<script>window.open('index.php', '_self')</script>";
			}
			else {
				echo "<script>window.alert('Error!')</script>";
			}
		}
		$con = null;
	}

?>



