<div class="full">
<!-- header --> 
	<div class="header">
		<div class="pleft">
			<?php if ($color_list=='') { ?>
				<!--<img src="<?php echo $url_dossier_awesome;?>files/img/interface-awesome/logo_315x60.png">-->
			<?php } else { ?> 	

			
<!-- couleurs produits --> 
			<div class="left visible3" style="margin:23px 0 0 2px">			
				<p class="txt"><?php _e( 'PRODUCT COLOR','woocommerce-awesome-designer' );?> :</p>
			</div>		
			
			<div class="pcolor2">
				<?php echo $color_list;?>
			
			</div>
			
			<?php }  ?>
			<div style="clear:both"></div> 
		</div>	
	

<!-- bouton finish --> 		
		<div class="right" style="margin:12px 23px 12px 13px">
			<a href="#" class="greenbutton" onclick="demande_finition();"><?php _e( 'FINISH', 'woocommerce-awesome-designer' );?></a>
		</div>
<!-- menu header --> 		
		<div class="menu-haut">
		 
			<div class="off right tooltip" style="margin:1px 0 0 0" tip="<?php _e( 'Redo', 'woocommerce-awesome-designer' );?>" id="redo" >
			</div>
			
			<div class="off right tooltip" style="margin:1px 10px 0 0" tip="<?php _e( 'Undo', 'woocommerce-awesome-designer' );?>"  id="undo" >
			</div>
			
			<div class="bt-new right tooltip" style="margin:1px 10px 0 0" tip="<?php _e( 'New', 'woocommerce-awesome-designer' );?>" onclick="clear_canvas();">
			</div>
			
			
			
			<div class="right" style="margin:14px 17px 0 0">  
				<div class="tooltip bt-no-color" style="display:block;margin:1px 10px 0 0;width:33px;height:33px;" tip="<?php _e( 'No bg color', 'woocommerce-awesome-designer' );?>" onclick="id_colorpick='colorbox-background';;change_colorpicker('');"></div>
			</div>
			<div class="right" style="margin:15px 12px 0 0"> 
				<input type="hidden"  id="colorbox-background"  value="" class="colorbox"/>				
			</div>
			<div class="right visible3" style="margin:27px 4px 0 0">
				<p class="txt"><?php _e( 'BACKGROUND COLOR','woocommerce-awesome-designer' );?> :</p>
			</div>
			
			
		</div>
		<div class="separator"></div>
		<div style="clear:both"></div> 
	</div>

	<div class="container">
		
	
	
<!-- canvas --> 
		
		
<!-- multiface --> 		
		<?php
		
		if ($nbe_total>1) {
			if ($affich_option=='txt') { ?>
				<div class="pface2" style="margin:auto; ">
					<?php echo $liste_obj_mul;?>
				</div>
				<div id="cont_canvas_surcouch" class="cont_canvas_surcouch2 canvas" onclick="desactive()">			
					<div id="cont_canvas" name="cont_canvas" class="cont_canvas">
						<div style="position: absolute; height: 50px; width: 50px;" id="conteneur_total" name="conteneur_total">
							<?php echo $form;?>
						</div>	
					</div>	
				</div>
			<?php } else { ?>
				<div class="pface" style="margin:auto; ">
					<?php echo $liste_obj_mul;?>
				</div>
				<div id="cont_canvas_surcouch" class="cont_canvas_surcouch canvas" onclick="desactive()">			
					<div id="cont_canvas" name="cont_canvas" class="cont_canvas">
						<div style="position: absolute; height: 50px; width: 50px;" id="conteneur_total" name="conteneur_total">
							<?php echo $form;?>
						</div>	
					</div>	
				</div>
			<?php } 
		} else { ?>
				<div id="cont_canvas_surcouch" class="cont_canvas_surcouch3 canvas" onclick="desactive()">			
					<div id="cont_canvas" name="cont_canvas" class="cont_canvas">
						<div style="position: absolute; height: 50px; width: 50px;" id="conteneur_total" name="conteneur_total">
							<?php echo $form;?>
						</div>	
					</div>	
				</div>
		<?php } ?>


