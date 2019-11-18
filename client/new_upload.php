<!DOCTYPE html>
<?php
// include("config/setup.php");
include "../functions/functions.php";
include "../functions/upload_taken.php";
ini_set("display_errors", true);
// session_start();
?>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="author" content="Alexan Anderson">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.8.0/css/bulma.min.css">
		<script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
		<script src="../functions/hasGetUserMedia.js"></script>
		<link rel="stylesheet" href="http://localhost:8080/Camagru/styles/index.css">
		<title>Camagru</title>
	</head>
	<body>
		<header>
			<div class="navbar">
				<div class="navbar-brand">
					<a href="../index.php">
					</a>
					<div class='navbar-start'>
					<div class='navbar-item'>
						<a class='button is-primary' href='../index.php'>Home</a>
					</div>
					<div class='navbar-item'>
						<a class='button is-light' href='my_account.php'>My Account</a>
					</div>
					<div class='navbar-item'>
						<a class='button is-light' href='new_upload.php?session_status=logout'>Log Out</a>
					</div>
				</div>
				</div>
			</div>
		</header>
		<main>
			<section class="section" style="margin-top:150px;margin-bottom:100px">
	<!-- webcam and image upload tile -->
				<div class="tile is-ancestor">
					<div class="tile is-8">
						<div class="tile is-parent">
							<article class="tile is-child box">
								<p class="title">Take a picture</p>
									<video autoplay id='vid' width='720' height='480' style=''></video>
									<br/>
									<div class="buttons is-centered">
										<button class="button is-centered" id="shoot" >Take Picture</button>
									</div>
									<canvas id='uploadCanvas' width='720' height='480' style=""></canvas>
								<form action="" method="POST" enctype=multipart/form-data>
									<input name="taken" id="taken" type="hidden" value="upload_taken.php">
									<div class="box column has-text-centered is-10 is-offset-1">
										<img src="http://localhost:8080/Camagru/images/superimposables/neon_triangle.png" class="supers" width="100" height="100">
										<img src="http://localhost:8080/Camagru/images/superimposables/cracked_glass.png" class="supers" width="100" height="100">
										<img src="http://localhost:8080/Camagru/images/superimposables/paint_me_bart.png" class="supers" width="100" height="100">
										<img src="http://localhost:8080/Camagru/images/superimposables/handsome.png" class="supers" width="100" height="100">
									</div>
									<div class="buttons is-centered">
										<button class="button is-centered is-hidden" type="submit" name="submit_taken" id="submit_taken" style="">Upload Photo</button>
									</div>
								<br/><br/><p class="title">Or Upload a picture</p>
									<input name="upl_image" id="upl_image" type="file">
									<input class="button" name="upload" type="submit" value="Upload Picture">
								</form>
							</article>
						</div>
					</div>
		<!-- end of webcam and image upload tile -->
		<!-- last 5 uploaded imgs sidebar -->
					<div class="tile is-4">
						<div class="tile is-parent">
							<article class="tile is-child box">
								<p class="title">Images You've Uploaded</p>
								<?php get_upload_thumbs($_SESSION['user_id']); ?>
							</article>
						</div>
					</div>
				</div>
		<!-- sidebar ends -->
				<?php
					if (isset($_POST['upload'])){
						if (isset($_FILES['upl_image']['name'])){
							upload_image($_SESSION['user_id']);
						}
					}
				?>
			</section>
		</main>
	</body>
	<footer>
		<div id="footer">
			<h2 style="text-align:center; padding-top:30px;">&#169; alanders</h2>
		</div>
	</footer>
	<script src="../includes/take_picture.js"></script>
</html>
<?php
if (isset($_GET['session_status'])) {
	if ($_GET['session_status'] == "logout") {
		log_out("new_upload");
	}
}
?>
