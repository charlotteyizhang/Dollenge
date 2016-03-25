<?php include "connect.php";
/**
 * Created by "CharlotteZhang".
 * User: user
 * Date: 2016/3/17
 * Time: 22:45
 */
$register = getConnect();
$username= $_POST['user_name'];
//check if the user is already exist
$query = "SELECT * FROM users WHERE user_name = ?";
if($stmt = $register->prepare($query)){
    $stmt->bind_param("s",$username);
    /* execute query */
    $stmt->execute();

    /* store result */
    $stmt->store_result();

    if($stmt->num_rows>0){
        echo "Sorry this name is taken";
    }else{
        echo "This name is great";
    }
    $stmt->close();

}
?>