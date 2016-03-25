<!DOCTYPE html>
<?php include "header.php";?><!--page-->
<div class="title bg-action center text-center">
    <div class="blank"></div> 
  <div class="blank"></div>
    <h2 >Donate Now</h2>
</div>
<div class="container-fluid bg-action">
     <div class="max-width center">
    <form class="form-horizontal max-width" role="form" action="php/createdonation.php" method="post">
        <div class="form-group">
            <!--php get funding id and action name-->
    <?php
        $temp_action_id = $_GET['action_id'];
        //get the funding information from database
        $mysqli = getConnect();
        /* create a prepared statement */
        $stmt =  $mysqli->stmt_init();
        $query = "SELECT action_name, funding_id FROM actions WHERE action_id = ?";
        if ($stmt->prepare($query)) {
            $stmt->bind_param("i", $temp_action_id);
            /* execute query */
            $stmt->execute();
            $stmt->bind_result($action_name, $funding_id);
            /* store result */
            $stmt->store_result();
            while($stmt->fetch()){
                echo "<p class=\"text-center\">Donate for <strong class=\"funding1\">".$action_name."</strong></p>\n";
                echo "<input type=\"number\" id=\"action_id\" class=\"hidden\" name=\"action_id\" value=\"".$temp_action_id."\">";
                echo "<input type=\"text\" id=\"action_name\" class=\"hidden\" name=\"action_name\" value=\"".$action_name."\">";
                echo "<input type=\"number\" id=\"funding_id\" class=\"hidden\" name=\"funding_id\" value=\"".$funding_id."\">";
            }
            $stmt->close();
        }
    ?>
            <label class="formlabel" for="donate_total">Donate:</label>
            <input type="number" class="form-control form-control1" id="donate_total" name="donate_total" placeholder="£">
        </div>
        <button type="submit" class="btn btn-primary btn-block" name="submit">Donate</button>
        <a class="btn btn-danger btn-block" onclick="history.back()">Cancel</a>
    </form>
</div>
</div>
<!-- End of Funding Group -->
<?php include "footer.php";?>
</body>
</html>
