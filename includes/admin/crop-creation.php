<?php

include(THE_AWE_DES_AWESOME_INCLUDES.'wordpress_env.php');

if (the_awe_des_check_is_admin_w()) {
	if (isset($_POST) && is_array($_POST)){
		foreach ($_POST as $key => $value){
			$POST_Secured[$key] = htmlentities($value, ENT_QUOTES);
		}
	}


	if (isSet($POST_Secured) and is_numeric($POST_Secured["awesome_width"]) and is_numeric($POST_Secured["awesome_top"]) and is_numeric($POST_Secured["awesome_left"])  and (preg_match('/^[0-9a-zA-Z _-]*$/i',$POST_Secured["awesome_folder"])) 
		 and is_numeric($POST_Secured["awesome_height"]) and (preg_match('/^[0-9a-zA-Z _-]*$/i',$POST_Secured["awesome_parent"])) and ctype_alnum($POST_Secured["awesome_name"]) and ctype_alnum($POST_Secured["awesome_extension"])) {
		

		
		$path_array  = wp_upload_dir();
		$path =  $path_array['basedir'].'/the-awe-des-awesome-product/';
		
		if (isSet($path)) {
		
			
			$nom_fich = $path.$POST_Secured["awesome_folder"].'/'. $POST_Secured["awesome_parent"].'.'.$POST_Secured["awesome_extension"];
			$new_fich = $path. $POST_Secured["awesome_name"].'_crop.'.$POST_Secured["awesome_extension"];
			
			
			if ($POST_Secured["awesome_extension"]=='png'){
				$image_p = imagecreatetruecolor( $POST_Secured["awesome_width"], $POST_Secured["awesome_height"]);
				$image = imagecreatefrompng($nom_fich);
				
				imagealphablending($image_p, false);
				 imagesavealpha($image_p,true);
				 $transparent = imagecolorallocatealpha($image_p, 255, 255, 255, 127);
				 imagefilledrectangle($image_p, 0, 0,  $POST_Secured["awesome_width"], $POST_Secured["awesome_height"], $transparent);
			
				imagecopyresampled($image_p, $image, 0, 0, $POST_Secured["awesome_left"], $POST_Secured["awesome_top"], $POST_Secured["awesome_width"],$POST_Secured["awesome_height"], $POST_Secured["awesome_width"],$POST_Secured["awesome_height"]);
				imagepng($image_p,$new_fich);
				imagedestroy($image_p);
				

			} else {
				$image_p = imagecreatetruecolor( $POST_Secured["awesome_width"], $POST_Secured["awesome_height"]);
				$image = imagecreatefromjpeg($nom_fich);
				
				imagecopyresampled($image_p, $image, 0, 0, $POST_Secured["awesome_left"], $POST_Secured["awesome_top"], $POST_Secured["awesome_width"],$POST_Secured["awesome_height"], $POST_Secured["awesome_width"],$POST_Secured["awesome_height"]);
				imagejpeg($image_p,$new_fich);
				imagedestroy($image_p);
				
			}
			
			echo 'ok';
			
		
		}
		
	}
}	

?>