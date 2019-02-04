<?php
//ini_set("memory_limit","10485760");

function ak_img_resize($target, $newcopy, $w, $h, $ext){
	list($w_orig, $h_orig) = getimagesize($target);
	$scale_ratio = $w_orig/$h_orig;
	if(($w / $h) > $scale_ratio){
		$w = $h * $scale_ratio;
	} else {
		$h = $w / $scale_ratio;
	}
	$img = null;
	if($ext == "gif" || $ext == "GIF"){
		$img = imagecreatefromgif($target);
	} else if($ext == "png" || $ext == "PNG"){
		$img = imagecreatefrompng($target);
	} else {
		ini_set('memory_limit', '-1');
		$img = imagecreatefromjpeg($target);
	}
	$tci = imagecreatetruecolor($w, $h);
	imagecopyresampled($tci, $img, 0, 0, 0, 0, $w, $h, $w_orig, $h_orig);
	imagejpeg($tci, $newcopy, 80);
}

function ak_event_img_resize($target, $newcopy, $w, $h, $ext){
	list($w_orig, $h_orig) = getimagesize($target);
	$scale_ratio = $w_orig/$h_orig;
	if(($w / $h) > $scale_ratio){
		$w = $h * $scale_ratio;
	} else {
		$h = $w / $scale_ratio;
	}
	$img = null;
	if($ext == "gif" || $ext == "GIF"){
		$img = imagecreatefromgif($target);
	} else if($ext == "png" || $ext == "PNG"){
		$img = imagecreatefrompng($target);
	} else {
		$img = imagecreatefromjpeg($target);
	}
	$tci = imagecreatetruecolor($w, $h);
	imagecopyresampled($tci, $img, 0, 0, 0, 0, $w, $h, $w_orig, $h_orig);
	imagejpeg($tci, $newcopy, 80);

}

?>