<!-- outils --> 	
		<div class="tools">
		
			<div style="top:0px;left:0px;right:0px;bottom:0px;position:absolute;" class="cache" id="cache_colorpicker" name="cache_colorpicker">
				<div class="option_grise" onClick="close_colorpicker();" >				
				</div >
				<div class="div_colorpick" style="">				
					
					<?php if (isSet($lst_coul_lim) and !empty($lst_coul_lim)) { ?>
					
						<div style="" id="histo_palett" name="histo_palett" class="sp-palette">
		
						<?php	echo '<div style="width:'.(55*count($lst_coul_lim)).'px;height:60px;white-space: nowrap;">';
							for($i=0;$i < count($lst_coul_lim);$i++) {
								echo '<span class="sp-thumb-el sp-thumb-light" style="background-color:'.$lst_coul_lim[$i].'" onclick="change_colorpicker(\''.$lst_coul_lim[$i].'\');"></span>';
							}
							echo '<div class="clear-shadow "></div></div>';
						 ?>
						</div>
							<input type="hidden" id="couleur_select" />
					<?php } else { ?>
					
					
						<div style="" id="histo_palett" name="histo_palett" class="sp-palette">
		
						</div>
					
						<input type="text" id="flat" />
						
					<?php }?>
					<div style="position:relative;width:45%;text-align:center;float:left;margin-top:10px;margin-left:5px;">
						<a href="#" onClick="close_colorpicker(true);" class="greenbutton" ><?php _e( 'CANCEL', 'woocommerce-awesome-designer' );?></a>
					</div>
					<div style="position:relative;width:45%;text-align:center;float:right;margin-top:10px;margin-right:10px;">
						<a href="#" onClick="valid_colorpicker();" class="greenbutton" ><?php _e( 'VALID', 'woocommerce-awesome-designer' );?></a>
					</div>
					<div style="clear: both; "></div>
					
				</div>			
			</div>
		
		
			<div class="menu-mid menu-mid-height cartouche_menu_objet" id="object_active" style="display:none;">
<!-- slider opacité --> 			
				<div class="right" style="width:42%;margin:8px 5px 0 0">
					<div class="left-icone-slider">
						<img src="<?php echo $url_dossier_awesome;?>files/img/interface-awesome/opac_minus.png">
					</div>
					<div class="left visible2" style="padding-left:28%">
						<p class="txt-slider"><?php _e( 'Opacity', 'woocommerce-awesome-designer' );?></p>
					</div>
					<div class="right-icone-slider">
						<img src="<?php echo $url_dossier_awesome;?>files/img/interface-awesome/opac_plus.png">
					</div>
					<div style="clear:both"></div>
					<input id="slider-opac" type="range" min="0" max="1" value="1" step="0.1" data-rangeslider>
				</div>
<!-- boutons options -->				
				<div class="right taille-mid">
					<div class="bt-delete right taille-bt tooltip" style="margin:5px 10px 0 0" tip="<?php _e( 'Delete', 'woocommerce-awesome-designer' );?>" onclick="save();poubelle()">
					</div>
					<div class="bt-settobackground right taille-bt tooltip" id="specific_image" style="margin:5px 6px 0 0" tip="<?php _e( 'Set to background', 'woocommerce-awesome-designer' );?>" onclick="save();CraPacrAxx.getActiveObject().toBackground();">
					</div>
					
					<div class="bt-flip right taille-bt tooltip" style="margin:5px 6px 0 0" tip="<?php _e( 'Flip', 'woocommerce-awesome-designer' );?>" onclick="save();flip()">
					</div>
					
					<div class="bt-background right taille-bt tooltip" style="margin:5px 6px 0 0" tip="<?php _e( 'Move to back', 'woocommerce-awesome-designer' );?>" onclick="save();CraPacrAxx.sendToBack(CraPacrAxx.getActiveObject(),true);">
					</div>
					

					<div class="bt-foreground right taille-bt tooltip" style="margin:5px 6px 0 0" tip="<?php _e( 'Move to front', 'woocommerce-awesome-designer' );?>" onclick="save();CraPacrAxx.bringToFront(CraPacrAxx.getActiveObject(),true);">
					</div>
					<div style="clear:both"></div>
				</div>
				<div style="clear:both"></div>
			</div> 
