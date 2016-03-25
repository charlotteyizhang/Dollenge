<!DOCTYPE html>
<?php include "header.php";
?>
<div class="bg-action">
    <div class="blank"></div> 
    <div id="wrapper">
        <div id="columns">
            <div class="actionpage-title text-center">
            <strong>Action</strong>
            <a class="btn btn-info btn-donate" href="fundingPage.php">Go to funding page to take an action</a> 
            </div>
        <?php
        require("php/manpics.php");
        /**
         * Created by "CharlotteZhang".
         * User: user
         * Date: 2016/2/25
         * Time: 15:12
         */
        //get the funding information from database
        $funding_id = isset($_GET['funding_id'])? $_GET['funding_id'] : null;
        $mysqli = getConnect();
        /* create a prepared statement */
        $stmt =  $mysqli->stmt_init();
        $query = "SELECT A.action_id, A.action_name, A.action_description, A.action_location,
            A.action_goal, A.action_total, A.action_start_date, A.action_expire_date, A.pic_id, A.action_status, A.user_id,A.funding_id,
            B.funding_name From actions AS A LEFT JOIN fundings AS B ON A.funding_id = B.funding_id  ";
        if($funding_id){
            $query = $query. "WHERE A.funding_id = ".$funding_id;
        }
        $query = $query. " ORDER BY action_id DESC";
        if ($stmt->prepare($query)){
            /* execute query */
            $stmt->execute();
            /* bind your result columns to variables, e.g. id column = $post_id */
            $stmt->bind_result($action_id, $action_name, $action_description, $action_location, $action_goal, $action_total, $action_start_date, $action_expire_date,$pic_id, $action_status, $user_id, $funding_id, $funding_name);
            /* store result */
            $stmt->store_result();
            if($stmt->num_rows) {
                /* fetch the result of the query & loop round the results */
                while ($stmt->fetch()) {
                    echo "
                <div class=\"pin\">
                 ";
                    if($pic_id){
                        $picStuff = Manpics::picInfoService($pic_id);
                        if(count($picStuff) == 0){
                            echo "<a href=\"php/upload.php?action_id =\"".$action_id."\" > Upload Image </a > ";
                        }else{
                            foreach($picStuff as $row){
                                printf("<a href=\"php/imagelocator.php?picID=%d\" target=\"_blank\"><img class=\"featurette-image img-responsive center-block action-pic\" src=\"php/imagelocator.php?thumb=yes&picID=%s\" /></a>\n", $row["id"], $row["id"]);
                            }
                        }
                    }else{
                        echo "
                    <img class=\"featurette-image img-responsive center-block action-pic\" src=\"img/action_pic_1.jpg\">
                ";
                    }
                    echo "<a class=\"subtitle\" href=\"action.php?action_id=" . $action_id . "\"><strong>" . $action_name . "</strong></a>\n";
                    //needs to be changed to left join funding name and funding id as the link table
                    echo "<p>for <a class=\"org-link\" href=\"funding.php?funding_id=".$funding_id."\">".$funding_name."</a></p>";
                    echo "<p>" . $action_description . "</p>";
                    $percentage = $action_total / $action_goal;
                    $action_goal = number_format($action_goal, 0);
                    $action_total = number_format($action_total, 0);
                    $number = number_format($percentage * 100, 1);
                    echo "<h4 class=\"margin-11\">£<span>" . $action_total . "/" . $action_goal . "</span></h4>";
                    echo "<div class=\"row\">
                    <div class=\"col-md-10\">
                    <div class=\"progress btn-donate\">
                    <div class=\"progress-bar mintgreen progress-bar-striped active\" role=\"progressbar\" aria-valuenow=\"" . $number . "\"
                         aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width:" . $number . "%\">
                    </div>
                    </div>
                    </div>
                    <div class=\"col-md-2 pa visible-md visible-lg\">
                        <p><span>" . $number . "%</span></p>
                    </div>
                    </div>";
                    $time_left = getTime(null, $action_start_date);
                    $time_total = getTime($action_start_date, $action_expire_date);
                    $time_start = -$time_left;
                    $percent = $time_start/$time_total;
                    $number2 = number_format($percent * 100, 0);
                    echo "<h4 class=\"margin-11\">".$time_start." /".$time_total." days</h4>";
                    echo "<div class=\"row\">
                    <div class=\"col-md-10\">
                    <div class=\"progress btn-donate\">
                    <div class=\"progress-bar lemonyellow progress-bar-striped active\" role=\"progressbar\" aria-valuenow=\"" . $number2 . "\"
                         aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width:" . $number2 . "%\">
                    </div>
                    </div>
                    </div>
                    <div class=\"col-md-2 visible-md visible-lg\">
                        <p><span>" . $number2 . "%</span></p>
                    </div>
                    </div>";
                    if($action_status == 0){
                    echo "
                <a class=\"btn btn-warning center-block btn-donate\" href=\"donationForm.php?action_id=".$action_id."\">Donate</a>
                </div>";
                    }else if($action_status ==1){
                        echo "<p>Action is completed!</p>
                    </div>";
                    }else{
                        echo "<p>Action is failed :(</p>
                       </div>";
                    }
                }
            }else {// there aren't any results
                echo "<p>There isn't any content</p>";
            }
            /* close statement */
            $stmt->close();
        }
        /* close connection */
        $mysqli->close();

        ?>
    </div>
    </div>
    </div>
<!-- End of Action Group -->
<?php include "footer.php"?>