<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Upload image form</title>
</head>

<body>
<div class="container-fluid">
<?php
if(isset($_GET['url'])){
    $url = $_GET['url'];
}else{
    $url = $_SESSION['userurl'];
}
$save_to_users = @$_GET['savetouser'];
if($save_to_users == 'yes'){
    echo "<form action=\"upload.php?savetouser=yes&url=".$url."\" method=\"post\" enctype=\"multipart/form-data\">";
}else{
    echo "<form action=\"upload.php?url=".$url."\" method=\"post\" enctype=\"multipart/form-data\">";
}

?>
    <p>Choose your file (<1 MB)</p>
    <label for="file">Select a file:</label>
    <input type="file"  name="file" id="file" />
    <br />
    <button type="submit" class="btn btn-primary btn-block" name="submit" />Submit</button>
</form>
</div>
<?php
include "connect.php";
// We have to include this: "require" will cause a fatal error if it isn't there
require("manpics.php");
$mysqli = getConnect();
if(isset($_GET['url'])){
    $url = $_GET['url'];
}else{
    $url = $_SESSION['userurl'];
}

if (isset($_POST["submit"])) {
    echo "<h3>Thanks for your upload ...</h3>\n";
    // This is calling the function uploadPic that belongs to the class Manpics, as defined in manpics.php
    $id = Manpics::uploadPic($_FILES["file"]);
    echo $url;
    $user = $_SESSION['user_id'];
    if($id){
        echo $save_to_users;
        if($save_to_users == 'yes'){
            $update = "UPDATE users SET pic_id = ? WHERE user_id = ?";
            $stmt = $mysqli->stmt_init();
            if($stmt->prepare($update)){
                $stmt->bind_param("ii", $id, $user);
                $stmt->execute();
            }
        }
        echo "<meta http-equiv=\"refresh\" content=\"0.5;url=".$url."& pic_id=".$id."\">";
    }
}
?>
</body>
</html>