<!-- menu deploy -->		
			<div class="menu-tools" id="menu-tools" >
					<div id='cssmenu' > 
						<ul>
					
						
						   <li class='has-sub' id="image_par" style="<?php if ($affich_img and $affich_img==2) echo 'display:none;';?>"><a href='#'><span ><?php _e( 'PHOTOS', 'woocommerce-awesome-designer' );?></span></a>
							 <ul class='menu-high'>
								 <li>
									
									
									<div style="padding:7px;display:none;width:100%;margin-bottom:5px;white-space: nowrap; display:none; "  id="info_uplo_drop">
										<?php _e( 'Click on your photos or drag and drop them', 'woocommerce-awesome-designer' );?>
									
									</div>				
										

									<div style="padding:7px;display:none;width:100%;margin-bottom:5px;white-space: nowrap; overflow-y: hidden;" name="div_lightbox" id="div_lightbox">
										
									</div>
									
									
									
									
									<div id="fine-uploader-gallery" class="fine_uploader_norm"></div>
							
									
									
									<div style="clear:both"></div>
									
																	
									
									
										<div id="switch_menu" name="switch_menu" style="text-align:center;margin-top:9px;display:none;">
											<div class="tab-23"></div>
												
											<div class="tab-25" >												
												
											</div>
											<div class="tab-off tab-select" id="sous_menu_img" name="sous_menu_img"  style="display:none;" onClick='show_sous_menu("img")'>												
												<?php _e( 'Photo options');?>
											</div>
											<div class="tab-2"></div>
											<div class="tab-on tab-select" id="sous_menu_canv" name="sous_menu_canv"  onClick='show_sous_menu("canv")'>
												<?php _e( 'Collage options');?>
											</div>
											<div class="tab-23"></div>
										</div>
										
										
										<div  id="img_menu" name="img_menu" class="menu-mid sous_sous_menu" style="display:none; text-align:center;">
											<div class="menu-mid" style="width:70%;margin:auto;padding-top:10px">
												<div class="left-icone-slider">
													<img src="<?php echo $url_dossier_awesome;?>files/img/interface-awesome/zoom_plus.png">
												</div>
												<div class="left visible2" style="padding-left:40%">
													<p class="txt-slider"><?php _e( 'Zoom', 'woocommerce-awesome-designer' );?></p>
												</div>
												<div class="right-icone-slider">
													<img src="<?php echo $url_dossier_awesome;?>files/img/interface-awesome/zoom_minus.png">
												</div>
												<div style="clear:both"></div>
												<input id="slider-taille-zoom" type="range" min="0.3" max="1" step="0.02" value="1" data-rangeslider>
											</div>
											
											<div class="left" style="width:80%;">
												<div class="menu-mid" style="width:70%;margin:auto;padding-top:15px;padding-left:20px">
													<div class="left-icone-slider">
														<img src="<?php echo $url_dossier_awesome;?>files/img/interface-awesome/border_minus.png">
													</div>
													<div class="left visible2" style="padding-left:38%">
														<p class="txt-slider"><?php _e( 'Border', 'woocommerce-awesome-designer' );?></p>
													</div>
													<div class="right-icone-slider">
														<img src="<?php echo $url_dossier_awesome;?>files/img/interface-awesome/border_plus.png">
													</div>
													<div style="clear:both"></div>
													<input id="slider-crapx-2" step="0.2" type="range" min="0" max="10" value="0" data-rangeslider>
												</div>
											</div>	
