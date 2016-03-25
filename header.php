<?php
include ("php/login.php");
include ("php/public.php");
?>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="initial-scale=1.0">
  <meta name="keywords" content="donation, charity, hero, Dollenge">
  <title>Dollenge | <?php echo getTitle();?></title>
  <link rel="SHORTCUT ICON" href="img/favicon.ico">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  <script type="text/javascript" src="js/bootstrap.min.js"></script>
  <script type="text/javascript" src="js/myjs.js"></script>
  <script type="text/javascript" src="js/ajax.js"></script> <!--Loading ajax.js-->
  <link rel="stylesheet" href="css/main.css">

</head>
<body>
<nav class="navbar navbar-default">
  <div class="container-fluid maxWidth">
    <div class="navbar-header center">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a href="index.php"><img class="logo" src="img/header_logo.png"></a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
        <li><a href="fundingPage.php">Fundings</a></li>
        <li><a href="actionPage.php">Actions</a></li>
        <li><a href="about.php">About Us</a></li>
      </ul>
      <form class="navbar-form navbar-left" role="search">
        <div class="input-group">
        <div class="input-group-btn">
          <select id="table" class="form-control choose">
            <option id="action" value="action">action</option>
            <option id="funding " value="funding">Funding</option>
          </select>
          <input type="text" id="search" name="search" class="form-control search" placeholder="Search">
        </div><!-- /btn-group -->
        
      </div>
    </form>
    <!-- /input-group -->
      
       <?php
      /**--logged in php **/
       $_SESSION['userurl'] = $_SERVER['REQUEST_URI'];
      $login = getConnect();
      // if( $_SESSION["name"] )
      if( isset($_SESSION["user_name"]) && $_SESSION["user_name"] )
      {
        echo " <div class=\"nav navbar-right\">
          <a id=\"userBtn\" class=\"loginBtn\" href=\"#\">".$_SESSION['user_name']."
          </a>
           <div id=\"userInfo\"class=\"userInfo navForm hide\">
           <a class=\"btn btn-block\" href=\"profile.php\">profile</a>
           <a class=\"btn btn-block\" href=\"php/logout.php\">Logout</a>
        </div>
      </div>";
      }else{
        echo"
      <div class=\"nav navbar-right\">
      <div class=\"notice row\">
        <span id=\"userText\"></span>
       </div>
        <a id=\"loginBtn\" class=\"loginBtn\" href=\"#\"> Login</a>
        <!--Login form-->
        <div id=\"loginForm\"class=\"loginForm navForm hide\">
          <form action = \"php/login.php?url=".$_SESSION['userurl']."\" method=\"post\">
            <input type=\"text\" class=\"form-control marginbottom\" name=\"user_name\" placeholder=\"User Name\" required=\"required\">
            <input type=\"password\" class=\"form-control marginbottom\" name=\"password\" placeholder=\"Password\" required=\"required\">
            <button type=\"submit\" class=\"btn btn-primary btn-block\" name=\"login\">Login</button>
          </form>
          <a href=\"#\">Forget password?</a>
        </div>
        <!-- register form-->
        <a id=\"registerBtn\" class=\"registerBtn\" href=\"#\"> Register</a>
        <div>
          <span id=\"userText\"></span><button type=\"button\" class=\"close\" id=\"close\"></button>
        </div>
        <div id=\"registerForm\" class=\"registerForm navForm hide\">
          <form action = \"php/register.php?url=".$_SESSION['userurl']."\" method=\"post\">
            <input type=\"text\" class=\"form-control marginbottom\" name=\"user_name\" onblur=\"valiate(this.value);\"placeholder=\"User Name\" required=\"required\">
            <input type=\"password\" class=\"form-control marginbottom\" name=\"password\" placeholder=\"Password\" required=\"required\">
            <input type=\"password\" class=\"form-control marginbottom\" name=\"rePw\" placeholder=\"Confirm Password\" required=\"required\">
            <input type=\"email\" class=\"form-control marginbottom\" name=\"email\"  placeholder=\"Email\" required=\"required\">
            <button type=\"submit\" class=\"btn btn-primary btn-block\" name=\"register\">Register</button>
          </form>
        </div>
      </div>
    ";
        }
       $stmt = $login->stmt_init();
       $undergo = 0;
       $query = "SELECT action_id, action_total, action_goal, action_expire_date FROM actions WHERE action_status =?";
       if($stmt->prepare($query)){
         $stmt->bind_param("i", $undergo);
         $stmt->execute();
         $stmt->bind_result($action_id, $action_total, $action_goal, $action_expire_date);
         $stmt->store_result();
         while($stmt->fetch()){
           $time_left = getTime(null, $action_expire_date);
             if($time_left<0){
               if($action_total>=$action_goal) {
                 echo "<script>window.open('modifyStatus.php?completed=yes&action_id=" . $action_id . "\"','_self')</script>";
                 //echo "<meta http-equiv=\"refresh\" content=\"0.5;url=modifyStatus.php?completed=yes&action_id=" . $action_id . "\">";
               }else{
                 echo "<script>window.open('modifyStatus.php?completed=no&action_id=" . $action_id . "\"','_self')</script>";
                 //echo "<meta http-equiv=\"refresh\" content=\"0.5;url=modifyStatus.php?completed=no&action_id=" . $action_id . "\">";
               }
             }
           }
         $stmt->close();
       }else{
         echo "Error: " . $login . "<br>" . mysqli_error($conn);
       }
       // update fundings
       $stmt = $login->stmt_init();
       $query = "SELECT funding_id, funding_expire_date FROM fundings WHERE funding_status = ?";
       if($stmt->prepare($query)){
         $stmt->bind_param("i", $undergo);
         $stmt->execute();
         $stmt->bind_result($funding_id, $funding_expire_date);
         $stmt->store_result();
         while($stmt->fetch()){
           $stmt2 = $login->stmt_init();
           if(getTime(null,$funding_expire_date)<0){
             $update = "UPDATE fundings SET funding_status = 1 WHERE funding_id = ?";
             if($stmt2->prepare($update)){
               $stmt2->bind_param("i", $funding_id);
               $stmt2->execute();
               $stmt2->close();
             }else{
               echo "Error: " . $login . "<br>" . mysqli_error($conn);
             }
           }
         }
         $stmt->close();
       }
       $login->close();
      ?>

  </div>
</nav>