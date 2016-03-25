<?php
// Notice there is no HTML on this page: no headers, nothing.  This is ABSOLUTELY CRUCIAL to the page working properly,
// because the headers are written by the code below and must not exist before that.

// We need to include the manpics material here
require("manpics.php");

// imagelocator.php  -- adapted from code at http://www.kobashicomputing.com/serving-images-outside-document-root-via-php
// But in this version  simpler, because we work from an image ID so don't need to check the validity of the filename etc.
// Given an image ID on the URL, it will fetch and display the corresponding file from above the web root (location defined
// by Manpics::$imagestore in manpics).  If given "thumb" on the URL, it retrieves the corresponding thumbnail.

// This defines a class, although we aren't going to create any objects from it.  It helps to keep the code organised, all the same.
class ImageLocator {
    // Defining an array that tells us which mime file types are allowed to be uploaded. This wil tell us the type for a given extension.
    static $allowedMimeTypes = array("jpg" => "image/jpeg",
        "png" => "image/png",
        "gif" => "image/gif");

    function identifyImageFile($id) {
        $picStuff = Manpics::picInfoService($id);	// Get the data about image with this ID
        $row = $picStuff[0];
        return $row["picfile"];
    }

    // Function to send a mime header appropriate to the type of image file: the builtin PHP function header() does this.
    static function SendImageHeader($imagepath) {
        $pathParts = pathinfo($imagepath);
        $extension = $pathParts["extension"];
        $mimeType = self::$allowedMimeTypes[$extension];
       header("Content-type: " . $mimeType);
    }

    // The PHP builtin readfile() just gets the content of the file and outputs it, so this will be pure image data, but the
    // header will ensure that it is seen as the right type of file when the browser includes it as an image.
    static function SendImage($imagefile) {
        $imagepath = Manpics::$imagestore . "/" . $imagefile;
        self::SendImageHeader($imagefile);
        readfile($imagepath);
    }
}

// This gets executed via the img src (typically -- or any URL)
// IF "thumb" is a query parameter, then show the thumbnail file instead.
if (isset($_GET["picID"])) {
    $image = Imagelocator::identifyImageFile($_GET["picID"]);
    if (isset($_GET["thumb"])) {
        ImageLocator::SendImage(Manpics::thumbFile($image));
    }
    else {
        ImageLocator::SendImage($image);
    }
}
?>