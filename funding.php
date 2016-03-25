<!DOCTYPE html>
<?php include "header.php";
    require "php/manpics.php";
    $funding_id = isset($_GET['funding_id'])? $_GET['funding_id'] : null;
    $mysqli = getConnect();
    $stmt = $mysqli->stmt_init();

    $query = "SELECT A.funding_id, A.funding_name, A.funding_description, A.funding_goal,
A.funding_total, A.funding_start_date, A.funding_expire_date,A.funding_location,A.funding_status, A.user_id, A.category_id, A.organization_id,A.pic_id,
B.user_name, C.organization_name From fundings AS A
LEFT JOIN users AS B ON A.user_id = B.user_id
LEFT JOIN organizations AS C ON A.organization_id = C.organization_id WHERE A.funding_id =?";

    if($stmt->prepare($query)) {
        $stmt->bind_param("i", $funding_id);
        $stmt->execute();
        $stmt->bind_result($funding_id, $funding_name, $funding_description, $funding_goal, $funding_total, $funding_start_date, $funding_expire_date, $funding_location, $funding_status, $user_id, $category_id, $organization_id,$pic_id, $user_name, $organization_name);
        $stmt->store_result();
        while ($stmt->fetch()) {
            if($pic_id){
                $picStuff = Manpics::picInfoService($pic_id);
                if(count($picStuff) == 0){
                    echo "<a class=\"btn btn-lg btn-info\" href=\"php/upload.php?action_id =\"".$action_id."\" > Upload Image </a > ";
                }else{
                    foreach($picStuff as $row){
                        echo "
                        <!--video-->
                        <div class=\"container-video text-center\">";
                        printf("<a href=\"php/imagelocator.php?picID=%d\" target=\"_blank\"><img class=\"img-responsive acCover\" src=\"php/imagelocator.php?picID=%s\" /></a>\n", $row["id"], $row["id"]);
                        echo" </div>
                        <!-- End of Video -->
                        ";
                    }
                }
            }else{
                echo "
                <div class=\"container-video text-center\">
                    <img class=\"img-responsive acCover\" src=\"img/funding_pic_1.jpg\">
                </div>
                ";
            }
            echo"
            <!-- content -->
            <div class=\"container-fluid bg-pink text-center\">
                <div class=\"maxWidth center\">
                <div class=\"row marginbottom\">
                    <p class=\"small-title\">Funding</p>
            ";
            echo "<h1 class=\"action-title\">" . $funding_name . "</h1>
        <p class=\"org-link2\">for " . $organization_name . "</p>
        ";
        if($funding_status == 0){
            if(@$_SESSION['user_id'] == null){
                echo "<p>Please login before you take an action</p>
               ";

            }else{
                echo "<a href=\"actionForm.php?funding_id=" . $funding_id . "\" class=\"btn btn btn-primary btn-default btn-center\">Take an action</a>
                ";
            }
        }else{
            echo "<p>Times up! All the money has been donated!</p>";
        }
                    $percentage = $funding_total / $funding_goal;
            $number = number_format($percentage * 100, 1);
            $funding_goal = number_format($funding_goal, 0);
            $funding_total = number_format($funding_total, 0);
        echo"</div>
        <div style=\"clear:both;\"></div>
        <div class=\"row marginbottom\">
        <!--left-->
        <div class=\"col-sm-6\">
        <p class=\"money\">£" . $funding_total . "/" . $funding_goal . "<small class=\"text\">funded</small></p>
        <div class=\"row\">
        <div class=\"col-sm-offset-1 col-sm-9 progress transparent-bar marginslight\">";
            echo "<div class=\"progress-bar mintgreen bar\" role=\"progressbar\" aria-valuenow=\"" . $number . "\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width:" . $number . "%\"></div>
            </div>
            <div class=\"col-sm-2\">
                <p class=\"visible-md visible-lg text pa2\">" . $number . "%</p>
            </div>
       </div>
       </div>";
          echo"
          <div class=\"col-sm-6\">";
            $time_left = getTime(null, $funding_expire_date);
            $time_total = getTime($funding_start_date, $funding_expire_date);
            $percent = $time_left/$time_total;
            $number2 = number_format($percent * 100, 1);
            echo"<p class=\"money\">".$time_left." /".$time_total." days <small class=\"text\">left</small></p>
            <div class=\"row\">
                <div class=\"col-sm-offset-1 col-sm-9 progress transparent-bar marginslight\">
                    <div class=\"progress-bar lemonyellow bar\" role=\"progressbar\" aria-valuenow=\"".$number2."\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width:".$number2."%\"></div>
                </div>
                <div class=\"col-sm-2\">
                    <p class=\"visible-md visible-lg text pa2\">".$number2."%</p>
                </div>
            </div>
          </div>
        </div>
        ";
            echo "<div>
        <p class=\"content text-center description\">" .
                $funding_description . "</p>
                <div class=\"maxWidth center\">
        <p class=\"postby\">posted by <a href=\"profile.php?user_id=".$user_id."\">".$user_name."</a></p>
        </div>
            </div>
        </div>";
        }
        $stmt->close();
        }
    echo "<div class=\"container-fluid bg-pink text-left\">
    <div class=\"maxWidth center\">
    <div class=\"col-sm-6\">
        <div class=\"titles2\">
            <h2>Location</h2>
        </div>
        <div style=\"clear:both;\"></div>
        <div id=\"map\" class=\"container-fluid map2\"></div>
        <input type=\"text\" id=\"location\" class=\"hidden\" name=\"location\" value=\"".$funding_location."\">
        <script src=\"https://maps.googleapis.com/maps/api/js?key=AIzaSyCEFjo-xtPuao6-6SeDF4kTIzrE7_mjEjY&libraries=places&callback=setLocation\"
              async defer></script>
        </div>
    </div>
    <div class=\"col-sm-6\">
        <div class=\"titles2\">
            <h2>Action</h2>
    ";
    $stmt = $mysqli->init();

    $query = "SELECT A.action_id, A.action_name, A.action_description, A.action_location,
            A.action_goal, A.action_total, A.action_start_date, A.action_expire_date, A.action_status,A.pic_id, A.user_id,A.funding_id,
            B.funding_name From actions AS A LEFT JOIN fundings AS B ON A.funding_id = B.funding_id WHERE A.funding_id = ? ORDER BY action_id DESC ";
    $n = 0;
    if ($stmt= $mysqli->prepare($query)){
        $stmt->bind_param("i", $funding_id);
        /* execute query */
        $stmt->execute();
        /* bind your result columns to variables, e.g. id column = $post_id */
        $stmt->bind_result($action_id, $action_name, $action_description, $action_location, $action_goal, $action_total, $action_start_date, $action_expire_date, $action_status,$pic_id, $user_id, $funding_id, $funding_name);
        /* store result */
        $stmt->store_result();
        if($stmt->num_rows) {
            if($stmt->num_rows > 2){
                echo"<a class=\"morebtn\" href=\"actionPage.php?funding_id=".$funding_id."\">More actions</a>
        ";
            }
            echo "</div>
        <div style=\"clear:both;\"></div>";
            /* fetch the result of the query & loop round the results */
            while ($stmt->fetch() && $n<2) {
                $n++;

                $percent2 = $action_total / $action_goal;
                $number3 = number_format($percent2 * 100, 1);
                $action_goal = number_format($action_goal, 0);
                $action_total = number_format($action_total, 0);

                if($pic_id){
                    $picStuff = Manpics::picInfoService($pic_id);
                    if(count($picStuff) == 0){
                        echo "<a class=\"btn btn-lg btn-info\" href=\"php/upload.php?action_id =\"".$action_id."\" > Upload Image </a > </div>";
                    }else{
                        foreach($picStuff as $row){
                            echo "<div class=\"row\">
                                <div class=\"col-sm-4\">";
                            printf("<a href=\"php/imagelocator.php?picID=%d\" target=\"_blank\"><img class=\"img-responsive bar\" src=\"php/imagelocator.php?picID=%s\" /></a>\n", $row["id"], $row["id"]);
                        }
                    }
                }else{
                    echo "
                    <div class=\"row\">
                        <div class=\"col-sm-4\">
                    <img class=\"img-responsive bar\" src=\"img/action_pic_1.jpg\">
                    ";
                }

                echo "
                 </div>
                 <div class=\"col-sm-8 bar\">";
                echo "
              <a class=\"small-title3\" href=\"action.php?action_id=". $action_id ."\"><strong>". $action_name ."</strong></a>\n";
                //needs to be changed to left join funding name and funding id as the link table
                echo "<p class=\"money2\">£ ".$action_total. "/". $action_goal."<small class=\"text2\"> funded</small></p>
                 <div class=\"row\">  ";
                echo "<div class=\"col-md-9\">
                    <div class=\"progress progress-bar-striped active marginslight\">
                    <div class=\"progress-bar mintgreen bar\" role=\"progressbar\" aria-valuenow=\"".$number3."\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width:".$number3."%\"></div>
                    </div>
                    </div>
                 
                 <div class=\"col-md-3\">
                    <p class=\"text2 visible-md visible-lg\">".$number3."%</p>
                     </div>
                </div>
                </div>
                </div>
                ";
            }
            echo "</div>
               </div>
               </div>";
        }else {// there aren't any results
            echo "</div><p>There isn't any content</p>";
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

<!-- End of Funding Group -->
<?php include "footer.php";?>
</body>
</html>