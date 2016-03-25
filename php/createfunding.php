<?php include "connect.php";
/**
 * Created by "CharlotteZhang".
 * User: user
 * Date: 2016/2/25
 * Time: 11:14
 */
$funding = getConnect();

$funding_name = isset($_POST["funding_name"]) ? $_POST["funding_name"] : null;
$funding_goal = isset($_POST["funding_goal"]) ? $_POST["funding_goal"] : null;
$funding_start_date = isset($_POST["funding_start_date"]) ? $_POST["funding_start_date"] : null;
$funding_expire_date = isset($_POST["funding_expire_date"]) ?  $_POST["funding_expire_date"] : null;
$funding_location = isset($_POST["funding_location"])? $_POST["funding_location"] : null;
$funding_description = isset($_POST["funding_description"]) ? $_POST["funding_description"] : null;
$category_id = isset($_POST["category_id"]) ? $_POST["category_id"] : null;
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$pic_id = isset($_POST['pic_id']) ? $_POST['pic_id'] : null;
$funding_total = 0;
$funding_status = 0;

$organization_id = isset($_POST['organization_id'])? $_POST['organization_id'] : null;
$stmt = $funding->stmt_init();
$insert = "INSERT INTO fundings (funding_id, funding_name, funding_goal, funding_start_date, funding_expire_date, funding_location, funding_description,funding_total,user_id, category_id,
organization_id,pic_id)VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)";
if($stmt = $funding->prepare($insert)){
    $stmt->bind_param("sdssssdiiii",$funding_name,$funding_goal,$funding_start_date, $funding_expire_date,$funding_location,$funding_description,$funding_total,$user_id, $category_id, $organization_id,$pic_id);
    /* execute query */
    $stmt->execute();

    echo "<p>".$stmt->affected_rows."</p>";
    echo "<script>window.open('../fundingPage.php','_self')</script>";
    $stmt->close();
}else{
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}
$funding->close();
/*
 * $funding = getConnect();

$funding_name = isset($_POST["funding_name"]) ? $_POST["funding_name"] : null;
$funding_goal = isset($_POST["funding_goal"]) ? $_POST["funding_goal"] : null;
$funding_start_date = isset($_POST["funding_start_date"]) ? $_POST["funding_start_date"] : null;
$funding_expire_date = isset($_POST["funding_expire_date"]) ?  $_POST["funding_expire_date"] : null;
$funding_location = isset($_POST["funding_location"])? $_POST["funding_location"] : null;
$funding_description = isset($_POST["funding_description"]) ? $_POST["funding_description"] : null;
$category_id = isset($_POST["category_id"]) ? $_POST["category_id"] : null;
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$pic_id = isset($_POST['pic_id']) ? $_POST['pic_id'] : null;
$funding_total = 0;
$organization_id = isset($_POST['organization_id'])? $_POST['organization_id'] : null;
$funding_status = 0;

$stmt = $funding->stmt_init();
$insert = "INSERT INTO fundings (funding_id, funding_name, funding_goal, funding_start_date, funding_expire_date, funding_location, funding_description, pic_id, funding_total,
user_id, category_id, organization_id,pic_id, funding_status)VALUES (NULL, ?, ?, ?, ?,?, ?, ?, ?, ?, ?, ?, ?,?)";
if($stmt = $funding->prepare($insert)){
    //action_name, action_goal, action_location, action_expire_date, action_description, action_status, funding_id, action_total
    $stmt->bind_param("sdssssidiiiii",$funding_name,$funding_goal,$funding_start_date,
        $funding_expire_date,$funding_location,$funding_description,$pic_id, $funding_total,$user_id, $category_id, $organization_id,$pic_id,$funding_status);

$stmt->execute();
echo "<script>window.open('../fundingPage.php','_self')</script>";
$stmt->close();
}else{
    echo "Error: " . $funding . "<br>" . mysqli_error($conn);
}

$funding->close();
?>

* */
?>

