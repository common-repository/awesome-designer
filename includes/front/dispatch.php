<?php


include(THE_AWE_DES_AWESOME_INCLUDES.'wordpress_env.php');


if (isSet($awesome_designer_id) and ctype_alnum($awesome_designer_id)) {
	
	$path_array  = wp_upload_dir();
	
	$path_img =  $path_array['basedir'].'/the-awe-des-awesome-commande/'.
	
	$files1 = @scandir( $path_array['basedir'].'/the-awe-des-awesome-commande/'.$awesome_designer_id,SCANDIR_SORT_NONE);
	if ($files1) { 
		for ($i=0;$i<count($files1);$i++) {
			if ($files1[$i]!='.' and $files1[$i]!='..' and substr($files1[$i],-3)=='png') {	
				echo '<img src="'.$path_array['baseurl'].'/the-awe-des-awesome-commande/'.$awesome_designer_id.'/'.$cart_item['the_awe_des_awesome_field'].'/'.$files1[$i].'">';
			}
		}
	}
	
	
}

?>