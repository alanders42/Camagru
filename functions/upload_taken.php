<?php

include '../includes/connect.php';
if (session_id() === "") {
    session_start();
}

if (isset($_POST['submit_taken'])) {
    if (isset($_POST['taken'])) {
        try {
            $image = $_POST['taken'];
            $pre = "data:image/png;base64,";

            if (substr($image, 0, strlen($pre)) == $pre) {
                $image = substr($image, strlen($pre));
            }
            if ($image === "upload_taken.php") {
                echo "<script>console.log('Error')</script>";
            } else {
                $u_id = $_SESSION['user_id'];
                $upl_sql = "INSERT INTO images(u_id, img_name) VALUES(:usr_id, :img)";
                $upld = $con->prepare($upl_sql);
                $upld->execute(array(':usr_id'=>$u_id, ':img'=>$image));
                echo "<script>alert('Upload Successful')</script>";
                // echo "<script>window.open('../client/my_account.php','_self')</script>";
            }
        } catch (PDOException $e) {
            echo $upl_sql."<br/>".$e;
        }
    }
}

?>