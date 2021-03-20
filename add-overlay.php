<?php
 
// first you have to get both input files in separate variables
$video = $_FILES["video"]["name"];
$image = $_FILES["image"]["name"];
 
// then you have to resize the selected image to lower resolution
$command = "/usr/bin/ffmpeg -i " . $image . " -s 128x128 output.jpeg";
 
// execute that command
if(system($command)){
	echo "Overlay has been resized";
}

 

 
// both input files has been selected
$command = "/usr/bin/ffmpeg -i " . $video . " -i output.jpeg";
 
// now apply the filter to select both files
// it must enclose in double quotes
// [0:v] means first input which is video
// [1:v] means second input which is resized image
$command .= " -filter_complex \"[0:v][1:v]";
 
// now we need to tell the position of overlay in video
$command .= " overlay=80:50\""; // closing double quotes
 
// save in a separate output file
$command .= " -c:a copy output.mp4";
 
// execute the command
if(system($command)){
	echo "Overlay has been added";
}
 

 
?>