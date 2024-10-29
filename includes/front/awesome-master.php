<?php


include(THE_AWE_DES_AWESOME_INCLUDES.'wordpress_env.php');

$max_upl = 	get_option( 'max_up_awesome_designer');
$min_upl = 	get_option( 'min_up_awesome_designer' );
$lst_coul_port =  explode(',',	get_option( 'col_mob_awesome_designer'));
$render_size = 	get_option( 'size_render_awesome' );

$couleur_deft = '#000000';
if (get_option( 'col_lim_awesome_designer')) {
	$lst_coul_lim =  explode(',',	get_option( 'col_lim_awesome_designer'));
	if (!empty($lst_coul_lim)) {
		$lst_coul_port = $lst_coul_lim;
		$couleur_deft = $lst_coul_lim[0];
	}
}


$affich_abort = get_option( 'aff_abort_awesome_designer');
$affich_rendu = get_option( 'aff_rendu_awesome_designer');

$affich_img = get_option( 'aff_img_awesome_designer');
$affich_txt = get_option( 'aff_txt_awesome_designer');
$affich_clip = get_option( 'aff_clip_awesome_designer');

$option_retour = 'image';
if ($affich_img and $affich_img==2) $option_retour = 'texte';
if ($affich_txt and $affich_txt==2)  $option_retour = 'clipart';

$quality_ratio = get_option( 'qual_ratio_awesome_designer');
$quality_action = get_option( 'qual_action_awesome_designer');

if (!$quality_ratio) $quality_ratio = 3;
if (!$quality_action) $quality_action = 2;



if ($quality_action ==2) $text_confirm =  __( "Warning! one or more photo doesn't have sufficient quality for optimal printing. Do you want to continue anyway?", 'woocommerce-awesome-designer' );
else $text_confirm =  __( "Warning! one or more photos does not have sufficient quality for optimal printing.Please choose another one, reduce its size or zoom it out.", 'woocommerce-awesome-designer' );;


define ('TAILLE_CREA',"590");
define ('TAILLE_MINI',"110");

$path_array  = wp_upload_dir();
$url_commande_rendu = $path_array['baseurl']. "/the-awe-des-awesome-commande/" ;
$url_clip = $path_array['baseurl'] . '/the-awe-des-awesome-clipart/';
$path_fond = $path_array['baseurl'].'/the-awe-des-awesome-product/';


