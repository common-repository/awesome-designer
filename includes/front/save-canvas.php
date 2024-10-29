<?php


if (isset($_POST) && is_array($_POST)){
    foreach ($_POST as $key => $value){
        $POST_Secured[$key] = htmlentities($value, ENT_QUOTES);
    }
}


if (isSet($POST_Secured) and isSet($POST_Secured['base64data']) and ctype_alnum($POST_Secured["id_com"]) and (preg_match('/^[0-9a-zA-Z+._,-=]*$/i',$POST_Secured["canv_sav"]))   and ctype_alnum($POST_Secured["info_rendu"]) and ($POST_Secured["cool"]=='' or preg_match('/^[0-9a-zA-Z#]*$/i',$POST_Secured["cool"])) )  {

	
	
	include(THE_AWE_DES_AWESOME_INCLUDES.'wordpress_env.php');
	
	require_once( ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php' );
	require_once( ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php' );
	$wp_fs_d = new WP_Filesystem_Direct( new StdClass() );
	
	$time = $POST_Secured["id_com"];
	$path_array  = wp_upload_dir();
	
	$path_img =  $path_array['basedir'].'/the-awe-des-awesome-commande/'.$time;
	
	if (!is_dir($path_img)) {
		
		$wp_fs_d->mkdir( $path_img, 0705 );			
	}
	
	
	
	$list_rendu = explode('--spre--', $POST_Secured['base64data']);
	
	for ($i=count($list_rendu);$i>0;$i--) {
		$path =  $path_img.'/rendu-'.$time.'-'.$i.'.png';
		$img = str_replace('data:image/png;base64,', '',$list_rendu[($i-1)]);
		$img = str_replace(' ', '+', $img);
		$data = base64_decode($img);
		
		$im = imagecreatefromstring($data);
		if ($im !== false) {		
			imagealphablending($im,false);
			imagesavealpha($im,true);
			imagepng($im,$path);			
		} 
		if ($i!=1) imagedestroy($im);
	}
	if (isSet($im) and $im !== false) {
	
		$table_name = $wpdb->prefix . 'the_awe_des_awesome_product';
		$results_product = $wpdb->get_results( 'SELECT image_back,width_cad,height_cad,width_canv,height_canv,top_canv,left_canv,obj_order FROM '.$table_name.' WHERE id_prod='.$POST_Secured["info_rendu"].' ORDER BY obj_order ASC LIMIT 1', OBJECT );

		if ($results_product) {
			foreach ( $results_product as $result_product ) {				
				
				list($width_canv, $height_canv) = getimagesize($path);
				
				$ratio_canv = $width_canv/$result_product->width_canv;
				
				
				$path_fond = $path_array['basedir'].'/the-awe-des-awesome-product/';
				list($width, $height) = getimagesize($path_fond.$result_product->image_back);
				if (substr($result_product->image_back,-3)=='png') {
					$src = imagecreatefrompng($path_fond.$result_product->image_back);					
					imagealphablending($src,false);
					imagesavealpha($src,true);	

					if ($POST_Secured["cool"]!='') {
						list($r, $g, $b) = sscanf($POST_Secured["cool"], "#%02x%02x%02x");	
						$dst = imagecreatetruecolor($result_product->width_cad*$ratio_canv,$result_product->height_cad*$ratio_canv);
						$color = imagecolorallocate($dst, $r, $g, $b);
					} else {
						$dst = imagecreatetruecolor($result_product->width_cad*$ratio_canv,$result_product->height_cad*$ratio_canv);
						$color = imagecolorallocate($dst, 255, 255, 255);
					}
					imagefill($dst, 0, 0, $color);					
				} else {
					$src = imagecreatefromjpeg($path_fond.$result_product->image_back);
					$dst = imagecreatetruecolor($result_product->width_cad*$ratio_canv,$result_product->height_cad*$ratio_canv);
				}				
				imagecopyresampled($dst, $src, 0, 0, 0, 0,$result_product->width_cad*$ratio_canv,$result_product->height_cad*$ratio_canv, $width, $height);
				imagecopyresampled($dst, $im, $result_product->left_canv*$ratio_canv, $result_product->top_canv*$ratio_canv, 0, 0,$result_product->width_canv*$ratio_canv,$result_product->height_canv*$ratio_canv,$result_product->width_canv*$ratio_canv,$result_product->height_canv*$ratio_canv);
				imagepng($dst,$path);
				imagedestroy($src);
				imagedestroy($dst);
			} 
		} else {
			imagepng($im,$path);
		}
		
		imagedestroy($im);
		
		$fx_sav = $POST_Secured["canv_sav"];
	
		$path =  $path_img.'/sav-'.$time.'.txt';
		
		$wp_fs_d->put_contents( $path, $fx_sav, FS_CHMOD_FILE);
		
		echo 'success';
			
	} else {
		echo 'Illegal picture';
	}
	
} else {
	$erreur = '';
	if (!preg_match('/^[0-9a-zA-Z._,-=]*$/i',$POST_Secured["canv_sav"])) $erreur .= $POST_Secured["canv_sav"];   
	if (!ctype_alnum($POST_Secured["info_rendu"]))  $erreur .= $POST_Secured["info_rendu"]; 
	if ($POST_Secured["cool"]!='' and !preg_match('/^[0-9a-zA-Z#]*$/i',$POST_Secured["cool"]))  $erreur .= $POST_Secured["cool"]; 

	
	echo 'Illegal value '.$erreur;
}