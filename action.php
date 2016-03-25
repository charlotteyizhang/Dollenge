<!DOCTYPE html>
<?php include "header.php";
?><!--page-->

<!-- content -->
    <?php
    require("php/manpics.php");
    $action_id = isset($_GET['action_id'])? $_GET['action_id'] : null;
    if(!$action_id){
        echo "<p>404</p>";
        exit();
    }
    $mysqli = getConnect();
    $stmt = $mysqli->stmt_init();
    $query ="SELECT A.action_id, A.action_name, A.action_description, A.action_location,
            A.action_goal, A.action_total, A.action_start_date, A.action_expire_date, A.action_status,  A.action_video, A.user_id,A.funding_id,A.pic_id,
            B.user_name, C.funding_name From actions AS A LEFT JOIN users AS B ON A.user_id = B.user_id
            LEFT JOIN fundings AS C ON A.funding_id = C.funding_id WHERE A.action_id = ? ";
    if($stmt->prepare($query)){
        $stmt->bind_param("i", $action_id);
        $stmt->execute();
        $stmt->bind_result($action_id, $action_name, $action_description, $action_location, $action_goal, $action_total, $action_start_date, $action_expire_date,
            $action_status,$action_video, $user_id, $funding_id, $pic_id, $user_name, $funding_name);
        $stmt->store_result();
        while($stmt->fetch()){
            echo "
        <div class=\"row\">";
            if($action_status == 0){
                if($pic_id){
                    $picStuff = Manpics::picInfoService($pic_id);
                    if(count($picStuff) == 0){
                        echo "<a class=\"btn btn-lg btn-info\" href=\"php/upload.php?action_id =\"".$action_id."\" > Upload Image </a > ";
                    }else{
                        foreach($picStuff as $row){
                            echo "<div class=\"container-video text-center\">";
                            printf("<a href=\"php/imagelocator.php?picID=%d\" target=\"_blank\"><img class=\"img-responsive acCover\" src=\"php/imagelocator.php?picID=%s\" /></a>\n", $row["id"], $row["id"]);
                            echo "</div>";
                        }
                    }
                }else{
                    echo "
                <div class=\"container-video text-center\">
                    <img class=\"img-responsive acCover\" src=\"img/action_pic_1.jpg\">
                </div>
                ";
                }
            echo"<div class=\"container-fluid bg-action text-center\">
            <div class=\"maxWidth center\">
            <p class=\"small-title\">Action</p>
            <h1 class=\"action-title\">".$action_name."</h1>
            <p>for <a class=\"link\" href=\"funding.php?funding_id=".$funding_id."\">".$funding_name."</a></p>";
            echo"
             <a href=\"donationForm.php?action_id=".$action_id."\" class=\"donatebtn smallBtn\">Donate</a>";

            }else if($action_status ==1){
                echo $action_video;
                echo"<div class=\"container-fluid bg-action text-center\">
                <div class=\"maxWidth center\">
                <p class=\"small-title\">Action</p>
            <h1 class=\"action-title\">".$action_name."</h1>
            <p>for <a class=\"link\" href=\"funding.php?funding_id=".$funding_id."\">".$funding_name."</a></p>";
                echo "<p>Action is completed!</p>";
            }else{
                echo"<div class=\"container-fluid bg-action text-center\">
                <div class=\"maxWidth center\">
                <p class=\"small-title\">Action</p>
            <h1 class=\"action-title\">".$action_name."</h1>
            <p>for <a class=\"link\" href=\"funding.php?funding_id=".$funding_id."\">".$funding_name."</a></p>";
                echo "<p>Action is failed :(</p>";
            }
        $number = calculate($action_total, $action_goal);
        $action_goal = number_format($action_goal, 0);
        $action_total = number_format($action_total, 0);
       echo"
        <div style=\"clear:both;\"></div>
        <div class=\"row text-center\">
        <!--left-->
        <div class=\"col-sm-6\">
            <p class=\"money\">£".$action_total."/".$action_goal."<small class=\"text\"> funded</small></p>
        <div class=\"row\">
        <div class=\"col-sm-offset-1 col-sm-9 progress transparent-bar marginslight\">
        <div class=\"progress-bar mintgreen bar progress-bar-striped active\" role=\"progressbar\" aria-valuenow=\"".$number."\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width:".$number."%\"></div>
        </div>
        <div class=\"col-sm-2 pa2\">
            <p class=\"text\">".$number."%</p>
        </div>
        </div>
        </div>
        <!--right-->";
        $time_left = getTime(null, $action_start_date);
                    $time_total = getTime($action_start_date, $action_expire_date);
                    $time_start = -$time_left;
                    $percent = $time_start/$time_total;
                    $number2 = number_format($percent * 100, 0);
        echo"<div class=\"col-sm-6\">
                <p class=\"money\">".$time_start." /".$time_total." days</small></p>
        <div class=\"row\">
        <div class=\"col-sm-offset-1 col-sm-9 progress transparent-bar marginslight\">
            <div class=\"progress-bar lemonyellow bar progress-bar-striped active\" role=\"progressbar\" aria-valuenow=\"" . $number2 . "\"
                         aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width:" . $number2 . "%\">
            </div>
        </div>
        <div class=\"col-sm-2 pa2\">
            <p class=\"text\"><span>" . $number2 . "%</span></p>
        </div>
        </div>
        </div>
        </div>
        <div class=\"marginbottom\">
        <p class=\"content text-center description\">".
           $action_description ."</p>
         </div>
         </div>
         </div>
         </div>
        <!-- End of Content -->
        ";
        }
        $stmt->close();
    }

    $stmt = $mysqli->stmt_init();
    $query = "SELECT user_description, user_donation, user_raise, pic_id FROM users WHERE user_id = ?";
    if($stmt->prepare($query)){
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($user_description, $user_donation, $user_raise, $pic_id);
        $stmt->store_result();
        while($stmt->fetch()){
        $user_donation = number_format($user_donation, 0);
        $user_raise = number_format($user_raise, 0);
    echo "
    <!--Funding Group-->
    <div class=\"container-fluid text-left bg-action\">
    <div class=\"maxWidth center\">
            <div class=\"col-sm-6\">
            <div class=\"titles2\">
                <h2>Hero</h2>
            </div>
            <div style=\"clear:both;\"></div>
            <div class=\"row\">
                <div class=\"col-md-4\">";
            if($pic_id){
                $picStuff = Manpics::picInfoService($pic_id);
                if(count($picStuff) == 0){
                    echo "<a class=\"btn btn-lg btn-info\" href=\"php/upload.php?action_id =\"".$action_id."\" > Upload Image </a > </div>";
                }else{
                    foreach($picStuff as $row){
                        printf("<a href=\"php/imagelocator.php?picID=%d\" target=\"_blank\"><img class=\"picmargin cover bar\" src=\"php/imagelocator.php?picID=%s\" /></a>\n", $row["id"], $row["id"]);
                    }
                }
            }else{
                echo "
                    <img src=\"img/profile.jpg\" class=\"picmargin cover bar\">
                    ";
            }

          echo"  </div>
                <div class=\"col-md-8 bar\">
                    <div>
                        <h3 class=\"profile-large\">".$user_name."</h3>
                    </div>
                    <div>
                        <p class=\"total\">total donated:</p>
                        <h7 class=\"profile-large\">".$user_donation."</h7>
                    </div>
                    <div>
                        <p class=\"total\">total funded:</p>
                        <h7 class=\"profile-large\">".$user_raise."</h7>
                    </div>
                </div>
                </div>
                <div>
                    <p class=\"content text-left total\">".$user_description."</p>
                </div>
            </div>
          ";
    echo "
        <div class=\"col-sm-6\">
            <div class=\"titles2\">
                <h2>Location</h2>
            </div>
            <div style=\"clear:both;\"></div>
            <div id=\"map\" class=\"container-fluid map3\"></div>
            <input type=\"text\" id=\"location\" class=\"hidden\" name=\"location\" value=\"".$action_location."\">
            <script src=\"https://maps.googleapis.com/maps/api/js?key=AIzaSyCEFjo-xtPuao6-6SeDF4kTIzrE7_mjEjY&libraries=places&callback=setLocation\"
                  async defer></script>
        </div>
    </div>
    </div>";
        }
    }
    $mysqli->close();
    ?>

<!-- End of Funding Group -->
<?php include "footer.php";?>
</body>
</html>