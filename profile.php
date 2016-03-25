<!DOCTYPE html>
<?php include "header.php";?><!--page-->
<?php
require("php/manpics.php");
$user_id = isset($_GET['user_id'])? $_GET['user_id'] : $_SESSION['user_id'];
$mysqli = getConnect();
$stmt = $mysqli->stmt_init();
$query = "SELECT user_id, user_name, user_description, user_donation, user_raise, pic_id FROM users WHERE user_id = ?";
if($stmt->prepare($query)){
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($user_id,$user_name, $user_description, $user_donation, $user_raise, $pic_id);
    $stmt->store_result();
    while($stmt->fetch()){
        if($pic_id){
            $picStuff = Manpics::picInfoService($pic_id);
            if(count($picStuff) == 0){
                echo "<div class=\"container-fluid\" style=\"background:url(img/starry-dusk.jpg); object-fit: cover;\">
                        <div class=\"overlay\">
                        <div class=\"blank\"></div>
                        <div class=\"blank\"></div>
                        <div class=\"blank\"></div>
                        <div class=\"profile-width center\">
                        <div class=\"row profile\">
                        <div class=\"col-md-4 text-center\">
                <a class=\"btn btn-lg btn-info\" href=\"php/upload.php?action_id =\"".$user_id."\" > Upload Image </a > </div>";
            }else{
                foreach($picStuff as $row){
                    printf("<div class=\"container-fluid ppic\" style=\"background:url(php/imagelocator.php?picID=%s); background-repeat: no-repeat; background-position:center; object-fit:cover; background-size:100%%;\">\n
                        <div class=\"overlay\">
                        <div class=\"blank\"></div>
                        <div class=\"blank\"></div>
                        <div class=\"blank\"></div>
                        <div class=\"blank\"></div>
                            <div class=\"profile-width center\">
                            <div class=\"row profile\">
                            <div class=\"col-md-4 text-center\">
                        <a href=\"php/imagelocator.php?picID=%d\" target=\"_blank\"><img class=\"img-responsive myphoto\" src=\"php/imagelocator.php?picID=%s\" /></a>\n", $row["id"], $row["id"], $row["id"]);
                }
            }
        }else{
            echo "<div class=\"container-fluid\" style=\"background:url(img/starry-dusk.jpg); object-fit: cover;\">
                        <div class=\"overlay\">
                        <div class=\"blank\"></div>
                        <div class=\"blank\"></div>
                        <div class=\"blank\"></div>
                        <div class=\"profile-width center\">
                        <div class=\"row profile\">
                        <div class=\"col-md-4 text-center\">
                   <img src=\"img/profile.png\" class=\"img-responsive myphoto\">
                    ";
        }
        echo "
            <p class=\"profile-large\">".$user_name."</p>
            <a class=\"btn btn-info btn-donate\"  href=\"mydetail.php?user_id=".$user_id."\">My Detail</a>
        </div>
        <div class=\"col-md-8 text-center\">
            <div>
                <p class=\"profile-text\">Total donated</p>
                <h6 class=\"profile-money\">".$user_donation."</h6>
            </div>
            <div>
                <p class=\"profile-text\">total funded</p>
                <h6 class=\"profile-money\">".$user_raise."</h6>
            </div>";
    }
    $stmt->close();
}
echo "<div class=\"row\">";
//query actions table
$query1 = "SELECT action_id FROM actions WHERE  user_id = ?";
$query2 = "SELECT A.action_id, A.action_name, A.action_description, A.action_location,A.action_location_name,
A.action_goal, A.action_total, A.action_start_date, A.action_expire_date, A.action_status,A.pic_id, A.user_id, A.funding_id,
B.funding_name From actions AS A LEFT JOIN fundings AS B ON A.funding_id = B.funding_id WHERE A.user_id = ?";
//query fundings table
$query3 = "SELECT funding_id FROM fundings WHERE user_id = ?";
$query4 = "SELECT A.funding_id, A.funding_name, A.funding_description, A.funding_goal,
A.funding_total, A.funding_start_date, A.funding_expire_date, A.user_id, A.category_id, A.organization_id,A.pic_id,
B.organization_name From fundings AS A
LEFT JOIN organizations AS B ON A.organization_id = B.organization_id WHERE A.user_id = ?";
$stmt = $mysqli->stmt_init();
if($stmt->prepare($query1)) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->store_result();
    echo "
    <div class=\"col-md-6 profile-block\">
    <div class=\"charthero2\">
        <h6 class=\"profile-money2\">" . $stmt->num_rows . "</h6>
        <p class=\"profilet\">Actions</p>
    </div>
    </div>
    ";
    $stmt->close();
}
$stmt = $mysqli->stmt_init();
if($stmt->prepare($query3)){
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->store_result();
    echo "
    <div class=\"col-md-6 profile-block\">
        <div class=\"charthero2\">
        <h6 class=\"profile-money2\">".$stmt->num_rows."</h6>
        <p class=\"profilet\">Fundings</p>
        </div>
    </div>
    </div>
    </div>

</div>
</div>
 <!--end of row profile-->
</div>
</div>
    ";

    $stmt->close();
}
$stmt = $mysqli->stmt_init();
if($stmt->prepare($query2)){
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($action_id, $action_name, $action_description, $action_location,$action_location_name, $action_goal, $action_total, $action_start_date, $action_expire_date,
        $action_status,$pic_id, $user_id, $funding_id, $funding_name);
    $stmt->store_result();
    if($stmt->num_rows>0){
        echo "
    <div class=\"container-fluid bg-action text-center\">
    <div class=\"maxWidth center\">
        <div class=\"titles\">
            <h2 >My Actions</h2>
        </div>
        <div style=\"clear:both;\"></div>
        <div class=\"text-center\">";
        while ($stmt->fetch()) {
                echo "
        <div class=\"col-sm-4 paddingside\">";
        if($pic_id){
                    $picStuff = Manpics::picInfoService($pic_id);
                    if(count($picStuff) == 0){
                        echo "<a class=\"btn btn-lg btn-info\" href=\"php/upload.php?action_id =\"".$action_id."\" > Upload Image </a > ";
                    }else{
            foreach($picStuff as $row){

                echo "<div class=\"container-video text-center\">";
                printf("<a href=\"php/imagelocator.php?picID=%d\" target=\"_blank\"><img class=\"img-responsive profileCover borderI\" src=\"php/imagelocator.php?thumb=yes&picID=%s\" /></a>\n", $row["id"], $row["id"]);
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
       echo" <div class=\"myaction borderP\">
            <div class=\"row paddingaction\">
                <!--left-->
                    <p class=\"porText\" href=\"action.php?action_id=" . $action_id . "\">" .$action_name."</p>
                    <p>for <a class=\"org-link\" href=\"funding.php?funding_id=".$funding_id."\">$funding_name</a></p>
                    <div class=\"progress transparent-bar marginslight\">";
                    $number = calculate($action_total, $action_goal);
                    $action_total = number_format($action_total, 0);
                    $action_goal = number_format($action_goal, 0);
                    echo"
                        <p class=\"number\">£ ".$action_total."/".$action_goal."</p>
                        <div class=\"progress-bar mintgreen\" role=\"progressbar\" aria-valuenow=\"".$number."\"
                             aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width:".$number."%\">
                        </div>
                    </div>";
                    $time_left = getTime(null, $action_start_date);
                    $time_total = getTime($action_start_date, $action_expire_date);
                    $time_start = -$time_left;
                    $percent = $time_start/$time_total;
                    $number2 = number_format($percent * 100, 0);
                    echo"
                    <div class=\"progress transparent-bar marginslight\">
                        <p class=\"number\">".$time_start." /".$time_total." days</p>
                        <div class=\"progress-bar lemonyellow bar\" role=\"progressbar\" aria-valuenow=\"".$number2."\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width:".$number2."%\"></div>
                    </div>";
            if($action_status == 0){
//                if($time_start == $time_total && $action_total<$action_goal){
//                    echo"
//                    <div class=\"col-md-5\">
//                    Mission failed
//                    </div>
//                    ";
//                }else{
                if($user_id == @$_SESSION['user_id']){
                   echo"<div class=\"row\">
                            <div class=\"col-md-6\">
                            <a class=\"editBtn text-center\" href=\"modifyStatus.php?completed=yes&action_id=".$action_id."\">Mission complete</a>
                            </div>
                            <div class=\"col-md-6\">
                            <a class=\"giveupBtn text-center\" href=\"modifyStatus.php?completed=no&action_id=".$action_id."\">Give up</a>
                            </div>
                        </div>";}else{
                    echo "
                    <a class=\"btn btn-warning center-block btn-donate\" href=\"donationForm.php?action_id=".$action_id."\">Donate</a>
                    ";
                }
//                  }
            }else if($action_status == 1){
                echo "<p>Action is completed!</p>";
            }else{
                echo "<div class=\"fail\">
                <p>Action is failed :(</p>
                </div>";
            }

            echo"
            </div>
             </div>
        </div>
            ";
        }
        echo"
        </div>
       ";
        }else{
            echo "
            <div class=\"container-fluid bg-action\">
            <div class=\"maxWidth center\">
            <div class=\"titleP\">
                <h2 >My Actions</h2>
            </div>
                <div class=\"alert alert-info text-center\" role=\"alert\">You have not taken any actions yet!
                <a href=\"fundingPage.php\" class=\"alert-link\">TAKE AN ACTION NOW!</a>
                </div>
            </div>
            </div>";
        }
    $stmt->close();
    }
        echo"
    </div>
    </div>
    </div>
    <!--end of action group-->";

$stmt = $mysqli->stmt_init();
if($stmt->prepare($query4)) {
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($funding_id, $funding_name, $funding_description, $funding_goal, $funding_total,
    $funding_start_date, $funding_expire_date, $user_id, $category_id, $organization_id, $pic_id, $organization_name);
$stmt->store_result();
if($stmt->num_rows > 0){
    echo"<div class=\"container-fluid bg-pink text-center\">
    <div class=\"maxWidth center\">
    <div class=\"titles\">
        <h2 >My fundings</h2>
    </div>
    <div style=\"clear:both;\"></div>
        <!--Funding Group-->
    <!--row1-->
    <div class=\"row marginbottom\">";
    while($stmt->fetch()){
        echo"
        <!--funding-->
        <div class=\"col-md-6 addpadding\">
        <div class=\"\">";
        if($pic_id){
            $picStuff = Manpics::picInfoService($pic_id);
            if(count($picStuff) == 0){
                echo "<a class=\"btn btn-lg btn-info\" href=\"php/upload.php?action_id =\"".$action_id."\" > Upload Image </a > ";
            }else{
                foreach($picStuff as $row){
                    printf("<a href=\"php/imagelocator.php?picID=%d\" target=\"_blank\"><img class=\"mg-responsive fundingcover\" src=\"php/imagelocator.php?thumb=yes&picID=%s\" /></a>\n", $row["id"], $row["id"]);
                    echo"</div>";
                }
             }
        }else{
        echo " <img class=\"mg - responsive fundingcover\" src=\"img/funding_pic_1.jpg\"></div> ";
        }
       echo"<div class=\"myfunding borderP text-center\">
            <div class=\"myfunding2\">
            <a class=\"porText\" href=\"funding.php?funding_id=".$funding_id."\">".$funding_name."</a>
            <p>for ".$organization_name."</a></p>
            </p>";
        $number3 = calculate($funding_total, $funding_goal);
        echo "
            <div class=\"progress transparent-bar\">
                <div class=\"progress-bar lemonyellow\" role=\"progressbar\" aria-valuenow=\"".$number3."\"
                     aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width:".$number3."%\">
                </div>
            </div>
            <p><span>£".$funding_total."/".$funding_goal."</span><span>21 days left</span><span>".$number3."%</span></p>
        </div>
        </div>
        </div>
    ";
    }
    echo "<!--funding-->
    </div>
    </div>
    </div>
</div>

<!-- End of Funding Group -->";
}else{
    echo"
    <div class=\"container-fluid bg-pink\">
            <div class=\"maxWidth center\">
            <div class=\"titleP\">
                <h2 >My Fundings</h2>
            </div>
                <div class=\"alert alert-danger text-center\" role=\"alert\">You have not applied for any funding yet!
                <a href=\"fundingForm.php\" class=\"alert-link\">APPLY NOW!</a>
                </div>
            </div>
            </div>";
}
}
$mysqli->close();
?>
<?php include "footer.php";?>
</body>
</html>
