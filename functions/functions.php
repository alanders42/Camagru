<?php

function validate_name($name) {
	if (!preg_match("/^[a-zA-Z ]*$/", $name)) {
		echo "<script>window.alert('Invalid name, only letters and white space allowed.')</script>";
		exit();
	}
}

function validate_email($email) {
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		echo "<script>window.alert('Invalid email.')</script>";
		exit();
	}
}

function validate_password($passwd) {
	if (!preg_match('/^(?=.*\d)(?=.*[a-zA-Z])[0-9a-zA-Z!@#$%]{6,50}$/', $passwd)) {
		echo "<script>window.alert('Passwords need to be more than 5 characters long, and contain at least 1 number and 1 letter.')</script>";
		exit();
	}
}

function validate_comment($comment) {
	if (preg_match("/^.*<script.*$/", $comment)) {
		return false;
	}
	return true;
}

//sends verification email
function verif_email($u_email, $ver_code) {
	$subject = "Activate your account with Camagru.";
	$body = "Please click <a href='http://localhost:8080/Camagru/client/verify_email.php?ver_key=".$ver_code."'>here</a> to activate your account.";
	$headers = "From: info@camagru.com\r\n";
	$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
	if (mail($u_email,$subject,$body,$headers))
		return true;
	else
		return false;
}

