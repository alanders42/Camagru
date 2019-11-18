<?php

function post_is_liked($img_id) {
	include 'includes/connect.php';

	if (isset($_SESSION['user_id'])) {
		$like_usr_id = $_SESSION['user_id'];

		$is_liked_sql = "SELECT * FROM likes WHERE like_usr_id=:usr_id AND like_img_id=:img_id ";
		$is_liked = $con->prepare($is_liked_sql);
		$is_liked->execute(array(':usr_id'=>$like_usr_id, ':img_id'=>$img_id));
		$usr_likes = $is_liked->fetchAll();
		$con = null;
		if (!empty($usr_likes))
			return true;
	}
	return false;
}

function post_like($img) {
	include 'includes/connect.php';

	if (isset($_SESSION['user_id'])) {

		$like_usr_id = $_SESSION['user_id'];

		if (!post_is_liked($img)) {
			$post_like_sql = "INSERT INTO likes(like_img_id, like_usr_id) VALUES(:img_id, :usr_id)";
			$post_like = $con->prepare($post_like_sql);
			$post_like->execute(array(':img_id'=>$img, ':usr_id'=>$like_usr_id));
		}
	} else {
		echo "<script>alert('Please Log In or Register to like or comment.')</script>";
	}
	$con = null;

}

function delete_like($img) {
	include 'includes/connect.php';

	if (isset($_SESSION['user_id'])) {

		$like_usr_id = $_SESSION['user_id'];
		$delete_sql = "DELETE FROM likes WHERE like_img_id=:img_id AND like_usr_id=:usr_id";
		$delete_like = $con->prepare($delete_sql);
		$delete_like->execute(array(':img_id'=>$img, ':usr_id'=>$like_usr_id));
	}
	$con = null;
}

function get_like_count($img_id) {
	include 'includes/connect.php';

	$get_likes_sql = "SELECT * FROM likes WHERE like_img_id=?";
	$get_likes = $con->prepare($get_likes_sql);
	$get_likes->execute([$img_id]);
	$likes = $get_likes->fetchAll();
	$con = null;
	return count($likes);
}
?>
