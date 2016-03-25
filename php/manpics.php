<?php
/**
 * Created by "CharlotteZhang".
 * User: user
 * Date: 2016/3/11
 * Time: 15:44
 */

//referred to DWD notebook
class Manpics
{
    static $imagestore = "../../../upload";
    static $pictable = "picdata";
    static $thumbsize = 280;
    static $connect;
    static $connectionInitialised = false;

    // connectDB just cononects to the database and then the connection can be used by other functions.
    // If the connection already exists, do nothing; so it's always safe to call this function.
    private function connectDB() {
        if (self::$connectionInitialised == true)
            return;
        $mysqli = new mysqli("localhost", "s1520365", "mYD5YSWCnx","s1520365");
        if ($mysqli->connect_errno) {
            printf("Connect failed: %s\n", $mysqli->connect_error);
            exit();
        }
        self::$connectionInitialised=true;
        self::$connect=$mysqli;
    }

    // Function that stores information about an image into the DB,
    // (at the same time creating an ID for it, which we'll use later).
    private function storePic($filename,$pictype) {
        self::connectDB();
        $q = "INSERT INTO ".self::$pictable."(picfile,pictype) VALUES(?,?)";
        if ($stmt = self::$connect->prepare($q)) {
            $stmt->bind_param("ss", $filename,$pictype);
            $stmt->execute();
            $pic_id = self::$connect->insert_id;
            $stmt->close();
        }
        return $pic_id;
    }

    // Create the name of the thumbnail file for the given image
    // -- just by adding "thumb-" to the start, but bearing in mind that it
    // will always be a .jpg file.
    public function thumbFile($picfile) {
        return "thumb-".pathinfo($picfile,PATHINFO_FILENAME).".jpg";
    }

    // This creates the actual thumbnail by resampling the image file to the size given by the thumbsize variable.
    // We can easily change this.  PHP has very rich image processing functionality; this is a simple example.
    // Based on code from PHP manual for imagecopyresampled()
    private function createThumbnail($filename, $thumbfile, $type) {
        // Set a maximum height and width
        $width = self::$thumbsize;
        $height = self::$thumbsize;

        // Get new dimensions
        list($width_orig, $height_orig) = getimagesize($filename);

        $ratio_orig = $width_orig/$height_orig;

        if ($width/$height > $ratio_orig) {
            $width = $height*$ratio_orig;
        } else {
            $height = $width/$ratio_orig;
        }

        // Resample
        $image_p = imagecreatetruecolor($width, $height);
        switch ($type) {
            case "jpeg":
                $image = imagecreatefromjpeg($filename);
                break;
            case "jpg":
                $image = imagecreatefromjpeg($filename);
                break;
            case "png":
                $image = imagecreatefrompng($filename);
                break;
            case "gif":
                $image = imagecreatefromgif($filename);
                break;
        }
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);

        // Output
        // Notice this is always a jpeg image.  We could also have made others, but this seems OK.
        imagejpeg($image_p, $thumbfile);
    }

    // Handle the uploaded image.  Note that the $picfile parameter, which came from the form variable, is a complex object.
    // Check that it's the right type of file, not too big, etc.
    // The uploaded image is already in a temporary file, which we just copy.  If the target file already exists, I use the
    // temporary filename to generate a unique name for it.  The filenames for the files are not important, because we will
    // track them in the DB, so we always have an ID as well.
    // Based on example uploading code from w3cschools
    public function uploadPic($picfile) {
        if ((($picfile["type"] == "image/gif")
                || ($picfile["type"] == "image/png")
                || ($picfile["type"] == "image/jpg")
                || ($picfile["type"] == "image/jpeg")
                || ($picfile["type"] == "image/pjpeg"))
            && ($picfile["size"] < 1000000))  {
            if ($picfile["error"] > 0) {
                echo "Return Code: " . $picfile["error"] . "<br />";
            }
            else {
                echo "Upload: " . $picfile["name"] . "<br />";
                echo "Type: " . $picfile["type"] . "<br />";
                echo "Size: " . ($picfile["size"] / 1024) . " Kb<br />";
                echo "Temp file: " . $picfile["tmp_name"] . "<br />";
                if (file_exists(self::$imagestore . "/" . $picfile["name"])) {
                    $newpicfile = basename($picfile["tmp_name"]).$picfile["name"];
                }
                else {
                    $newpicfile = $picfile["name"];
                }

                $newpicfile = strtolower($newpicfile);  // map to lower case, avoiding problems later with recognising uppercase file extensions
                $dest = self::$imagestore . "/" . $newpicfile;
                move_uploaded_file($picfile["tmp_name"], $dest);
                echo "Stored in: " . $dest . "<br />\n";
                $pic_id = self::storePic($newpicfile,$picfile["type"]);
                self::createThumbnail($dest, self::$imagestore . "/" .self::thumbFile($newpicfile), basename($picfile["type"]));
                echo "Thumbnail: " . self::thumbFile($newpicfile) . "<br />\n";
                return $pic_id;
            }
        }
        else {
            echo "Invalid file";
            return null;
        }
    }

    // This just returns all the data we have about images in the DB, as a resultset object.
    // If given no argument, it uses the default argument, 0.  In this case it returns data about all images.
    // If given an image ID as argument (there can be no image with ID 0), it returns data only about that image.
    // Data is returned as an array, containing another array for each row of data in the resultset.
    public function picInfoService($picID=0) {
        self::connectDB();
        if ($picID == 0) {
            if ($result = self::$connect->query("SELECT id, picfile, pictype FROM ". self::$pictable))   {
                // We are now constructing an array to return, containing the result rows as associative arrays
                $ret = array();
                while ($data = $result->fetch_assoc()) {
                    $ret[] = $data;
                }
                return $ret;
            }
        }
        else {
            $id = null;
            $picfile = null;
            $pictype = null;
            if ($stmt = self::$connect->prepare("SELECT id, picfile, pictype FROM ". self::$pictable . " WHERE id=?")) {
                $stmt->bind_param("i", $picID);
                $stmt->execute();
                $stmt->bind_result($id,$picfile,$pictype);
                $stmt->fetch();  // There should be only one result: no loop here
                $arr = array("id"=>$id, "picfile"=>$picfile, "pictype"=>$pictype);
                $ret[] = $arr;
                return $ret;  // An array just like the one in the other condition, but with only one row in it.
            }
        }
    }


    // Function to delete image, given its ID: remove the file and thumbnail, then delete its data from the DB.
    public function deletePicService($picID) {
        $picfile = null;
        self::connectDB();
        if ($stmt = self::$connect->prepare("SELECT picfile FROM ". self::$pictable . " WHERE id=?")) {
            $stmt->bind_param("i", $picID);
            $stmt->execute();
            $stmt->bind_result($picfile);
            $stmt->fetch();  // There should be only one result: no loop here
            unlink(self::$imagestore . "/" . $picfile);
            unlink(self::$imagestore . "/" . self::thumbFile($picfile));
            $stmt->close();
        }
        if ($stmt = self::$connect->prepare("DELETE FROM ". self::$pictable . " WHERE id=?")) {
            $stmt->bind_param("i", $picID);
            $stmt->execute();
            $stmt->close();
        }
        return "Deleted";
    }
}
?>