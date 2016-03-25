<?php include "connect.php";
/**
 * Created by "CharlotteZhang".
 * User: user
 * Date: 2016/3/15
 * Time: 18:10
 */
$user_id = isset($_SESSION['user_id'])? $_SESSION['user_id'] : null;
$mysqli = getConnect();
$url = isset($_GET['url'])?$_GET['url'] : null;
//changepassword
if(isset($_POST['changePw'])) {
    $currentPw = isset($_POST["currentPw"]) ? $_POST["currentPw"] : null;
    $newPw =  $mysqli->real_escape_string($_POST['newPw']);
    $confirmPw =  $mysqli->real_escape_string($_POST['confirmPw']);
    $currentPw = $mysqli->real_escape_string($currentPw);
    $currentPw = md5($currentPw);
    $query = "select user_id from users where user_id = ? AND user_pw = ?";
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("is", $user_id, $currentPw);
        $stmt->execute();
        $stmt->bind_result($userId);
        /* store result */
        $stmt->store_result();
        if ($stmt->num_rows == 0) {
            echo "<p>Wrong password</p>";
            //loading pic needs
            // echo "<img src='../img/action_pic_1.jpg'>";
            echo "<meta http-equiv=\"refresh\" content=\"0.5;url=$url\">";
        } else {
            if (!($newPw == $confirmPw)) {
                echo "<p style='color: #ff7970;'>Please re-enter your password</p>";
                echo "<meta http-equiv=\"refresh\" content=\"0.5;url=$url\">";
            } else {
                $stmt_update = $mysqli->stmt_init();
                $newPw = md5($newPw);
                $update = "UPDATE users SET user_pw=? WHERE user_id=?";
                if ($stmt_update->prepare($update)) {
                    $stmt_update->bind_param("si", $newPw,$user_id);
                    $stmt_update->execute();
                    echo "<p style='color: #ff7970;'>Succeed!</p>";
                    echo "<meta http-equiv=\"refresh\" content=\"0.5;url=$url\">";
                    $stmt_update->close();
                }
            }
        }
        $stmt->close();
    }else{
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}
//end of change password
//change details
if(isset($_POST['saveDetails'])){
    $update_1 = "UPDATE users ";
    $update_2 = " WHERE user_id= ".$user_id;
    if( isset($_POST['user_email'])){
        $update = $update_1."SET user_email =".$_POST['user_email'].$update_2;
        $mysqli->query($update);
    }
    if( isset($_POST['user_tel'])){
        $update = $update_1."SET user_tel = ".$_POST['user_tel'].$update_2;
        $mysqli->query($update);
    }
    if( isset($_POST['user_location'])){
        $update = $update_1."SET user_location= '".$_POST['user_location']."'".$update_2;
        $mysqli->query($update);
    }
    if( isset($_POST['user_description'])){
        $update = $update_1."SET user_description = '".$_POST['user_description']."'".$update_2;
        $mysqli->query($update);
    }
    echo "<p style='color: #ff7970;margin-top:250px;text-align:center;font-size:4em;'>Succeed!</p>";
    echo "<meta http-equiv=\"refresh\" content=\"2;url=$url\">";
}

    $mysqli->close();
?>