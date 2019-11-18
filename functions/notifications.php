<?php

function notify_comment($user) {
    include 'includes/connect.php';

    if (verif_user($user)) {
        $usr_email_sql = "SELECT * FROM users WHERE user_id=:usr_id";
        $get_usr_email = $con->prepare($usr_email_sql);
        $get_usr_email->execute([':usr_id'=>$user]);
        $usr_email = $get_usr_email->fetch();

		if ($usr_email['notify'] == 1) {
			$subject = "Camagru Comments";
			$body = "Someone commented on one of your posts.";
			$headers = "From: info@camagru.com\r\n";
			$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
			mail($usr_email['user_email'],$subject,$body,$headers);
		}
    }

}


?>