<!-- couleur bordure -->
											<div class="right" style="margin:auto;margin-top:35px;margin-right:10%;">
												<input type="hidden"  id="colorbox-bord-img"  value="#<?php echo $couleur_deft;?>" class="colorbox"/>
											</div>
											<div style="clear:both"></div>
										
<!-- boutons formes -->									
											<div class="menu-mid overflowforme" style="text-align:center;margin:auto;margin-top:20px;">
												<div class="bt_edge on tooltip bt_edge_top" style="margin:0 5px; display:inline-block" tip="<?php _e( 'No shape', 'woocommerce-awesome-designer' );?>" onclick="change_value_object('pattern','');">
												</div>
												<div class="bt_edgecercle bt_edge_top" style="margin:0 5px; display:inline-block" onclick="change_value_object('pattern','cercle');">
												</div>
												<?php if ($firefox==0) { ?>
												<div class="bt_edgecoeur bt_edge_top" style="margin:0 5px; display:inline-block" onclick="change_value_object('pattern','coeur');">
												</div>
												<div class="bt_edgecoeur_2 bt_edge_top" style="margin:0 5px; display:inline-block" onclick="change_value_object('pattern','coeur_2');">
												</div>
												<div class="bt_edgefleur bt_edge_top" style="margin:0 5px; display:inline-block" onclick="change_value_object('pattern','fleur');">
												</div>
												<div class="bt_edgenuage bt_edge_top" style="margin:0 5px; display:inline-block" onclick="change_value_object('pattern','nuage');">
												</div>
												<div class="bt_edgesquare bt_edge_top" style="margin:0 5px; display:inline-block" onclick="change_value_object('pattern','square');">
												</div>
												<div class="bt_edgesquare_2 bt_edge_top" style="margin:0 5px; display:inline-block" onclick="change_value_object('pattern','square_2');">
												</div>
												<?php } ?>
												<div class="bt_edgeetoile bt_edge_top" style="margin:0 5px; display:inline-block" onclick="change_value_object('pattern','etoile');">
												</div>
												<div class="bt_edgeetoile_2 bt_edge_top" style="margin:0 5px; display:inline-block" onclick="change_value_object('pattern','etoile_2');">
												</div>
												<div class="bt_edgeetoile_3 bt_edge_top" style="margin:0 5px; display:inline-block" onclick="change_value_object('pattern','etoile_3');">
												</div>
											</div>
										</div>
<!-- OPTIONS PELE MELE -->								
								<div class="menu-mid sous_sous_menu"  id="canv_menu" name="canv_menu" style="display:none">
<!-- slider image spacing--> 		
									

									<div class="menu-mid" style="width:80%;margin:10px auto">
										
										<div class="left-icone-slider">
											<img src="<?php echo $url_dossier_awesome;?>files/img/interface-awesome/image-spacing_minus.png">
										</div>
										<div class="left visible2" style="padding-left:33%">
											<p class="txt-slider"><?php _e( 'Photo spacing', 'woocommerce-awesome-designer' );?></p>
										</div>
										<div class="right-icone-slider">
											<img src="<?php echo $url_dossier_awesome;?>files/img/interface-awesome/image-spacing_plus.png">
										</div>
										<div style="clear:both"></div>
										<input id="slider-spacing" type="range" min="0" max="10" value="0" step="1" data-rangeslider>
									
									</div>
									
									<div id="divpelemele" class="menu-mid overflowpele" style="margin:15px auto;">
										
										<?php for ($i=0;$i < count($list_canv_predf);$i++) {
										echo '<div class="pelemele pelemele_'.$i.'"><canvas id="predef_'.$i.'" name="predef_'.$i.'"></div>';
										
										
									} ?>	
										
									</div>
								</div>
									
								</li>
							  </ul>
						   </li>
						 
						   <li class='has-sub' id="texte_par" style="<?php if ($affich_txt and $affich_txt==2) echo 'display:none;';?>" ><a href='#'><span><?php _e( 'TEXT', 'woocommerce-awesome-designer' );?></span></a>
							  <ul class='menu-high'>
								 <li>
