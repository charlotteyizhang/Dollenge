<?php include "connect.php";
/**
 * Created by "CharlotteZhang".
 * User: user
 * Date: 2016/2/24
 * Time: 20:04
 */
//check if the user is logged in
$action = getConnect();

$action_name = isset($_POST["action_name"]) ? $_POST["action_name"] : null;
$action_goal = isset($_POST["action_goal"]) ? $_POST["action_goal"] : null;
$action_location = isset($_POST["action_location"]) ? $_POST["action_location"] : null;
$action_start_date = isset($_POST["action_start_date"]) ?  $_POST["action_start_date"] : null;
$action_expire_date = isset($_POST["action_expire_date"]) ?  $_POST["action_expire_date"] : null;
$action_description = isset($_POST["action_description"]) ? $_POST["action_description"] : null;
$funding_id = isset($_POST["funding_id"])? $_POST["funding_id"] : null;
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$pic_id = isset($_POST['pic_id'])? $_POST['pic_id'] : null;
$action_status = 0;
$action_total = 0;

$stmt = $action->stmt_init();
$insert = "INSERT INTO actions (action_id, action_name, action_goal, action_location, action_start_date, action_expire_date, action_description, action_status, funding_id, action_total,user_id, pic_id)
VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?)";
if($stmt = $action->prepare($insert)){
    //action_name, action_goal, action_location, action_expire_date, action_description, action_status, funding_id, action_total
    $stmt->bind_param("sdssssiidii",$action_name,$action_goal,$action_location,$action_start_date,$action_expire_date,$action_description, $action_status,$funding_id,$action_total,$user_id,$pic_id);
    /* execute query */
    $stmt->execute();
    echo "<script>window.open('../actionPage.php','_self')</script>";
    $stmt->close();
}else{
    echo "Error: " . $action . "<br>" . mysqli_error($conn);
}
$action->close();
?>