<?php include ("connect.php");

/**
 * Created by "CharlotteZhang".
 * User: user
 * Date: 2016/2/18
 * Time: 9:41
 */
$login = getConnect();
// *** While or is nice solution, it doesn't take into account when the 'name' index is not set, which generates a php warning
// $userName = $_POST["name"] or "";
$userName = isset($_POST["user_name"]) ? $_POST["user_name"] : null;

// *** same change as above
// $userPass = $_POST["pass"] or "";
$password = isset($_POST["password"]) ? $_POST["password"] : null;
if(isset($_SESSION['userurl'])){
    $url = $_SESSION['userurl'];
}else{
    $url = "../index.php";
}
if(isset($_POST['login'])){
    $username = $login->real_escape_string($userName);
    $password = $login->real_escape_string($password);
    $password = md5($password);
    $query = "select user_id from users where user_name= ? AND user_pw = ?";
    if($stmt = $login->prepare($query)){
        $stmt->bind_param("ss", $username, $password);

        $stmt->execute();
        $stmt->bind_result($userId);
        /* store result */
        $stmt->store_result();
        if($stmt->num_rows == 1){
            while($stmt->fetch()){
                $_SESSION['user_id'] = $userId;
                $_SESSION['user_name'] = $username;
            }
            echo "<p>log in successed!</p>";
            echo "<meta http-equiv=\"refresh\" content=\"0.5;url=$url\">";
        }else{
            echo "<p>Username or password is not correct, try again!<a href='../index.php'>back</a></p>";
        }

        $stmt->close();

    }
};
$login->close();
?>
