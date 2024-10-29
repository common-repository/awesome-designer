<?php

include(THE_AWE_DES_AWESOME_INCLUDES.'wordpress_env.php');

if (the_awe_des_check_is_admin_w()) {

$new_name = uniqid().rand(1,100);		

$path_array  = wp_upload_dir();
$path =  $path_array['baseurl']. "/the-awe-des-awesome-product/" ;
$path_r =  $path_array['basedir']. "/the-awe-des-awesome-product/" ;
$path_base =  $path_array['baseurl']. "/the-awe-des-awesome-product/base/" ;
$path_base_r =  $path_array['basedir']. "/the-awe-des-awesome-product/base/" ;
$path_base_c =  $path_array['basedir']. "/the-awe-des-awesome-commande/" ;
$path_base_u =  $path_array['basedir']. "/the-awe-des-awesome-upload/" ;


$scan = glob($path_r."*",GLOB_ONLYDIR);
foreach ($scan as $val){
		
		$list = explode('/',$val);
		$name_fold = end($list);
		if ($name_fold!='base') {
			
			if($dossier = opendir($val.'/')){	
				
				while(false !== ($fichier = readdir($dossier))){
					
					if($fichier != '.' && $fichier != '..' ){
						$array_bonus[] = array(
							'folder' => $name_fold,
							'nom' => $fichier);	
					}
				}
			}
		}
}


if (isset($_POST) && !empty($_POST)){
	
	
    foreach ($_POST as $key => $value){
		
        $POST_Secured[$key] = htmlentities($value, ENT_QUOTES);
    }
	

	if (isset( $POST_Secured['action']) and  $POST_Secured['action']!='') {
		
			
		require_once( ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php' );
		require_once( ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php' );
		
		$wp_fs_d = new WP_Filesystem_Direct( new StdClass() );
		if ($POST_Secured['action']=='remove_tel') {
			
		
			$table_name = $wpdb->prefix . 'the_awe_des_awesome_product';
			$array_use = array();
			$results_lightbox = $wpdb->get_results( 'SELECT DISTINCT(image_par) FROM '.$table_name.' WHERE folder="base" group by image_par order by time desc', OBJECT );
			if ($results_lightbox) {
				foreach ( $results_lightbox as $results_l ) {
					$array_use[] = $results_l->image_par;		
				}	
			}
		$compt = 0;
			if($dossier = opendir($path_base_r)){	
				while(false !== ($fichier = readdir($dossier))){
					if($fichier != '.' && $fichier != '..' && !in_array($fichier,$array_use)){
						$wp_fs_d->delete($path_base_r.$fichier);
						$compt++;
					}
				}
			}
			//$msg = __('Files deleted.','woocommerce-awesome-designer');
			$msg =__( 'Files deleted', 'woocommerce-awesome-designer' ).' : '.$compt;
		} else if ($POST_Secured['action']=='remove_com') {
			$compt = 0;
			$table_name = $wpdb->prefix . 'woocommerce_order_itemmeta';
			$results_lightbox = $wpdb->get_results( 'SELECT meta_value FROM '.$table_name.'  where meta_key = "awesome"', OBJECT );
			if ($results_lightbox) {
				foreach ( $results_lightbox as $results_l ) {
					$array_use[] = $results_l->meta_value;		
				}
			}
			$compt = 0;
			
			$date_lim = time()-(($POST_Secured['nbe_jours']*1)*24*3600);
		
			if($dossier = opendir($path_base_c)){	
				while(false !== ($fichier = readdir($dossier))){
					
					if($fichier != '.' && $fichier != '..' && !in_array($fichier,$array_use)){
						$date_mod = filemtime($path_base_c.$fichier);
						
						if ($date_mod < $date_lim) {
							$compt++;
							$wp_fs_d->delete($path_base_c.$fichier,true);
						}
						
						
					
					}
				}
			}
			if($dossier = opendir($path_base_u)){	
				while(false !== ($fichier = readdir($dossier))){
					
					if($fichier != '.' && $fichier != '..' && !in_array($fichier,$array_use)){
						$date_mod = filemtime($path_base_u.$fichier);
						
						if ($date_mod < $date_lim) {
							$compt++;
							$wp_fs_d->delete($path_base_u.$fichier,true);
						}
						
						
						
					}
				}
			}
			$msg =__( 'Files deleted', 'woocommerce-awesome-designer' ).' : '.$compt;
		}
	}	 
} 

?>
<html><head>
<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1">
<meta http-equiv="Content-Language" content="fr"> 
<meta http-equiv="imagetoolbar" content="no">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="mobile-web-app-capable" content="yes">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<link href='https://fonts.googleapis.com/css?family=News+Cycle' rel='stylesheet' type='text/css'>
<link type="text/css" rel="stylesheet" href="<?php echo $url_dossier_awesome;?>files/css/admin.css">
<script src="//code.jquery.com/jquery-2.1.4.min.js"></script>
</head>
<body topmargin="0" style="height:100%;">

	<form method="post" id="form_effet" name="form_effet" > 

<!-- header --> 
<?php include(THE_AWE_DES_AWESOME_INCLUDES.'admin/module-header.php'); 

if (isSet($msg)) { ?>
<div class="full txt" >
	<?php echo $msg;?>
</div>
<?php } ?>
<!-- BT NEXT --> 
<div class="full " >
 	<div style="margin-top:15px;display:inline-block;">
		<p class="txt"><?php _e('DELETE ALL UNUSED IMAGES, UPLOADED BY YOURSELF','woocommerce-awesome-designer');?></p>
	</div>
	<div style="margin-top:15px;display:inline-block;">
		<a href="#" class="admin-button" style="width:280px" onclick="$('#action').val('remove_tel');$('#form_effet').submit();"><?php _e('CLEAR UNUSED PRODUCT PHOTOS','woocommerce-awesome-designer');?></a>
	</div>	
	</br></br><div class="txt-cadre-gris " style="height:2px; padding:0px;" ></div></br>
	<div style="margin-top:15px;display:inline-block;">
		<p class="txt"><?php _e("DELETE ALL UNUSED IMAGES, UPLOADED BY YOUR CLIENTS UP TO (number of days ago)&nbsp;:",'woocommerce-awesome-designer');?></p>
	</div>
	<div style="margin-top:15px;display:inline-block;">
		<select class="txt" id="nbe_jours" name="nbe_jours"><?php for ($i=10;$i<60;$i++){ echo '<option value="'.$i.'">'.$i.'</option>'; } ?></select>
	</div>
	
	<div style="margin-top:15px;display:inline-block;">
		<a href="#" class="admin-button" style="width:280px" onclick="$('#action').val('remove_com');$('#form_effet').submit();"><?php _e('CLEAR UNUSED CLIENT\'S PHOTOS','woocommerce-awesome-designer');?></a>
	</div>
</div>



<!-- separator --> 
<div class="txt-cadre-gris " style="height:2px; padding:0px;" ></div>


	
				<input type="hidden" name="valid" id="valid" value="">
				<input type="hidden" name="action" id="action" value="">
				<input type="hidden" name="cible" id="cible" value="">
			
				
			</form>
</body>
</html>

<?php }  
  ?>
 <script>
 function test(e){
		window.parent.postMessage(e, "*");
	}	
</script>