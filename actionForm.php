<!DOCTYPE html>
<?php include "header.php";?><!--page-->
<?php
$_SESSION['userurl'] = $_SERVER['REQUEST_URI'];
?>
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/themes/hot-sneaks/jquery-ui.css" rel="stylesheet">
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js"></script>
 <div class="title bg-action center text-center">
  <div class="blank"></div> 
  <div class="blank"></div> 
    <h2 >Take An Action</h2>
    </div>
<div class="container-fluid bg-action">
 <div class="max-width center">

    <form class="form-horizontal" role="form" action="php/createaction.php" method="post">

    <div class="form-group text-center">
      <label class="formlabel" class="raise" for="funding_id">Raise for </label>
      <?php
      $funding_id = isset($_GET['funding_id'])? $_GET['funding_id'] : null;
      $mysqli = getConnect();
      $stmt = $mysqli->init();
      $query = "SELECT funding_name FROM fundings WHERE funding_id= ?";
      if($stmt = $mysqli->prepare($query)){
        $stmt->bind_param("i", $funding_id);
        $stmt->execute();
        $stmt->bind_result($funding_name);
        $stmt->store_result();
        $stmt->fetch();
      echo "<input type=\"number\" class=\"hidden\" id=\"funding_id\" name=\"funding_id\" value=\"".$funding_id."\" >";
      echo "<p class=\"funding1\">".$funding_name."</p>";

        $stmt->close();
      }
      ?>
    </div>
<div class="form-group">
    <?php
    require "php/manpics.php";
    $url = $_SERVER['REQUEST_URI'];
    $_SESSION['userurl'] = $url;
    //give the url of current page
    echo "<div class=\"text-center center\">
            <a class=\"btn btn-warning\" href=\"php/upload.php?url=".$url."\">Upload photo</a></div>";
    $pic_id=isset($_GET['pic_id'])? $_GET['pic_id'] : null;
    if($pic_id){
        $picStuff = Manpics::picInfoService($pic_id);
        if(count($picStuff) != 0){
           foreach($picStuff as $row){
                echo "<div class=\"container - video text - center\">";
                printf("<a href=\"php/imagelocator.php?thumb=yes&picID=%d\" target=\"_blank\"><img class=\"img-responsive\" src=\"php/imagelocator.php?picID=%s\" /></a>\n", $row["id"], $row["id"]);
                echo "</div>";
            }
        }
        echo"<input type=\"number\" class=\"hidden\" id=\"pic_id\", name=\"pic_id\", value=\"".$pic_id."\">";
    }
    ?>
</div>
  <div class="form-group">
    <label class="formlabel" for="action_name">Action Name</label>
    <input type="text" class="form-control form-control1" id="action_name" required="required" name="action_name" placeholder="type action name">
  </div>
  <div class="form-group">
    <label class="formlabel" for="action_name">Goal</label>
    <input type="number" class="form-control form-control1" required="required" id="action_goal" name="action_goal" placeholder="£">
  </div>
  <div class="row">
        <div class="form-group">
            <div class="col-md-6">
                <label class="formlabel" for="action_start_date">start date</label>
                <input class="form-control form-control1" id="action_start_date" type="text" name="action_start_date"/>
                <script language="JavaScript">
                  $.noConflict();
                   jQuery( document ).ready(function( $ ) {
                      $("#action_start_date").datepicker({firstDay: 1,dateFormat:"yy-mm-dd"});
                      });
                </script>
              </div>
              <div class="col-md-6">
                <label class="formlabel" for="action_expire_date">Expire date</label>
                <input class="form-control form-control1" id="action_expire_date" type="text" name="action_expire_date"/>
                <script language="JavaScript">
                  $.noConflict();
                   jQuery( document ).ready(function( $ ) {
                      $("#action_expire_date").datepicker({firstDay: 1,dateFormat:"yy-mm-dd"});
                      });
                </script>
              </div>
        </div>
  </div>
  <div class="form-group">
    <label class="formlabel" for="action_location">Location</label>
      <input id="location" placeholder="Enter your address"
             onFocus="geolocate()" type="text" class="form-control form-control1 add" required="required">
      <input id="currentPage" type="text" class="hidden" value="action">
      <input type="text" id="action_location" class="hidden" name="action_location" >
      <input type="text" id="action_loaction_name" class="hidden" name="action_location_name">
      <div id="map" class="map"></div>
      <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCEFjo-xtPuao6-6SeDF4kTIzrE7_mjEjY&libraries=places&callback=initMap"
              async defer></script>
   <!-- <input type="text" class="form-control form-control1" id="action_location" name="action_location" placeholder="city name">-->
  </div>
  <div class="form-group">
    <label class="formlabel" for="action_description">Description</label>
    <textarea class="form-control form-control1 col-sm-10" rows="5" id="description" name="action_description" placeholder="describe your action" required="required"></textarea>
      <span id="countText">500 characters left</span>
  </div>
  <div class="row"></div>
        <div class="col-md-6">
            <button type="submit" class="btn btn-primary btn-block" name="submit">Save</button>
        </div>
        <div class="col-md-6">
            <a class="btn btn-danger btn-block" onclick="history.back()">Cancel</a>
        </div>
</form>
</div>
</div>
<!-- End of Funding Group -->
<?php include "footer.php";?>
</body>
</html>