<!-- TEXTE --> 
								<div class="menu-mid" >
<!-- champs txt --> 								
										<div class="menu-mid left" style="width:55%;">
											<textarea name="text" id="text" class="textarea" style="height:60px; width:95%"  onClick="if (this.value=='<?php _e( 'Your text...', 'woocommerce-awesome-designer' );?>') this.value='';" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false"><?php _e( 'Your text...', 'woocommerce-awesome-designer' );?></textarea>
										</div>
<!-- txt couleur --> 											
										
										<div class="right txt_menu" style="margin:10px 10px;display:none;">
											<table style="">
													<tr>
														<td>
															<p class="txt"><?php _e( 'Text color', 'woocommerce-awesome-designer' );?></p>
														</td>
														<td>
															<input type="hidden" id="colorbox-craPxXxXx" value="#<?php echo $couleur_deft;?>" class="colorbox"/>
														</td>
													</tr>
												</table>
										</div>
										
										<div style="clear:both"></div>
									</div>
									<div class="txt_menu" style="display:none;" >	
										<div class="menu-mid" >			
	<!-- google fonts -->										
											<div class="menu-mid left overflow hfont" style="width:53%; border:1px solid #DEDEDE">
												<?php	for ($z=0;$z<count($font_liste);$z++) {
														$font_wei='normal';
														if (strrpos($font_liste[$z], ":")!==false) {			
															$font_fam = substr($font_liste[$z],0,strrpos($font_liste[$z], ":"));
															
														} else {
															$font_fam = $font_liste[$z];
															
															
														}
														
														if ($z==$font_defaut) echo '<div style="position:relative;font-family:'.$font_fam.';font-weight:'.$font_wei.';font-size:20px;" class="fontFamil ftActive" id="font_'.str_replace(' ','_',$font_fam).'" onClick="change_value_object(\'fontFamily\',\''.$font_fam.'\');">'.$font_fam.'</div>';
														else echo '<div style="position:relative;font-family:'.$font_fam.';font-weight:'.$font_wei.';font-size:20px;" id="font_'.str_replace(' ','_',$font_fam).'"  class="fontFamil"  onClick="change_value_object(\'fontFamily\',\''.$font_fam.'\');">'.$font_fam.'</div>';
													}
												
												?>
											</div>
											<div class="right" style="width:45%">

	<!-- slider texte spacing--> 			
												<div class="right" style="width:95%;margin:13px auto">
													<div class="left-icone-slider">
														<img src="<?php echo $url_dossier_awesome;?>files/img/interface-awesome/text-spacing_minus.png">
													</div>
													<div class="left visible2" style="padding-left:22%">
														<p class="txt-slider"><?php _e( 'Text spacing', 'woocommerce-awesome-designer' );?></p>
													</div>
													<div class="right-icone-slider">
														<img src="<?php echo $url_dossier_awesome;?>files/img/interface-awesome/text-spacing_plus.png">
													</div>
													<div style="clear:both"></div>
													<input id="slider-CraPaX" type="range" min="0.5" max="2" step="0.1"  val-dft="1.3" value="1.3" data-rangeslider>
												</div>

	<!-- slider outline--> 												
												<div class="right compact" style="width:95%;">
													<div class="left-icone-slider">
														<img src="<?php echo $url_dossier_awesome;?>files/img/interface-awesome/outline_minus.png">
													</div>
													<div class="left visible2" style="padding-left:22%">
														<p class="txt-slider"><?php _e( 'Border', 'woocommerce-awesome-designer' );?></p>
													</div>
													<div class="right-icone-slider">
														<img src="<?php echo $url_dossier_awesome;?>files/img/interface-awesome/outline_plus.png">
													</div>
													<div style="clear:both"></div>
													<input id="slider-crapx" step="0.2" type="range" min="0" max="3"  val-dft="0" value="0" data-rangeslider>
												</div>				
	<!-- outline color--> 											
											
												<div class="right" style="margin:10px 10px">
													<table style="">
														<tr>
															<td>
																<p class="txt"><?php _e( 'Border color', 'woocommerce-awesome-designer' );?></p>
															</td>
															<td>
																<input type="hidden" id="colorbox-crapX" value="#<?php echo $couleur_deft;?>" class="colorbox"/>
															</td>
														</tr>
													</table>												
												</div>
												<div style="clear:both"></div>
											</div>	
											<div style="clear:both"></div>
										</div>
	<!-- curv-->									
										<div class="menu-mid" >	
											<div class="left" style="width:48%;margin:15px auto;text-align:center">
	<!-- BT curve -->								




	
												<div class="menu-mid">
													<div class="bt-arc bt-arc-plat on taille-bt-marginleft left tooltip" style="margin:1px 0 0 30px;" tip="<?php _e( 'No curve', 'woocommerce-awesome-designer' );?>" onclick="change_value_object('arc','plat');">
													</div>
					
													<div class="bt-arc bt-arc-arc taille-bt-marginleft left tooltip" tip="<?php _e( 'Curve high', 'woocommerce-awesome-designer' );?>" onclick="change_value_object('arc','arc');">
													</div>
													
													<div class="bt-arc bt-arc-arc_inv taille-bt-marginleft left tooltip" tip="<?php _e( 'Curve bottom', 'woocommerce-awesome-designer' );?>" onclick="change_value_object('arc','arc_inv');">
													</div>
													<div style="clear:both"></div>
												</div>
											</div>
											<div class="right" style="width:48%;margin:15px auto;text-align:center">
	<!-- BT alignement -->											
												<div class="right">
													<div class="bt-align bt-align-right taille-bt-margin right tooltip-right" style="margin:1px 0;" tip="<?php _e( 'Align right', 'woocommerce-awesome-designer' );?>" onclick="change_value_object('textAlign','right');">
													</div>
					
													<div class="bt-align bt-align-center taille-bt-margin right tooltip" tip="<?php _e( 'Align center', 'woocommerce-awesome-designer' );?>" onclick="change_value_object('textAlign','center');">
													</div>
													
													<div class="bt-align bt-align-left on taille-bt-margin right tooltip" tip="<?php _e( 'Align left', 'woocommerce-awesome-designer' );?>" onclick="change_value_object('textAlign','left');">
													</div>
													<div style="clear:both"></div>
												</div>

					
											</div>
											<div class="curvealign">
	<!-- slider curve 1--> 						<div class="menu-arc" style="display:none;" >						
													<div class="menu-mid-40 left" style="margin:0 10px 0 0">
														<div class="left-icone-slider">
															<img src="<?php echo $url_dossier_awesome;?>files/img/interface-awesome/curve1_minus.png">
														</div>
														<div class="left visible2" style="padding-left:22%">
															<p class="txt-slider"><?php _e( 'Curve', 'woocommerce-awesome-designer' );?></p>
														</div>
														<div class="right-icone-slider">
															<img src="<?php echo $url_dossier_awesome;?>files/img/interface-awesome/curve1_plus.png">
														</div>
														<div style="clear:both"></div>
														<input type="range" id="slider-CraArCPacrA" min="0" max="1.9" value="1" step="0.1" val-dft="1" data-rangeslider>
													</div>
		<!-- slider curve 2--> 											
													<div class="menu-mid-40 right" style="margin:0 0 0 5px">
														<div class="left-icone-slider">
															<img src="<?php echo $url_dossier_awesome;?>files/img/interface-awesome/curve2_minus.png">
														</div>
														<div class="left visible2" style="padding-left:22%">
															<p class="txt-slider"><<?php _e( 'Length', 'woocommerce-awesome-designer' );?></p>
														</div>
														<div class="right-icone-slider">
															<img src="<?php echo $url_dossier_awesome;?>files/img/interface-awesome/curve2_plus.png">
														</div>
														<div style="clear:both"></div>
														<input type="range" id="slider-CraARCPacrA" min="0.1" max="2" step="0.1" value="1"  val-dft="1" data-rangeslider>
													</div>
													<div style="clear:both"></div>
												</div>
											</div>
											
											<div style="clear:both"></div>
										
										</div>
									</div>									
								 </li>
							  </ul>
						   </li>
						   
						   <li class='has-sub' id="clipart_par" style="border-bottom:3px solid black;<?php if ($affich_clip and $affich_clip==2) echo 'display:none;';?>"><a href='#'><span><?php _e( 'CLIPART', 'woocommerce-awesome-designer' );?></span></a>
							  <ul class='menu-high'>
								 <li>		
