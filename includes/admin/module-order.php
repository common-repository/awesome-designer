<?php
include(THE_AWE_DES_AWESOME_INCLUDES.'wordpress_env.php');

define ('TAILLE_CREA',"590");

if (the_awe_des_check_is_admin_w()) {
	$dpi = 300;
	$choix=0;
	$color='FFFFFF';
	$is_multi = false;
	if (isset($_POST) && is_array($_POST)){
		foreach ($_POST as $key => $value){
				$POST_Secured[$key] = htmlentities($value, ENT_QUOTES);
		}
		if (isset( $POST_Secured['dpi'])) {$dpi = $POST_Secured['dpi'];}
	}
	if (isset($_GET) && is_array($_GET)){
		foreach ($_GET as $key => $value){
			$GET_Secured[$key] = htmlentities($value, ENT_QUOTES);
		}
		if (isset( $GET_Secured['color']) and ctype_alnum($GET_Secured['color'])) $color=$GET_Secured['color'];
		if (isset( $GET_Secured['id']) and ctype_alnum($GET_Secured['id'])) {
			$path_array  = wp_upload_dir();
			$path =  $path_array['basedir'].'/the-awe-des-awesome-commande/'.$GET_Secured['id'].'/sav-'.$GET_Secured['id'].'.txt';
			$path_img = $path_array['baseurl'].'/the-awe-des-awesome-commande/'.$GET_Secured['id'].'/rendu-'.$GET_Secured['id'].'.png';
			$path_crop =  $path_array['baseurl'].'/the-awe-des-awesome-product/';
			$path_img_compo  = $path_array['baseurl'].'/the-awe-des-awesome-upload/'.$GET_Secured['id'].'/';
			
			
			$url_clip = $path_array['baseurl'] . '/the-awe-des-awesome-clipart/';
			
			$fp = fopen ($path, "r");  
			$info_commande = fread($fp, filesize($path));  
			fclose ($fp);
			$liste_param = explode('--seprainf--',$info_commande);
			
			if (strpos($liste_param[0],'--sepracanv--')!=false) {
				if (isset( $POST_Secured['choix'])) $choix = $POST_Secured['choix'];
				$is_multi = true;	
				$list_multi = explode('--sepracanv--',$liste_param[0]);
				$canv_sav = json_decode (utf8_encode(base64_decode($list_multi[$choix])), true);
												
				$list_taill_multi = explode('--sepracanv--',$liste_param[1]);		
				$liste_param_taill = explode(',',$list_taill_multi[$choix]);
				
				$table_name = $wpdb->prefix . 'the_awe_des_awesome_product';
				
				$results_img_mul = $wpdb->get_results( 'SELECT image_back FROM '.$table_name.' WHERE id_multi="'.$liste_param_taill[11].'" ORDER BY obj_order ASC ', OBJECT );	

				
				
		
			} else {
				$canv_sav = json_decode (utf8_encode(base64_decode($liste_param[0])), true);
			
				$liste_param_taill = explode(',',$liste_param[1]);
				
			
			}
			//print_r($canv_sav);
			
			
			$list_font = '';
			for ($r=0;$r<count($canv_sav["objects"]);$r++) {
					
					if ($canv_sav["objects"][$r]["type"]=='imagepattern') {
						$canv_sav["objects"][$r]["src"]=$path_img_compo.$canv_sav["objects"][$r]["urlImage"];
					} else {
						if ($canv_sav["objects"][$r]["type"]=='text') $list_font .= '|'.str_replace(' ','+',$canv_sav["objects"][$r]["fontFamily"]);
						
					}
				
			
			}
			//print_r($canv_sav);
			
			$width_imp = $liste_param_taill[0];
			$height_imp = $liste_param_taill[1];
			$mesure_imp= $liste_param_taill[2];
			
			if ($mesure_imp=='mm') {
				$width_imp = round(($dpi * $width_imp)/25.4);
				$height_imp = round(($dpi * $height_imp)/25.4);
			} else {
				$width_imp = ($dpi * $width_imp);
				$height_imp = ($dpi * $height_imp);
				
			}
			$width_canv = $canv_sav["width"]; 
			$height_canv = $canv_sav["height"];
			$espacement = $canv_sav["imageSpace"];
			$masq_url = $path_crop.$liste_param_taill[6];
			
			$form =  '<div id="par_canv_0" data-width="'.TAILLE_CREA.'" data-height="'.TAILLE_CREA.'" name="par_canv_0" class="disparition" style="position:relative;margin-top:0px;margin-left:0px;width:'.TAILLE_CREA.'px;height:'.TAILLE_CREA.'px;">
				<div class="div_fond_couleur" id="fond_0" name="fond_0" data-width="'.$liste_param_taill[3].'" data-height="'.$liste_param_taill[4].'" style="position:absolute;left:0px;top:0px;width:'.$liste_param_taill[3].'px;height:'.$liste_param_taill[4].'px;z-index:10;background:white url('.$path_crop.$liste_param_taill[5].') no-repeat center center;background-size: 100% 100%;background-color:#'.$color.'">
					<div id="fond_blanc" style="position:absolute;top:0;bottom:0;left:0;right:0;background-color:#FFFFFF;z-index:1;opacity:1;display:none;"></div>	
					<div id="div_canv_0" class="" data-left="'.$liste_param_taill[9].'" data-top="'.$liste_param_taill[10].'" data-width="'.$liste_param_taill[7].'" data-height="'.$liste_param_taill[8].'"  style="position:absolute;width:'.$liste_param_taill[7].'px;height:'.$liste_param_taill[8].'px;left:'.$liste_param_taill[9].'px;top:'.$liste_param_taill[10].'px;z-index:11;" >';
			$form .=  '<canvas id="CraPacrA_0" width="'.$liste_param_taill[7].'" height="'.$liste_param_taill[8].'"  >
					</div>
				</div>
			</div>';

?>
<html>
	<head>
		<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1">
		<meta http-equiv="Content-Language" content="fr">
		<meta http-equiv="imagetoolbar" content="no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="mobile-web-app-capable" content="yes">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
	
		<link href='https://fonts.googleapis.com/css?family=Roboto:400,700,400italic,300|News+Cycle<?php echo $list_font;?>' rel='stylesheet' type='text/css'>
		
		<link type="text/css" rel="stylesheet" href="<?php echo $url_dossier_awesome;?>files/css/admin.css">
		<link type="text/css" rel="stylesheet" href="<?php echo $url_dossier_awesome;?>files/css/admin-print.css" media="print">
		<script src="//code.jquery.com/jquery-2.1.4.min.js"></script>
		<script src="<?php echo $url_dossier_awesome;?>files/js/fabric.js"></script>	
	</head>
	<body topmargin="0">
		
		<div id="waiting_mini" style="position:absolute;top:0;bottom:0;left:0;right:0;background-color:#ded2c6;z-index:10000000;opacity:1;">
			<img src="<?php echo $url_dossier_awesome;?>files/img/interface-awesome/puff.svg" style="margin:auto;width:91px;display: block;margin-top:100px;" >	
		</div>	
	
	
		<form method="post" id="form_effet" name="form_effet" >
			<input type="hidden" name="choix" id="choix" value="<?php echo $choix;?>">
			<!-- header -->
			<div id="header" class="header">
				<img src="<?php echo $url_dossier_awesome;?>files/img/interface-admin/awesome_logo.jpg">
				<div class="close" style="background-image: url('<?php echo $url_dossier_awesome;?>files/img/interface-admin/close.png');" onclick="test('');"></div>
				<div style="clear:both"></div>
			</div>
			<!-- contenu -->
			<?php //print_r($canv_sav); ?>
			<div class="full">
			
				
			
			
				<!-- PUB WtP -->
				<div id="pub" class="prodpub">
					<div style="display:inline-block;vertical-align: middle;">
						<p class="txtpub">
							<?php _e('DO YOU WANT TO SAVE TIME ? YOU CAN RECOVER <span style="font-weight:700">READY TO PRINT</span> IMAGE','woocommerce-awesome-designer');?> 
						</p>
					</div>
					<div style="padding:1px 20px; display:inline-block;vertical-align: middle;">
						<a href="http://www.theawesomedesigner.com/" target="_blank" class="wtp-button" onclick=""><?php _e('LEARN MORE ABOUT</br><span style="color:#fff">OUR WEB TO PRINT</span></br>TECHNOLOGY','woocommerce-awesome-designer');?></a>
					</div>
				</div>
				
				
				
				<div id="left" style="width:500px;position:relative;float:left;height:600px;">
					
					
					
					
				
					<div id="cont_canvas_surcouch" class="canvas" style="position:absolute;bottom:0px;top:0px;left:0px;right:0px;width:500px;height:600px;	">
						
					
						<div id="cont_canvas" name="cont_canvas" class="cont_canvas" style="top:0px;bottom:0px;left:0px;right:0px;position:absolute;">
							<div style="position: absolute; height: 50px; width: 50px;" id="conteneur_total" name="conteneur_total">
								
								<?php echo $form;?>
							</div>	
						</div>	
					</div>
					<div style="clear:both"></div>
				</div>
				
				<div id="right" class="right2" style="position:absolute;left:500px;right:0px;top:110px;bottom:0px;">
					<?php 
					if ($is_multi) {
						echo '	<div class="proddpiswitch" style="margin-top:5px;">	';
						if (isSet( $results_img_mul) and  $results_img_mul) {  	
							$i=0;		
							foreach ( $results_img_mul as $results_img_mu ) {		
								
								echo '<div onclick="$(\'#choix\').val('.$i.');$(\'#form_effet\').submit();" style="background-image:url(\''.$path_crop.$results_img_mu->image_back.'\');float: left; width: 70px; height: 70px; margin-left:5px;   cursor: pointer;   border: 1px solid #DEDEDE;   background-position: center;   background-size: contain;   background-repeat: no-repeat;" ></div>';
								
							
								$i++;
							}  
							
						}
						
						
						echo '</div>';
					} else {
						echo '<div style="display:block;height:10px;width:100%"></div>';
					}
					?>
					<div class="admin-button highligh-bouton" id="add" name="add" style="margin-right:5px; font-size:80%;">
						<a style="text-decoration:none; color:fff;" href="javascript:add_object_highlight()"><?php _e('Add reference points','woocommerce-awesome-designer');?></a>
					</div>
					<div class="admin-button highligh-bouton cache" id="del" name="del" style="margin-right:5px; font-size:80%;">
						<a style="text-decoration:none; color:fff;" href="javascript:remove_object_highlight()"><?php _e('Remove reference points','woocommerce-awesome-designer');?></a>
					</div>
					<div class="admin-button canv-bouton" id="compo" name="compo"  style="margin-right:5px; font-size:80%;">
						<a style="text-decoration:none; color:fff;" href="javascript:agg_canv()"><?php _e('Composition only','woocommerce-awesome-designer');?></a>
					</div>
					<div class="admin-button canv-bouton cache" id="canv" name="canv" style="margin-right:5px; font-size:80%;">
						<a style="text-decoration:none; color:fff;" href="javascript:dim_canv();"><?php _e('Full preview','woocommerce-awesome-designer');?></a>
					</div>
					<div class="proddpi">
						<div style="padding:10px 0; text-align:center">
							<div style="display:inline-block;vertical-align: middle;">
								<img src="<?php echo $url_dossier_awesome.'files/img/interface-admin/';?>print-icone.png" width="50" height="50">
							</div>
							<!-- IMPRIMER -->
							<div style=" display:inline-block;vertical-align: middle;">
								<a href="#" class="admin-button-petit-txt" onclick="imprim()"><?php _e('PRINT THESE INFORMATIONS','woocommerce-awesome-designer');?></a>
							</div>
						</div>
					</div>
					<div class="proddpi">
						<!-- Choix DPI -->
						<!-- INFO image finale (taille et résolution) -->
						<div id="left" class="prodinfo">
							<div style="padding:15px 20px;box-sizing: border-box;">
								<p class="txt">
									<span style="font-weight:700">
										<?php _e('FINAL DOCUMENT SIZE','woocommerce-awesome-designer');?> : &nbsp;  
										<?php echo $liste_param_taill[0].' x '.$liste_param_taill[1].' '.$liste_param_taill[2];?>
									</span>
									<select name="dpi" id="dpi" onchange="$('#form_effet').submit();">
										<option value="300" 
											<?php if ($dpi==300) echo 'selected=selected'; ?>>
											300
										</option>
										<option value="150" 
										<?php if ($dpi==150) echo 'selected=selected'; ?>>
										150
										</option>
										<option value="72" 
											<?php if ($dpi==72) echo 'selected=selected'; ?>>
											72
										</option>
									</select>
									<span style="font-weight:700"> DPI </span>
									&nbsp;=&nbsp;
									<span style="color:#ae270c; font-weight:700;border:1px solid black">
										&nbsp; 
										<?php echo $width_imp;?>
										 X 
										<?php echo $height_imp;?>
										 pixels&nbsp; 
									</span>
									
								</p>
									<p class="txt">
								<?php
								
									if ($canv_sav["background"]!='') { ?>
										<span style="color:#ae270c; font-weight:700;"><?php echo __('BACKGROUND COLOR','woocommerce-awesome-designer').' : '. $canv_sav["background"];?></span>
									<?php } ?>
								</p>
							</div>
						</div>
			<!-- bloc infos compo -->
			<div class="prodblock">
				<?php
				for ($r=0;$r<count($canv_sav["objects"]);$r++) {
					
					if ($canv_sav["objects"][$r]["type"]=='imagepattern') {
					$size = getimagesize($path_img_compo.$canv_sav["objects"][$r]["urlImage"]);
					$width_orig = $size[0];$height_orig = $size[1];
					$widthImg = $canv_sav["objects"][$r]["widthImg"];
					$heightImg = $canv_sav["objects"][$r]["heightImg"];
					$ratio = $width_orig / $widthImg ;
					$widthPat = round($canv_sav["objects"][$r]["widthPat"]*$ratio*$canv_sav["objects"][$r]["scaleImg"]);
					$heightPat = round($canv_sav["objects"][$r]["heightPat"]*$ratio*$canv_sav["objects"][$r]["scaleImg"]);
					$topPat = round($canv_sav["objects"][$r]["topPat"]*$ratio);
					$leftPat = round($canv_sav["objects"][$r]["leftPat"]*$ratio);
					$canv_sav["objects"][$r]["widthPat"] = $canv_sav["objects"][$r]["widthPat"]*$ratio;
					$canv_sav["objects"][$r]["heightPat"] = $canv_sav["objects"][$r]["heightPat"]*$ratio;
					$canv_sav["objects"][$r]["topPat"] = $canv_sav["objects"][$r]["topPat"]*$ratio;
					$canv_sav["objects"][$r]["leftPat"] = $canv_sav["objects"][$r]["leftPat"]*$ratio;
					
					$ratio_2 = $width_imp / $width_canv ;
					$width = round(($canv_sav["objects"][$r]["width"]*$ratio_2)-($espacement*2*$ratio_2));
					$height = round(($canv_sav["objects"][$r]["height"]*$ratio_2)-($espacement*2*$ratio_2));
					$top = round(($canv_sav["objects"][$r]["top"]*$ratio_2)+($espacement*$ratio_2));
					$left= round(($canv_sav["objects"][$r]["left"]*$ratio_2)+($espacement*$ratio_2));
					?>
					<div id="getimage" class="item">
						<div class="item-title">
							<h1 class="txt" style="font-weight:700">-- <?php _e('IMAGE NUMBER','woocommerce-awesome-designer');?> : <span style="font-size:150%;"><?php echo ($r+1)?></span>  --</h1>
						</div>
						<div class="item-image" >
							<div style="position: absolute; bottom: 50px; top:0px;left: 0; right: 0;background-position: center;background-size: contain;background-repeat: no-repeat;background-image: url('<?php echo $path_img_compo.$canv_sav["objects"][$r]["urlImage"];?>');"></div>
							<div style="position: absolute; bottom: 4px; left: 0; right: 0">
								<a href="<?php echo $path_img_compo.$canv_sav["objects"][$r]["urlImage"];?>" class="admin-button-petit-txt" target="_blank"><?php _e('DOWNLOAD','woocommerce-awesome-designer');?></a>
							</div>
						</div>
						<div class="item-info">
							<p class="infotxt" style="font-weight:400;font-style: italic;">
								<?php _e('Original image','woocommerce-awesome-designer');?> : 
								<?php echo $width_orig.' x '.$height_orig.' px';?>
							</p>
							</br>
							<p class="infotxt">
								<?php _e('CROP LEFT','woocommerce-awesome-designer');?> :
								<span style="color:#ae270c; font-weight:700">
									&nbsp; 
									<?php echo $leftPat;?>
									 px
								</span>
							</p>
							<p class="infotxt">
								<?php _e('CROP TOP','woocommerce-awesome-designer');?> :
								<span style="color:#ae270c; font-weight:700">
									&nbsp; 
									<?php echo $topPat;?>
									 px
								</span>
							</p>
							<p class="infotxt">
								<?php _e('CROP WIDTH','woocommerce-awesome-designer');?> :
								<span style="color:#ae270c; font-weight:700">
									&nbsp; 
									<?php echo $widthPat;?>
									 px
								</span>
							</p>
							<p class="infotxt">
								<?php _e('CROP HEIGHT','woocommerce-awesome-designer');?> :
								<span style="color:#ae270c; font-weight:700">
									&nbsp; 
									<?php echo $heightPat;?>
									 px
								</span>
							</p>
							</br>
							<p class="infotxt">
								<?php _e('FINAL IMAGE SIZE','woocommerce-awesome-designer');?> : 
								<span style="color:#ae270c; font-weight:700">
									&nbsp; 
									<?php echo $width.' x '.$height.' px';?>
								</span>
							</p>
							</br>
							<?php if ($canv_sav["objects"][$r]["masque"]!='') { ?>
							<div style=" display:inline-block;vertical-align: middle;">
								<p class="infotxt">
									<?php _e('IMAGE SHAPE','woocommerce-awesome-designer');?>
								</p>
							</div>
							<div style=" display:inline-block;vertical-align: middle;">
								<a href="<?php echo $url_dossier_awesome;?>files/img/shapes/<?php echo $canv_sav["objects"][$r]["masque"];?>.svg" class="admin-button-petit-txt" target="_blank"><?php _e('GET SHAPE','woocommerce-awesome-designer');?></a>
							</div>
							</br>
							</br>
							<?php } if ($canv_sav["objects"][$r]["angle"]!=0) { ?>
							<p class="infotxt">
								<?php _e('ROTATION','woocommerce-awesome-designer');?> :
								<span style="color:#ae270c; font-weight:700">
									&nbsp; 
									<?php echo $canv_sav["objects"][$r]["angle"];?>
									 ° <?php _e('Clockwork','woocommerce-awesome-designer');?>
								</span>
							</p>
							</br>
							<?php } ?>
							<p class="infotxt">
								<?php _e('POSITION','woocommerce-awesome-designer');?> 
								<span style="font-weight:400;font-style: italic;">(<?php _e('LEFT TOP CORNER OF THE IMAGE','woocommerce-awesome-designer');?>)</span>
								&nbsp;:
							</p>
						
							<p class="infotxt">
								<?php _e('LEFT POSITION','woocommerce-awesome-designer');?> :
								<span style="color:#ae270c; font-weight:700">
									&nbsp; 
									<?php echo $left.' px';?>
								</span>
							</p>
							<p class="infotxt">
								<?php _e('TOP POSITION','woocommerce-awesome-designer');?> :
								<span style="color:#ae270c; font-weight:700">
									&nbsp; 
									<?php echo $top.' px';?>
								</span>
							</p>
							</br>
							<?php if ($canv_sav["objects"][$r]["opacity"]!=1) { ?>
							<p class="infotxt">
								<?php _e('OPACITY','woocommerce-awesome-designer');?> :
								<span style="color:#ae270c; font-weight:700">
									&nbsp; 
									<?php echo $canv_sav["objects"][$r]["opacity"]*100;?>
									 %
								</span>
							</p>
							</br>
							<?php } if ($canv_sav["objects"][$r]["flipX"]!='') { ?>
							<p class="infotxt">
								<?php _e('FLIP HORIZONTAL','woocommerce-awesome-designer');?> :
								<span style="color:#ae270c; font-weight:700">&nbsp; <?php _e('TRUE','woocommerce-awesome-designer');?></span>
							</p>
							</br>
							<?php }
							
							if ($canv_sav["objects"][$r]["borderSize"]!=0) { ?>
							<p class="infotxt">
								<?php _e('BORDER INSIDE','woocommerce-awesome-designer');?> :
								<span style="color:#ae270c; font-weight:700">
									&nbsp; 
									<?php if ($canv_sav["objects"][$r]["masque"]!='')  echo round(($canv_sav["objects"][$r]["borderSize"]*$ratio_2)/2); else echo round($canv_sav["objects"][$r]["borderSize"]*$ratio_2);;?>
									 px - <?php _e('COLOR','woocommerce-awesome-designer');?> : 
									<?php echo $canv_sav["objects"][$r]["stroke"];?>
								</span>
							</p>
							</br>
							<?php } ?> 
						</div>
						<div style="clear:both"></div>
					</div>
					<div class="separator"></div>
					<?php
					} else if ($canv_sav["objects"][$r]["type"]=='text') {
						$ratio_2 = $width_imp / $width_canv ;
						$width = round($canv_sav["objects"][$r]["scaleX"]*$canv_sav["objects"][$r]["width"]*$ratio_2);
						$height = round($canv_sav["objects"][$r]["scaleY"]*$canv_sav["objects"][$r]["height"]*$ratio_2);
						$top = round($canv_sav["objects"][$r]["top"]*$ratio_2);
						$left= round($canv_sav["objects"][$r]["left"]*$ratio_2);
						?>
					<div id="gettext" class="item">
						<div class="item-title">
							<h1 class="txt" style="font-weight:700">-- <?php _e('TEXTE NUMBER','woocommerce-awesome-designer');?> : <span style="font-size:150%;"><?php echo ($r+1)?></span>  --</h1>
						</div>
						<div class="item-blocktxt">
							<div style="position: absolute; bottom: 100px; left: 0; right: 0">
								<a href="<?php echo site_url.'/index.php?awesome_designer_back_get_text=1&id='.$GET_Secured['id'].'&choix='.$choix.'&r='.$r.'&ratio='.$ratio_2;?>" class="admin-button-petit-txt" target='_blank'><?php _e('GET TEXT','woocommerce-awesome-designer');?></a>
							</div>
						</div>
						<div class="item-info">
							<p class="infotxt">
								<?php _e('SIZE WIDTH','woocommerce-awesome-designer');?> :
								<span style="color:#ae270c; font-weight:700">
									&nbsp; 
									<?php echo $width.' px';?>
								</span>
							</p>
							<p class="infotxt">
								<?php _e('SIZE HEIGHT','woocommerce-awesome-designer');?> :
								<span style="color:#ae270c; font-weight:700">
									&nbsp; 
									<?php echo $height.' px';?>
								</span>
							</p>
							</br>
							<?php if ($canv_sav["objects"][$r]["angle"]!=0) { ?>
							<p class="infotxt">
								<?php _e('ROTATION','woocommerce-awesome-designer');?> :
								<span style="color:#ae270c; font-weight:700">
									&nbsp; 
									<?php echo $canv_sav["objects"][$r]["angle"];?>
									 ° <?php _e('Clockwork','woocommerce-awesome-designer');?> 
								</span>
							</p>
							</br>
							<?php } ?>
							<p class="infotxt">
								<?php _e('POSITION','woocommerce-awesome-designer');?> 
								<span style="font-weight:400;font-style: italic;">(<?php _e('Bounding Box if Rotated','woocommerce-awesome-designer');?>)</span>
								&nbsp;:
							</p>
							<p class="infotxt">
								<?php _e('LEFT POSITION','woocommerce-awesome-designer');?> :
								<span style="color:#ae270c; font-weight:700">
									&nbsp; 
									<?php echo $left.' px';?>
								</span>
							</p>
							<p class="infotxt">
								<?php _e('TOP POSITION','woocommerce-awesome-designer');?> :
								<span style="color:#ae270c; font-weight:700">
									&nbsp; 
									<?php echo $top.' px';?>
								</span>
							</p>
							</br>
							<?php if ($canv_sav["objects"][$r]["opacity"]!=1) { ?>
							<p class="infotxt">
								<?php _e('OPACITY','woocommerce-awesome-designer');?> :
								<span style="color:#ae270c; font-weight:700">
									&nbsp; 
									<?php echo $canv_sav["objects"][$r]["opacity"]*100;?>
									 %
								</span>
							</p>
							</br>
							<?php } if ($canv_sav["objects"][$r]["flipX"]!='') { ?>
							<p class="infotxt">
								<?php _e('FLIP HORIZONTAL','woocommerce-awesome-designer');?> :
								<span style="color:#ae270c; font-weight:700">&nbsp; <?php _e('TRUE','woocommerce-awesome-designer');?></span>
							</p>
							</br>
							<?php } ?>
							<p class="infotxt" style="font-weight:400;font-style: italic;">
								<?php _e('For Your Information the Font Is','woocommerce-awesome-designer');?> :
								<span style="color:#ae270c; font-weight:700">
									&nbsp; 
									<?php echo $canv_sav["objects"][$r]["fontFamily"];?>
								</span>
							</p>
							</br>
						</div>
						<div style="clear:both"></div>
					</div>
					<div class="separator"></div>
					<?php
					} else  {
						//$left = $canv_sav["objects"][$r]["type"] 
						$ratio_2 = $width_imp / $width_canv ;
						$width = round($canv_sav["objects"][$r]["scaleX"]*$canv_sav["objects"][$r]["width"]*$ratio_2);
						$height = round($canv_sav["objects"][$r]["scaleY"]*$canv_sav["objects"][$r]["height"]*$ratio_2);
						$top = round($canv_sav["objects"][$r]["top"]*$ratio_2);
						$left= round($canv_sav["objects"][$r]["left"]*$ratio_2);?>
					<div id="getclipart" class="item">
						<div class="item-title">
							<h1 class="txt" style="font-weight:700">-- <?php _e('CLIPART NUMBER','woocommerce-awesome-designer');?> : <span style="font-size:150%;"><?php echo ($r+1)?></span>  --</h1>
						</div>
						<div class="item-image">
							<div style="position: absolute; bottom: 50px; top:0px;left: 0; right: 0;background-position: center;background-size: contain;background-repeat: no-repeat;background-image: url('<?php echo $canv_sav["objects"][$r]["urlImage"];?>');"></div>
							<div style="position: absolute; bottom: 4px; left: 0; right: 0">
							<?php	if ($canv_sav["objects"][$r]["typeImage"]=='svg') { ?>
										<a href="<?php echo $canv_sav["objects"][$r]["urlImage"];?>" class="admin-button-petit-txt" target="_blank"><?php _e('DOWNLOAD','woocommerce-awesome-designer');?></a>
							<?php } else { ?>
								<a href="<?php echo  $url_clip.$canv_sav["objects"][$r]["idImage"].'.'.$canv_sav["objects"][$r]["typeImage"];?>" class="admin-button-petit-txt" target="_blank"><?php _e('DOWNLOAD','woocommerce-awesome-designer');?></a>
							<?php } ?>
							</div>
						</div>
						<div class="item-info">
							<p class="infotxt">
								<?php _e('SIZE WIDTH','woocommerce-awesome-designer');?> :
								<span style="color:#ae270c; font-weight:700">
									&nbsp; 
									<?php echo $width.' px';?>
								</span>
							</p>
							<p class="infotxt">
								<?php _e('SIZE HEIGHT','woocommerce-awesome-designer');?> :
								<span style="color:#ae270c; font-weight:700">
									&nbsp; 
									<?php echo $height.' px';?>
								</span>
							</p>
							</br>
							<?php if ($canv_sav["objects"][$r]["angle"]!=0) { ?>
							<p class="infotxt">
								<?php _e('ROTATION','woocommerce-awesome-designer');?> :
								<span style="color:#ae270c; font-weight:700">
									&nbsp; 
									<?php echo $canv_sav["objects"][$r]["angle"];?>
									 ° <?php _e('Clockwork','woocommerce-awesome-designer');?>
								</span>
							</p>
							</br>
							<?php } ?>
							<p class="infotxt">
								<?php _e('POSITION','woocommerce-awesome-designer');?> 
								<span style="font-weight:400;font-style: italic;">(<?php _e('Bounding Box if Rotated','woocommerce-awesome-designer');?>)</span>
								&nbsp;:
							</p>
							<p class="infotxt">
								<?php _e('LEFT POSITION','woocommerce-awesome-designer');?> :
								<span style="color:#ae270c; font-weight:700">
									&nbsp; 
									<?php echo $left.' px';?>
								</span>
							</p>
							<p class="infotxt">
								<?php _e('TOP POSITION','woocommerce-awesome-designer');?> :
								<span style="color:#ae270c; font-weight:700">
									&nbsp; 
									<?php echo $top.' px';?>
								</span>
							</p>
							</br>
							<?php if ($canv_sav["objects"][$r]["opacity"]!=1) { ?>
							<p class="infotxt">
								<?php _e('OPACITY','woocommerce-awesome-designer');?> :
								<span style="color:#ae270c; font-weight:700">
									&nbsp; 
									<?php echo $canv_sav["objects"][$r]["opacity"]*100;?>
									 %
								</span>
							</p>
							</br>
							<?php } if ($canv_sav["objects"][$r]["flipX"]!='') { ?>
							<p class="infotxt">
								<?php _e('FLIP HORIZONTAL','woocommerce-awesome-designer');?> :
								<span style="color:#ae270c; font-weight:700">&nbsp; <?php _e('TRUE','woocommerce-awesome-designer');?></span>
							</p>
							</br>
							<?php }  
							if ($canv_sav["objects"][$r]["fill"]!='' and $canv_sav["objects"][$r]["fill"]!='rgb(0,0,0)') { ?>
							<p class="infotxt">
								<?php _e('COLOR','woocommerce-awesome-designer');?> :
								<span style="color:#ae270c; font-weight:700">&nbsp; <?php echo $canv_sav["objects"][$r]["fill"];?></span>
							</p>
							</br>
							<?php }  ?>
							</br>
							</br>
							<p class="infotxt" style="font-weight:400;font-style: italic;">
								<?php _e('For Your Information the Clipart Is','woocommerce-awesome-designer');?> :
								<span style="color:#ae270c; font-weight:700">
									&nbsp; 
									<?php echo $canv_sav["objects"][$r]["idImage"].'.'.$canv_sav["objects"][$r]["typeImage"];?>
									
								</span>
							</p>
							</br>
						</div>
						<div style="clear:both"></div>
					</div>
					<div class="separator"></div>
					<?php
						} 
			}
			?>
					<!-- bloc texte -->
					<!-- bloc clipart -->
				</div>
			</div>
			<div style="clear:both"></div>
		</div>
	</form>
</body>
</html>
<script >
	 function test(e){
		window.parent.postMessage(e, "*");
	}
	function clic(){
		$("#div_template").css("display",'');
		 
	}
	function imprim() {
		window.print();
	}
	var CraPacrAxx_save;
	var CraPacrAxx = this.__canvas = new fabric.Canvas('CraPacrA_0');
	CraPacrAxx.loadFromJSON(<?php echo json_encode($canv_sav);;?>, CraPacrAxx.renderAll.bind(CraPacrAxx), function(o, object) {
	
	});
	
	
console.log(CraPacrAxx);
	
	
	
	CraPacrAxx.forEachObject(function(object){ object.selectable = false });	
	var  paddingImg = 0;
	function cache_all_ligne(){}
	
	function remove_object_highlight(){
		
		$('.highligh-bouton').addClass('cache');
		$('#add').removeClass('cache');
		
		var item = CraPacrAxx.getObjects();
		var objectList = [];
		for (t in item) {			
			if (item[t]["typeImage"] && item[t]["typeImage"]=="highlight") {
				objectList.push(item[t]);
			}
		}
		for (z in objectList) {			
			objectList[z].remove();
		}
		CraPacrAxx.renderAll();	
	}
	
	function add_object_highlight(){
		
		$('.highligh-bouton').addClass('cache');
		$('#del').removeClass('cache');
		
		CraPacrAxx_save = CraPacrAxx;
		var item = CraPacrAxx.getObjects();
		for (i in item) {	
			var point = item[i].getCenterPoint();
			nbe = ((i*1)+1)+'';		
				var normScripta = new fabric.Text(nbe, {
						fontSize: 40,
						fontFamily: 'Roboto',
						fill: '#FFFFFF',
						color: '#FFFFFF',						
						strokeWidth: 2,						
						stroke: '#000000',
						selectable:false,
						left : point["x"],
						top : point["y"],
						originX:"center",
						originY:"center",
						typeImage:"highlight"
					});
			CraPacrAxx.add(normScripta);
		}
		CraPacrAxx.renderAll();		
	}
	
	
	function check_nbe(){}
	
	$(document).ready( function () {	
		
		setTimeout(function(){
			resize_canv(false);	
			CraPacrAxx.interactive = false;
			CraPacrAxx.skipTargetFind= true;
			$('#waiting_mini').css("display","none");
		},1000);
				
	} ) ;
	
	function dim_canv() {
		
		$('.canv-bouton').addClass('cache');
		$('#compo').removeClass('cache');
		resize_canv();
	}
	
	function agg_canv() {
		
		$('.canv-bouton').addClass('cache');
		$('#canv').removeClass('cache');
		
		w_c = 0;
		h_c = 0;
		
		$('#fond_blanc').css("display","block");		
		var z=0;
		w = $('#cont_canvas').css("width").substr(0,($('#cont_canvas').css("width").length-2));
		h = $('#cont_canvas').css("height").substr(0,($('#cont_canvas').css("height").length-2));
		
		width_min = $("#div_canv_"+z).attr("data-width");
		height_min = $("#div_canv_"+z).attr("data-height");
		
		if (width_min > height_min) {
			var ratio = width_min/height_min;				
			if (h>w) {
				w_c = w;
				h_c = w/ratio;
			} else {
				if ((w/ratio) > h) {
					w_c = h*ratio;
					h_c = h;
				} else {
					w_c = w;
					h_c = w/ratio;
				}					
			}				
		} else {
			var ratio = height_min/width_min;
			if (w>h) {
				w_c = h/ratio;
				h_c = h;
			} else {
				if ((h/ratio) > w) {
					w_c = w;
					h_c = w*ratio;
				} else {
					w_c = h/ratio;
					h_c = h;
				}
			}				
		}
		var new_ratio = w_c/width_min;	
		
		$("#fond_"+z).css("width","100%");
		$("#fond_"+z).css("height","100%");
		$("#fond_"+z).css("left",0);
		$("#fond_"+z).css("top",0);
		
		$("#div_canv_"+z).css("width",w_c);
		$("#div_canv_"+z).css("height",h_c);
		$("#div_canv_"+z).css("left",((w-w_c)/2));
		$("#div_canv_"+z).css("top",((h-h_c)/2));
		
		
		var item = CraPacrAxx.getObjects();
		var canv_with = CraPacrAxx.getWidth();
	
		CraPacrAxx.setDimensions({ width: w_c, height: h_c });					

		if (CraPacrAxx.overlayImage && CraPacrAxx.overlayImage != "") {		
			CraPacrAxx.overlayImage.setWidth(0).setHeight(0);
		}
		
		var new_ratio = canv_with/CraPacrAxx.getWidth();			
		CraPacrAxx.imageSpace = CraPacrAxx.imageSpace/new_ratio;					
		if (item.length > 0) {
			for (i in item) {							
				var left_objet = item[i].getLeft();
				var top_objet = item[i].getTop();							
				if (item[i]["type"] == "imagepattern") {	
var borderSize = item[i].get("borderSize");						
					var size_object_x = item[i].get("width");
					var size_object_y = item[i].get("height");	
					item[i].setLeft(Math.round(left_objet/new_ratio)).setTop(Math.round(top_objet/new_ratio)).set("borderSize",(borderSize/new_ratio)).set("width",(size_object_x/new_ratio)).set("height",(size_object_y/new_ratio)).setCoords();
				} else {
					var size_object_x = item[i].get("scaleX");
					var size_object_y = item[i].get("scaleY");	
					item[i].setLeft(Math.round(left_objet/new_ratio)).setTop(Math.round(top_objet/new_ratio)).set("scaleX",(size_object_x/new_ratio)).set("scaleY",(size_object_y/new_ratio)).setCoords();
				}							
			}						
		}	
		
		CraPacrAxx.renderAll();
		
	}
	
	
	function resize_canv() {
		
		
		
		w_c = 0;
		h_c = 0;
		$('#fond_blanc').css("display","none");		
		w = $('#cont_canvas').css("width").substr(0,($('#cont_canvas').css("width").length-2));
		h = $('#cont_canvas').css("height").substr(0,($('#cont_canvas').css("height").length-2));
		
		$("#conteneur_total").css("width",w);
		$("#conteneur_total").css("height",h);
						
		var z=0;
		
		width_min = $("#fond_"+z).attr("data-width");
		height_min = $("#fond_"+z).attr("data-height");
	
		if (width_min > height_min) {
			var ratio = width_min/height_min;				
			if (h>w) {
				w_c = w;
				h_c = w/ratio;
			} else {
				if ((w/ratio) > h) {
					w_c = h*ratio;
					h_c = h;
				} else {
					w_c = w;
					h_c = w/ratio;
				}					
			}				
		} else {
			var ratio = height_min/width_min;
			if (w>h) {
				w_c = h/ratio;
				h_c = h;
			} else {
				if ((h/ratio) > w) {
					w_c = w;
					h_c = w*ratio;
				} else {
					w_c = h/ratio;
					h_c = h;
				}
			}				
		}
		
		w_c = Math.round(w_c);
		h_c = Math.round(h_c);
								
		$("#par_canv_"+z).css("width",w);
		$("#par_canv_"+z).css("height",h);
		
		$("#fond_"+z).css("width",w_c);
		$("#fond_"+z).css("height",h_c);
		$("#fond_"+z).css("left",((w-w_c)/2));
		$("#fond_"+z).css("top",((h-h_c)/2));
		
		var new_ratio = w_c/width_min;	
		
		width_canv = $("#div_canv_"+z).attr("data-width")*new_ratio;
		height_canv = $("#div_canv_"+z).attr("data-height")*new_ratio;
		left_canv = $("#div_canv_"+z).attr("data-left")*new_ratio;
		top_canv = $("#div_canv_"+z).attr("data-top")*new_ratio;
		
		$("#div_canv_"+z).css("width",width_canv);
		$("#div_canv_"+z).css("height",height_canv);
		$("#div_canv_"+z).css("left",left_canv);
		$("#div_canv_"+z).css("top",top_canv);
							
		var item = CraPacrAxx.getObjects();
		var canv_with = CraPacrAxx.getWidth();
		
		CraPacrAxx.setDimensions({ width: width_canv, height: height_canv });					

		if (CraPacrAxx.overlayImage && CraPacrAxx.overlayImage != "") {		
			CraPacrAxx.overlayImage.setWidth(width_canv*1).setHeight(height_canv*1);
		}
		
		var new_ratio = canv_with/CraPacrAxx.getWidth();			
		CraPacrAxx.imageSpace = CraPacrAxx.imageSpace/new_ratio;								
		if (item.length > 0) {
			for (i in item) {							
				var left_objet = item[i].getLeft();
				var top_objet = item[i].getTop();							
				if (item[i]["type"] == "imagepattern") {
					var borderSize = item[i].get("borderSize");					
					var size_object_x = item[i].get("width");
					var size_object_y = item[i].get("height");	
					item[i].setLeft(Math.round(left_objet/new_ratio)).setTop(Math.round(top_objet/new_ratio)).set("borderSize",(borderSize/new_ratio)).set("width",(size_object_x/new_ratio)).set("height",(size_object_y/new_ratio)).setCoords();
				} else {
					var size_object_x = item[i].get("scaleX");
					var size_object_y = item[i].get("scaleY");	
					item[i].setLeft(Math.round(left_objet/new_ratio)).setTop(Math.round(top_objet/new_ratio)).set("scaleX",(size_object_x/new_ratio)).set("scaleY",(size_object_y/new_ratio)).setCoords();
				}							
			}						
		}	

		CraPacrAxx.renderAll();
		
	}
</script>
<?php
		}
	}
 } ?>