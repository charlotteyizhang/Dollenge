<?php include "connect.php";
/**
 * Created by "CharlotteZhang".
 * User: user
 * Date: 2016/3/16
 * Time: 22:08
 */
$action_completed = 1;

if(isset($_POST['submit'])){
    $action_video = $_POST['action_video'];
    $action_id = $_POST['action_id'];
    if($action_video){
        $mysqli = getConnect();
        $stmt = $mysqli->stmt_init();
        echo $action_id;
        $update = "UPDATE actions SET action_video = ?, action_status = ? WHERE action_id = ?";
        if($stmt->prepare($update)){
            $stmt->bind_param("sii", $action_video,$action_completed, $action_id);
            $stmt->execute();
            echo "Mission completed! We will donate all the money to the organization";
            echo "<meta http-equiv=\"refresh\" content=\"0.5;url=../profile.php\">";
            $stmt->close();
        }else{
            echo "Error: " . $mysqli . "<br>" . mysqli_error($conn);
        }
        //update funding total
        $stmt = $donation->stmt_init();
        $query = "SELECT funding_id FROM actions WHERE action_id =?";
        if($stmt->prepare($query)){
            $stmt->bind_param("i",$action_id);
            $stmt->execute();
            $stmt->bind_result($funding_id);
            while($stmt->fetch()){
                $update = "UPDATE fundings SET funding_total = funding_total + ? WHERE  funding_id = ?";
                if($stmt2 = $donation->prepare($update2)){
                    $stmt2->bind_param("di", $donate_total, $funding_id);
                    $stmt2->execute();
                    echo "<p>Thank you for donating £".$donate_total." for ".$action_name."</p>";
                    echo "<script>window.open('../action.php?action_id=$action_id','_self')</script>";
                    $stmt2->close();
                }else{
                    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
                }
            }
            $stmt->close();
        }
        $mysqli->close();
    }else{
        echo "<p>You must update a video</p>";
    }

}
?>