<!-- CLIPARTS --> 
									
										
										<div id="couleur_svg" style="display:none; margin-bottom:6px; margin-left:5px;width:100%">
											<div style="float:left;display:inline-block;margin-top:10px;margin-right:2px;"> 
												<p class="txt" ><?php _e( 'CLIPART COLOR', 'woocommerce-awesome-designer' );?> :</p>												
											</div>
											<div style="float:left;display:inline-block;"> 
												<input type="hidden" id="colorbox-fill-svg" value="#<?php echo $couleur_deft;?>" class="colorbox"/>
											</div>	
											<div style="clear:both"></div>
										</div>	
										
									
										
										<div style="width: 100%;   text-align: center;  position: relative;">
											<p class="txt" style="margin:auto;margin-bottom:10px;"><?php _e( 'CATEGORY', 'woocommerce-awesome-designer' );?></p>
										
										
										<script type="text/javascript">
									
										function change_forme_menu(){
											name = $("select[name='choix_forme'] > option:selected").val();		
											$(".forme_active").toggleClass("forme_pres_hide");
											$(".forme_active").toggleClass("forme_active");
											$("#"+name+"_forme").toggleClass("forme_active");
											$("#"+name+"_forme").toggleClass("forme_pres_hide");
										}
										
										function change_sous_forme(id,forme){
											
											$("div[id^='"+forme+"_forme_'].sous_forme_active").toggleClass("sous_forme_pres_hide");
											$("div[id^='"+forme+"_forme_'].sous_forme_active").toggleClass("sous_forme_active");
											$("#"+forme+"_forme_"+id).toggleClass("sous_forme_active");
											$("#"+forme+"_forme_"+id).toggleClass("sous_forme_pres_hide");
											
											$("a[id^='"+forme+"_navig_'].navig").toggleClass("navig_no");
											$("a[id^='"+forme+"_navig_'].navig").toggleClass("navig");
											$("#"+forme+"_navig_"+id).toggleClass("navig");
											$("#"+forme+"_navig_"+id).toggleClass("navig_no");
										}
										
										</script>
												<?php
			if ($results_cat) {
				echo '<select name="choix_forme" onChange="javascript:change_forme_menu();" class="texte_titre">';
				foreach ( $results_cat as $result_cat ) {	
					echo '<option value="'.$result_cat->ID.'">'.$result_cat->NOM.'</option>';
				}
				echo '</select>';
			} else {
				
				
			}
