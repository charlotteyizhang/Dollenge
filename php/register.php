<?php include "connect.php";
/**
 * Created by "CharlotteZhang".
 * User: user
 * Date: 2016/1/29
 * Time: 12:46
 */
$register = getConnect();
$stmt = $register->stmt_init();
//get inform
$username = $register->real_escape_string($_POST['user_name']);
$password =  $register->real_escape_string($_POST['password']);
$rePw =  $register->real_escape_string($_POST['rePw']);
$email =  $register->real_escape_string($_POST['email']);
$user_description = "I want to be a hero to share my love";
$user_donation = 0;
$user_raise = 0;
if(!($password == $rePw)){
    echo "<p style='color: #ff7970;'>Please re-enter your password</p>";
    return;
}
$password = md5($password);
if(isset($_SESSION['userurl'])){
    $url = $_SESSION['userurl'];
}else{
    $url = "../profile.php";
}
////check if the user is already exist
//$query = "SELECT * FROM users WHERE user_name = ?";
//if($stmt = $register->prepare($query)){
//    $stmt->bind_param("s",$username);
//    /* execute query */
//    $stmt->execute();
//
//    /* store result */
//    $stmt->store_result();
//
//    if($stmt->num_rows>0){
//        echo "<p>Sorry this username is taken</p><a href='../index.php'>back</a>";
//        exit();
//    }
//    $stmt->close();
//
//}

$stmt = $register->stmt_init();
$insert = "INSERT INTO users (user_id, user_name, user_pw, user_email, user_description, user_donation, user_raise) VALUES (NULL, ?, ?, ?, ?, ?, ?)";
if($stmt = $register->prepare($insert)){ 
    $stmt->bind_param("ssssdd",$username,$password,$email,$user_description,$user_donation,$user_raise);
    /* execute query */
    $stmt->execute();

    $_SESSION['user_id'] = $register->insert_id;
    $_SESSION['user_name'] = $username;
    echo "<p>You are registered!</p>";
    echo "<meta http-equiv=\"refresh\" content=\"0.5;url=$url\">";
    $stmt->close();
}else{
        echo "Error: " . $register . "<br>" . mysqli_error($conn);
}

$register->close();
?>