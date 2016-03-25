<?php
include "header.php";
/**
 * Created by "CharlotteZhang".
 * User: user
 * Date: 2016/3/16
 * Time: 15:56
 */
$action_id = isset($_GET['action_id']) ? $_GET['action_id'] : null;
$completed = isset($_GET['completed']) ? $_GET['completed'] : null;
if($completed == 'yes'){
    echo "
<div class=\"container-fluid bg-action\">
<div class=\"max-width center\">
    <form  class=\"form-horizontal max-width\" role=\"form\" method=\"post\" action=\"php/completeaction.php\">
     <div class=\"form-group\">
        <label for=\"action_video\">Upload your video's link
        (Please click share of the video and copy the embed link on YouTube):</label>
        <input text=\"url\" class=\"form-control form-control1\" id=\"action_video\" name=\"action_video\" required=\"required\">
        <input text=\"number\"  class=\"hidden\"  id=\"action_id\" name=\"action_id\" value=\"".$action_id."\">
      </div>
        <button type=\"submit\" class=\"btn btn-primary btn-block\" name=\"submit\">Submit</button>
    </form>
    </div>
</div>
    ";
}else{
    $mysqli = getConnect();
    //give back money
    $stmt = $mysqli->stmt_init();
    /***get information from actions table**/
    $query_actions = "SELECT action_total, funding_id, user_id FROM actions WHERE action_id =".$action_id;
    if($stmt->prepare($query_actions)){
        $stmt->execute();
        $stmt->bind_result($action_total, $funding_id, $raise_user);
        $stmt->store_result();
        while($stmt->fetch()){
            /** update fundings table set funding total - action_total  */
            $stmt2 = $mysqli->stmt_init();
            $update_fundings= "UPDATE fundings SET funding_total = funding_total - ? WHERE funding_id = ?";
            if($stmt2->prepare($update_fundings)){
                $stmt2->bind_param("di", $action_total, $funding_id);
                $stmt2->execute();
                echo "<p>Funding".$funding_id." money reduced".$action_total."</p>";
            }else{
                echo "Error: " . $mysqli . "<br>" . mysqli_error($conn);
            }
            /** update user raise set user raise - action_total **/
            $update_user_raise = "UPDATE users SET user_raise = user_raise - ? WHERE user_id = ?";
            if($stmt2->prepare($update_user_raise)){
                $stmt2->bind_param("di", $action_total, $raise_user);
                $stmt2->execute();
                echo "<p>Money raised by ".$raise_user." is returned</p>";
                $stmt2->close();
            }
        }
        $stmt->close();
    }

    //set the action status to failed and give back the money to people
    $action_failed = 2;
    $update_actions = "UPDATE actions SET action_total = '0',action_status = ? WHERE action_id=?";
    $stmt = $mysqli->stmt_init();
    if($stmt->prepare($update_actions)){
        $stmt->bind_param("ii", $action_failed, $action_id);
        $stmt->execute();
        echo "<p>You gave up the mission, the money will be returned".$action_total."</p>";
        $stmt->close();
    }else{
        echo "Error: " . $mysqli . "<br>" . mysqli_error($conn);
    }
    $stmt = $mysqli->stmt_init();
    /** set the user_donation of those who login and donated - donated **/
    $query_donation = "SELECT donation_total, user_id FROM donation WHERE action_id = ?";
    if($stmt->prepare($query_donation)){
        $stmt->bind_param("i", $action_id);
        $stmt->execute();
        $stmt->bind_result($donation_total, $donate_user);
        $stmt->store_result();
        while($stmt->fetch()){
            if($donate_user >0){
                $update_user_donation = "UPDATE users SET user_donation = user_donation - ? WHERE user_id = ?";
                $stmt2 = $mysqli->stmt_init();
                if($stmt2->prepare($update_user_donation)){
                    $stmt2->bind_param("di", $donation_total, $donate_user);
                    $stmt2->execute();
                    echo "<p>".$donation_total."has been returned to".$donate_user."</p>";
                    $stmt2->close();
                }
            }
        }
        echo "<meta http-equiv=\"refresh\" content=\"0.5;url=profile.php\">";
        $stmt->close();
    }else{
        echo "Error: " . $mysqli . "<br>" . mysqli_error($conn);
    }
    $mysqli->close();
}

?>