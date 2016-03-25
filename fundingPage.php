<!DOCTYPE html>
<?php include "header.php";
?>
<div class="bg-pink">
    <div class="blank"></div> 
    <div id="wrapper">
        <div id="columns">
            <div class="actionpage-title text-center">
            <strong>Fundings</strong>
            <div style="clear:both;"></div>
            <?php
                if(@$_SESSION['user_id'] == null){
                    echo "<p>Please first login then apply for a funding</p>";
                }else{
                    echo "<a class=\"btn btn-info btn-donate\" href=\"fundingForm.php?user_id=".$_SESSION['user_id']."\">Apply for a funding</a>";
                }
            ?>

            </div>
    <?php
    require "php/manpics.php";
    $funding_id = isset($_GET['funding_id'])? $_GET['funding_id'] : null;
    $mysqli = getConnect();
    $stmt = $mysqli->stmt_init();

    $query = "SELECT A.funding_id, A.funding_name, A.funding_description, A.funding_goal,
A.funding_total, A.funding_start_date, A.funding_expire_date,A.funding_status, A.user_id, A.category_id, A.organization_id,A.pic_id,
B.user_name, C.organization_name From fundings AS A
LEFT JOIN users AS B ON A.user_id = B.user_id
LEFT JOIN organizations AS C ON A.organization_id = C.organization_id ORDER BY A.funding_id DESC";

    if ($stmt->prepare($query)){
        /* execute query */
        $stmt->execute();
        /* bind your result columns to variables, e.g. id column = $post_id */
        $stmt->bind_result($funding_id, $funding_name, $funding_description, $funding_goal, $funding_total, $funding_start_date, $funding_expire_date,$funding_status, $user_id, $category_id, $organization_id,$pic_id, $user_name, $organization_name);
        /* store result */
        $stmt->store_result();
        if($stmt->num_rows) {
            /* fetch the result of the query & loop round the results */
            while ($stmt->fetch()) {
                if($pic_id){
                    $picStuff = Manpics::picInfoService($pic_id);
                    if(count($picStuff) == 0){
                        echo "<a class=\"btn btn-lg btn-info\" href=\"php/upload.php?action_id =\"".$action_id."\" > Upload Image </a > ";
                    }else{
                        foreach($picStuff as $row){
                            echo "
                         <div class=\"pin\">
                        ";
                            printf("<a href=\"php/imagelocator.php?picID=%d\" target=\"_blank\"><img class=\"featurette-image img-responsive center-block action-pic\" src=\"php/imagelocator.php?thumb=yes&picID=%s\" /></a>\n", $row["id"], $row["id"]);
                        }
                    }
                }else{
                    echo "
                <div class=\"pin\">
                    <img class=\"featurette-image img-responsive center-block action-pic\" src=\"img/funding_pic_1.jpg\">
                 ";
                }

                echo "<a class=\"subtitle\" href=\"funding.php?funding_id=" . $funding_id . "\"><strong>" . $funding_name . "</strong></a>\n";
                //needs to be changed to left join organization table
                echo "<p class=\"org-link\">for " . $organization_name . "</p>";
                echo "<p>" . $funding_description . "</p>";
                $percentage = $funding_total / $funding_goal;
                $funding_goal = number_format($funding_goal, 0);
                $funding_total = number_format($funding_total, 0);
                $number = number_format($percentage * 100, 1);
                echo "<h4 class=\"margin-11\">£<span>" . $funding_total . "/" . $funding_goal . "</span></h4>";
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
                    $time_left = getTime(null, $funding_start_date);
                    $time_total = getTime($funding_start_date, $funding_expire_date);
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
                    <div class=\"col-md-2 pa visible-md visible-lg\">
                        <p><span>" . $number2 . "%</span></p>
                    </div>
                    </div>";
                //needs to use now date to minus expiredate
                // dot delimited query values \" escaped character
                if($funding_status == 0){
                    if(@$_SESSION['user_id'] == null){
                        echo "<p>Please login before you take an action</p>
                    </div>";

                    }else{
                    echo "<a class=\"actionbtn btn center-block btn-donate\" href=\"actionForm.php?funding_id=".$funding_id."\">Take an action</a>
                    </div>";
                    }
                }else{
                    echo "<div id=\"text-center\">
                        <p>Times up! All the money has been donated!</p>
                    </div></div>";
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
