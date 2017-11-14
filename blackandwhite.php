<?php
session_start();
require 'vendor/autoload.php';
use  Aws\Rds\RdsClient;
$client = RdsClient::factory(array(
        'version' => 'latest',
        'region' => 'us-west-2'
));
$s3 = new Aws\S3\S3Client(['version' => 'latest', 'region' => 'us-west-2']);
$result = $client->describeDBInstances(array(
        'DBInstanceIdentifier' => 'mp1-rds',
));
$endpoint = "";
$url = "";

foreach($result['DBInstances'] as $ep)
        {
        $url = $ep['Endpoint']["Address"];
break;
        foreach($ep['Endpoint'] as $endpointurl)
                {
                $url = $endpointurl["Address"];
                break;
                }
        }
$link = mysqli_connect($url, "admin", "password", "school");

if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

$sql = "CREATE TABLE records
(
id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
email VARCHAR(32),
phone VARCHAR(32),
s3_raw_url VARCHAR(512),
s3_finished_url VARCHAR(512),
status INT(1),
receipt VARCHAR(256)
)";
$create_tbl = $link->query($sql);

$name = $_FILES["fileToUpload"]["name"];
$tmp = $_FILES['fileToUpload']['tmp_name'];
$resultput = $s3->putObject(array(
  'Bucket' => 'mp1-pre-processed',
  'Key' => $name,
  'SourceFile' => $tmp,
  'region' => 'us-west-2',
  'ACL' => 'public-read'
));

$imageurl = $resultput['ObjectURL'];
$_SESSION['s3-raw'] = $imageurl;
$rawurl = $imageurl;
$im = ''; // replace this path with $rawurl
$checkimgformat = substr($rawurl, -3);
if ($checkimgformat == 'png' || $checkimgformat == 'PNG')
{
$im = @imagecreatefrompng($rawurl);
}
else
{
$im = @imagecreatefromjpeg($rawurl);
}
$lstoccuranceofslash = strripos($rawurl, "/") + 1;
$imagename = substr($rawurl, $lstoccuranceofslash, strlen($rawurl));
ImageFilter($im, IMG_FILTER_GRAYSCALE);
$tmp = "/tmp/$imagename";
imagepng($im, $tmp);
imagedestroy($im);
$resultfinalput = $s3->putObject(array(
    'Bucket' => 'mp1-post-processed',
    'Key' => $imagename,
    'SourceFile' => $tmp,
    'region' => 'us-west-2',
    'ACL' => 'public-read'
));
$finishedimageurl = $resultfinalput['ObjectURL'];
if (!($stmt2 = $link->prepare("INSERT INTO records (id,email,phone,s3_raw_url,s3_finished_url,status,receipt) VALUES (NULL,?, ?, ?, ?, ?, ?)")))
        {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
$stmt = $link->prepare("INSERT INTO records (email,phone,s3_raw_url,s3_finished_url,status,receipt) VALUES (?, ?, ?, ?, 1, ?)");
$stmt->bind_param("sssss", $email, $phone, $s3_raw_url, $s3_finished_url, $receipt);
$email = $_SESSION['emailid'];
$phone = "3313079248";
$s3_raw_url = $imageurl;
$s3_finished_url = $finishedimageurl;
$receipt = md5($imageurl);
$stmt->execute();
$stmt->close();
$link->close();
$_SESSION['receipt'] = $receipt;
?>

<html>
<head>
<title>Uploaded Image</title>
<style>
body {
    margin: 0;
}
ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    width: 25%;
    background-color: #f1f1f1;
    position: fixed;
    height: 100%;
    overflow: auto;
}
li a {
    display: block;
    color: #000;
    padding: 8px 16px;
    text-decoration: none;
    border-bottom: 1px solid #555;
}
li a.active {
    background-color: #4CAF50;
    color: white;
}
li a:hover:not(.active) {
    background-color: #555;
    color: white;
}
.button {
    background-color: #4CAF50;
    border: none;
    color: white;
    padding: 15px 32px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    cursor: pointer;
}
.buttonreturn{
    background-color: #4CAF50;
    color: white;
    padding: 14px 25px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
}
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymo
forcloudmp1  gallery.php  upload.php  blackandwhite.php  index.php
us">
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</head>
<body>

<nav class="navbar navbar-inverse bg-inverse" >
<a class="navbar-brand" href="upload.php" style="color: black; background-color : white;"> << Back </a>
 </nav>

<div style="margin-left:25%;padding:1px 16px;height:1000px;">

<form action="" method='post' enctype="multipart/form-data">
<h1>Below Shown Image is Successfully Uploaded</h1>
<h3>
<img src="<?php
echo $imageurl; ?>" height="200" width="200">
<br />
<br />

</form>
</div>
</body>
</html>
