<!DOCTYPE html>
<?php include "header.php";?><!--page-->
<div class="container-fluid bg-pink">
     <div class="max-width center">
        <div class="blank"></div> 
            <!--php get funding id and action name-->
    <?php
        $user_id = isset($_SESSION['user_id'])? $_SESSION['user_id'] : null;
        $mysqli = getConnect();
        $stmt = $mysqli->stmt_init();
        $query = "SELECT user_id, user_name, user_description, user_donation, user_raise FROM users WHERE user_id = ?";
        if($stmt->prepare($query)){
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->bind_result($user_id,$user_name, $user_description, $user_donation, $user_raise);
            $stmt->store_result();
            while($stmt->fetch()){
                echo "<p class=\"profile-large text-center\">Hi, ".$user_name."</p>\n
                <div class=\"blank\"></div> 
                    <p class=\"text-center\">If you have any suggestion, pleace contact us through the following ways.</p>\n";
            }
            $stmt->close();
        }
    ?>
    <div class="row">
     <div class="blank"></div> 
        <div class="col-md-2 col-md-offset-2">
         <a href="mailto:s1462058@sms.ed.ac.uk?cc=s1520365@sms.ed.ac.uk&amp;subject=Hello%20Dollenge&amp;body=Hi%20there%2C%0A"><img src="img/mail.svg" class="img-responsive" alt="Image"></a>
        </div>
        <div class="col-md-2 col-md-offset-1">
         <a href="#"><img src="img/facebook.svg" class="img-responsive" alt="Image"></a>
        </div>
        <div class="col-md-2 col-md-offset-1">
         <a href="#"><img src="img/twitter.svg" class="img-responsive" alt="Image"></a>
        </div>
    </div>
</div>
</div>
<!-- End of Funding Group -->
<?php include "footer.php";?>
</body>
</html>
