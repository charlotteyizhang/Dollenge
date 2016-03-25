<?php include "connect.php";
/**
 * Created by "CharlotteZhang".
 * User: user
 * Date: 2016/2/25
 * Time: 23:08
 */
$donation = getConnect();

$donate_total = isset($_POST["donate_total"]) ? $_POST["donate_total"] : null;
$user_id = isset($_SESSION["user_id"])? $_SESSION["user_id"] : 0;
$action_id = isset($_POST["action_id"])? $_POST["action_id"] : null;
$funding_id = isset($_POST["funding_id"])? $_POST["funding_id"] : null;
$action_name = isset($_POST["action_name"])? $_POST["action_name"] : null;
$stmt = $donation->stmt_init();
echo $action_id;
echo $funding_id;
$insert = "INSERT INTO donation (donation_id, donation_total, user_id, action_id)
VALUES (NULL, ?, ?, ?)";

if($stmt = $donation->prepare($insert)){
    $stmt->bind_param("dii",$donate_total, $user_id, $action_id);
    /* execute query */
    $stmt->execute();
    $stmt->close();
}else{
    echo "Error: " . $donation . "<br>" . mysqli_error($conn);
}
if($user_id){
    $stmt = $donation->stmt_init();
    $update = "UPDATE users SET user_donation = user_donation + ? WHERE user_id = ?";
    if($stmt = $donation->prepare($update)){
        $stmt->bind_param("di", $donate_total, $user_id);
        $stmt->execute();
        echo "<p>Thank you for donating ".$user_id."</p>";
        $stmt->close();
    }else{
        echo "Error: " . $donation . "<br>" . mysqli_error($conn);
    }
}
$stmt = $donation->stmt_init();
// update action total
$update1 = "UPDATE actions SET action_total = action_total + ? WHERE action_id = ?";
if($stmt = $donation->prepare($update1)) {
    $stmt->bind_param("di", $donate_total, $action_id);
    /* execute query */
    $stmt->execute();
    $stmt->close();
}else{
    echo "Error: " . $donation . "<br>" . mysqli_error($conn);
}
$stmt = $donation->stmt_init();
$update1 = "UPDATE fundings SET funding_total = funding_total + ? WHERE funding_id = ?";
if($stmt = $donation->prepare($update1)){
    $stmt->bind_param("di", $donate_total, $funding_id);
    /* execute query */
    $stmt->execute();
    $stmt->close();
}else{
    echo "Error: " . $donation . "<br>" . mysqli_error($conn);
}
$stmt = $donation->stmt_init();
$query = "SELECT user_id FROM actions WHERE action_id = ?";
if($stmt ->prepare($query)){
    $stmt->bind_param("i", $action_id);
    $stmt->execute();
    $stmt->bind_result($action_user);
    echo "I'm giving money to".$action_user."'s action'";
    while($stmt->fetch()){
        $update2 = "UPDATE users SET user_raise = user_raise + ? WHERE user_id = ?";
        if($action_user){
            if($stmt->prepare($update2)){
                $stmt->bind_param("di", $donate_total, $action_user);
                $stmt->execute();
                echo "<p>Thank you for donating £".$donate_total." for ".$action_user."</p>";
                $url = "../action.php?action_id=".$action_id;
                echo "<meta http-equiv=\"refresh\" content=\"0.5;url=$url\">";
            }
        }else{
            echo "Error: " . $donation . "<br>" . mysqli_error($conn);
        }
    }
}else{
    echo "Error: " . $donation . "<br>" . mysqli_error($conn);
}



$donation->close();
?>