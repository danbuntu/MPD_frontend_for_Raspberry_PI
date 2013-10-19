<?php
include('config.php');

$imageDir = $musicDir;
$image = $_GET['name'];
if($image !== false and file_exists($imageDir . $image))
{
    header('Content-Type: image/jpeg');
    readfile($imageDir . $image);
    exit;
} else {
    header('Content-Type: image/jpeg');
    readfile('blah.jpg');
}
header("HTTP/1.0 404 Not Found");

?>