// Checks if user id corresponds to registered user
function verif_user($user_id) {
	try {
		$con = new PDO("mysql:host=localhost;dbname=db_camagru", "root", "root");
		$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	catch(PDOException $e) {
		echo "ERROR: ".$e->getMessage();
	}
	$u_verif = $con->prepare("SELECT * FROM users WHERE user_id=? AND verified=1");
	$u_verif->execute([$user_id]);
	$row = $u_verif->fetch();
	if ($row['user_id'] == $user_id)
		return true;
	else
		return false;
}

function get_menu() {
	if (isset($_SESSION['user_id']))
	{
		if (verif_user($_SESSION['user_id'])) {
			echo "<div class='navbar-start'>
						<div class='navbar-item'>
							<a class='button is-primary' href='index.php'>Home</a>
						</div>

						<div class='navbar-item'>
							<a class='button is-light' href='client/new_upload.php'>New Upload</a>
						</div>

						<div class='navbar-item'>
							<a class='button is-primary' href='client/my_account.php?user=".hash('whirlpool',$_SESSION['user_name'])."'>My Account</a>
						</div>

						<div class='navbar-item'>
							<a class='button is-light' href='index.php?session_status=logout'>Log Out</a>
						</div>
				</div>";
		}
	}
	else {
			echo "<div class='navbar-start'>
					<div class='navbar-item'>
						<a class='button is-primary' href='index.php'>Home</a>
					</div>
					<div class='navbar-item'>
						<a class='button is-light' href='login.php'>Log In</a>
					</div>
					<div class='navbar-item'>
						<a class='button is-primary' href='register.php'>Register</a>
					</div>
				</div>";
	}
}

function log_in() {
	include 'includes/connect.php';
		//validate email + password
		$u_email = $_POST['user_email'];
		$u_passwd = hash('whirlpool',$_POST['user_passwd']);
		$get_udata = $con->prepare("SELECT * FROM users WHERE user_email=?");
		$get_udata->execute([$u_email]);
		$user_data = $get_udata->fetch();
		if (empty($user_data) || $u_email != $user_data['user_email'] || $u_passwd != $user_data['user_passwd']) {
			echo "<script>window.alert('Invalid password or e-mail.')</script>";
		}
//if email from form == email from db && passwd from form == passwd from db and user has been verified
		if ($u_email == $user_data['user_email'] && $u_passwd == $user_data['user_passwd'] && $user_data['verified'] == 1) {
//store session data
			$_SESSION['user_email'] = $u_email;
			$_SESSION['user_id'] = $user_data['user_id'];
			$_SESSION['user_name'] = $user_data['user_name'];
			$_SESSION['notif'] = $user_data['notify'];
//take client to their account page
			echo "<script>window.open('client/my_account.php?', '_self')</script>";
		} //if all of the above, but user has not gone through verification link emailed to them
		else if ($u_email == $user_data['user_email'] && $u_passwd == $user_data['user_passwd'] && $user_data['verified'] == 0) {

			echo "<script>window.alert('Please verify your account.')</script>";
			$_SESSION['user_email'] = $u_email;
			$_SESSION['user_id'] = $user_data['user_id'];
			$_SESSION['user_name'] = $user_data['user_name'];
			$u_ver = $user_data['token'];
			verif_email($u_email, $u_ver);
		}
		$con = null;
}

function log_out($page){

		session_destroy();
		if ($page == "my_account" || $page == "verify_email" || $page == "new_upload") {
			echo "<script>window.open('../index.php', '_self')</script>";
		}
		else if ($page == "index") {
			echo "<script>window.open('./index.php', '_self')</script>";
		}
}

function reset_passwd() {
	include '../includes/connect.php';

	$u_email = $_POST['user_email'];

	$get_udata = $con->prepare("SELECT * FROM users WHERE user_email=?");
	$get_udata->execute([$u_email]);
	$user_data = $get_udata->fetch();

	// if (verif_user($user_data['user_id'])) {

		$ver_code = hash('whirlpool', time().$u_email);
		$updt_verif = $con->prepare('UPDATE users SET token=:ver_code WHERE user_email=:u_email');
		$updt_verif->execute(array(':ver_code'=>$ver_code, ':u_email'=>$user_data['user_email']));

		$subject = "Reset your password.";
		$body = "Forgot your password? Please click <a href='http://localhost:8080/Camagru/passwd_reset.php?ver_key=".$ver_code."'>here</a> to reset your password.\r\nIf this wasn't you, call the internet police.";
		$headers = "From: info@camagru.com\r\n";
		$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
		if (mail($u_email,$subject,$body,$headers))
			return true;
	// }
	return false;
}

function replace_passwd($new_passwd, $verif_key) {
	include 'includes/connect.php';

	$u_email = $_POST['user_email'];

	$get_udata = $con->prepare("SELECT * FROM users WHERE user_email=?");
	$get_udata->execute([$u_email]);
	$user_data = $get_udata->fetch();
	if ($verif_key != $user_data['token']) {
		echo "window.alert('Error!')";
		exit();
	}
	$updt_pw = $con->prepare("UPDATE users SET user_passwd=:new_passwd WHERE user_email=:u_email");
	if ($updt_pw->execute(array(':new_passwd'=>$new_passwd, ':u_email'=>$u_email)))
		return true;
	else
		return false;
}

//gets and displays images uploaded by all users
function get_gallery() {
	include 'includes/connect.php';
	include_once 'functions/comment_functions.php';
	include_once 'functions/like_functions.php';

	$get_imgs = "SELECT * FROM images ORDER BY date_created DESC";
	$exe_imgs = $con->prepare($get_imgs);
	$exe_imgs->execute();

	while ($image = $exe_imgs->fetch()) {
		$img_name = $image['img_name'];
		$img_id = $image['img_id'];
		$cmnts_amnt = get_comment_count($img_id);
		$likes_amnt = get_like_count($img_id);
		echo "	<div class='tile is-ancestor'>
					<div class='tile is-12 is-vertical'>
						<div class='tile is-parent'>
						<article class='tile is-child box'>
							<figure class='image'>
									<a href='image_page.php?img=$img_id'>
										<img src='data:image/png;base64,".$img_name."' />
									</a>
							</figure>
							<p class='subtitle'>Likes: $likes_amnt Comments: $cmnts_amnt</p>
						</article>
					  </div>
					</div>
				</div>";
	}
}

function upload_image($user) {
	include '../includes/connect.php';

	if (!empty($user)) {

		$upl_img_name = $_FILES['upl_image']['name'];
		if (!$upl_img_name)
			exit();
		$upl_img_tmp = base64_encode(file_get_contents($_FILES['upl_image']['tmp_name']));

		$upload_sql = "INSERT INTO images(u_id, img_name) VALUES(:u_id, :img)";
		$upload_image = $con->prepare($upload_sql);
		$upload_image->execute(array(':u_id'=>$user, ':img'=>$upl_img_tmp));
	}
}

function get_upload_thumbs($user) {
	include '../includes/connect.php';

	if (!empty($user)) {
		$get_imgs_sql = "SELECT * FROM images WHERE u_id=:usr_id ORDER BY date_created DESC LIMIT 5";
		$get_user_imgs = $con->prepare($get_imgs_sql);
		$get_user_imgs->execute(array(':usr_id'=>$user));

		while ($img = $get_user_imgs->fetch()) {
			$img_name = $img['img_name'];
			$img_id = $img['img_id'];
			echo "	<figure class='image'>
						<a href='../image_page.php?img=$img_id'>
							<img src='data:image/png;base64,".$img_name."' />
						</a>
					</figure>";
		}
	}
}

function is_my_post($img) {
	include 'includes/connect.php';

	if (isset($_SESSION['user_name'])) {
		$get_img_sql = "SELECT u_id FROM images WHERE img_id=:image_id";
		$img_usr_id = $con->prepare($get_img_sql);
		$img_usr_id->execute([':image_id'=>$img]);
		$img_usr = $img_usr_id->fetch();
		if ($img_usr['u_id'] == $_SESSION['user_id']) {
			return true;
		}
		return false;
	}
}

function update_user($user_id) {

	if (isset($_POST['updt_name'])) {
		validate_name($_POST['new_name']);
		update_name($user_id, $_POST['new_name']);
	}
	if (isset($_POST['updt_email'])) {
		validate_email($_POST['new_email']);
		update_email($user_id, $_POST['new_email']);
		log_out("my_account");
		echo "<script>window.open('../index.php', '_self')</script>";
	}
	if (isset($_POST['updt_passwd'])) {
		validate_password($_POST['new_passwd']);
		update_passwd($user_id, hash('whirlpool',$_POST['new_passwd']));
	}
	if (isset($_POST['updt_image'])) {
		//validate_image($_POST['new_image']);
		$new_img_tmp = base64_encode(file_get_contents($_FILES['new_image']['tmp_name']));
		update_image($user_id, $new_img_tmp);
	}
	if (isset($_POST['updt_notif'])) {

		$user = $_SESSION['user_id'];
		update_notify($user);
	}
	echo "<script>window.open('my_account.php', '_self')</script>";
}

function update_notify($user_id) {
	include '../includes/connect.php';
	if (isset($_POST['notif'])) {
		$updt_sql = "UPDATE users SET notify=1 WHERE user_id=:u_id";
		$updt_notif = $con->prepare($updt_sql);
		$updt_notif->execute([':u_id'=>$user_id]);
		$_SESSION['notif'] = 1;
	} else {
		$updt_sql = "UPDATE users SET notify=0 WHERE user_id=:u_id";
		$updt_notif = $con->prepare($updt_sql);
		$updt_notif->execute([':u_id'=>$user_id]);
		$_SESSION['notif'] = 0;
	}
}

function update_name($user_id, $new_name) {
	include '../includes/connect.php';

	if (isset($_POST['updt_name'])) {
		$updt_sql = "UPDATE users SET user_name=:u_name WHERE user_id=:u_id";
		$updt_name = $con->prepare($updt_sql);
		$updt_name->execute(array(':u_name'=>$new_name, ':u_id'=>$user_id));
		$_SESSION['user_name'] = $new_name;
	}
}
function update_email($user_id, $new_email) {
	include '../includes/connect.php';

	if (isset($_POST['updt_email'])) {
		$_SESSION['user_email'] = $new_email;
		$new_verif = hash('whirlpool', time().$new_email);

		$updt_sql = "UPDATE users SET user_email=:u_email, verified=0, token=:new_verif WHERE user_id=:u_id";
		$updt_email = $con->prepare($updt_sql);
		$updt_email->execute(array(':u_email'=>$new_email, ':new_verif'=>$new_verif,':u_id'=>$user_id));

		verif_email($new_email, $new_verif);
	}
}
function update_passwd($user_id, $new_passwd) {
	include '../includes/connect.php';

	if (isset($_POST['updt_passwd'])) {
		$updt_sql = "UPDATE users SET user_passwd=:u_passwd WHERE user_id=:u_id";
		$updt_passwd = $con->prepare($updt_sql);
		$updt_passwd->execute(array(':u_passwd'=>$new_passwd, ':u_id'=>$user_id));
	}
}
function update_image($user_id, $new_image) {
	include '../includes/connect.php';

	if (isset($_POST['updt_image'])) {
		$updt_sql = "UPDATE users SET user_image=:u_image WHERE user_id=:u_id";
		$updt_image = $con->prepare($updt_sql);
		$updt_image->execute(array(':u_image'=>$new_image, ':u_id'=>$user_id));
	}
}

function delete_post($img_id) {
	include 'includes/connect.php';

	$del_like_sql = "DELETE FROM likes WHERE like_img_id=:img";
	$del_like = $con->prepare($del_like_sql);
	$del_like->execute(array(':img'=>$img_id));

	$del_cmnt_sql = "DELETE FROM comments WHERE cmnt_img_id=:img";
	$del_cmnt = $con->prepare($del_cmnt_sql);
	$del_cmnt->execute(array(':img'=>$img_id));

	$del_img_sql = "DELETE FROM images WHERE img_id=:img AND u_id=:usr";
	$del_img = $con->prepare($del_img_sql);
	$del_img->execute(array(':img'=>$img_id, ':usr'=>$_SESSION['user_id']));

	$con = null;
	echo "<script>alert('Post deleted.')</script>";
	echo "<script>window.open('index.php', '_self')</script>";
}

function get_post_user($img_id) {
	include 'includes/connect.php';

	$get_post_usr_sql = "SELECT u_id FROM images WHERE img_id=:img";
	$get_post_usr = $con->prepare($get_post_usr_sql);
	$get_post_usr->execute([':img'=>$img_id]);
	$post_usr = $get_post_usr->fetch();

	return $post_usr['u_id'];
}
?>
