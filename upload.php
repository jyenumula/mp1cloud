<html>
<head>
<title>Uploaded Image</title>
<style>
body {
    margin: 0;
}

</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymo
mp1cloud  gallery.php  upload.php  blackandwhite.php  index.php
us">
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</head>
<body>
<nav class="navbar navbar-inverse bg-inverse" >
<a class="navbar-brand" href="index.php" style="color: black; background-color : white;"> << Back </a>
 </nav>

<div class="jumbotron">
<form action="blackandwhite.php" method="post" enctype="multipart/form-data">
    <h3>Select File to Upload</h3>
<input type="file" name="fileToUpload" id="fileToUpload">
    <input class="btn btn-success" type="submit" value="Upload Image" name="submit">

</form>
</div>
</body>
</html>
