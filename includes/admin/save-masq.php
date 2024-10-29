<?php

include(THE_AWE_DES_AWESOME_INCLUDES.'wordpress_env.php');

if (the_awe_des_check_is_admin_w()) {

	$taille_fich = 800;

	if (isset($_POST) && is_array($_POST)){
		foreach ($_POST as $key => $value){
			$POST_Secured[$key] = htmlentities($value, ENT_QUOTES);
		}
	}


	if (isSet($POST_Secured) and is_numeric($POST_Secured["save_width"]) and is_numeric($POST_Secured["save_height"]) and is_numeric($POST_Secured["save_canv_width"])
		 and is_numeric($POST_Secured["save_canv_height"])  and is_numeric($POST_Secured["save_canv_top"])  and is_numeric($POST_Secured["save_canv_left"]) and ctype_alnum($POST_Secured["awesome_name"]) and ctype_alnum($POST_Secured["awesome_extension"])) {
			
		$path_array  = wp_upload_dir();
		$path =  $path_array['basedir'].'/the-awe-des-awesome-product/';
			
		$nom_fich = $path. $POST_Secured["awesome_name"].'_crop.'.$POST_Secured["awesome_extension"];
		$new_fich = $path. $POST_Secured["awesome_name"].'_masq.'.$POST_Secured["awesome_extension"];
		
		
		$image = imagecreatefrompng($nom_fich);
		$width = imagesx($image);
		$height = imagesy($image);
		
		
		
		if ($POST_Secured["save_width"] > $POST_Secured["save_height"]) {
			$new_width = $taille_fich;
			$new_height = ($taille_fich*$POST_Secured["save_height"])/ $POST_Secured["save_width"];
			$ratio = $new_width/$POST_Secured["save_width"];
		} else {
			$new_height = $taille_fich;
			$ratio = $new_height/$POST_Secured["save_height"];
			$new_width = ($taille_fich*$POST_Secured["save_width"])/ $POST_Secured["save_height"];
		}
		
		
		
		$image_p = imagecreatetruecolor($new_width, $new_height);
		imagealphablending($image_p, false);
		imagesavealpha($image_p,true);
		$transparent = imagecolorallocatealpha($image_p, 255, 255, 255, 127);
		imagefilledrectangle($image_p, 0, 0,  $new_width, $new_height, $transparent);
		
		imagecopyresampled($image_p, $image, 0, 0,0, 0, $new_width, $new_height, $width,$height);
		
		imagepng($image_p,$nom_fich);
		
		$save_canv_width = $POST_Secured["save_canv_width"] *$ratio;
		$save_canv_height = $POST_Secured["save_canv_height"] *$ratio;
		$save_canv_top = $POST_Secured["save_canv_top"] *$ratio;
		$save_canv_left = $POST_Secured["save_canv_left"] *$ratio;		
		
		$image_m = imagecreatetruecolor($save_canv_width, $save_canv_height);
		imagealphablending($image_m, false);
		imagesavealpha($image_m,true);
		$transparent = imagecolorallocatealpha($image_m, 255, 255, 255, 127);
		imagefilledrectangle($image_m, 0, 0,  $save_canv_width, $save_canv_height, $transparent);
				
		imagecopyresampled($image_m, $image_p, 0,0, $save_canv_left, $save_canv_top, $save_canv_width, $save_canv_height,$save_canv_width, $save_canv_height);
	
		imagepng($image_m,$new_fich);
		imagedestroy($image);
		imagedestroy($image_p);
		imagedestroy($image_m);
		
		
	} 
}
?>