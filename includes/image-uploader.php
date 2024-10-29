<?php



class THE_AWE_DES_Image_Uploader
{
  /**
   * The path to upload the images
   *
   * @var string
   */
  private $path;
  /**
   * The salt used by the application to encrypt image path
   *
   * @var string
   */
  private $salt;
  /**
   * The min size allowed for upload (in bytes)
   *
   * @var number
   */
  private $min_size;
  /**
   * The max size allowed for upload (in bytes)
   *
   * @var number
   */
  private $max_size;
  /**
   * List of valid mime types alongwith processing functions
   *
   * @var array
   */
  private static $MIME_TYPES_PROCESSORS = array(   
    "image/jpg"       => array("imagecreatefromjpeg", "imagejpeg"),
    "image/jpeg"      => array("imagecreatefromjpeg", "imagejpeg"),
    "image/png"       => array("imagecreatefrompng", "imagepng")
  );
  /**
   * Constructor function
   */
  public function __construct($path = null,
                              $salt = null,
                              $min_file_size = null,
                              $max_file_size = null)
  {
    $this->path = $path;
    $this->salt = $salt;
    $this->min_file_size = $min_file_size;
    $this->max_file_size = $max_file_size;
  }
  /**
   * Set $path
   *
   * @param       $path         The path to upload images
   */
  public function setPath($path)
  {
    $this->path = $path;
  }
  /**
   * Get $path
   *
   * @return      string        The path to upload images
   */
  public function getPath()
  {
    return $this->path;
  }
  /**
   * Set $salt
   *
   * @param       $salt         The salt
   */
  public function setSalt($salt)
  {
    $this->salt = $salt;
  }
  /**
   * Get $salt
   *
   * @return      string        The salt
   */
  public function getSalt()
  {
    return $this->salt;
  }
  /**
   * Set $min_file_size
   *
   * @param       $min_file_size          The minimum file size
   */
  public function setMinFileSize($min_file_size)
  {
    $this->min_file_size = $min_file_size;
  }
  /**
   * Get $min_file_size
   *
   * @return      number                  The minimum file size
   */
  public function getMinFileSize()
  {
    return $this->min_file_size;
  }
  /**
   * Set $max_file_size
   *
   * @param       $max_file_size           The maximum file size
   */
  public function setMaxFileSize($max_file_size)
  {
    $this->max_file_size = $max_file_size;
  }
  /**
   * Get $max_file_size
   *
   * @return      number                  The maximum file size
   */
  public function getMaxFileSize()
  {
    return $this->max_file_size;
  }
  /**
   * Checks the files and path parameters
   *
   * @var         $image         The $_FILE["image"] parameter
   */
  private function checkParameters($image)
  {
    if (!is_array($image)) {
      throw new Exception("No image with given name uploaded");
    }
    
    if ($this->min_file_size !== null
      && $this->max_file_size !== null
      && $this->min_file_size > $this->max_file_size) {
      throw new Exception("Invalid file size parameters");
    }
  }
  /**
   * Checks upload error
   *
   * @var         $image        The $_FILE["image"] parameter
   */
  private function checkUploadError($image)
  {
    if ( !isset($image['error']) || is_array($image['error']) ) {
      throw new Exception("Invalid parameters");
    }
    switch ($image['error']) {
      case UPLOAD_ERR_OK:
        break;
      case UPLOAD_ERR_NO_FILE:
        throw new Exception('No file sent.');
      case UPLOAD_ERR_INI_SIZE:
      case UPLOAD_ERR_FORM_SIZE:
        throw new Exception('Exceeded filesize limit.');
      default:
        throw new Exception('Unknown errors.');
    }
  }
  /**
   * Checks if uploaded file size is within upload limit
   *
   * @var         $image        The $_FILE["image"] parameter
   */
  private function checkFileSize($image)
  {
    if ($this->min_file_size !== null && $image['size'] < $this->min_file_size) {
      throw new Exception("Size too small ".$image['size']." < ".$this->min_file_size);
    }
    if ($this->max_file_size !== null && $image['size'] > $this->max_file_size) {
      throw new Exception("Size limit exceeded");
    }
  }
  /**
   * Checks if first 100 bytes contains any non ASCII char
   * Throws an exception on any error
   *
   * @var         $image        The $_FILE["image"] parameter
   */
  private function checkInitialBytes($image)
  {
    // Reading first 100 bytes
    $contents = file_get_contents($image['tmp_name'], null, null, 0, 100);
    if ($contents === false) {
      throw new Exception("Unable to read uploaded file");
    }
    $regex = "[\x01-\x08\x0c-\x1f]";
    if (preg_match($regex, $contents)) {
      throw new Exception("Unknown bytes found");
    }
  }
  /**
   * Makes a list of security checks before uploading
   * Throws an exception on any error
   *
   * @var         $image        The $_FILE["image"] parameter
   */
  private function securityChecks($image)
  {
    $this->checkParameters($image);
    $this->checkUploadError($image);
    $this->checkFileSize($image);
    $this->checkInitialBytes($image);
  }
  

  
  /**
   * Checks the mime type as well as uses the GD library to reprocess the image
   *
   * @var         $image        The $_FILE["image"] parameter
   * @var         $callback     The callback function for further image manipulations
   */
  private function reprocessImage($image, $callback)
  {
    // Extracting mime type using getimagesize
    $image_info = getimagesize($image["tmp_name"]);
    if ($image_info === null) {
      throw new Exception("Invalid image type");
    }
    $mime_type = $image_info["mime"];
    if (!array_key_exists($mime_type, self::$MIME_TYPES_PROCESSORS)) {
      throw new Exception("Invalid image MIME type");
    } 
	if ($mime_type == 'image/png') $extens = 'png';
	else $extens = 'jpeg';
    $image_from_file = self::$MIME_TYPES_PROCESSORS[$mime_type][0];
    $image_to_file = self::$MIME_TYPES_PROCESSORS[$mime_type][1];
    $reprocessed_image = $image_from_file($image["tmp_name"]);
    
	if ($extens == 'png') {
		imagealphablending($reprocessed_image,false);
		imagesavealpha($reprocessed_image,true);
	}
	if (!$reprocessed_image) {
      throw new Exception("Unable to create reprocessed image from file");
    }
    // Calling callback(if set) with path of image as a parameter
    if ($callback !== null) {
      $callback($reprocessed_image);
    }
    $image_to_file($reprocessed_image, $image["tmp_name"]);
    // Freeing up memory
    imagedestroy($reprocessed_image);
	return  $extens;
  }
  /**
   * Returns the path of an image depending on identifier
   *
   * @var         $identifier   The image identifier
   *
   * @return      string        The path of the image
   */
  private function getImagePath($identifier,$extens){	
	
    $image_path = $this->path . DIRECTORY_SEPARATOR . $identifier.'.'.$extens;
    return $image_path;
  }

  public function upload($image, $identifier, $callback = null)
  {
    $this->securityChecks($image);
    $extens = $this->reprocessImage($image, $callback);
    $destination_path = $this->getImagePath($identifier,$extens);
    

	$result = move_uploaded_file($image["tmp_name"], $destination_path);
    return $identifier.'.'.$extens;
  }
  
 
 
}

?>