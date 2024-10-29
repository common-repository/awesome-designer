<?php
class UploadHandler {

    public $allowedExtensions = array();
    public $sizeLimit = null;
    public $inputName = 'qqfile';
    public $chunksFolder = 'chunks';

    public $chunksCleanupProbability = 0.001; // Once in 1000 requests on avg
    public $chunksExpireIn = 604800; // One week

    protected $uploadName;

    /**
     * Get the original filename
     */
    public function getName(){
        if (isset($_REQUEST['qqfilename']))
            return $_REQUEST['qqfilename'];

        if (isset($_FILES[$this->inputName]))
            return $_FILES[$this->inputName]['name'];
    }

    public function getInitialFiles() {
        $initialFiles = array();

        for ($i = 0; $i < 5000; $i++) {
            array_push($initialFiles, array("name" => "name" + $i, uuid => "uuid" + $i, thumbnailUrl => "/test/dev/handlers/vendor/fineuploader/php-traditional-server/fu.png"));
        }

        return $initialFiles;
    }
	
	public function check_if_rotate($filename,$ext){
		
		if ($ext!="png") {
			$rotate = 0;
			$exif = exif_read_data($filename);
			if (!empty($exif['Orientation'])) {
				switch ($exif['Orientation']) {
					case 3:
						$rotate = 180;                 
						break;
					case 6:
						$rotate = -90;					
						break;
					case 8:
						$rotate = 90;                  
						break;
				}
			
			} 
			
			if ($rotate!=0) {			
				
				$image = imagecreatefromjpeg($filename);				  
				
				$image = imagerotate($image, $rotate, 0);
				
				imagejpeg($image, $filename);
				
				imagedestroy($image);
			}
		}
	}
	
    /**
     * Get the name of the uploaded file
     */
    public function getUploadName(){
        return $this->uploadName;
    }

   

    /**
     * Process the upload.
     * @param string $uploadDirectory Target directory.
     * @param string $name Overwrites the name of the file.
     */
    public function handleUpload($uploadDirectory, $name_sortie){

        	 	
        if ($this->isInaccessible($uploadDirectory)){
            return array('error' => "Server error. Uploads directory isn't writable");
        }

        $type = $_SERVER['CONTENT_TYPE'];
        if (isset($_SERVER['HTTP_CONTENT_TYPE'])) {
            $type = $_SERVER['HTTP_CONTENT_TYPE'];
        }

        if(!isset($type)) {
            return array('error' => "No files were uploaded.");
        } else if (strpos(strtolower($type), 'multipart/') !== 0){
            return array('error' => "Server error. Not a multipart request. Please set forceMultipart to default value (true).");
        }

        // Get size and name
        $file = $_FILES[$this->inputName];
        $size = $file['size'];
        if (isset($_REQUEST['qqtotalfilesize'])) {
            $size = $_REQUEST['qqtotalfilesize'];
        }

       
        $name = $this->getName();
        

        // check file error
        if($file['error']) {
			if ($file['error']==1) {
				
				$max_size = parse_size(ini_get('post_max_size'));
				$upload_max = parse_size(ini_get('upload_max_filesize'));
				if ($upload_max<$max_size) $max_size = $upload_max;
				
				return array('error'=>"Server error. Increase post_max_size and upload_max_filesize to accept file bigger than ".max(1,round(($max_size/1024),2)) . 'M');
				
			} else {
				return array('error' => 'Upload Error #'.$file['error']);
			}
        }
        	
        // Validate name
        if ($name === null || $name === ''){
            return array('error' => 'File name empty.');
        }

        // Validate file size
        if ($size == 0){
            return array('error' => 'File is empty.');
        }

        if (!is_null($this->sizeLimit) && $size > $this->sizeLimit) {
            return array('error' => 'File is too large.', 'preventRetry' => true);
        }
	
		
	
	
        // Validate file extension
        $pathinfo = pathinfo($name);
        $ext = isset($pathinfo['extension']) ? $pathinfo['extension'] : '';
		
		
        if($this->allowedExtensions && !in_array(strtolower($ext), array_map("strtolower", $this->allowedExtensions))){
            $these = implode(', ', $this->allowedExtensions);
            return array('error' => 'File has an invalid extension, it should be one of '. $these . '.');
        }
		
     
	   
	   
	   
        # non-chunked upload
		
		$final_name=$name_sortie.'.'.strtolower($ext);
		
		$target = join(DIRECTORY_SEPARATOR, array($uploadDirectory, $final_name));

		if ($target){
			$this->uploadName = basename($target);

			if (move_uploaded_file($file['tmp_name'], $target)){
				
				$this->check_if_rotate($target,$ext);
				
				return array('success'=> true, "url" => $final_name);
			}
		}

		return array('error'=> 'Could not save uploaded file.' .
			'The upload was cancelled, or server error encountered');
        
    }

  
    

    /**
     * Converts a given size with units to bytes.
     * @param string $str
     */
    protected function toBytes($str){
        $val = trim($str);
        $last = strtolower($str[strlen($str)-1]);
        switch($last) {
            case 'g': $val *= 1024;
            case 'm': $val *= 1024;
            case 'k': $val *= 1024;
        }
        return $val;
    }

    /**
     * Determines whether a directory can be accessed.
     *
     * is_executable() is not reliable on Windows prior PHP 5.0.0
     *  (http://www.php.net/manual/en/function.is-executable.php)
     * The following tests if the current OS is Windows and if so, merely
     * checks if the folder is writable;
     * otherwise, it checks additionally for executable status (like before).
     *
     * @param string $directory The target directory to test access
     */
    protected function isInaccessible($directory) {
        $isWin = $this->isWindows();
        $folderInaccessible = ($isWin) ? !is_writable($directory) : ( !is_writable($directory) && !is_executable($directory) );
        return $folderInaccessible;
    }

    /**
     * Determines is the OS is Windows or not
     *
     * @return boolean
     */

    protected function isWindows() {
    	$isWin = (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN');
    	return $isWin;
    }

}
 
include(THE_AWE_DES_AWESOME_INCLUDES.'wordpress_env.php');

if (isSet($id_fold) and ctype_alnum($id_fold)) {	
	
	$path_array  = wp_upload_dir();
	$path =  $path_array['basedir'].'/the-awe-des-awesome-upload/'.$id_fold;
	
	$name =  uniqid().rand(1,100);			
	
	if (!is_dir($path)) {
		require_once( ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php' );
		require_once( ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php' );
		$wp_fs_d = new WP_Filesystem_Direct( new StdClass() );
		$wp_fs_d->mkdir( $path, 0705 );			
	}
	
	$uploader = new UploadHandler();

	// Specify the list of valid extensions, ex. array("jpeg", "xml", "bmp")
	$uploader->allowedExtensions = array('jpg', 'jpeg', 'png'); // all files types allowed by default

	// Specify max file size in bytes.
	if (get_option( 'max_up_awesome_designer' )) $uploader->sizeLimit = get_option( 'max_up_awesome_designer' )*1000;
	 
	// Specify the input name set in the javascript.
	$uploader->inputName = "qqfile"; // matches Fine Uploader's default inputName value by default

	// If you want to use the chunking/resume feature, specify the folder to temporarily save parts.
	$uploader->chunksFolder = "chunks";

	$method = $_SERVER["REQUEST_METHOD"];
	
	header("Content-Type: text/plain");

	// Call handleUpload() with the name of the folder, relative to PHP's getcwd()
	$result = $uploader->handleUpload($path,$name);

	// To return a name used for uploaded file you can use the following line.
	$result["uploadName"] = $uploader->getUploadName();
	

	echo json_encode($result);
	
}
?>
