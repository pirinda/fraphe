<!DOCTYPE html>
<html>
<body>
    <form action="fileuploadaction.php" method="post" enctype="multipart/form-data">
        Your name:
        <input type="text" name="yourName" required><br>
        Select image to upload:
        <input type="hidden" name="MAX_FILE_SIZE" value="500000" />
        <input type="file" name="fileToUpload" id="fileToUpload" required><br>
        <input type="submit" value="Upload image" name="submit">
    </form>
</body>
</html>
