<?php


	function the_awe_des_check_is_admin_w(){
		
		if( is_user_logged_in() ){ // check if user is logged in
			$get_user_id = get_current_user_id(); // get user ID
			$get_user_data = get_userdata($get_user_id); // get user data
			$get_roles = implode($get_user_data->roles);
			
			$pos = strpos(strtolower($get_roles),'administrator');

			if ($pos !== false) return true;
			else return false;
		} else return false;
		
	}


	
	global $wpdb;
	
	$url_dossier_awesome = THE_AWE_DES_AWESOME_URL;
	
?>