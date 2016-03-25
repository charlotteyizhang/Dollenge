<!DOCTYPE html>
<?php include "header.php";?><!--page-->
<?php
    $_SESSION['userurl'] = $_SERVER['REQUEST_URI'];
?>
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/themes/hot-sneaks/jquery-ui.css" rel="stylesheet">
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js"></script>
<div class="container-fluid bg-pink">
<div class="title3">
  <p>Create a funding</p>  
</div>
<div class="blank"></div> 
<div style="clear:both;"></div>
            <?php
            require "php/manpics.php";
            $url = $_SERVER['REQUEST_URI'];
            $_SESSION['userurl'] = $url;
            //give the url of current page
            echo "<div class=\"text-center center\">
            <a class=\"btn btn-warning\" href=\"php/upload.php?url=".$url."\">Upload photo</a></div>
                <form class=\"form-horizontal max-width\" role=\"form\" action=\"php/createfunding.php\" method=\"post\">
        <div class=\"form-group\">";
            $pic_id=isset($_GET['pic_id'])? $_GET['pic_id'] : null;
            if($pic_id){
                $picStuff = Manpics::picInfoService($pic_id);
                if(count($picStuff) != 0){
                    foreach($picStuff as $row){
                        echo "<div class=\"container-video text-center\">";
                        printf("<a href=\"php/imagelocator.php?thumb=yes&picID=%d\" target=\"_blank\"><img class=\"img-responsive\" src=\"php/imagelocator.php?picID=%s\" /></a>\n", $row["id"], $row["id"]);
                        echo "</div>";
                    }
                }
                echo"<input type=\"number\" class=\"hidden\" id=\"pic_id\", name=\"pic_id\", value=\"".$pic_id."\">";
            }
            ?>
        </div>
        <div class="form-group">
            <label class="formtext4" for="funding_name">Funding Name</label>
            <input type="text" class="form-control funding-border" id="funding_name" name="funding_name" placeholder="type funding name" required="required">
        </div>
        <div class="form-group">
            <label class="formtext4" for="funding_goal">Goal</label>
            <input type="number" class="form-control funding-border" id="funding_goal" name="funding_goal" placeholder="£" required="required">
        </div>
        <div class="row">
        <div class="form-group">
            <div class="col-md-6">
                <label class="formtext4" for="funding_start_date">start date</label>
                <input class="form-control funding-border" id="funding_start_date" type="text" name="funding_start_date" required="required"/>
                <script language="JavaScript">
                  $.noConflict();
                   jQuery( document ).ready(function( $ ) {
                      $("#funding_start_date").datepicker({firstDay: 1,dateFormat:"yy-mm-dd"});
                      });
                </script>
            </div>
            <div class="col-md-6">
                <label class="formtext4" for="funding_expire_date">Expire date</label>
                <input class="form-control funding-border" id="funding_expire_date" type="text" name="funding_expire_date" required="required"/>
                <script language="JavaScript">
                  $.noConflict();
                   jQuery( document ).ready(function( $ ) {
                      $("#funding_expire_date").datepicker({firstDay: 1,dateFormat:"yy-mm-dd"});
                      });
                </script>
            </div>
        </div>
        </div>
        <div class="form-group">
            <label class="formtext4" for="organization_id">Organization</label>
            <select  class="form-control" id="organization_id" name="organization_id">
                <?php
                    $mysqli = getConnect();
                    $stmt = $mysqli->init();
                    $query = "SELECT organization_id, organization_name FROM organizations;";
                    if($stmt = $mysqli->prepare($query)){
                        $stmt->execute();
                        $stmt->bind_result($organization_id, $organization_name);
                        $stmt->store_result();
                        while($stmt->fetch()){
                            echo "<option value=\"".$organization_id."\">".$organization_name."</option>";
                        }
                        $stmt->close();
                    }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label class="formtext4" for="funding_location">Location</label>
            <input id="location" placeholder="Enter your address"
                   onFocus="geolocate()" type="text" class="form-control funding-border add2" required="required">
            <input id="currentPage" type="text" class="hidden" value="funding">
            <input type="text" id="funding_location" class="hidden" name="funding_location" >

            <div id="map" class="map"></div>
            <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCEFjo-xtPuao6-6SeDF4kTIzrE7_mjEjY&libraries=places&callback=initMap"
                    async defer></script>
            <!-- <input type="text" class="form-control form-control1" id="action_location" name="action_location" placeholder="city name">-->
        </div>
        <div class="form-group">
            <label class="formtext4" for="funding_description">Description</label>
            <textarea class="form-control col-sm-10  funding-border" rows="5" id="description" name="funding_description" required="required"></textarea>
            <span id="countText">500 characters left</span>
        </div>
        <div class="blank"></div>
        <div class="row">
        <div class="col-md-6">
            <button type="submit" class="btn btn-primary btn-block" name="submit">Save</button>
        </div>
        <div class="col-md-6">
            <a class="btn btn-danger btn-block" onclick="history.back()">Cancel</a>
        </div>
        </div>
    </form>
</div>
</div>
<!-- End of Funding Group -->
<?php include "footer.php";?>
</body>
</html>