if (isSet($id_type_awesome) and is_numeric($id_type_awesome)) {


require (THE_AWE_DES_AWESOME_INCLUDES.'front/mobile_detect.php');

$detect = new The_Awe_Des_Mobile_Detect;
 $firefox = 0;
 if ($detect->version('Firefox') || $detect->version('IE')  ) $firefox = 1;
 

if ( $detect->isMobile() ) {
   $couleur_deft = $lst_coul_port[0];
   $is_mobile=true;
   $mobile=1;
} else {
   $is_mobile=false;
    $mobile=0;
}

 
$couleur_deft = substr( $couleur_deft,1,7);
   
$id_com = uniqid();
$color_choix = '';
$color_dft = '#FFFFFF';
$list_taille = $color_list = $liste_obj_mul='';
$table_name = $wpdb->prefix . 'the_awe_des_awesome_product';
$results_product = $wpdb->get_results( 'SELECT affich_option,colors,id_prod,width_prod,height_prod,unit_prod,image_masq,image_back,width_cad,height_cad,width_canv,height_canv,top_canv,left_canv,id_multi,nom_masque,obj_order FROM '.$table_name.' WHERE id_prod='.$id_type_awesome.' ORDER BY obj_order ASC LIMIT 1', OBJECT );
$i=0;
if ( $results_product ) { 	
	$separateur = '--seprainf--';
	
	foreach ( $results_product as $result_product ) {	
		$colors = $result_product->colors;
		if ($colors!=''){
			$lst_color = explode(',',$colors);	
			$color_dft = '#'.$lst_color[0];
			$color_choix =  '#'.$lst_color[0];
			for ($j=0;$j<count($lst_color);$j++){
					$color_list .= '<span class="sp-thumb-el sp-thumb-light sp-thumb-light sp-thumb-el-o sp-awe" style="background-color:#'.$lst_color[$j].'" onclick="change_color_product(\''.$lst_color[$j].'\');"></span>';
				
			}
		}
	
		if ($result_product->id_multi!=0 ) {
			
			$affich_option = $result_product->affich_option;
			$results_product_multi = $wpdb->get_results( 'SELECT id_prod,width_prod,height_prod,unit_prod,image_masq,image_back,width_cad,height_cad,width_canv,height_canv,top_canv,left_canv,id_multi,nom_masque,obj_order FROM '.$table_name.' WHERE id_multi="'.$result_product->id_multi.'" ORDER BY obj_order ASC', OBJECT );
			foreach ( $results_product_multi as $result_product_multi ) {					
				$width_grand = $result_product_multi->width_cad;
				$height_grand = $result_product_multi->height_cad;
				$url_back = $path_fond.$result_product_multi->image_back; 
				if ($result_product_multi->image_masq!='') $url_masq = $path_fond.$result_product_multi->image_masq;
				else $url_masq ='';
				$width_canv = $result_product_multi->width_canv;
				$height_canv = $result_product_multi->height_canv;
				$left_canv = $result_product_multi->left_canv;
				$top_canv = $result_product_multi->top_canv;
				$width_prod = $result_product_multi->width_prod;
				$height_prod = $result_product_multi->height_prod;
				$unit_prod = $result_product_multi->unit_prod;				 				
				
	
				if ($affich_option=='txt') $liste_obj_mul.='<a href="#" onclick="change_object('.$i.')" class="lien_produit">'.strtoupper($result_product_multi->nom_masque).'</a>';
				else $liste_obj_mul.='<div onclick="javascript:change_object('.$i.')" style="background-image:url(\''.$url_back.'\');"  class="forme_pres_min2 bibli_forme" ></div>';
				
				$form .=  '<div id="par_canv_'.$i.'" data-width="'.TAILLE_CREA.'" data-height="'.TAILLE_CREA.'" name="par_canv_'.$i.'" class="disparition" style="position:relative;margin-top:0px;margin-left:0px;width:'.TAILLE_CREA.'px;height:'.TAILLE_CREA.'px;';
				if ($i!=0) $form .=  'display:none;';
				$form .=  '">
					<div class="div_fond_couleur" id="fond_'.$i.'" name="fond_'.$i.'" data-width="'.$width_grand.'" data-height="'.$height_grand.'" style="position:absolute;left:0px;top:0px;width:'.$width_grand.'px;height:'.$height_grand.'px;z-index:10;background-image:url('.$url_back.');background-repeat:no-repeat;background-position: center center;background-size: 100% 100%;background-color:'.$color_dft.'">
						<div id="div_canv_'.$i.'" class="" data-left="'.$left_canv.'" data-top="'.$top_canv.'" data-width="'.$width_canv.'" data-height="'.$height_canv.'"  style="position:absolute;width:'.$width_canv.'px;height:'.$height_canv.'px;left:'.$left_canv.'px;top:'.$top_canv.'px;z-index:11;" >';
				$form .=  '<canvas id="CraPacrA_'.$i.'" width="'.$width_canv.'" height="'.$height_canv.'"  >
						</div>
					</div>
				</div>';
				$list_url_masq[] = $url_masq;
				if ($list_taille!='') $list_taille .= '--sepracanv--';
				$list_taille .= $width_prod.','.$height_prod.','.$unit_prod.','.$width_grand.','.$height_grand.','.$result_product_multi->image_back.','.$result_product_multi->image_masq.','.
			$width_canv.','.$height_canv.','.$left_canv.','.$top_canv.','.$result_product->id_multi;
				
			
				$i++;
			}
		} else {			
			$width_grand = $result_product->width_cad;
			$height_grand = $result_product->height_cad;
			$url_back = $path_fond.$result_product->image_back;
			if ($result_product->image_masq!='') $url_masq = $path_fond.$result_product->image_masq;
			else $url_masq ='';
			
			$width_canv = $result_product->width_canv;
			$height_canv = $result_product->height_canv;
			$left_canv = $result_product->left_canv;
			$top_canv = $result_product->top_canv;
			$width_prod = $result_product->width_prod;
			$height_prod = $result_product->height_prod;
			$unit_prod = $result_product->unit_prod;			
			$list_taille .= $width_prod.','.$height_prod.','.$unit_prod.','.$width_grand.','.$height_grand.','.$result_product->image_back.','.$result_product->image_masq.','.
			$width_canv.','.$height_canv.','.$left_canv.','.$top_canv.','.$id_type_awesome;
		
			$list_url_masq[] = $url_masq;						
						
			$form =  '<div id="par_canv_'.$i.'" data-width="'.TAILLE_CREA.'" data-height="'.TAILLE_CREA.'" name="par_canv_'.$i.'" class="disparition" style="position:relative;margin-top:0px;margin-left:0px;width:'.TAILLE_CREA.'px;height:'.TAILLE_CREA.'px;">
				<div class="div_fond_couleur" id="fond_'.$i.'" name="fond_'.$i.'" data-width="'.$width_grand.'" data-height="'.$height_grand.'" style="position:absolute;left:0px;top:0px;width:'.$width_grand.'px;height:'.$height_grand.'px;z-index:10;background-image:url('.$url_back.');background-repeat:no-repeat;background-position: center center;background-size: 100% 100%;background-color:'.$color_dft.'">
					<div id="div_canv_'.$i.'" class="" data-left="'.$left_canv.'" data-top="'.$top_canv.'" data-width="'.$width_canv.'" data-height="'.$height_canv.'"  style="position:absolute;width:'.$width_canv.'px;height:'.$height_canv.'px;left:'.$left_canv.'px;top:'.$top_canv.'px;z-index:11;" >';
			$form .=  '<canvas id="CraPacrA_'.$i.'" width="'.$width_canv.'" height="'.$height_canv.'"  >
					</div>
				</div>
			</div>';
			$i++;			
		}
	}	
} else {
	$erreur = 'The product was not found.';
}


$nbe_total=$i;

$desktop = true;

$table_name = $wpdb->prefix . 'the_awe_des_awesome_clipart';
$results = $wpdb->get_results( 'SELECT TYPE,NOM,ID_CAT FROM '.$table_name.' WHERE VISIB=1 ORDER BY ID_CAT ASC,TIME DESC', OBJECT );

$results_cat = $wpdb->get_results( 'SELECT nacc.NOM,nacc.ID,nac.ID_CAT FROM '.$wpdb->prefix.'the_awe_des_awesome_clipart as nac,'.$wpdb->prefix.'the_awe_des_awesome_clipart_cat as nacc WHERE nac.VISIB = 1 and nac.ID_CAT=nacc.ID GROUP BY nac.ID_CAT order by nac.ID_CAT ASC', OBJECT );

$table_name = $wpdb->prefix . 'the_awe_des_awesome_font';
$results_font = $wpdb->get_results( 'SELECT DFT,ID,NOM,NOM_URL FROM '.$table_name.' order by ID ASC ', OBJECT );

$list_canv_predf = array("0,00,50.2,50.2,0,dft-0,50,50.2,50.2,0,dft-50,0,50.2,50.2,0,dft-50,50,50.2,50.2,0,dft",
"0,0,75.2,25.2,0,dft-75,0,25.2,75.2,0,dft-0,25,25.2,75.2,0,dft-25,75,75.2,25.2,0,dft-25,25,50.2,50.2,0,dft",
"0,0,25.2,25.2,0,dft-0,25,25.2,25.2,0,dft-0,50,25.2,25.2,0,dft-0,75,25.2,25.2,0,dft-25,0,25.2,25.2,0,dft-50,0,25.2,25.2,0,dft-75,0,25.2,25.2,0,dft-25,75,25.2,25.2,0,dft-50,75,25.2,25.2,0,dft-75,75,25.2,25.2,0,dft-75,25,25.2,25.2,0,dft-75,50,25.2,25.2,0,dft-25,25,50.2,50.2,0,dft",
"0,0,50.2,50.2,0,dft-50,0,50.2,25.2,0,dft-50,25,50.2,25.2,0,dft-0,50,50.2,25.2,0,dft-0,75,50.2,25.2,0,dft-50,25,50.2,25.2,0,dft-50,50,50.2,50.2,0,dft",
"0,0,25.2,50.2,0,dft-25,0,25.2,50.2,0,dft-50,0,50.2,50.2,0,dft-0,50,50.2,50.2,0,dft-50,50,25.2,50.2,0,dft-75,50,25.2,50.2,0,dft",
"0,0,50.2,25.2,0,dft-50,0,50.2,75.2,0,dft-0,25,50.2,75.2,0,dft-50,75,50.2,25.2,0,dft",
"0,0,75.2,50.2,0,dft-75,0,25.2,50.2,0,dft-0,50,25.2,50.2,0,dft-25,50,75.2,50.2,0,dft",
"0,0,33.2,33.2,0,dft-33,0,33.2,33.2,0,dft-66,0,34.2,33.2,0,dft-0,33,33.2,33.2,0,dft-33,33,33.2,33.2,0,dft-66,33,34.2,33.2,0,dft-0,66,33.2,34.2,0,dft-33,66,33.2,34.2,0,dft-66,66,34.2,34.2,0,dft",
"0,0,66.2,33.2,0,dft-66,0,34.2,66.2,0,dft-0,33,33.2,67.2,0,dft-33,33,33.2,33.2,0,dft-33,66,67.2,34.2,0,dft",
"0,0,100,33.2,0,dft-0,33,100,33.2,0,dft-0,66,100,34.2,0,dft",
"0,0,33.2,100,0,dft-33,0,33.2,100,0,dft-66,0,34.2,100,0,dft",
"0,0,33.2,66.2,0,dft-33,0,33.2,50.2,0,dft-66,0,34.2,33.2,0,dft-0,66,33.2,34.2,0,dft-33,50,33.2,50.2,0,dft-66,33,34.2,67.2,0,dft",
"0,0,33.2,33.2,0,dft-33,0,67.2,33.2,0,dft-0,33,50.2,33.2,0,dft-50,33,50.2,33.2,0,dft-0,66,66.2,34.2,0,dft-66,66,34.2,34.2,0,dft",
"33,0,67.2,50.2,0,dft-0,50,66.2,50.2,0,dft-0,0,33.2,66.2,0,dft-66,33,34.2,67.2,0,dft"
);


if (isSet($erreur)) { echo $erreur; }
else {
?>
<!DOCTYPE html>
<html><head>

<?php	
	$font_defaut = 0;
	$font_liste= array();
	if ( $results_font ) { 
			$i = 0;
			
			echo "<link href='https://fonts.googleapis.com/css?family=";	
				foreach ( $results_font as $result_font ) {	
					echo $result_font->NOM_URL;
					$font_liste[] = $result_font->NOM;
					
					if ($result_font->DFT=='1') $font_defaut = $i;
					$i++;
					if ($result_font !== end($results_font)) echo '|';
				}
				echo "' rel='stylesheet' type='text/css'>";
				
		}
	
	if (strrpos($font_liste[$font_defaut], ":")!==false) {			
		$font_fam_dft = substr($font_liste[$font_defaut],0,strrpos($font_liste[$font_defaut], ":"));
		$font_wei_dft = substr($font_liste[$font_defaut],strrpos($font_liste[$font_defaut], ":")+1,strlen($font_liste[$font_defaut]));
	} else {
		$font_fam_dft = $font_liste[$font_defaut];
		$font_wei_dft = 'normal';
		
	}
	
	
?>
<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1">
<meta http-equiv="Content-Language" content="fr"> 
<meta http-equiv="imagetoolbar" content="no">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="mobile-web-app-capable" content="yes">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<?php if (!$is_mobile) { ?>
<link type="text/css" rel="stylesheet" href="<?php echo $url_dossier_awesome;?>files/css/awesome.css?ver=1.2">
<link type="text/css" rel="stylesheet" href="<?php echo $url_dossier_awesome;?>files/css/fine-uploader.css?ver=1.1">
<?php } else  { ?>
<link type="text/css" rel="stylesheet" href="<?php echo $url_dossier_awesome;?>files/css/awesome-mobile.css?ver=1.1">
<link type="text/css" rel="stylesheet" href="<?php echo $url_dossier_awesome;?>files/css/fine-uploader-mobile.css">
<?php } ?>
<link type="text/css" rel="stylesheet" href="<?php echo $url_dossier_awesome;?>files/css/other.css">
<script > 	
var is_zoom =false;
	var array_canv = {}; 
	var objet_max = <?php echo $nbe_total;?> ;	
	var id_obj_sel=0;		
	var is_mobile = <?php echo $mobile;?>;
	var is_ff = <?php echo $firefox;?>;
	var is_portable=0;
	var long_histo = 11;
	var id_drag;
	var compteur_image;
	var images;	
	var is_finish = false;	
	var is_changing = false;	
	var is_undo = false;
	var IMAGE = '<?php echo $url_dossier_awesome.'files/img/interface-awesome/';?>';
	var IMAGE_INTERF = '<?php echo $url_dossier_awesome.'files/img/interface-canvas/';?>';
	
	var liste_taille = '<?php echo $list_taille; ?>';
	var font_creapex = '<?php echo $font_fam_dft ;?>';
	var weight_creapex = '';
	var texte_defaut = '<?php _e( 'Your text...', 'woocommerce-awesome-designer' );?>';
	var left_magnetic,top_magnetic ;
	var state;
    var undo = [];
	var redo = [];
	var  paddingImg = 0;
	var is_first=true;	
	var liste_id_img = [];
	var val_magnetisme = 4;
	var col_dft = '<?php echo $color_dft;?>';
	var option_retour = '<?php echo $option_retour;?>';
	
	var col_chx = '<?php echo $color_choix;?>';
	var uniq = '<?php echo $id_com;?>';
	var upload_back = '<?php echo site_url();?>/index.php?awesome_designer_front_upload_back='+uniq;
	var affich_rendu = <?php if ($affich_rendu and $affich_rendu==2) echo 'false'; else echo 'true';?>;
	
	var block_slide = false;
	var save_val_slid;
	var wait_undo=false;	
	var url_back = '<?php echo site_url();?>/index.php?awesome_designer_front_save_canvas=1';
	var img_first = true;
	var max_uplod = <?php echo ($max_upl*1024);?>;
	var min_uplod = <?php echo ($min_upl*1024);?>;
	
	var render_size = <?php echo $render_size;?>;
	
	var info_rend = <?php echo  $id_type_awesome;?>;
	var url_commande_rendu = '<?php echo $url_commande_rendu;?>';
	var text_drag_drop = "<?php _e( "Drag'n drop your photos", 'woocommerce-awesome-designer' );?>";
	var command_fin = false;
	var color_base = "<?php echo $couleur_deft;?>";
	
	var compteur_img = 0;
	
	var quality_ratio = <?php echo  $quality_ratio;?>;
	var quality_action = <?php echo  $quality_action;?>;
	var quality_texte = "<?php _e( "QUALITY", 'woocommerce-awesome-designer' );?>";
	var text_confirm = "<?php echo $text_confirm;?>";
</script>
<script src="//code.jquery.com/jquery-2.1.4.min.js"></script>
<script src="<?php echo $url_dossier_awesome;?>files/js/jquery.fine-uploader.min.js?ver=1.0"></script>
<script src="<?php echo $url_dossier_awesome;?>files/js/rangeslider.js"></script>	
<script src="<?php echo $url_dossier_awesome;?>files/js/fabric.min.js?ver=1.5"></script>	


</head>
<body topmargin="0" style="height:100%;overflow:hidden;">
	
	<div id="waiting_charg" name="waiting_charg" style="position:absolute;top:0;bottom:0;left:0;right:0;background-color:#ded2c6;z-index:10000000;">
			<img src="<?php echo $url_dossier_awesome;?>files/img/interface-awesome/puff.svg" style="margin:auto;width:91px;display: block;margin-top:50px;" >
		</div>	
	<div id="waiting_mini" style="position:absolute;top:0;bottom:0;left:0;right:0;background-color:#ded2c6;z-index:10000000;display:none;opacity:1;">
				
		<div id="waiting_photos" class="txt-end"  style="width:50%;margin:auto;margin-top:20px;z-index:10000000;opacity:1;text-align:center;" >
			<img src="<?php echo $url_dossier_awesome;?>files/img/interface-awesome/puff.svg" style="margin:auto;width:91px;display: block;margin-top:100px;" >	
			<p class="txt"><?php _e( 'Please wait while downloading your photos', 'woocommerce-awesome-designer' );?></p>
			<div style="width:100%;margin:auto;text-align:center;margin-top:20px;z-index:10000000;opacity:1;<?php if ($affich_abort and $affich_abort==2) echo 'display:none;';?>" >
				<a href="#" class="greenbutton" onclick="javascript:reload_all();"><?php  _e( 'CHANGE', 'woocommerce-awesome-designer');?></a> &nbsp; &nbsp;<a href="#" class="greenbutton" onclick="test('');"><?php  _e( 'ABORT', 'woocommerce-awesome-designer');?></a>
			</div>
		</div>
		<div id="waiting_donnees" class="txt-end" style="width:50%;margin:auto;display:none;margin-top:20px;z-index:10000000;opacity:1;text-align:center;" >
			<img src="<?php echo $url_dossier_awesome;?>files/img/interface-awesome/puff.svg" style="margin:auto;width:91px;display: block;margin-top:100px;" >	
			<p class="txt"><?php _e( 'Generation rendering and data backup', 'woocommerce-awesome-designer' );?></p>	
			<div style="width:100%;margin:auto;text-align:center;margin-top:20px;z-index:10000000;opacity:1;<?php if ($affich_abort and $affich_abort==2) echo 'display:none;';?>" >
				<a href="#" class="greenbutton" onclick="javascript:reload_all();"><?php  _e( 'CHANGE', 'woocommerce-awesome-designer');?></a> &nbsp; &nbsp;<a href="#" class="greenbutton" onclick="test('');"><?php  _e( 'ABORT', 'woocommerce-awesome-designer');?></a>
			</div>
		</div>
		
		
		
		<div id="div_rendu"  style="z-index:10000000;opacity:1;display:none;position:absolute;top:0;left:0;right:0;bottom:0;flex-direction: column;" >
			
		
			<div id="div_rendu_img" style="flex: 1;backround-color:white;margin:auto;width:90%;height:90%;max-width:<?php echo $render_size;?>px;max-height:<?php echo $render_size;?>px;background-size:contain;background-repeat: no-repeat; background-position: center; " >
				
			</div>
			<div style="margin: auto;padding:10px;">
				<a href="#" class="greenbutton" onclick="javascript:reload_all();"><?php  _e( 'CHANGE', 'woocommerce-awesome-designer');?></a>&nbsp;&nbsp;&nbsp;<a href="#" class="greenbutton" onclick="groovefx();"><?php  _e( 'CONFIRM', 'woocommerce-awesome-designer');?></a>
			</div>
		</div>
		<div id="waiting_telechargement"  style="width:90%;margin:auto;margin-top:30px;z-index:10000000;opacity:1;" >
			
		</div>
	</div>
	
	<div style="position:absolute;width:0px;height:0px;overflow:hidden;opacity:0">
		<img src="<?php echo $url_dossier_awesome;?>files/img/interface-canvas/rotate.svg">
		<img src="<?php echo $url_dossier_awesome;?>files/img/interface-canvas/resize_angle.svg">
		<img src="<?php echo $url_dossier_awesome;?>files/img/interface-canvas/resize_horizontal.svg">
		<img src="<?php echo $url_dossier_awesome;?>files/img/interface-canvas/resize_vertical.svg">
		<img src="<?php echo $url_dossier_awesome;?>files/img/interface-canvas/deplace.svg">
		<img src="<?php echo $url_dossier_awesome;?>files/img/interface-awesome/divideh.svg">
		<img src="<?php echo $url_dossier_awesome;?>files/img/interface-awesome/dividev.svg">
		<img src="<?php echo $url_dossier_awesome;?>files/img/interface-awesome/swap.svg">
		<img src="<?php echo $url_dossier_awesome;?>files/img/interface-canvas/warning_median.svg">
		<img src="<?php echo $url_dossier_awesome;?>files/img/interface-canvas/warning_low.svg">
	</div>

	 <form id="form_creation" action="index.php" method="post">
		<input type="HIDDEN" id="width_prod" name="width_prod" value="<?php echo $width_prod;?>">
		<input type="HIDDEN" id="height_prod" name="height_prod" value="<?php echo $height_prod;?>">
		<input type="HIDDEN" id="unit_prod" name="unit_prod" value="<?php echo $unit_prod;?>">	
	</form>	
<?php


if ($is_mobile) include (THE_AWE_DES_AWESOME_INCLUDES.'front/awesome-mobile.php');
else include (THE_AWE_DES_AWESOME_INCLUDES.'front/awesome.php');

?>
	

<script src="<?php echo $url_dossier_awesome;?>files/js/awesome-image.js?ver=1.9"></script>	
	
	<script > 
	
	function groovefx(){	
		test(url_commande_rendu+uniq+'/rendu-'+uniq+'-1.png--sepra--'+uniq+'--sepra--'+col_chx);		
	}

	
	function rempli_canvas() {	

		if (is_mobile==1) taill_canv = 50;
		else taill_canv = 75;
	
		if (CraPacrAxx.getWidth()>CraPacrAxx.getHeight()) {
			w = taill_canv;
			h = (taill_canv*CraPacrAxx.getHeight())/CraPacrAxx.getWidth();
		} else {
			h = taill_canv;
			w = (taill_canv*CraPacrAxx.getWidth())/CraPacrAxx.getHeight();		
		}
		
	<?php for($i=0;$i < count($list_canv_predf);$i++) { 
			$list_arg = explode(',',$list_canv_predf[$i]);
		?>		var staticCanvas = new fabric.StaticCanvas('predef_<?php echo $i;?>',{width:w,height:h});				
				peuple_canvas(staticCanvas,'<?php echo $list_canv_predf[$i];?>',false);				
				if (is_first) $('.pelemele_<?php echo $i;?>').click(function(){peuple_canvas(CraPacrAxx,'<?php echo $list_canv_predf[$i];?>',true)});				
		<?php	} ?>		
		is_first = false;			
	}
	
	function reload_all() {
			affich_upload(false);
			resize_canv();
			<?php  
				for ($i=0;$i<$nbe_total;$i++) {
					if ($list_url_masq[$i]!='') {
						echo "CraPacrA_".$i.".setOverlayImage('".$list_url_masq[$i]."',function (){CraPacrA_".$i.".renderAll()}, {});";
						echo "array_canv[".$i."] =  CraPacrA_".$i.";";
					}
				}
				echo 'var CraPacrAxx = CraPacrA_0;';
			?>
			command_fin = false;
			$('#waiting_photos').css("display","block");
			$('#waiting_donnees').css("display","none");
			$('#div_rendu').css("display","none");
			$('#cont_canvas').css("display","block");
			$('#waiting_mini').css("display","none");
	}
	
	
	<?php  
	for ($i=0;$i<$nbe_total;$i++) {
			echo "var CraPacrA_".$i." = this.__canvas = new fabric.Canvas('CraPacrA_".$i."');";
		
			if ($list_url_masq[$i]!='') echo "CraPacrA_".$i.".setOverlayImage('".$list_url_masq[$i]."',function (){CraPacrA_".$i.".renderAll()}, {});";
			
			echo "CraPacrA_".$i.".on( 'mouse:down', tuClick);";
			echo "CraPacrA_".$i.".on('object:moving', on_moving);";
			echo "array_canv[".$i."] =  CraPacrA_".$i.";";
	}
	

	echo 'var CraPacrAxx = CraPacrA_0;';	
	
	?>
	
</script>
<script src="<?php echo $url_dossier_awesome;?>files/js/awesome-interface.js?ver=1.6"></script>	
<?php
		
		
	}
}
?>	