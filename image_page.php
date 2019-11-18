<!DOCTYPE html>
<?php
include("config/setup.php");
include "functions/functions.php";
include "functions/image_functions.php";
include "functions/comment_functions.php";
include "functions/like_functions.php";

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
		<style>
			.navbar-brand {
				position: fixed;
				top: 0;
				left: 0;
				width: 100%;
				height: 220px;
				z-index: 10;
				background: #eeeeee;
				-webkit-box-shadow: 0 7px 8px rgba(0, 0, 0, 0.12);
				-moz-box-shadow: 0 7px 8px rgba(0, 0, 0, 0.12);
				box-shadow: 0 7px 8px rgba(0, 0, 0, 0.12);
			}
		</style>
		<title>Camagru</title>
		
	</head>
	<body>
		<header>
			<!-- <div class="navbar"> -->
				<div class="navbar-brand">
					<?php
						get_menu();
					?>
				</div>
			<!-- </div> -->
		</header>
		<main>
			<section class="section" style="margin-top:220px">
				<div class="container is-fluid">
					<!-- <div class="gallery"> -->
						<?php
							if (isset($_POST['like'])) {
								post_like($_GET['img']);
							}
							if (isset($_POST['apathy'])) {
								delete_like($_GET['img']);
							}
							if (isset($_POST['comment'])) {
								post_comment($_GET['img']);
							}
							if (isset($_POST['delete_post'])) {
								delete_post($_GET['img']);
							}
							get_image($_GET['img']);
						?>
						<div class="control">
							<form method="POST">
								<?php
								echo "<div class='level'>";
									if (!post_is_liked($_GET['img'])) {
										echo "<div class='level-left'><input class='button is-light' type='submit' name='like' value='Like'></div><br/>";
									} else {
										echo "<div class='level-left'><input class='button is-success' type='submit' name='apathy' value='Liked'></div><br/>";
									}
									if (is_my_post($_GET['img'])) {
										echo "<div class='level-right'><input class='button is-danger' type='submit' name='delete_post' value='Delete'></div>";
									}
								echo "</div>";
								?>
								<input class="textarea" type="text" name="cmntContent" placeholder="Comment...">
								<div class="field is-grouped is-grouped-right">
									<input class="button is-success" type="submit" name="comment" value="Comment">
								</div>
							</form>
						</div>
						<?php
							get_comments($_GET['img']);
						?>
					<!-- </div> -->
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
