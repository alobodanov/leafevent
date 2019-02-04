<?php
//in this file, you will abe to find all the clases you need to connect tot he DB and such


//With this class, we can connect to our database and select/modify/add/change data in our DB
//
class DBLink {
	private $link;

	public function __construct(){
        $link = mysqli_connect("localhost","eafeventroot","93milbefwsdfjyrhnt3252skhsnsser93@","MILBER") or die ("could not connect " . mysqli_connect_error());
        $this->link = $link;
	}

	function __destruct(){
        mysqli_close($this->link);
    }

	function conn(){
       return $this->link;
    }

	function logInQuery($sql_query){
		$result = mysqli_query($this->$link, $sql_query) or die ("Could not query " . mysqli_error($this->$link));
		return $result;
	}
	function query($sql_query){
		$result = mysqli_query($this->link, $sql_query) or die ("Could not query " . mysqli_error($this->link));
		return $result;
	}
	function lastInsertId(){
		return $this->link->insert_id;
	}
}
//Function to check if the request is an AJAX request
function isajax() {
  return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

function verifyDateTime($dt, $strict = true)
{
    $dateTime = DateTime::createFromFormat('Y-m-d H:i:s', $dt);
    
    if ($strict) {
        $errors = DateTime::getLastErrors();
        if (!empty($errors['warning_count'])) {
            return false;
        }
    }
    return $dateTime !== false;
}

function formatDateTime($dt, $strict = true)
{
    $dateTime = DateTime::createFromFormat('Y-m-d H:i:s', $dt);
    
    if ($strict) {
        $errors = DateTime::getLastErrors();
        if (!empty($errors['warning_count'])) {
            return 'error: date is invaild.';
        }
    }
    return $dateTime;
}

/*
$p_thumb_file - name of the file (including path) where thumb should be saved to

$p_photo_file - name of the source JPEG file (including path) thatthumbnail should be created of

$p_max_size - with and height (they will be the same) in pixels for thumbnail image

$p_quality - quality of jpeg thumbnail
*/
function photoCreateCropThumb ($p_thumb_file, $p_photo_file, $p_max_size, $p_quality = 75) {
  
    $pic = @imagecreatefromjpeg($p_photo_file);

    if ($pic) {
        $thumb = @imagecreatetruecolor ($p_max_size, $p_max_size) or die ("Can't create Image!");
        $width = imagesx($pic);
        $height = imagesy($pic);
        if ($width < $height) {
                $twidth = $p_max_size;
                $theight = $twidth * $height / $width; 
                imagecopyresized($thumb, $pic, 0, 0, 0, ($height/2)-($width/2), $twidth, $theight, $width, $height); 
        } else {
                $theight = $p_max_size;
                $twidth = $theight * $width / $height; 
                imagecopyresized($thumb, $pic, 0, 0, ($width/2)-($height/2), 0, $twidth, $theight, $width, $height); 
        }

        ImageJPEG ($thumb, $p_thumb_file, $p_quality);
    }

}
function generateSalt(){
  $characters = '0123456789abcdef';
  $length = 64; 

  $string = '';
  for ($max = mb_strlen($characters) - 1, $i = 0; $i < $length; ++ $i)
  {
    $string .= mb_substr($characters, mt_rand(0, $max), 1);
  }
  return $string;
}
//This will return true if the password is correct, false otherwise.
function testPassword($fPassword, $fSaltFromDatabase, $fHashFromDatabase){
  if (hash_hmac("sha256", $fPassword, $fSaltFromDatabase) === $fHashFromDatabase){
    return(true);
  }else{
    return(false);
  }
}
?>