if ( $results ) {
	echo ' <div class="div_option_forme_2" >';
	$id_sav='';
	foreach ( $results as $result ) {		
		$id_cat = $result->ID_CAT;
		if ($id_cat!=$id_sav) {
			if ($id_sav!='') echo '</div><div id="'.$result->ID_CAT.'_forme" class="forme_pres_hide">';
			else echo '<div id="'.$result->ID_CAT.'_forme" class="forme_active">';
			$id_sav=$id_cat;
		}
		if ($result->TYPE=='svg')echo '<div onclick="javascript:add_forme(\''.$result->NOM.'\',\''.$result->TYPE.'\')" style="background-image:url(\''.$url_clip.$result->NOM.'.'.$result->TYPE.'\');" id_src="'.$url_clip.$result->NOM.'.'.$result->TYPE.'" class="forme_pres_min bibli_forme" id="'.$result->NOM.'"></div>';
		else	echo '<div onclick="javascript:add_forme(\''.$result->NOM.'\',\''.$result->TYPE.'\')" style="background-image:url(\''.$url_clip.$result->NOM.'_2.'.$result->TYPE.'\');" id_src="'.$url_clip.$result->NOM.'_2.'.$result->TYPE.'" class="forme_pres_min bibli_forme" id="'.$result->NOM.'"></div>';
											 
											
	} 
	echo '</div></div></div>';

}
?>
									
									
									
								 </li>
							  </ul>
						   </li>
						   
						</ul>

				 </div>  
								
			</div>
		</div>
	</div>
