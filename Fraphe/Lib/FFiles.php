<?php
namespace Fraphe\Lib;

abstract class FFiles
{
    public static function createFileNameForId(string $filePrefix, int $idDigits, int $id, int $num, string $ext): string
    {
        $idAsString = strval($id);
        return $filePrefix . (strlen($idAsString) >= $idDigits ? "" : str_repeat("0", $idDigits - strlen($idAsString))) . "$idAsString" . ($num == 0 ? "" : "_$num") . ".$ext";
    }

    /* Uploads file. Requires $_POST["submit"]
     */
    public static function uploadFile($file, string $targetFile, bool $overwrite = true, int $maxSize = 500000)
    {
        $baseFile = basename($targetFile);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        $check = getimagesize($file["tmp_name"]);
        if ($check !== false) {
            //echo "File '$baseFile' is an image - " . $check["mime"] . ".";
        }
        else {
            throw new \Exception("File '$baseFile' is not an image.");
        }

        // Check file size
        if ($file["size"] > $maxSize) {
            throw new \Exception("Sorry, your file '$baseFile' is too large: " . $file["size"] . " > $maxSize.");
        }

        // Allow certain file formats
        if ($imageFileType != "jpg") {
            throw new \Exception("Sorry, only JPG files are allowed.");
        }
        /*if ($imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "png" && $imageFileType != "gif") {
            throw new Exception("Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
        }*/

        // Check if file already exists
        if (file_exists($targetFile)) {
            if ($overwrite) {
                unlink($targetFile);
            }
            else {
                throw new \Exception("Sorry, file '$targetFile' already exists.");
            }
        }

        // Upload file
        if (move_uploaded_file($file["tmp_name"], $targetFile)) {
            //echo "The file '$baseFile' has been uploaded.";
        }
        else {
            throw new \Exception("Sorry, there was an error uploading your file '$targetFile'.");
        }
    }

    /* Uploads file. Requires $_POST["submit"]
     */
    public static function uploadFileXXX(string $inputName, string $targetFile, bool $overwrite = true, int $maxSize = 500000)
    {
        $baseFile = basename($_FILES[$inputName]["name"]);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        if (isset($_POST["submit"])) {
            $check = getimagesize($_FILES[$inputName]["tmp_name"]);
            if ($check !== false) {
                //echo "File '$baseFile' is an image - " . $check["mime"] . ".";
            }
            else {
                throw new \Exception("File '$baseFile' is not an image.");
            }
        }

        // Check file size
        if ($_FILES[$inputName]["size"] > $maxSize) {
            throw new \Exception("Sorry, your file '$baseFile' is too large: " . $_FILES[$inputName]["size"] . " > $maxSize.");
        }

        // Allow certain file formats
        if ($imageFileType != "jpg") {
            throw new \Exception("Sorry, only JPG files are allowed.");
        }
        /*if ($imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "png" && $imageFileType != "gif") {
            throw new Exception("Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
        }*/

        // Check if file already exists
        if (file_exists($targetFile)) {
            if ($overwrite) {
                unlink($targetFile);
            }
            else {
                throw new \Exception("Sorry, file '$targetFile' already exists.");
            }
        }

        // Upload file
        if (move_uploaded_file($_FILES[$inputName]["tmp_name"], $targetFile)) {
            //echo "The file '$baseFile' has been uploaded.";
        }
        else {
            throw new \Exception("Sorry, there was an error uploading your file '$baseFile'.");
        }
    }
}
