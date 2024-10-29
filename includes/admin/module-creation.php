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
$text_color =  __('Type your colors here','woocommerce-awesome-designer');

$id_multi = time();
$nbe_tot = 1;
$id_current = 1;
$ProductName=$awesome_type = $awesome_masq = '';
$is_current_multi = false;
$is_finitio_multi = false;

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


if (isset($_POST) && !empty($_POST)){
	
	
    foreach ($_POST as $key => $value){
		
        $POST_Secured[$key] = htmlentities($value, ENT_QUOTES);
    }
	
	if (isset( $POST_Secured['FaceName'])) $FaceName=$POST_Secured['FaceName'];
	else $FaceName='';
	
	if (isset( $POST_Secured['valid']) and  $POST_Secured['valid']!='') {
		
		//save produit mult
		$sav_multi = $POST_Secured["sav_multi"];		
		$list_total = explode('$_$',$sav_multi);
		$id_multi = uniqid();
		
		$colors_multi= $POST_Secured["colors_multi"];	
		$affich_option = $POST_Secured["affich_option"];	
		$ProductName=$POST_Secured["ProductName"];		
		for ($i=0;$i<count($list_total);$i++) {
			$table_name = $wpdb->prefix . 'the_awe_des_awesome_product';
			$sous_liste = explode('$-$',$list_total[$i]);
			if ($i==0) {
				$is_visible=1;
				$color = $colors_multi;
				$choix = $affich_option;
			} else {
				$is_visible=0;
				$ProductName=$choix =$color = '';
				
			}
			$sav_atrr = array(
			'nom' => $ProductName,
				'nom_masque' => $sous_liste[1],
				'obj_order' => $sous_liste[2],
				'image_masq' => $sous_liste[3],
				'id_multi' => $id_multi,
				'image_back' => $sous_liste[5],
				'TIME' => $sous_liste[6],
				'width_cad' => $sous_liste[7],
				'height_cad' => $sous_liste[8],
				'width_canv' => $sous_liste[9],
				'height_canv' => $sous_liste[10],
				'top_canv' => $sous_liste[11],
				'left_canv' => $sous_liste[12],
				'width_prod' => $sous_liste[13],
				'height_prod' => $sous_liste[14],
				'unit_prod' => $sous_liste[15],
				'image_par' => $sous_liste[16],
				'folder' => $sous_liste[17],
				'colors' => $color,
				'is_visible' => $is_visible,
				'affich_option' => $choix					
			);
								
			$wpdb->insert(
				$table_name ,
				$sav_atrr
			);	
			
		}
		$ProductName=$awesome_type = $awesome_masq = '';
		$is_ok = true;
	
	} else if (isset( $POST_Secured['save_height'])) {
		
		$img_masq='';
		if ($POST_Secured["awesome_masq"]!='background') $img_masq=$POST_Secured["awesome_name"].'_masq.'.$POST_Secured["awesome_extension"];
		
		$nbe_tot = $POST_Secured["nbe_tot"];
		$id_current=$POST_Secured["id_current"];
		$awesome_type=$POST_Secured["awesome_type"];
		$awesome_masq=$POST_Secured["awesome_masq"];
		$ProductName=$POST_Secured["ProductName"];
		if ($POST_Secured["colors"]==$text_color) $POST_Secured["colors"] = '';
				
		$sav_atrr = array(
					'nom' => $POST_Secured["ProductName"],
					'nom_masque' => $FaceName,
					'obj_order' => ($id_current-1),
					'image_masq' => $img_masq,
					
					'id_multi' => 0,
					'image_back' => $POST_Secured["awesome_name"].'_crop.'.$POST_Secured["awesome_extension"],
					'TIME' => current_time( 'mysql' ),
					'width_cad' => $POST_Secured["save_width"],
					'height_cad' => $POST_Secured["save_height"],
					'width_canv' => $POST_Secured["save_canv_width"],
					'height_canv' => $POST_Secured["save_canv_height"],
					'top_canv' => $POST_Secured["save_canv_top"],
					'left_canv' => $POST_Secured["save_canv_left"],
					'width_prod' => $POST_Secured["save_prod_width"],
					'height_prod' => $POST_Secured["save_prod_height"],
					'unit_prod' => $POST_Secured["save_prod_unit"],
					'image_par' => $POST_Secured["awesome_parent"].'.'.$POST_Secured["awesome_extension"],
					'folder' => $POST_Secured["awesome_folder"],
					'colors' => $POST_Secured["colors"],
					'is_visible' => 1,
				);
		
		if ($nbe_tot == 1) {
			$table_name = $wpdb->prefix . 'the_awe_des_awesome_product';
			$wpdb->insert(
				$table_name ,
				$sav_atrr
			);	
			$is_ok = true;
			$ProductName=$awesome_type = $awesome_masq = '';
		} else {
			//echo $POST_Secured["sav_multi"];
			if ($POST_Secured["sav_multi"]!='') $sav_multi = $POST_Secured["sav_multi"].'$_$'.implode('$-$',$sav_atrr);
			else $sav_multi = implode('$-$',$sav_atrr);
			
			if ($id_current>$nbe_tot) {
				
				$is_current_multi = true;
				$is_finitio_multi = true;
			} else {
				$is_current_multi = true;
			
			
			}			
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


<link type="text/css" rel="stylesheet" href="<?php echo $url_dossier_awesome;?>files/css/cropper.css">
<link type="text/css" rel="stylesheet" href="<?php echo $url_dossier_awesome;?>files/css/admin.css">
<link type="text/css" rel="stylesheet" href="<?php echo $url_dossier_awesome;?>files/css/file_upload.css">
<script src="//code.jquery.com/jquery-2.1.4.min.js"></script>
<script src="<?php echo $url_dossier_awesome;?>files/js/file_upload.js"></script>
<script src="<?php echo $url_dossier_awesome;?>files/js/admin-creation.js?ver=1.0"></script>	
<script src="<?php echo $url_dossier_awesome;?>files/js/cropper.js"></script>	
<script src="<?php echo $url_dossier_awesome;?>files/js/fabric-admin.js"></script>	
<script src="<?php echo $url_dossier_awesome;?>files/js/lazzy.js"></script>	

</head>

<body topmargin="0" style="height:100%;">



<!-- header --> 
<?php include(THE_AWE_DES_AWESOME_INCLUDES.'admin/module-header.php'); 
include(THE_AWE_DES_AWESOME_INCLUDES.'admin/galerie.php'); 

if ($is_ok) { ?>
<div class="full debut <?php if ($is_current_multi) echo 'cache';?>" style="padding:5 0 0 0">
	<p class="txt" style="font-size:110%;font-weight:700;color:red;"><?php _e('YOUR PRODUCT IS CREATED','woocommerce-awesome-designer');?></p>
	
	<div style="margin-top:20px; margin-bottom:20px">
		<a href="index.php?awesome_designer_back_popup=creation" class="admin-button-petit-txt" ><?php _e('CREATE NEW PRODUCTS','woocommerce-awesome-designer');?></a>
	</div>
	<div class="txt" style="position:relative:width:100%;">
	Good job! What next?<BR><BR>

	1- go to the main Woocommerce tab named "products" and edit the product you want to associate.<BR><br>
		<img   src="<?php echo $url_dossier_awesome;?>files/img/interface-admin/link-product-step1.png" style="width:80%">
		<br><br>
	2-Go to the "AwesomeDesigner" tab, as below, and select the new awesome product you've just created.
	<br><br>
		 <img   src="<?php echo $url_dossier_awesome;?>files/img/interface-admin/link-product-step2.png"style="width:80%">
	<br><br>
	3- That's all, you are ready to sell your customizable product.
	<br><br><br><br>
	</div>
</div>
<?php } else { ?>
<div class="full debut <?php if ($is_current_multi) echo 'cache';?>" style="padding:5 0 0 0">
	<p class="txt" style="font-size:110%;font-weight:700"><?php _e("Create your own product ! You're the boss",'woocommerce-awesome-designer');?> :</p>
</div>


<form method="post" id="form_effet" name="form_effet" > 
<!-- choix 1 : choix single ou multi photo --> 
<div class="menu div_type debut <?php if ($is_current_multi) echo 'cache';?>">
<!-- single --> 
	<div class="left-crea" onclick="valid_etap('type','one');">
		<div class="icone-crea1 type one"></div>
		<div class="bloc_div" style="width:160px" >
			<p class="txt" style="margin-left:15px"><?php _e('Single Print','woocommerce-awesome-designer');?></p>
		</div>
	</div>
<!-- multi --> 	
	<div class="right-crea" onclick="valid_etap('type','multi');">
		<div class="icone-crea2 type multi"></div>
		<div class="bloc_div" style="width:160px" >
			<p class="txt" style="margin-left:15px"><?php _e('Multi Print','woocommerce-awesome-designer');?></p>
		</div>
	</div>	
	<div style="clear:both"></div>
</div>

<!-- separator --> 
<div class="txt-cadre-gris div_type debut <?php if ($is_current_multi) echo 'cache';?>" style="height:5px; padding:0px;" ></div>

<!-- choix 2 : choix masque ou background --> 
<div class="menu cache div_masq debut <?php if ($is_current_multi) echo 'cache';?>"  >

	
<!-- masque --> 	
	<div class="left-crea" onclick="valid_etap('masq','mask');">
		<div class="icone-crea3 masq mask"></div>
		<div class="bloc_div" style="width:160px;" >
			<p class="txt" style="margin-left:15px"><?php _e('Full Size Print Zone</br>with Mask above','woocommerce-awesome-designer');?></p>
		</div>
	</div>
<!-- background --> 	
	<div class="right-crea" onclick="valid_etap('masq','background');">
		<div class="icone-crea4 masq background"></div>
		<div class="bloc_div" style="width:160px" >
			<p class="txt" style="margin-left:15px"><?php _e('Print Zone inside</br>with Background','woocommerce-awesome-designer');?></p>
		</div>
	</div>	
	

	
	<div style="clear:both"></div>
		<div class="icone-quicktip" style="position:absolute; top:30px; left: 20%;" onclick="$('#quicktip').toggleClass('cache');">
	</div>
	<div id="quicktip" class="quicktip visible-tip cache" style="position:relative;margin:auto;margin-top:20px;">
	</div>
	
</div>

<!-- separator --> 
<div class="txt-cadre-gris cache div_masq debut <?php if ($is_current_multi) echo 'cache';?>" style="height:5px; padding:0px;"></div>

<!-- nom du produit -->
<div class="full <?php if (!$is_current_multi) echo 'cache';?> div_name" >
 	<div style="padding:15 5 2 2; float:left">
		<p class="txt"><?php _e('Name your Product','woocommerce-awesome-designer');?> :</p>
	</div>
	<div style="padding:19 5 2 2; float:left">
		<input style="width:170px; height:20px" type="text" id="ProductName" name="ProductName" value="<?php echo $ProductName; ?>">
	</div>
	<div class="nbe_prod multi_produc <?php if (!$is_current_multi) echo 'cache';?>" style="padding:15 5 2 2; float:left;">
		<p class="txt"><?php _e('Number of printing zone','woocommerce-awesome-designer');?> :</p>
	</div>
	<div class="multi_produc <?php if (!$is_current_multi) echo 'cache';?> " style="padding:19 5 2 2; float:left;">
		<select name="number_product" id="number_product">
		<?php for ($i=2;$i<20;$i++) echo '<option value="'.$i.'">'.$i.'</option>'; ?>
		</select>
	</div>
	<div class="nbe_prod cache result_nbe" style="padding:15 5 2 2; float:left;">
		<p class="txt"><?php echo $nbe_tot;?></p>
	</div>
	<div class="reset <?php if (!$is_current_multi) echo 'cache';?> "  style="padding:15 5 2 2; float:right">
		<p class="txt"><a class="admin-button" href="index.php?awesome_designer_back_popup=creation"><?php _e('RESET','woocommerce-awesome-designer');?></a></p>
	</div>
	<div style="clear:both"></div>
</div>

<!-- BT NEXT --> 
<div class="full cache div_name input_name" >
 	<div style="margin-top:8px" >
		<a href="#" class="admin-button" onclick="if ($('#ProductName').val()!='') {valid_etap('name',$('#ProductName').val());}"><?php _e('NEXT','woocommerce-awesome-designer');?></a>
	</div>
</div>



<!-- separator --> 
<div class="txt-cadre-gris <?php if (!$is_current_multi) echo 'cache';?> div_name" style="height:2px; padding:0px;" ></div>



<div class="full <?php if (!$is_current_multi) echo 'cache';?>  fin" style="position:absolute;top:190px;left:0px;right:0px;bottom:10px;">

	<div class="25percent" style="top: 0px;bottom: 0px;right: 0px;width:29%; position: absolute;">
		
		<div class="div_multi <?php if (!$is_current_multi) echo 'cache';?> ">
			
			<?php if ($is_finitio_multi) { ?>
			
			<div style="margin-top:35px">
				<p class="txt">1- <?php _e('Define the Colors of the Product (default : white)','woocommerce-awesome-designer');?></br><span class="txt-orange"><?php _e('hexadecimal, separated with commas','woocommerce-awesome-designer');?>.</br>&nbsp;( 45ec78, 004400, be48ef, ffffff )</span></p>
			</div>
	<!-- SAISIE COLORS --> 
			
			<div id="list_colors_multi" name="list_colors_multi" style="margin-top:5px;width:100%;overflow-x:auto;height:40px; overflow-y: hidden; white-space: nowrap;">
			</div>
			<div style="margin:5 0 0 5">
				<TEXTAREA name="colors_multi" id="colors_multi" rows=2 cols=42 placeholder="<?php echo $text_color;?>"  ></TEXTAREA>
			</div>
			
			
			<?php if (count($array_deja)>0) { ?>
				<div style="">
					<p class="txt" style="font-size:80%;"><?php _e('Colors already used (you can pick them)','woocommerce-awesome-designer');?> :</p>
				</div>
				<div style="">	
					<div id="list_colors" name="list_colors" style="margin-top:5px; overflow-x: auto; height: 40px;  overflow-y: hidden; white-space: nowrap;">
					<?php
					for ($i=0;$i<count($array_deja);$i++) {
						echo '<div onclick="add_color(\''.$array_deja[$i].'\');change_canv_color(\''.$array_deja[$i].'\')" style="display:inline-block;width:15px;height:15px;background-color:#'.$array_deja[$i].';margin-left:5px;"></div>';
					}?>
					</div>
				</div>
			<?php } ?>
			
			<div style="margin-top:35px">
				<p class="txt">2- <?php _e("Choose the display type",'woocommerce-awesome-designer');?></br><span class="txt-orange"><?php _e('For the front office','woocommerce-awesome-designer');?></span></p>
			</div>
			<div style="margin:5 0 0 5">
				<p class="txt"><INPUT type="radio" name="affich_option" value="txt" checked="checked"><?php _e('Printing zone name','woocommerce-awesome-designer');?> - <INPUT type="radio" name="affich_option" value="img" selected=selected><?php _e('Printing zone picture','woocommerce-awesome-designer');?></p>
			</div>
			<div style="margin-top:20px; margin-bottom:50px">
				<a href="#" class="admin-button" onclick="valid_name()"><?php _e('OK, DONE','woocommerce-awesome-designer');?></a>
			</div>
			
			
			<?php } else { ?>
				<div class="" style="margin-top:5px; float:left;">
					<p class="txt"><?php _e('Printing zone number','woocommerce-awesome-designer');?> :</p>
				</div>
				<div class="num_current" style="margin-top:5px; float:left;">
					<p class="txt"><?php echo $id_current.'/'.$nbe_tot ;?> </p>
				</div>
				<div style="clear:both"></div>
				<div style="padding:15 5 2 0; float:left">
					<p class="txt"><?php _e('Name your printing zone ','woocommerce-awesome-designer');?> :</p>
				</div>
				<div style="padding:19 5 2 2; float:left">
					<input style="width:150px; height:20px" type="text" id="FaceName" name="FaceName">
				</div>
				<div style="clear:both"></div>
				<div class="valid_multi" style="margin:15 10 0 0;float:left" >
					<a href="#" class="admin-button" onclick="if ($('#FaceName').val()!='') {$('.valid_multi').addClass('cache');$('.div_upload').removeClass('cache');}"><?php _e('NEXT','woocommerce-awesome-designer');?></a>
				</div>
				<div style="clear:both"></div>
			<!--	<div class="chang_upload_multi cache" style="margin:15 10 0 0;float:left" >
					<a href="#" class="admin-button" onclick="reinit_imag_multi()"><?php _e('CHANGE IMAGE','woocommerce-awesome-designer');?></a>
				</div>-->
				<div style="clear:both"></div>	
			
			<?php }  ?>
		</div>
		
<!-- 1 --> 
<!-- TXT UPLOAD --> 
		 <div class="div_upload <?php if ($is_current_multi) echo 'cache';?>">
			<div style="margin-top:10px;" class="div_background <?php if (!$is_current_multi || $awesome_masq!='background') echo 'cache';?>">
				<p class="txt">1- <?php _e('Upload A Background','woocommerce-awesome-designer');?></br><span class="txt-orange">(JPG or PNG)</span></p>
			</div>
			<div style="margin-top:5px;" class="div_mask <?php if (!$is_current_multi || $awesome_masq!='masq') echo 'cache';?> ">
				<p class="txt">1- <?php _e('Upload A Mask','woocommerce-awesome-designer');?></br><span class="txt-orange">(PNG)</span></p>
			</div>
	<!-- BT UPLOAD --> 
			<div class="left2" style="margin-top:15px;">
				<div id="waiting_telechargement"  style="width:90%;margin:auto;margin-top:20px;z-index:10000000;opacity:1;" ></div>
				<div id="fileuploader"><?php _e( 'Upload', 'woocommerce-awesome-designer' );?></div>
			</div>
			<?php //if ($results_lightbox) {?>
				<div class="left2" style="margin-top:15px; margin-left:15px">
					<a href="#" class="admin-button-petit-txt" onclick="$('#lightbox').addClass('galerie-view');"><?php _e('OR CHOOSE FROM GALLERY','woocommerce-awesome-designer');?></a>
				</div>
			<?php //}  ?>
			<div style="clear:both"></div>
		</div>
		<div class="menu_multi div_crop cache">
			<div class="chang_upload " style="margin-top:5px;" >
					<a href="#" class="admin-button" onclick="reinit_imag()"><?php _e('CHANGE IMAGE','woocommerce-awesome-designer');?></a>
			</div> 
		<!-- 1-1 --> 
		<!-- TXT CROP --> 
			<div style="margin-top:5px;" >
				<p class="txt"><?php _e('If needed, crop your image','woocommerce-awesome-designer');?></br><span class="txt-orange"><?php _e('(keep what your client will see)','woocommerce-awesome-designer');?></span></p>
			</div>
				
	<!-- miniature image --> 
			<div  class="img-preview preview-md" style="float: left; overflow: hidden;width:100px;height:100px;margin:10 0 0 25;border:1px solid black"></div>		
	<!-- BT CROP --> 
		<div style="clear:both"></div>
			<div style="margin-top:10px;position:relative;width:100%;" >
				<a href="#" class="admin-button" onclick="valid_crop();"><?php _e('CROP IT','woocommerce-awesome-designer');?></a>
			</div>		
		</div>


		
<!-- 2 --> 
<!-- TXT AREA --> 
	 <div class="menu_multi div_canv cache">
			<div class="chang_upload " style="margin-top:5px;" >
					<a href="#" class="admin-button" onclick="reinit_imag()"><?php _e('CHANGE IMAGE','woocommerce-awesome-designer');?></a>
			</div> 
		<div style="margin-top:15px;"  >
			<p class="txt">2- <?php _e('Define the customization zone','woocommerce-awesome-designer');?></br><span class="txt-orange"><?php _e("(Adjust the area and don't forget the bleeds)",'woocommerce-awesome-designer');?></span></p>
		</div>
<!-- taille du produit -->
			<div style="margin-top:5px;">
				<p class="txt"><?php _e('Type the <span style="font-weight:700">EXACT FINAL PRINT SIZE</span> of this product ','woocommerce-awesome-designer');?></br><span style="font-weight:700"><?php _e('(round numbers, no comma)','woocommerce-awesome-designer');?></span> :</p>
			</div>
			<div class="full">
				<div style="padding:0 5 1 2; float:left">
					<p class="txt"><?php _e('WIDTH','woocommerce-awesome-designer');?> :</p>
				</div>
				<div style="padding:3 5 1 2; float:left">
					<input type="text" name="width" id="width" style="width:30px">
					
				</div>
				<div style="padding:0 5 1 2; float:left">
					<p class="txt"><?php _e('HEIGHT','woocommerce-awesome-designer');?> :</p>
				</div>
				<div style="padding:3 5 1 2; float:left">
					<input type="text" name="height" id="height"  style="width:30px">
				
				</div>
					
				<div style="padding:1 5 1 2; float:left">
					<select style="margin-top:3px" id="mesure" name="mesure">
						<option value="mm" selected><?php _e('Millimeters','woocommerce-awesome-designer');?></option> 
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
			
		<div class="menu_multi div_strech cache">	
		
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
				<div class="left2" style="margin:0 10 0 35" onclick="">
					<p class="mini-txt"><?php _e('horizontal</br>stretch','woocommerce-awesome-designer');?> <span style="font-size:150%">+</span></p>
				</div>
				<div class="left2" style="margin:0 10 0 17" onclick="">
					<p class="mini-txt"><?php _e('horizontal</br>stretch','woocommerce-awesome-designer');?> <span style="font-size:150%">-</span></p>
				</div>
				<div class="left2" style="margin:0 10 0 20" onclick="">
					<p class="mini-txt"><?php _e('vertical</br>stretch','woocommerce-awesome-designer');?> <span style="font-size:150%">+</span></p>
				</div>
				<div class="left2" style="margin:0 10 0 22" onclick="">
					<p class="mini-txt"><?php _e('vertical</br>stretch','woocommerce-awesome-designer');?> <span style="font-size:150%">-</span></p>
				</div>
				<div style="clear:both"></div>
			</div>
		</div>
		<div class="menu_multi div_couleur cache">	
	<!-- 3 --> 
	<!-- TXT COLORS --> 
			<div style="margin-top:15px">
				<p class="txt">3- <?php _e('Define the Colors of the Product (default : white)','woocommerce-awesome-designer');?></br><span class="txt-orange"><?php _e('hexadecimal, separated with commas','woocommerce-awesome-designer');?>.&nbsp;(45ec78,004400,ffffff)</span></p>
			</div>
	<!-- SAISIE COLORS --> 
			<div id="list_colors" name="list_colors" style="margin-top:5px;width:100%;overflow-x:auto;height: 40px; overflow-y: hidden; white-space: nowrap;display:none;">
			</div>
			<div style="margin:5 0 0 5">
				<TEXTAREA name="colors" id="colors" rows=2 cols=42 placeholder="<?php echo $text_color;?>"  ></TEXTAREA>
			</div>
				
			
			<?php if (count($array_deja)>0) { ?>
				<div style="">
					<p class="txt" style="font-size:80%;"><?php _e('Colors already used (you can pick them)','woocommerce-awesome-designer');?> :</p>
				</div>
				<div style="">	
					<div id="list_color" name="list_color" style="margin-top:5px; overflow-x: auto; height: 40px; overflow-y: hidden; white-space: nowrap;">
					<?php
					for ($i=0;$i<count($array_deja);$i++) {
						echo '<div onclick="add_color(\''.$array_deja[$i].'\');change_canv_color(\''.$array_deja[$i].'\')" style="display:inline-block;width:15px;height:15px;background-color:#'.$array_deja[$i].';margin-left:5px;"></div>';
					}?>
					</div>
				</div>
			<?php } ?>
			
			<div style="margin-top:10px; margin-bottom:50px">
				<a href="#" class="admin-button" onclick="valid_name()"><?php _e('OK, DONE','woocommerce-awesome-designer');?></a>
			</div>
			
		</div>
		<div class="menu_multi div_next cache">
			<div style="margin-top:10px; margin-bottom:50px">
				<a href="#" class="admin-button" onclick="save()"><?php _e('NEXT','woocommerce-awesome-designer');?></a>
			</div>
			
		</div>		
	</div>
	
	<!-- CANVAS --> 
	
		<div id="cont_canvas_surcouch" class="cont_canvas_surcouch" style="top: 0px;bottom: 0px;left: 0px;width:70%; position: absolute;">					
			<div id="cont_canvas" name="cont_canvas" class="cont_canvas" style="top: 5px;bottom: 5px;left: 5px;right: 5px; position: absolute;"  >
				
				 <img id="image_crop"  src="<?php echo $url_dossier_awesome;?>files/img/interface-admin/blank_pixel.png" style="display:none;">
			</div>	
		</div>	
	
<!-- BT FIN --> 

	<div style="clear:both"></div>
</div>
	
				<input type="hidden" name="valid" id="valid" value="">
				<input type="hidden" name="action" id="action" value="">
				<input type="hidden" name="cible" id="cible" value="">
				<input type="hidden" name="sav_multi" id="sav_multi" value="<?php echo $sav_multi;?>">
				<input type="hidden" name="id_multi" id="id_multi" value="<?php echo $id_multi;?>">
				<input type="hidden" name="id_current" id="id_current" value="<?php echo $id_current;?>">			
				<input type="hidden" name="nbe_tot" id="nbe_tot" value="<?php echo $nbe_tot;?>">
				
				<input type="hidden" name="awesome_parent" id="awesome_parent" value="">
				
				<input type="hidden" name="awesome_name" id="awesome_name" value="">
				<input type="hidden" name="awesome_left" id="awesome_left" value="">
				<input type="hidden" name="awesome_top" id="awesome_top" value="">
				<input type="hidden" name="awesome_width" id="awesome_width" value="">
				<input type="hidden" name="awesome_height" id="awesome_height" value="">
				<input type="hidden" name="awesome_extension" id="awesome_extension" value="">
				
				<input type="hidden" name="awesome_folder" id="awesome_folder" value="">
				
				<input type="hidden" name="awesome_type" id="awesome_type" value="<?php echo $awesome_type;?>">
				<input type="hidden" name="awesome_masq" id="awesome_masq" value="<?php echo $awesome_masq;?>">
				<input type="hidden" name="valid_color" id="valid_color" value="">
				<input type="hidden" name="save_width" id="save_width" value="">
				<input type="hidden" name="save_height" id="save_height" value="">
				<input type="hidden" name="save_canv_width" id="save_canv_width" value="">
				<input type="hidden" name="save_canv_height" id="save_canv_height" value="">
				<input type="hidden" name="save_canv_top" id="save_canv_top" value="">
				<input type="hidden" name="save_canv_left" id="save_canv_left" value="">
				
				<input type="hidden" name="save_prod_width" id="save_prod_width" value="">
				<input type="hidden" name="save_prod_height" id="save_prod_height" value="">
				<input type="hidden" name="save_prod_unit" id="save_prod_unit" value="">
				
				
			</form>
</body>
</html>


<script > 	
	var PLUGIN = '<?php echo $url_dossier_awesome;?>';
	var IMAGE = '<?php echo $url_dossier_awesome.'files/img/interface/';?>';
	var IMAGE_INTERF = '<?php echo $url_dossier_awesome.'files/img/interface-canvas/';?>';
	var is_changing =false;
	var objet_max=1;
	var left_magnetic='';
	var nbe_face = <?php echo $nbe_tot;?>;
	var current_face = <?php echo $id_current;?>;
	var PATH = '<?php echo $path;?>';
	var new_name = '<?php echo $new_name;?>';
	var taille_crea = <?php echo TAILLE_CREA;?>;
	var is_mobile = 0;
	var text_drag_drop = "<?php _e( "Drag'n drop your photos", 'woocommerce-awesome-designer' );?>";
	
	var upload_back = '<?php echo site_url();?>/index.php?awesome_designer_back_upload_back=creation';
	var recup_back = '<?php echo site_url();?>/index.php?awesome_designer_back_recup_name=1';
	 
	var  url_crop_creation = '<?php echo site_url();?>/index.php?awesome_designer_back_crop_creation=1';
	var  url_sav_masq = '<?php echo site_url();?>/index.php?awesome_designer_back_sav_masq=1';
	 
	$(document).ready( function () {	
		
		$.lazyLoadXT.scrollContainer = '.wrapper';
		
		<?php if (!$is_finitio_multi) { ?>
		document.getElementById("colors").onkeyup = valid_color;
		document.getElementById("colors").onkeydown = valid_color;
	<?php } else { ?>
		document.getElementById("colors_multi").onkeyup = valid_color;
		document.getElementById("colors_multi").onkeydown = valid_color;
	<?php } ?>
		
			
	
		$("#fileuploader").uploadFile({
			url:upload_back,
			fileName:"myfile",			
			acceptFiles:".jpg,.jpeg, .png",
			showAbort:true,			
			showPreview:false,
			spec_module:'creation',
			maxFileCount:1,
			showQueueDiv:'waiting_telechargement',
			showCancel:true
		});
	

	
		setTimeout(function(){
			$(".sous_galerie").removeClass("galerie-cache");							
			$(".sous_galerie").addClass("cache");	
			$(".sous_galerie").first().removeClass("cache");
		},1000);
				
	} ) ;
	
	

	
	
	
	
	
	function valid_name(){			
		
		$.ajax({
			url: recup_back,
			type: "POST",
			data: $("input[name*='ProductName']").serialize(),			
			success: function(c) {
				
				if (c.trim()=='ok'){
					valid_color();
					if ($('#awesome_type').val()=='multi'){
						
						if ($('#valid_color').val()==1) save_multi();
						else alert("<?php _e('A color is incorrect','woocommerce-awesome-designer');?>");
					} else {
						if ($('#valid_color').val()==1) save();
						else alert("<?php _e('A color is incorrect','woocommerce-awesome-designer');?>");
					}
					
				} else {
						alert("<?php _e('Name of product already exist','woocommerce-awesome-designer');?>");
					$('#ProductName').css('border-color','red');
				}
			},error: function(c) {
				alert("<?php _e('Name of product already exist','woocommerce-awesome-designer');?>");
				$('#ProductName').css('border-color','red');
			}
		})
		
	}
	
	

	
	
	function valid_wtp(){
	 
		if ($('#width').val()!='' && $('#height').val()!=''){
	 
			CraPacrAxx.clear();
			
			height_area = CraPacrAxx.height -20;
			width_area = ((CraPacrAxx.height -20)*$('#width').val())/$('#height').val();
			
			if (width_area>CraPacrAxx.width) {
				width_area = CraPacrAxx.width -20;
				height_area = ((CraPacrAxx.width -20)*$('#height').val())/$('#width').val();
			}
			
			$('#save_prod_width').val($('#width').val());
			$('#save_prod_height').val($('#height').val());
			$('#save_prod_unit').val($('#mesure').val());
			
			if ($('#awesome_masq').val()=='background')  color = 'rgba(33,33,33,0.3)';
			else {
				color = 'rgba(123,185,35,1)';
				CraPacrAxx.backgroundColor ='red';
			}
			var rect = new fabric.Rect({
			
				width: width_area*1,
				height: height_area*1,			
				fill: color,
			  originX: 'center',
			  originY: 'center'
				
			 });
			
			var text = new fabric.Text("<?php _e('Printing zone','woocommerce-awesome-designer');?>", {
				fontSize: 25,
			  fontFamily: 'News Cycle',
			  originX: 'center',
			  originY: 'center',			  	 
			  fill: '#000000'
			});
			
			
			/*if (text.width>rect.width) text.angle=270;
			if (text.height>rect.height) text.scaleToWidth(0);*/
			
			if ((width_area/height_area) > 7 || (height_area/width_area) > 7) {
				text.scaleToWidth(0);
			}
			 text.scaleToWidth(0);
			var group = new fabric.Group([ rect, text ], {
			  left: 10,
				top: 10,
				transparentCorners: true,
				hasRotatingPoint: false,
				lockUniScaling :true,
				hasBorders:true,
				transparentCorners: false,
				borderColor:'#ff6000',
				cornerColor:'#ff6000'
			});
		  
		  CraPacrAxx.add(group).setActiveObject(group);
			$('.div_strech').removeClass("cache");
		  if ($('#awesome_type').val()=='multi') {
			  $('.div_next').removeClass("cache");
		  } else {
			  $('.div_couleur').removeClass("cache");
		  }
		}
	}
	
	
	
	
	
</script>
<?php }  
 } ?>
 <script>
 function test(e){
		window.parent.postMessage(e, "*");
	}	
</script>