</div>	

 <script type="text/template" id="qq-template-validation-groo">
		<div class="qq-uploader-selector qq-uploader" qq-drop-area-text="<?php _e( "Drag'n drop your photos", 'woocommerce-awesome-designer' );?>">
			
						
			<div class="qq-upload-drop-area-selector qq-upload-drop-area" qq-hide-dropzone>
				<span class="qq-upload-drop-area-text-selector"></span>
			</div>
			<div class="buttons">
				<div class="qq-upload-button-selector qq-upload-button">
					<div><?php _e( 'Upload', 'woocommerce-awesome-designer' );?></div>
				</div>
				<button type="button" id="trigger-upload" class="btn btn-primary cache">
					<i class="icon-upload icon-white"></i> <?php _e( 'Upload', 'woocommerce-awesome-designer' );?>
				</button>
			</div>
			
			<span class="qq-drop-processing-selector qq-drop-processing">
				
				<span class="qq-drop-processing-spinner-selector qq-drop-processing-spinner"></span>
			</span>
			<ul class="qq-upload-list-selector qq-upload-list qq-hide" aria-live="polite" aria-relevant="additions removals">
				<li>
									
					<span class="qq-upload-file-selector qq-upload-file"></span>
					<span class="qq-edit-filename-icon-selector qq-edit-filename-icon" aria-label="Edit filename"></span>
					<input class="qq-edit-filename-selector qq-edit-filename" tabindex="0" type="text">
					<span class="qq-upload-size-selector qq-upload-size"></span>
				
				</li>
			</ul>

			<dialog class="qq-alert-dialog-selector">
				<div class="qq-dialog-message-selector"></div>
				<div class="qq-dialog-buttons">
					<button type="button" class="qq-cancel-button-selector"><?php _e( 'Close', 'woocommerce-awesome-designer' );?></button>
				</div>
			</dialog>

			<dialog class="qq-confirm-dialog-selector">
				<div class="qq-dialog-message-selector"></div>
				<div class="qq-dialog-buttons">
					<button type="button" class="qq-cancel-button-selector"><?php _e( 'Leave', 'woocommerce-awesome-designer' );?></button>
					<button type="button" class="qq-ok-button-selector"><?php _e( 'OK', 'woocommerce-awesome-designer' );?></button>
				</div>
			</dialog>

			<dialog class="qq-prompt-dialog-selector">
				<div class="qq-dialog-message-selector"></div>
				<input type="text">
				<div class="qq-dialog-buttons">
					<button type="button" class="qq-cancel-button-selector"><?php _e( 'Leave', 'woocommerce-awesome-designer' );?></button>
					<button type="button" class="qq-ok-button-selector"><?php _e( 'OK', 'woocommerce-awesome-designer' );?></button>
				</div>
			</dialog>
		</div>
	</script>
	
</body>
</html>
	
						

	