<!DOCTYPE html>
<?php include "header.php";?>
<div class="container-fluid bg-detail">
    <div class="detailWidch center">
        <div class="blank"></div>
        <div class="row text-center">
                <div class="col-md-4">
                <div class="blank"></div>
                <div class="blank"></div>
                    <?php
                    require  "php/manpics.php";
                    $user_id = isset($_SESSION['user_id'])? $_SESSION['user_id'] : null;
                    $mysqli = getConnect();
                    $stmt = $mysqli->stmt_init();
                    $query = "SELECT user_name,user_email,user_location,user_tel,user_description,pic_id FROM users WHERE user_id=".$user_id;
                    if($stmt= $mysqli->prepare($query)) {
                        $stmt->execute();
                        $stmt->bind_result($user_name, $user_email, $user_location, $user_tel, $user_description, $pic_id);
                        $stmt->store_result();
                        while ($stmt->fetch()) {
                            if ($pic_id) {
                                $picStuff = Manpics::picInfoService($pic_id);
                                if (count($picStuff) != 0) {
                                    foreach ($picStuff as $row) {
                                        echo "<div class=\"container - video text - center\">";
                                        printf("<a href=\"php/imagelocator.php?picID=%d\" target=\"_blank\"><img class=\"myphoto\" src=\"php/imagelocator.php?thumb=yes&picID=%s\" /></a>\n", $row["id"], $row["id"]);
                                        echo "</div>";
                                    }
                                }
                            } else {
                                echo "<img src=\"img/profile.png\" class=\"cover\">";
                            }
                            $url = $_SERVER['REQUEST_URI'];
                            $_SESSION['userurl'] = $url;
                            //give the url of current page
                            echo "<div style=\"clear:both;\"></div>
                            <a class=\"btn btn-warning UPic\" href=\"php/upload.php?savetouser=yes&url=" . $url . "\">Upload photo</a></div>";
                            $pic_id = isset($_GET['pic_id']) ? $_GET['pic_id'] : null;
                            if ($pic_id) {
                                echo "<input type=\"number\" class=\"hidden\" id=\"pic_id\", name=\"pic_id\", value=\"" . $pic_id . "\">";
                            }
                            echo "
                            <div class=\"col-md-8 text-left\">
                                <p class=\"hero\">Hero</p>
                                <p class=\"name\">".$user_name."</p>
                                <div class=\"row\">
                                <form class=\"form-horizontal\" role=\"form\" method=\"post\" action=\"php/updateDetails.php?url=".$url."\">
                                    <div class=\"form-group\">
                                        <label for=\"currentPw\" class=\"col-md-4 control-label formtext\">Current Password</label>
                                        <div class=\"col-md-8\">
                                          <input type=\"password\" class=\"form-control password2\" id=\"currentPw\" name=\"currentPw\" placeholder=\"xxxxxxxx\">
                                        </div>
                                    </div>
                                    <div class=\"form-group\">
                                        <label for=\"newPw\" class=\"col-md-4 control-label formtext\">New Password</label>
                                        <div class=\"col-md-8\">
                                          <input type=\"password\" class=\"form-control password2\" id=\"newPw\" name= \"newPw\" placeholder=\"xxxxxxxx\">
                                        </div>
                                    </div>
                                    <div class=\"form-group\">
                                        <label for=\"confirmPw\" class=\"col-md-4 control-label formtext\">Confirm Password</label>
                                        <div class=\"col-md-8\">
                                          <input type=\"password\" class=\"form-control password2\" id=\"confirmPw\" name=\"confirmPw\" placeholder=\"xxxxxxxx\">
                                          <button type=\"submit\" class=\"btn btn-info pwBtn\" name=\"changePw\">Change Password</button>
                                        </div>
                                    </div>
                                    <p id=\"warningfield\" style=\"color:red;\"></p>
                                    
                                </form>
                                </div>
                            </div>
                    </div>";

                   echo" <div class=\"blank\"></div>
                    <form class=\"form-horizontal row\" role=\"form\" method=\"post\" action=\"php/updateDetails.php?url=".$url."\">
                        <div class=\"form-group\">
                        <label for=\"user_email\" class=\"col-md-2 control-label formtext2\">E-mail</label>
                            <div class=\"col-md-10\">";
                      if($user_email){
                          echo "
                              <input type=\"text\" class=\"form-control password3\" id=\"user_email\" name=\"user_email\" value=\"".$user_email."\">
                            ";
                      }else{
                          echo"
                              <input type=\"text\" class=\"form-control password3\" id=\"user_email\" name=\"user_email\" placeholder=\"type your e-mail\">
                              ";
                      }
                      echo"
                           </div>
                        </div>
                        <div class=\"form-group\">
                            <label for=\"user_tel\" class=\"col-md-2 control-label formtext2\">Phone</label>
                            <div class=\"col-md-10\">";
                       if($user_tel){
                           echo "<input type=\"number\" class=\"form-control password3\" id=\"user_tel\" name=\"user_tel\" value=\"".$user_tel."\">";
                       }else{
                           echo "<input type=\"number\" class=\"form-control password3\" id=\"user_tel\" name=\"user_tel\" placeholder=\"type your phone nunber\">";
                       }
                       echo"
                            </div>
                        </div>
                        <div class=\"form-group\">
                            <label class=\"col-md-2 control-label formtext2\" for=\"user_location\">Address</label>
                            <div class=\"col-md-10\">";
                            if($user_location){
                                echo"<input type=\"text\" class=\"form-control password3\" id=\"user_location\" name=\"user_location\" value=\"".$user_location."\"> ";

                            }else{
                                echo"<input type=\"text\" class=\"form-control password3\" id=\"user_location\" name=\"user_location\" placeholder=\"type your address\">";
                            }

                           echo "
                        </div>
                        </div>
                    <div class=\"blank\"></div>
                        <div class=\"form-group\">
                            <label for=\"description\" class=\"col-md-2 control-label formtext3\">About Me</label>
                            <div class=\"col-md-10\">";
                            if($user_description){
                                echo "<textarea class=\"form-control password4\"  id=\"description\" rows=\"5\" name=\"user_description\">".$user_description."</textarea>";
                            }else{
                                echo "<textarea class=\"form-control password4\" id=\"description\" rows=\"5\" name=\"user_description\" placeholder=\"type something about you\"></textarea>";
                            }
                            echo"
                            <span id=\"countText\">500 characters left</span>
                           </div>
                            <div class=\"row\">
                            <div class=\"col-md-6\">
                            <button type=\"submit\" class=\"btn btn-success pwBtn\" name=\"saveDetails\">Save details</button>
                            </div>
                            <div class=\"col-md-6\">
                                <a class=\"btn btn-danger btn-block pwBtn\" href=\"profile.php\"'>Back to my profile</a>
                            </div>
                            </div>
                            </div>
                        </div>
                </form>
  </div>
                            ";
                        }
                        $stmt->close();
                    }
                    $mysqli->close();
                    ?>

    </div>
</div>
<!-- End of Action Group -->
<?php include "footer.php"?>