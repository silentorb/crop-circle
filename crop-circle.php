<?php

include 'config.inc';
ini_set("memory_limit", "80M");

$template = $_GET['template'];

if (!isset($templates[$template])) {
  header("Status: 404 Not Found");
  echo "<br/>Invalid media template: $template";
  return 0;
}

$file = $_GET['file'];
$orig_file = "$orig_dir/$file";

if (!file_exists($orig_file)) {
  header("Status: 404 Not Found");
  echo "<br/>ORIGFILE=$orig_file";
  return 0;
}

$save_path = "$cache_folder/$template/$file";
$save_dir = dirname($save_path);

$new_width = $templates[$template]['width'];
$new_height = $templates[$template]['height'];
$image = new Imagick($orig_file);

//$ext = pathinfo($orig_file, PATHINFO_EXTENSION);
//if ($ext == 'gif') {
//  $image = $image->coalesceImages();
//}

list($orig_width, $orig_height, $type, $attr) = getimagesize($orig_file);

$desired_aspect = $new_width / $new_height;
$orig_aspect = $orig_width / $orig_height;

if ($desired_aspect > $orig_aspect) {
  $trim = $orig_height - ($orig_width / $desired_aspect);
  $image->cropImage($orig_width, $orig_height - $trim, 0, $trim / 2);
  error_log("HEIGHT TRIM $trim");
} else {
  $trim = $orig_width - ($orig_height * $desired_aspect);
  $image->cropImage($orig_width - $trim, $orig_height, $trim / 2, 0);
}
$image->resizeImage($new_width, $new_height, imagick::FILTER_LANCZOS, 1);

$image->writeImage($save_path);
header('Content-Type: ' . exif_imagetype($save_path));
echo file_get_contents($save_path);

return true;
