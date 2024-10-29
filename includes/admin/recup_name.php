<?php


include(THE_AWE_DES_AWESOME_INCLUDES.'wordpress_env.php');

if (the_awe_des_check_is_admin_w()) {




	if (isset($_POST) && is_array($_POST)){
		foreach ($_POST as $key => $value){
			
			$POST_Secured[$key] = htmlentities($value, ENT_QUOTES);
		}
		
		
		$table_name = $wpdb->prefix . 'the_awe_des_awesome_product';
		$results = $wpdb->get_results( 'SELECT nom FROM '.$table_name.' WHERE nom = "'.$POST_Secured["ProductName"].'"', OBJECT );

		if ( $results ) echo 'ko';
		else echo 'ok';
		
	}
	
} 

?>