<!DOCTYPE html>
<?php include "header.php";?>

<!--carousel-->
<div id="myCarousel" class="carousel slide" data-ride="carousel">
    <!-- Indicators -->
    <ol class="carousel-indicators">
        <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
        <li data-target="#myCarousel" data-slide-to="1"></li>
        <li data-target="#myCarousel" data-slide-to="2"></li>
    </ol>

    <!-- Wrapper for slides -->
    <div id="carousel-inner" class="carousel-inner" role="listbox">
        <div class="item active">
            <img src="img/jump.jpg" alt="First slide" >
            <div class="carousel-caption">
                <h1 class="beHero animated fadeInUp">BE A <strong>HERO</strong></h1>
                <p class="text1 animated fadeInUp">Take an action to encourage people to share their love and donate</p>
                <div class="charthero animated fadeInUp">
                    <?php
                    $mysqli = getConnect();
                    /* Select queries return a resultset */
                    if ($result = $mysqli->query("SELECT user_id FROM users")) {
                        echo "<h2 class=\"heronumber\">".$result->num_rows."</h2>";
                        /* free result set */
                        $result->close();
                    }
                    ?>
                    <h5 class="herotext">hero join us</h5>
                </div>
                <a href="fundingPage.php" class="carousel-button carousel-button3 animated fadeInUp" role="button">Take an action</a>
            </div>
        </div>

        <div class="item">
            <img src="img/funding_pic.jpg" alt="Second slide">
            <div class="carousel-caption">
                <h1 class="beHero animated fadeInUp">Someone needs <strong>help</strong></h1>
                <p class="text1 animated fadeInUp">Discover someone who needs your help and share to everybody</p>
                    <div class="charthero animated fadeInUp">
                        <?php
                        $mysqli = getConnect();
                        /* Select queries return a resultset */
                        if ($result = $mysqli->query("SELECT action_id FROM actions")) {
                            echo "<h2 class=\"heronumber\">".$result->num_rows."</h2>";
                            /* free result set */
                            $result->close();
                        }
                        ?>
                        <h5 class="herotext">Actions</h5>
                    </div>
                
                <a href="fundingForm.php" class="carousel-button animated fadeInUp" role="button">Apply for a Funding</a>
            </div>
        </div>

        <div class="item">
            <img src="img/hope.jpg" alt="Third slide">
            <div class="carousel-caption">
                <h1 class="beHero animated fadeInUp"><strong>Support</strong> Heros</h1>
                <p class="text1 animated fadeInUp">Donate some money to support heroes' actions</p>
                <div class="c animated fadeInUp">
                    <?php
                    $mysqli = getConnect();
                    /* Select queries return a resultset */
                    if ($result = $mysqli->query("SELECT sum(funding_total) AS total FROM fundings")) {
                        while($obj = $result->fetch_object()){
                            echo "<h2 class=\"heronumber\"> £".$obj->total."</h2>";
                        }

                        /* free result set */
                        $result->close();
                    }
                    ?>
                    <h5 class="herotext">people funding</h5>
                </div>
                        
                <a href="actionPage.php" class="carousel-button carousel-button2 animated fadeInUp" role="button">Donate</a>
            </div>
        </div>
    </div>

    <!-- Left and right controls -->
    <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>
<!-- End of Carousel -->
<!-- Action group -->
<div id="actionHome" class="container-fluid bg-action">
    <div class="maxWidth center">
    <div class="title">
        <h2 class="marginzero to-animate">Support Actions</h2>
        <a class="morebtn to-animate" href="actionPage.php">More actions</a>
    </div>
    <div style="clear:both;"></div>
    <div class="row text-center" style="margin=30px;">
        <?php
        require "php/manpics.php";
        /**
         * Created by "CharlotteZhang".
         * User: user
         * Date: 2016/2/26
         * Time: 15:12
         */
        //get the action information from database
        $mysqli = getConnect();
        /* create a prepared statement */
        $stmt =  $mysqli->stmt_init();
        $query = "SELECT A.action_id, A.action_name, A.action_description, A.action_location,
