<?php
define ('TAILLE_CREA',"590");
include(THE_AWE_DES_AWESOME_INCLUDES.'wordpress_env.php');

if (the_awe_des_check_is_admin_w()) {
$new_name = uniqid().rand(1,100);		
$path_array  = wp_upload_dir();
$path =  $path_array['baseurl']. "/the-awe-des-awesome-product/" ;
$path_r =  $path_array['basedir']. "/the-awe-des-awesome-product/" ;
$path_base =  $path_array['baseurl']. "/the-awe-des-awesome-product/base/" ;
$path_base_r =  $path_array['basedir']. "/the-awe-des-awesome-product/base/" ;

$awesome_product = '';
$select='';
$id_prod_multi = $id_prod_par = $compteur = 0;
$table_name = $wpdb->prefix . 'the_awe_des_awesome_product';
if (isset($_POST) && !empty($_POST)){
	
	
    foreach ($_POST as $key => $value){
		
        $POST_Secured[$key] = htmlentities($value, ENT_QUOTES);
    }
	
	if ($POST_Secured["awesome_product"]) $awesome_product = $POST_Secured["awesome_product"];
	
	if (isSet($POST_Secured["action"])){
		if ( $POST_Secured["action"]=='save_all') {
		
			
			if (isSet($POST_Secured["affich_option"])) {
				$sav_atrr = array(
						'nom' => $POST_Secured["nom"],
						'affich_option' => $POST_Secured["affich_option"],
						'colors' => $POST_Secured["colors"]
					);
			} else {
					$sav_atrr = array(
						'nom' => $POST_Secured["nom"],
						'colors' => $POST_Secured["colors"]
					);
			}
			$id_dest =  array(
						'id_prod' => $POST_Secured["id_prod_parent"]
					);
			$wpdb->update( $table_name, $sav_atrr, $id_dest, null, null ); 
		} else 	if ( $POST_Secured["action"]=='delete_all'){
			if ($POST_Secured["id_prod_multi"]!=0) {
				$wpdb->delete( 
								$table_name, 						
								array( 'id_multi' => $POST_Secured["id_prod_multi"] )	
							);
			} else {
				$wpdb->delete( 
								$table_name, 						
								array( 'id_prod' => $POST_Secured["id_prod_parent"] )	
							);
				
			}
			$awesome_product ='';
		} else if ($POST_Secured["action"]=='modif_crop'){
			
			if ($POST_Secured["awesome_masq"]!='background') $img_masq=$POST_Secured["awesome_name"].'_masq.'.$POST_Secured["awesome_extension"];
			else $img_masq='';
			$sav_atrr = array(					
					'image_masq' => $img_masq,		
					'folder' => $POST_Secured["awesome_folder"],
					'image_back' => $POST_Secured["awesome_name"].'_crop.'.$POST_Secured["awesome_extension"],
					'width_cad' => $POST_Secured["save_width"],
					'height_cad' => $POST_Secured["save_height"],
					'width_canv' => $POST_Secured["save_canv_width"],
					'height_canv' => $POST_Secured["save_canv_height"],
					'top_canv' => $POST_Secured["save_canv_top"],
					'left_canv' => $POST_Secured["save_canv_left"],
					'width_prod' => $POST_Secured["save_prod_width"],
					'height_prod' => $POST_Secured["save_prod_height"],
					'unit_prod' => $POST_Secured["save_prod_unit"],
					'image_par' => $POST_Secured["awesome_parent"].'.'.$POST_Secured["awesome_extension"]					
				);
			$where_atrr = array(					
					'id_prod' => $POST_Secured["awesome_id_prod"]					
									
				);
			
			$table_name = $wpdb->prefix . 'the_awe_des_awesome_product';
			$wpdb->update(
				$table_name ,
				$sav_atrr,
				$where_atrr
			);	
		}
	}
	
	$table_name = $wpdb->prefix . 'the_awe_des_awesome_product';
	$results_col = $wpdb->get_results( 'SELECT count(id_prod) as NBE,colors as COLORS FROM '.$table_name.' where colors!="" group by colors order by NBE desc ', OBJECT );


	$array_deja = array();
	if ($results_col) {
		foreach ( $results_col as $result ) {		
			$arr_col = explode(',',$result->COLORS);
			for ($i=0;$i<count($arr_col);$i++) {
				
				if (!in_array($arr_col[$i], $array_deja)) $array_deja[] =$arr_col[$i]; 			
			
			}				
			//echo '<option value="'.$result->ID.'">'.$result->NOM.'</option>';
		} 	
	}

	//Lightbox
	$array_use = array();
	$results_lightbox = $wpdb->get_results( 'SELECT DISTINCT(image_par) FROM '.$table_name.' WHERE folder="base" group by image_par order by time desc', OBJECT );
	if ($results_lightbox) {
		foreach ( $results_lightbox as $results_l ) {
			$array_use[] = $results_l->image_par;		
		}	
	}
	$array_tel = array();

	if($dossier = opendir($path_base_r)){	
		while(false !== ($fichier = readdir($dossier))){
			if($fichier != '.' && $fichier != '..' && !strpos($fichier,'_crop') && !strpos($fichier,'_masq') && !in_array($fichier,$array_use)){
				$array_tel[] = $fichier;		
			}
		}
	}

	$scan = glob($path_r."*",GLOB_ONLYDIR);
	foreach ($scan as $val){
			
			$list = explode('/',$val);
			$name_fold = end($list);
			//echo $name_fold;
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

	
	
	
	
}

$results = $wpdb->get_results( 'SELECT image_par,width_prod,height_prod,unit_prod,id_prod,width_cad,height_cad,top_canv,left_canv,width_canv,height_canv,image_back,folder,id_multi,nom_masque,obj_order,image_masq,nom,colors,is_visible,affich_option FROM '.$table_name.' WHERE (id_multi=0 OR obj_order=1) ORDER BY id_multi ASC,obj_order DESC ', OBJECT );	

if ( $results ) {  	
					
	foreach ( $results as $result ) {		
		$intit = $result->nom;
		
		
		if ($result->obj_order <= 1){
			$compteur ++;
			if ($compteur>1) $intit.= ' - Printing zone : '.$compteur;
			if ($result->colors) $intit.= ' - Color : '.$result->colors;
			if ($awesome_product==$result->id_prod) {
				$select.= '<option value="'.$result->id_prod.'" class="txt" selected=selected>'.$intit.'</option>';
				$obj_select = $result;
			} else $select.= '<option value="'.$result->id_prod.'" class="txt">'.$intit.'</option>';
			$compteur = 0;
		} else {
			$compteur ++;
		}
	}  
}

$form='';

if (isSet($obj_select)) {
	$id_prod_par = $obj_select->id_prod;
	$id_prod_multi = $obj_select->id_multi;
	if ($obj_select->obj_order!=0) {
		$results_multi = $wpdb->get_results( 'SELECT image_par,width_prod,height_prod,unit_prod,id_prod,width_cad,height_cad,top_canv,left_canv,width_canv,height_canv,image_back,folder,id_multi,nom_masque,obj_order FROM '.$table_name.' where id_multi="'.$obj_select->id_multi.'" ORDER BY obj_order ASC ', OBJECT );	
		
		foreach ( $results_multi as $result_m ) {
			$array_trans =  (array) $result_m;
			$form.='<input type="hidden" id="recover_'.$result_m->obj_order.'" name="recover_'.$result_m->obj_order.'" value="'.implode(',',$array_trans).'">';
		}
	} else {
		
		$array_trans =  (array) $obj_select;
		$form.='<input type="hidden" id="recover_1" name="recover_1" value="'.implode(',',$array_trans).'">';	
	}
	if ($obj_select->image_masq!='') $awesome_masq='masq';
	else  $awesome_masq='background';
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
<link type="text/css" rel="stylesheet" href="<?php echo $url_dossier_awesome;?>files/css/awesome-back.css">
<link type="text/css" rel="stylesheet" href="<?php echo $url_dossier_awesome;?>files/css/cropper.css">
<link type="text/css" rel="stylesheet" href="<?php echo $url_dossier_awesome;?>files/css/admin.css">
<link type="text/css" rel="stylesheet" href="<?php echo $url_dossier_awesome;?>files/css/file_upload.css">
<script src="//code.jquery.com/jquery-2.1.4.min.js"></script>
<script src="<?php echo $url_dossier_awesome;?>files/js/file_upload.js"></script>
<script src="<?php echo $url_dossier_awesome;?>files/js/admin-modif.js?ver=1.0"></script>	
<script src="<?php echo $url_dossier_awesome;?>files/js/super-select.js"></script>	
<script src="<?php echo $url_dossier_awesome;?>files/js/cropper.js"></script>	
<script src="<?php echo $url_dossier_awesome;?>files/js/fabric-admin.js"></script>	
<script src="<?php echo $url_dossier_awesome;?>files/js/lazzy.js"></script>	

</head>

<body topmargin="0" style="height:100%;">
<!-- header --> 
<?php include(THE_AWE_DES_AWESOME_INCLUDES.'admin/module-header.php'); 
include(THE_AWE_DES_AWESOME_INCLUDES.'admin/galerie.php'); 

 if ($is_ok) { ?>
<div class="full" style="padding:5px 0 0 0">
	<p class="txt" style="font-size:110%;font-weight:700;color:red;"><?php _e('YOUR PRODUCT IS MODIFIED','woocommerce-awesome-designer');?></p>
</div>
<?php } else { ?>
<div class="full" style="padding:5px 0 0 0">
	<p class="txt" style="font-size:110%;font-weight:700"><?php _e('Choose and change your product','woocommerce-awesome-designer');?> !</p>
</div>
<?php }  ?>



<form method="post" id="form_effet" name="form_effet" > 

	<input type="hidden" name="action" id="action"  value="">
	<input type="hidden" name="id_prod_parent" id="id_prod_parent" value="<?php echo $id_prod_par; ?>">
	<input type="hidden" name="id_prod_multi" id="id_prod_multi" value="<?php echo $id_prod_multi; ?>">
<!-- choix 1 : choix single ou multi photo --> 
<div class="menu div_type" style="margin-top:10px;">
<!-- single --> 
	<div  style="width:60%;float:left;padding-left:10px;" >
		<select class="css-awesome txt" name="awesome_product" id="awesome_product">
		<?php echo $select;?>
		</select>
	</div>
<!-- multi --> 	
	<div  style="width:20%;float:left;padding-left:10px;" >
		<div  >
			<a href="#" class="admin-button-modify" onclick="$('#form_effet' ).submit();"><?php _e('MODIFY','woocommerce-awesome-designer');?></a>
		</div>
	</div>	
	<div style="clear:both"></div>
</div>

<div id="change_none" class="full">
	<?php if (isSet($obj_select)) { ?>
		<div class="txt-cadre-gris" style="height:2px; padding:0px;" ></div>
				
	<div style="margin:5px;float:left;border:1px solid #cfcfcf;width:250px;height:180px">	
		<div class="full" style="margin-top:5px;">
			<div style="padding:5px; float:left">
				<?php if ($results_multi) echo '<div class="icone-crea92"></div>';
				else echo '<div class="icone-crea91"></div>'; ?>
			</div>
			<div style="padding:5px; float:left">
			<?php if ($obj_select->image_masq!='') echo '<div class="icone-crea93"></div>';
				else echo '<div class="icone-crea94"></div>'; ?>
			</div>
			
			<div style="clear:both"></div>	
		</div>
		
		<div class="full">
			<div style="padding:0px 5px 1px 2px; float:left">
				<p class="txt"><?php _e('NAME','woocommerce-awesome-designer');?> :</p>
			</div>
			<div style="padding:3px 5px 1px 2px; float:left">
				<input type="text" name="nom" id="nom" style="width:170px" value="<?php echo $obj_select->nom;?>">
				
			</div>
			<div style="clear:both"></div>
		</div>
	</div>	
	<div style="padding-right:5px;margin:5px 0 0 40px;float:left;border:1px solid #cfcfcf;width:auto;height:180px">		
		<div class="full">			
			<div style="margin-top:0px">
				<p class="txt"><?php _e('Define the Colors of the product','woocommerce-awesome-designer');?> :</p>
			</div>
			<div id="list_colors" name="list_colors" style="margin-top:5px;width:100%;overflow-x:auto;height: 40px; overflow-y: hidden; white-space: nowrap;">
			</div>
			<div style="margin:5px 0 0 5px">
				<TEXTAREA style=" width:30em;height:4em;" name="colors" id="colors" rows=2 cols=42 placeholder=""  ><?php echo $obj_select->colors;?></TEXTAREA>
			</div>
			
			<?php if (count($array_deja)>0) { ?>
				<div style="">
					<p class="txt" style="font-size:80%;"><?php _e('Colors already used (you can pick them)','woocommerce-awesome-designer');?> :</p>
				</div>
				<div style="">	
					<div id="list_colors" name="list_colors" style="margin-top:5px; overflow-x: auto; height: 40px; overflow-y: hidden; white-space: nowrap;">
					<?php
					for ($i=0;$i<count($array_deja);$i++) {
						echo '<div onclick="add_color(\''.$array_deja[$i].'\');" style="display:inline-block;width:15px;height:15px;background-color:#'.$array_deja[$i].';margin-left:5px;"></div>';
					}?>
					</div>
				</div>
			<?php } ?>
		</div>
	</div>	
	<div style="padding:3px 5px 1px 2px; float:right">
		<a href="#" class="admin-button-petit-txt-delete" onclick="$('#action' ).val('delete_all');$('#form_effet' ).submit();"><?php _e('DELETE THIS PRODUCT','woocommerce-awesome-designer');?></a>
	</div>
	
			
			
	<div style="clear:both"></div>	
		
	<?php if ($results_multi) { ?>
	
		<div class="full">
			<div style="padding:2px 5px 1px 2px; float:left">
				<p class="txt"><?php _e("Display type",'woocommerce-awesome-designer');?> :</p>
			</div>
			<div style="display:inline;padding:3px 5px 1px 2px; float:left">
				<?php 
				if ($obj_select->affich_option=='txt') echo '<INPUT type="radio" name="affich_option" value="txt" checked="checked"><label for="Printing zone name"><span class="txt">'.__('Printing zone name','woocommerce-awesome-designer').'</span></label> - <INPUT type="radio" name="affich_option" value="img"><label for="Printing zone picture"><span class="txt">'.__('Printing zone picture','woocommerce-awesome-designer').'</span></label>';
				else  echo '<INPUT type="radio" name="affich_option" value="txt" ><label for="Printing zone name"><span class="txt">'.__('Printing zone name','woocommerce-awesome-designer').'</span></label> - <INPUT type="radio" name="affich_option" value="img" checked="checked"><label for="Printing zone picture"><span class="txt">'.__('Printing zone picture','woocommerce-awesome-designer').'</span></label>';
				?>
			</div>
					
			<div style="clear:both"></div>
		</div>
		
	
	<?php } ?>
		<div style="padding:0px 5px 1px 2px; float:right">
			<a href="#" class="admin-button-save" onclick="$('#action' ).val('save_all');$('#form_effet' ).submit();"><?php _e('SAVE CHANGES','woocommerce-awesome-designer');?></a>
		</div>			
		<div style="clear:both"></div>
		
		<div class="txt-cadre-gris" style="height:2px; padding:0px;" ></div>
		
	
			
	
	<?php   

		if ($results_multi) { 
		
		echo '<div class="full">';
		$compteur = 1;
		foreach ( $results_multi as $obj_select ) {
			
			echo '<div style="padding:7 5 1 2; float:left" ><a href="#" onclick="change_multi('.$compteur.');return false;" class="lien_produit">'.$compteur.' - '.$obj_select->nom_masque.'</a></div>';
			$compteur++;
		}
		echo '<div style="clear:both"></div></div>';
		$compteur = 1;
		foreach ( $results_multi as $obj_select ) {?>		
			
			<div id="div_<?php echo $compteur?>" class="full <?php if ($compteur!=1) echo 'cache'?> div_multi">
				<div class="25percent" style="top: 0px;bottom: 0px;right: 0px;width:29%; position: absolute;">
				
					<div class="full">
				
						<div style="padding:3px 5px 1px 2px; float:right">
							<a href="#" class="admin-button-petit-txt" onclick="change(<?php echo $compteur;?>);return false;"><?php _e('CHANGE PICTURE OR CROP','woocommerce-awesome-designer');?></a>
						</div>
						<div style="padding:3px 5px 1px 2px; float:right">
							<a href="#" class="admin-button-petit-txt" onclick="change_wtp(<?php echo $compteur;?>);return false;"><?php _e('CHANGE PRINT AREA','woocommerce-awesome-designer');?></a>
						</div>
						<div style="clear:both"></div>
					</div>
					
				</div>
		  
			
				
				<div style="top: 0px;bottom: 0px;left: 0px;width:70%; position: absolute;">		
					<div class="full">
						<div style="padding:0 5px 1px 2px; float:left">
							<p class="txt"><?php _e('PRINT AREA SIZE','woocommerce-awesome-designer');?> :</p>
						</div>
						<div style="padding:0 5px 1px 2px; float:left">
							<p class="txt"><?php echo $obj_select->width_prod.' x '. $obj_select->height_prod.' '. $obj_select->unit_prod;?></p>			
						</div>
							
					
						<div style="clear:both"></div>
					</div>
					<div class="full">
						<div style="border:1px solid black;width:<?php echo $obj_select->width_cad;?>px;height:<?php echo $obj_select->height_cad;?>px;z-index:10;">
							<div style="position:absolute;">
								<img src="<?php echo $path.$obj_select->image_back;?>" WIDTH="<?php echo $obj_select->width_cad;?>" HEIGHT="<?php echo $obj_select->height_cad;?>">
							</div>
							<div style="position:absolute;background-color:rgba(55,55,55,0.5);width:<?php echo $obj_select->width_canv;?>px;height:<?php echo $obj_select->height_canv;?>px;left:<?php echo $obj_select->left_canv;?>px;top:<?php echo $obj_select->top_canv;?>px;z-index:11;">
							</div>
							
						
						</div>
					</div>
				</div>
			</div>	
			
			
			
		<?php 
		$compteur++;
		}
	 } else { ?>
	 
		<div class="full">		
			<div class="25percent" style="top: 0px;bottom: 0px;right: 0px;width:29%; position: absolute;">
				
					<div class="full">
				
						<div style="padding:3px 5px 1px 2px; float:right">
							<a href="#" class="admin-button-petit-txt" onclick="change(1);return false;"><?php _e('CHANGE PICTURE OR CROP','woocommerce-awesome-designer');?></a>
						</div>
						<div style="padding:3px 5px 1px 2px; float:right">
							<a href="#" class="admin-button-petit-txt" onclick="change_wtp(1);return false;"><?php _e('CHANGE PRINT AREA','woocommerce-awesome-designer');?></a>
						</div>
						<div style="clear:both"></div>
					</div>
				
			</div>
		  
			
			
			<div style="top: 0px;bottom: 0px;left: 0px;width:70%; position: absolute;">		
				<div class="full">		
					<div style="padding:0 5px 1px 2px; float:left">
						<p class="txt"><?php _e('PRINT AREA SIZE','woocommerce-awesome-designer');?> :</p>
					</div>
					<div style="padding:0 5px 1px 2px; float:left">
						<p class="txt"><?php echo $obj_select->width_prod.' x '. $obj_select->height_prod.' '. $obj_select->unit_prod;?></p>			
					</div>
						
				
					<div style="clear:both"></div>
				</div>
				<div class="full">
					<div style="border:1px solid black;width:<?php echo $obj_select->width_cad;?>px;height:<?php echo $obj_select->height_cad;?>px;z-index:10;">
						<div style="position:absolute;">
							<img src="<?php echo $path.$obj_select->image_back;?>" WIDTH="<?php echo $obj_select->width_cad;?>" HEIGHT="<?php echo $obj_select->height_cad;?>">
						</div>
						<div style="position:absolute;background-color:rgba(55,55,55,0.5);width:<?php echo $obj_select->width_canv;?>px;height:<?php echo $obj_select->height_canv;?>px;left:<?php echo $obj_select->left_canv;?>px;top:<?php echo $obj_select->top_canv;?>px;z-index:11;">
						</div>			
					
					</div>
				</div>
			</div>
		</div>	
	<?php 
		} 
	?>
</div>		
		
	<div id="change_total" class="full cache" style="position:absolute;top:220px;left:0px;right:0px;bottom:10px;">
		<div class="txt-cadre-gris" style="height:2px; padding:0px; margin-top:-15px;" ></div>
		<div class="25percent" style="top: 0px;bottom: 0px;right: 0px;width:29%; position: absolute;">
			
			<div style="margin-top:10px;margin-bottom:10px;position:relative;width:100%;" >
					<a href="#" class="admin-button" onclick="close_change();"><?php _e('CANCEL CHANGE','woocommerce-awesome-designer');?></a>
			</div>	
			
			
			 <div class="div_upload ">
				<div style="margin-top:5px;" >
					<p class="txt"><?php _e('CHANGE IMAGE','woocommerce-awesome-designer');?></br><span class="txt-orange"><?php _e('(keep what your client will see)','woocommerce-awesome-designer');?></span></p>
				</div>
				<div style="margin-top:10px;" class="div_background <?php if ( $awesome_masq!='background') echo 'cache';?>">
					<p class="txt"><?php _e('Upload A Background','woocommerce-awesome-designer');?></br><span class="txt-orange">(JPG or PNG)</span></p>
				</div>
				<div style="margin-top:5px;" class="div_mask <?php if ( $awesome_masq!='masq') echo 'cache';?> ">
					<p class="txt"><?php _e('Upload A Mask','woocommerce-awesome-designer');?></br><span class="txt-orange">(PNG)</span></p>
				</div>
		<!-- BT UPLOAD --> 
				<div class="left2" style="margin-top:15px;">
					<div id="waiting_telechargement"  style="width:90%;margin:auto;margin-top:20px;z-index:10000000;opacity:1;" ></div>
				<div id="fileuploader"><?php _e( 'Upload', 'woocommerce-awesome-designer' );?></div>
				</div>
				
				<div class="left2" style="margin-top:15px; margin-left:15px">
					<a href="#" class="admin-button-petit-txt" onclick="$('#lightbox').addClass('galerie-view');"><?php _e('OR CHOOSE FROM GALLERY','woocommerce-awesome-designer');?></a>
				</div>
				<div style="clear:both"></div>
			</div>
			<div class="menu_change_div div_crop">
			<!-- 1-1 --> 
			<!-- TXT CROP --> 
				<div style="margin-top:5px;" >
					<p class="txt"><?php _e('CHANGE CROP','woocommerce-awesome-designer');?></br><span class="txt-orange"><?php _e('(keep what your client will see)','woocommerce-awesome-designer');?></span></p>
				</div>
					
		<!-- miniature image --> 
				<div  class="img-preview preview-md" style="float: left; overflow: hidden;width:100px;height:100px;margin:10 0 0 25;border:1px solid black"></div>		
		<!-- BT CROP --> 
			<div style="clear:both"></div>
				<div style="margin-top:10px;position:relative;width:100%;" >
					<a href="#" class="admin-button-save" onclick="valid_crop();"><?php _e('CROP IT','woocommerce-awesome-designer');?></a>
				</div>		
			</div>
			
			
			 <div class="div_wtp cache">

				<div style="margin-top:35px;"  >
					<p class="txt"><?php _e('Define the customization zone','woocommerce-awesome-designer');?></br><span class="txt-orange"><?php _e("(Adjust the area and don't forget the bleeds)",'woocommerce-awesome-designer');?></span></p>
				</div>
		<!-- taille du produit -->
					<div style="margin-top:5px;">
						<p class="txt"><?php _e('Type the <span style="font-weight:700">EXACT FINAL PRINT SIZE</span> of this product ','woocommerce-awesome-designer');?></br><span style="font-weight:700"><?php _e('(round numbers, no comma)','woocommerce-awesome-designer');?></span> :</p>
					</div>
					<div class="full">
						<div style="padding:0 5px 1px 2px; float:left">
							<p class="txt"><?php _e('WIDTH','woocommerce-awesome-designer');?> :</p>
						</div>
						<div style="padding:3px 5px 1px 2px; float:left">
							<input type="text" name="width" id="width" style="width:30px">
							
						</div>
						<div style="padding:0 5px 1px 2px; float:left">
							<p class="txt"><?php _e('HEIGHT','woocommerce-awesome-designer');?> :</p>
						</div>
						<div style="padding:3px 5px 1px 2px; float:left">
							<input type="text" name="height" id="height"  style="width:30px">
						
						</div>
							
						<div style="padding:1px 5px 1px 2px; float:left">
							<select style="margin-top:3px" id="mesure" name="mesure">
								<option value="mm"><?php _e('Millimeters','woocommerce-awesome-designer');?></option> 
								<option value="in"><?php _e('Inches','woocommerce-awesome-designer');?></option>
							</select>
						</div>				
						
						<div style="clear:both"></div>
					</div>	
		<!-- BT AREA -->  
				<div style="margin-top:15px">
					<a href="#" class="admin-button" onclick="valid_wtp()"><?php _e('DEFINE AREA','woocommerce-awesome-designer');?></a>
				</div>
		<!-- ajuste image --> 		
				
			</div>
					
			<div class="div_wtp cache">	
			
				<div style="margin-top:15px">
					<p class="txt"><?php _e('Optional: if your image doesn’t fit perfectly with</br>the Print Area, please adjust it','woocommerce-awesome-designer');?> :</p>
				</div>
				<div style="margin-top:10px">
					<div class="left2 hstretchplus" style="margin:0 10 0 30" onclick="strech('plus','width')"></div>
					<div class="left2 hstretchmoins" style="margin:0 10 0 10" onclick="strech('moins','width')"></div>
					<div class="left2 vstretchplus" style="margin:0 10 0 10" onclick="strech('plus','height')"></div>
					<div class="left2 vstretchmoins" style="margin:0 10 0 10" onclick="strech('moins','height')"></div>
					<div style="clear:both"></div>
				</div>
				<div style="margin-top:1px; vertical-align:center; text-align:center">
					<div class="left2" style="margin:0 10px 0 35px" onclick="">
						<p class="mini-txt"><?php _e('horizontal</br>stretch','woocommerce-awesome-designer');?> <span style="font-size:150%">+</span></p>
					</div>
					<div class="left2" style="margin:0 10px 0 17px" onclick="">
						<p class="mini-txt"><?php _e('horizontal</br>stretch','woocommerce-awesome-designer');?> <span style="font-size:150%">-</span></p>
					</div>
					<div class="left2" style="margin:0 10px 0 20px" onclick="">
						<p class="mini-txt"><?php _e('vertical</br>stretch','woocommerce-awesome-designer');?> <span style="font-size:150%">+</span></p>
					</div>
					<div class="left2" style="margin:0 10px 0 22px" onclick="">
						<p class="mini-txt"><?php _e('vertical</br>stretch','woocommerce-awesome-designer');?> <span style="font-size:150%">-</span></p>
					</div>
					<div style="clear:both"></div>
				</div>
			</div>
			<div class="div_wtp cache">	
				<div style="margin-top:20px; margin-bottom:50px">
					<a href="#" class="admin-button-save" onclick="save()"><?php _e('OK, DONE','woocommerce-awesome-designer');?></a>
				</div>
			</div>
		</div>	
		<div id="cont_canvas_surcouch" class="cont_canvas_surcouch" style="top: 0px;bottom: 0px;left: 0px;width:70%; position: absolute;">					
			<div id="cont_canvas" name="cont_canvas" class="cont_canvas" style="top: 5px;bottom: 5px;left: 5px;right: 5px; position: absolute;"  >
				<img  id="image_crop"   src="<?php echo $url_dossier_awesome;?>files/img/interface-admin/blank_pixel.png"  style="display:none;">				
			</div>	
		</div>	
		
		<div style="clear:both"></div>
	</div>

	<?php echo $form;?>
	<input type="hidden" name="awesome_name" id="awesome_name" value="">
	<input type="hidden" name="awesome_left" id="awesome_left" value="">
	<input type="hidden" name="awesome_top" id="awesome_top" value="">
	<input type="hidden" name="awesome_width" id="awesome_width" value="">
	<input type="hidden" name="awesome_height" id="awesome_height" value="">
	<input type="hidden" name="awesome_extension" id="awesome_extension" value="">
	<input type="hidden" name="awesome_id_prod" id="awesome_id_prod" value="">
	
	<input type="hidden" name="awesome_parent" id="awesome_parent" value="">
	<input type="hidden" name="awesome_folder" id="awesome_folder" value="">
	<input type="hidden" name="awesome_masq" id="awesome_masq" value="<?php echo $awesome_masq;?>">
	
	<input type="hidden" name="save_width" id="save_width" value="">
	<input type="hidden" name="save_height" id="save_height" value="">
	<input type="hidden" name="save_canv_width" id="save_canv_width" value="">
	<input type="hidden" name="save_canv_height" id="save_canv_height" value="">
	<input type="hidden" name="save_canv_top" id="save_canv_top" value="">
	<input type="hidden" name="save_canv_left" id="save_canv_left" value="">
	
	<input type="hidden" name="save_prod_width" id="save_prod_width" value="">
	<input type="hidden" name="save_prod_height" id="save_prod_height" value="">
	<input type="hidden" name="save_prod_unit" id="save_prod_unit" value="">



	
	
	<script > 	
	
		var PLUGIN = '<?php echo $url_dossier_awesome;?>';
		var IMAGE = '<?php echo $url_dossier_awesome.'files/img/interface/';?>';
		var IMAGE_INTERF = '<?php echo $url_dossier_awesome.'files/img/interface-canvas/';?>';
		var IMAGE_PROD = '<?php echo $path;?>';
		var new_name = '<?php echo $new_name;?>';
		var en_cours;
		var objet_max = 1;
		var taille_crea = <?php echo TAILLE_CREA;?>;
		var msg_wtp = "<?php _e('Printing zone','woocommerce-awesome-designer');?>";
		var is_mobile = 0;
		var text_drag_drop = "<?php _e( "Drag'n drop your photos", 'woocommerce-awesome-designer' );?>";
	
		var upload_back = '<?php echo site_url();?>/index.php?awesome_designer_back_upload_back=creation';
		var  url_sav_masq = '<?php echo site_url();?>/index.php?awesome_designer_back_sav_masq=1';
		var  url_crop_creation = '<?php echo site_url();?>/index.php?awesome_designer_back_crop_creation=1';
		$(document).ready( function () {	
		
			$.lazyLoadXT.scrollContainer = '.wrapper';
			
			$("#fileuploader").uploadFile({
				url:upload_back,
				fileName:"myfile",			
				acceptFiles:".jpg,.jpeg, .png",
				showAbort:true,			
				showPreview:false,
				spec_module:'modif',
				maxFileCount:1,
				showQueueDiv:'waiting_telechargement',
				showCancel:true
			});
		
			
			
			
			document.getElementById("colors").onkeyup = valid_color;
			document.getElementById("colors").onkeydown = valid_color;
			valid_color();	
			
			setTimeout(function(){
				$(".sous_galerie").removeClass("galerie-cache");							
				$(".sous_galerie").addClass("cache");	
				$(".sous_galerie").first().removeClass("cache");
			},1000);
					
		} ) ;
		
		
		
	
	</script>
	<?php } ?>


</form>



<script > 	
	
	function test(e){
		window.parent.postMessage(e, "*");
	}
	$(document).ready( function () {	
		
			jQuery(".css-awesome").select2();

					
		} ) ;
	
	
</script > 
<?php } ?>