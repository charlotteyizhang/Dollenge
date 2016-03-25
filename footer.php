<footer class="container-fluid bg-grey text-center">
    <div class="row marginbottom">
        <!--1-->

        <div class = "col-sm-2 wider">
            <p class="header">Fundings</p>
            <hr class="line">
            <?php
                if(@$_SESSION['user_id'] == null){
                    echo "<a class=\"footer-text\" href=\"#top\">Log in to apply a funding</a>";
                }else{
                    echo "<a class=\"footer-text\" href=\"fundingForm.php?user_id=".$_SESSION['user_id']."\">Apply a funding</a>";
                }
            ?>
            <div class="blank10"></div>
            <a class="footer-text" href="fundingPage.php">see all fundings</a>
        </div>
        <!--2-->
        <div class = "col-sm-2 wider">
            <p class="header">Actions</p>
            <hr class="line">
            <a class="footer-text" href="actionPage.php">see all actions</a>
        </div>
        <!--3-->
        <div class = "col-sm-2 wider">
            <p class="header">About us</p>
            <hr class="line">
            <p class="footer-text">about us</p>
            <p class="footer-text">how we work</p>
            <p class="footer-text">F & Q</p>
        </div>
        <!--4-->
        <div class = "col-sm-2 wider">
            <p class="header">members</p>
            <hr class="line">
            <?php
                if(@$_SESSION['user_id'] == null){
                    echo "<a class=\"footer-text\" href=\"#top\">Log in to see my profile</a>
                    <div class=\"blank10\"></div>
                          <a class=\"footer-text\" href=\"#top\">Log in to see my details</a>

                    ";
                }else{
                    echo "<a class=\"footer-text\" href=\"profile.php?user_id=".$_SESSION['user_id']."\">my profile</a>
                    <div class=\"blank10\"></div>
                    <a class=\"footer-text\" href=\"mydetail.php?user_id=".$_SESSION['user_id']."\">my details</a>";
                }
            ?>
        </div>
        <!--5-->
        <div class = "col-sm-2 wider">
            <p class="header">Contect Us</p>
            <hr class="line">
            <a href="mailto:s1462058@sms.ed.ac.uk?cc=s1520365@sms.ed.ac.uk&amp;subject=Hello%20Dollenge&amp;body=Hi%20there%2C%0A" class="footer-text" >E-mail</a>
            <div class="blank10"></div>
            <a class="footer-text">Facebook</a>
            <div class="blank10"></div>
            <a class="footer-text">Twitter</a>
        </div>
    </div>
    <div >
        <a class="notes" href="#">contact us</a>
        <a class="notes" href="#">Trust & Safety</a>
        <a class="notes" href="#">Privacy Policy</a>
        <a class="notes" href="#">Cookie Policy</a>
        <a class="notes" href="#" style="color:white;">2016 © Dollenge Co.</a>
    </div>
</footer>
</body>
</html>