A.action_goal, A.action_total, A.action_start_date, A.action_expire_date, A.action_status, A.pic_id, A.user_id,A.funding_id,
B.user_name, C.funding_name From actions AS A LEFT JOIN users AS B
ON A.user_id = B.user_id
LEFT JOIN fundings AS C ON A.funding_id = C.funding_id WHERE A.action_status = '0' ORDER BY action_id DESC";
        if ($stmt->prepare($query)){
            /* execute query */
            $stmt->execute();
            /* bind your result columns to variables, e.g. id column = $post_id */
            $stmt->bind_result($action_id, $action_name, $action_description, $action_location, $action_goal, $action_total,
                $action_start_date, $action_expire_date, $action_status,$pic_id, $user_id, $funding_id, $user_name, $funding_name);
            /* store result */
            $stmt->store_result();
            if($stmt->num_rows) {
                /*counter to select 3 rows from the result*/
                $n = 0;
                /* fetch the result of the query & loop round the results */
                while ($stmt->fetch() && ($n<3)) {
                    $n ++;
                    if($pic_id){
                        $picStuff = Manpics::picInfoService($pic_id);
                        if(count($picStuff) == 0){
                            echo "<a class=\"btn btn-lg btn-info\" href=\"php/upload.php?action_id =\"".$action_id."\" > Upload Image </a > </div>";
                        }else{
                            foreach($picStuff as $row){
                                echo "
                       <div class=\"col-sm-4\">";
                                printf("<a href=\"php/imagelocator.php?picID=%d\" target=\"_blank\"><img class=\"img-responsive picCover\" src=\"php/imagelocator.php?picID=%s\" /></a>\n", $row["id"], $row["id"]);
                            }
                        }
                    }else{
                        echo "
                    <div class=\"col-sm-4\">
                        <img src=\"img/action_pic_1.jpg\" class=\"img-responsive picCover\" alt=\"Image\">
                    ";
                    }
                    echo"<a class=\"subtitle\" href=\"action.php?action_id=" . $action_id . "\"><h3>" . $action_name . "</h3></a>\n";
                    //needs to be changed to left join funding name and funding id as the link table
                    echo "<p>for <a class=\"funding-link\" href=\"fundingPage.php?funding_id=".$funding_id."\">".$funding_name."</a></p>";
                    $summary = substr_replace($action_description, "...", 100);
                    echo "<p class=\"action-text\">" . $summary . "<a href=\"action.php?action_id=" . $action_id . "\">read more</a>
                     </p>";
                    //later left join user name
                    echo "<p class=\"postby\">post by <a href=\"profile.php?user_id=".$user_id."\">".$user_name."</a> </p>
                     <div style=\"clear:both;\"></div>";
                    $percentage = $action_total / $action_goal;
                    $action_goal = number_format($action_goal, 0);
                    $action_total = number_format($action_total, 0);
                    $number = number_format($percentage * 100, 1);
                    echo "<p class=\"margin-10\">£<span>" . $action_total . "/" . $action_goal . "</span></p>";
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
                    echo "<div class=\"row\">
                    <div class=\"col-md-10\">
                    <p class=\"margin-10\">".$time_start." /".$time_total." days</p>
                    </div>
                    </div>";
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
                    // dot delimited query values \" escaped character
                    echo "
                   <a class=\"donatebtn\" href=\"donationForm.php?action_id=".$action_id."\">Donate</a>
                </div>";
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
<!--Funding Group-->
<div class="container-fluid bg-pink">
    <div class="maxWidth center">
    <div class="titles">
        <h2 class="marginzero">Raise for fundings</h2>
        <a class="morebtn" href="fundingPage.php">More fundings</a>
    </div>
    <div style="clear:both;"></div>
    <?php
    /**
     * Created by "CharlotteZhang".
     * User: user
     * Date: 2016/2/25
     * Time: 15:12
     */
    //get the funding information from database
    $mysqli = getConnect();
    /* create a prepared statement */
    $stmt =  $mysqli->stmt_init();

    if ($stmt->prepare("SELECT funding_id, funding_name, organization_id, funding_description, funding_goal, funding_total, funding_start_date, funding_expire_date, funding_status, pic_id
 FROM fundings WHERE funding_status='0' ORDER BY funding_id DESC ")){
        /* execute query */
        $stmt->execute();
        /* bind your result columns to variables, e.g. id column = $post_id */
        $stmt->bind_result($funding_id, $funding_name, $organization_id, $funding_description, $funding_goal, $funding_total, $funding_start_date, $funding_expire_date, $funding_status, $pic_id);
        /* store result */
        $stmt->store_result();
        if($stmt->num_rows) {
            /* fetch the result of the query & loop round the results */
            $n = 0;
            while ($stmt->fetch() && ($n<2)) {
                $n++;

                if($pic_id){
                    $picStuff = Manpics::picInfoService($pic_id);
                    if(count($picStuff) == 0){
                        echo "<a class=\"btn btn-lg btn-info\" href=\"php/upload.php?action_id =\"".$action_id."\" > Upload Image </a > </div>";
                    }else{
                        foreach($picStuff as $row){
                            echo "
                            <div class=\"row marginbottom\">
                             <div class=\"col-sm-6 addpadding\">
                            ";
                            printf("<a href=\"php/imagelocator.php?picID=%d\" target=\"_blank\"><img class=\"featurette-image img-responsive picCover2\" src=\"php/imagelocator.php?picID=%s\" /></a>\n", $row["id"], $row["id"]);
                            echo "
                            </div>
                         <div class=\"col-sm-6 addpadding\">
                            ";
                        }
                    }
                }else{
                    echo "
                    <div class=\"row marginbottom\">
                        <div class=\"col-sm-6 addpadding\">
                            <img class=\"featurette-image img-responsive picCover2\" alt=\"Responsive image\" src=\"img/funding_pic_1.jpg\">
                        </div>
                         <div class=\"col-sm-6 addpadding\">
                     ";
                }
                echo "<a class=\"ftitle\" href=\"funding.php?funding_id=" . $funding_id . "\"><h3>" . $funding_name . "</h3></a>\n";
                //needs to be changed to left join organization table
                echo "<p>for <a class=\"org-link\" href=\"#\">Cancer research UK</a></p>";
                $summary = substr_replace($funding_description, "...", 200);
                echo "<p class=\"action-text\">" . $summary . "<a href=\"funding.php?funding_id=" . $funding_id . "\">read more</a>
                </p>";
                $percentage = $funding_total / $funding_goal;
                $funding_goal = number_format($funding_goal, 0);
                $number = number_format($percentage * 100, 1);
                echo "<div class=\"progress\">
                    <div class=\"progress-bar mintgreen\" role=\"progressbar\" aria-valuenow=\"" . $number . "\"
                         aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width:" . $number . "%\">
                    </div>
                </div>";
                $time_left2 = getTime(null, $funding_start_date);
                $time_total2 = getTime($funding_start_date, $funding_expire_date);
                $time_start2 = -$time_left2;
                $percent2 = $time_start2/$time_total2;
                $number3 = number_format($percent2 * 100, 0);
                //needs to use now date to minus expiredate
                echo "<p class=\"text-center\"><span>" . $funding_total . "/" . $funding_goal . "</span><span>".$time_start2." /".$time_total2." days</span><span>" . $number3 . "%</span></p>";
                // dot delimited query values \" escaped character
                echo "
                <a class=\"actionbtn\" href=\"actionForm.php?funding_id=".$funding_id."\">Take an action</a>
                </div>
                <div style=\"clear:both;\"></div>
                <hr class=\"line2\">
                </div>";
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

<div class="container-fluid bg-action">
    <div class="maxWidth center text-center">
    <div class="contactus">
        <p>Contect Us</p>
    </div>
<script type="text/javascript">
var servicedomain="www.123contactform.com"; 
var frmRef=''; 
try { frmRef=window.top.location.href; } catch(err) {}; 
var cfJsHost = (("https:" == document.location.protocol) ? "https://" : "http://"); 
document.write(unescape("%3Cscript src='" + cfJsHost + servicedomain + "/includes/easyXDM.min.js' type='text/javascript'%3E%3C/script%3E")); 
frmRef=encodeURIComponent(frmRef).replace('%26','[%ANDCHAR%]'); 
document.write(unescape("%3Cscript src='" + cfJsHost + servicedomain + "/jsform-1878347.js?ref="+frmRef+"' type='text/javascript'%3E%3C/script%3E")); 
</script>
</div>
</div>

<!-- End of Funding Group -->
<?php include "footer.php";?>

