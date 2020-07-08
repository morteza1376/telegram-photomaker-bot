<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting();

if(!isset($_GET['access_key']) || $_GET['access_key'] != 'ACCESS_KEY') {
	echo json_encode([
		'error access key'
	]);
	exit();
}

if(!isset($_GET['file_src'])) {
	echo json_encode([
		'error photo src'
	]);
	exit();
}


header('content-type: image/jpeg');  

require 'functions/fagd.php';
require 'vendor/autoload.php';
require_once('functions/Persian-txt2img/persian_txt2pic.php');

use GDText\Box;
use GDText\Color;
$gd = new FarsiGD();


// User Information
$fullname = $_GET['name'];

// Font
$font = 'functions/Persian-txt2img/fonts/IRANSans.ttf';


// photo srcs
$templateSRC = 'dist/images/template.png';
$profileSRC = $_GET['file_src'];

// make gd photos
$template = imagecreatefrompng($templateSRC);
$profile = imagecreatefromjpeg($profileSRC);

// get photos size
list($templateWidth, $templateHeight) = getimagesize($templateSRC);
list($profileWidth, $profileHeight) = getimagesize($profileSRC);

// make output photo
$outputPhoto = imagecreatetruecolor($templateWidth, $templateHeight);

$whiteBackground = imagecolorallocate($outputPhoto, 255, 255, 255);
imagefill($outputPhoto,0,0,$whiteBackground);


// make new width and height for profile photo
$smallProfileHeight = 312;
$smallProfileWidth = $profileWidth * $smallProfileHeight / $profileHeight;

// copy profile photo and template photo to output photo
imagecopyresampled($outputPhoto, $profile, 810 - $smallProfileWidth/2, 158, 0, 0, $smallProfileWidth, $smallProfileHeight, $profileWidth, $profileHeight);

imagecopyresampled($outputPhoto, $template, 0, 0, 0, 0, $templateWidth, $templateHeight, $templateWidth, $templateHeight);




$box = new Box($outputPhoto);
$box->setFontFace($font); // http://www.dafont.com/minecraftia.font
$box->setFontSize(35);
$box->setLineHeight(1);
//$box->enableDebug();
$box->setFontColor(new Color(255, 255, 255));
$box->setBox(656, 510, 284, 37);
$box->setTextAlign('center', 'center');
persian_log2vis($fullname);
$box->draw($fullname);



// save output photo
imagejpeg($outputPhoto);
imagedestroy($outputPhoto);
