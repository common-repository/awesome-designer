<div class="full">
<!-- header --> 
	<div class="header">
		<div class="bt-new" style=" width:40px; height:40px;" onclick="clear_canvas();">
		
		</div>
		
		<div class="bt-menu-header bt-menu-header-image on verif_class" id-cible="image" style="width:40px; height:40px;<?php if ($affich_img and $affich_img==2) echo 'display:none;';?>;" onclick="display('image');">
			
		</div>
		
		<div class="bt-menu-header bt-menu-header-texte verif_class" id-cible="texte" style=" width:40px; height:40px;<?php if ($affich_txt and $affich_txt==2) echo 'display:none;';?>;"  onclick="display('texte');">
			
		</div>
		
		<div class="bt-menu-header bt-menu-header-clipart verif_class" id-cible="clipart" style=" width:40px; height:40px;<?php if ($affich_clip and $affich_clip==2) echo 'display:none;';?>;" onclick="display('clipart');">
			
		</div>
		
		<div class="bt-menu-header bt-menu-header-magnify" style=" width:40px; height:40px;" onclick="display('magnify');">
		</div>
		
		<div style=" width:40px; height:40px;">
		</div>
		
		<div>
			<a href="#" onclick="demande_finition()" class="finish txt-fin"></a>
		</div>
	</div>
			
		
	<div class="container">
	
		<div class="div_zoom div-jonction" style="z-index:0"></div>
<!-- canvas --> 

		

<!-- menus second niveau --> 
		<div class="div_zoom full-canvas-menu">
		<div id="zoom_off" class="div_zoom_off display_none_div" onclick="display('magnify');"></div>
			
			<div class="div_zoom menu-second">			
				
				<div class="tools_select image_par" style="display:none;">
				
					<div style=" width:33px; height:33px;">
					</div>
					
					<div class="bt-trash" style=" width:33px; height:33px;"  onclick="save();poubelle()">					
					</div>
							
					<div class="bt-to-front" style=" width:33px; height:33px;" onclick="save();CraPacrAxx.bringToFront(CraPacrAxx.getActiveObject(),true);">						
					</div>
					
					<div class="bt-to-back" style=" width:33px; height:33px;" onclick="save();CraPacrAxx.sendToBack(CraPacrAxx.getActiveObject(),true);">						
					</div>
					
					<div class="bt-rotate" style=" width:33px; height:33px;" onclick="save();flip()">
					</div>
					
					<div style=" width:33px; height:33px;">
					</div>
					
					
				</div>
				
				<div class="tools_select texte_par" style="display:none;">
					
					<div style=" width:33px; height:33px;">
					</div>
					
					<div class="visible_ico" style=" width:33px; height:33px;">
					</div>
					
					<div class="bt-trash" style=" width:33px; height:33px;"  onclick="save();poubelle()">
					</div>
					
					<div class="bt-to-front" style=" width:33px; height:33px;" onclick="save();CraPacrAxx.bringToFront(CraPacrAxx.getActiveObject(),true);">
					</div>
					
					<div class="bt-to-back" style=" width:33px; height:33px;" onclick="save();CraPacrAxx.sendToBack(CraPacrAxx.getActiveObject(),true);">
					</div>
				
					
					
					<div class="visible_ico" style=" width:33px; height:33px;">
					</div>
					
					<div style=" width:33px; height:33px;">
					</div>
					
				</div>
				
				<div class="tools_select clipart_par" style="display:none;">
				
					<div style=" width:33px; height:33px;">
					</div>
												
					<div class="bt-trash" style=" width:33px; height:33px;"  onclick="save();poubelle()">
					</div>
				
					<div class="bt-to-front" style=" width:33px; height:33px;" onclick="save();CraPacrAxx.bringToFront(CraPacrAxx.getActiveObject(),true);">
					</div>
					
					<div class="bt-to-back" style=" width:33px; height:33px;" onclick="save();CraPacrAxx.sendToBack(CraPacrAxx.getActiveObject(),true);">
					</div>
					
					<div class="bt-rotate" style=" width:33px; height:33px;" onclick="save();flip()">
					</div>
					
				
					
					<div class="visible_ico" style=" width:33px; height:33px;">
					</div>
					
				</div>
				
			</div>
	
<!-- canvas --> 
		
			<div id="cont_canvas_surcouch" class="div_zoom cont_canvas_surcouch canvas" onclick="desactive()">

				
				<div id="cont_canvas" name="cont_canvas" class="cont_canvas">
					<div style="position: absolute; height: 50px; width: 50px;" id="conteneur_total" name="conteneur_total">
						<?php echo $form;?>
					</div>	
				</div>	
			</div>
		</div>

<!-- outils --> 

		<div class="div_zoom tools">
		
			<div class="minitoolbar">
				
				<?php if ($nbe_total > 1) { ?>
					<div class="previous_button" style=" width:34px; height:50px; margin-left:4px;"  onclick="slide_object('pos');"> 
				
					</div>
					
					<div class="next_button" style=" width:34px; height:50px; margin-left:4px;"  onclick="slide_object('neg');"> 
				
					</div>	
				<?php } ?>
				<div class="color_menu" style=" width:34px; height:159px; margin-left:4px;"  onclick="$('#color_par').toggleClass('cache');"> 
			
				</div>	
				
			</div>	
			


			
			
			
			
			
			<div class="maxitoolbar">	
		 
<!-- color picker --> 			
				<div style="top:0px;left:0px;right:0px;bottom:0px;position:absolute;" class="cache" id="cache_colorpicker" name="cache_colorpicker">
					<div class="option_grise" onClick="close_colorpicker();" ></div >
					<div class="div_colorpick" style="">				
						<div style="" id="histo_palett" name="histo_palett" class="sp-palette">
						<?php 
							echo '<div style="width:'.(55*count($lst_coul_port)).'px;height:60px;white-space: nowrap;">';
							for($i=0;$i < count($lst_coul_port);$i++) {
								echo '<span class="sp-thumb-el sp-thumb-light" style="background-color:'.$lst_coul_port[$i].'" onclick="$(\'#flat\').spectrum(\'set\', \''.$lst_coul_port[$i].'\');change_colorpicker(\''.$lst_coul_port[$i].'\');"></span>';
							}
							echo '<div class="clear-shadow "></div></div>';
						 ?>
						</div>
						
						<input type="text" id="flat" />
						<div style="position:relative;width:45%;text-align:center;float:left;margin-top:10px;margin-left:5px;">
							<a href="#" onClick="close_colorpicker(true);" class="greenbutton" ><?php _e( 'CANCEL', 'woocommerce-awesome-designer' );?></a>
						</div>
						<div style="position:relative;width:45%;text-align:center;float:right;margin-top:10px;margin-right:10px;">
							<a href="#" onClick="valid_colorpicker();" class="greenbutton" ><?php _e( 'VALID', 'woocommerce-awesome-designer' );?></a>
						</div>
						<div style="clear: both; "></div>
						
					</div>			
				</div>
				
				
	<!-- outils color -->			
				<div class="sous-menu cache txt" id="color_par" style="z-index:492;background-color:#FFFFFF;text-align:center;padding:0px 10px">
				
					
						
						<div style="display:none;" class="color_list_div" id="color_list_img">
							<br><?php _e( 'BORDER COLOR','woocommerce-awesome-designer' );?> : <br>
							<div style="height:70px;width:100%;;position:relative;overflow-y:hidden;overflow-x:auto;white-space:nowrap">
							<?php  
							for($i=0;$i < count($lst_coul_port);$i++) {
								echo '<span class="sp-thumb-el sp-thumb-light sp-thumb-el-o" style="background-color:'.$lst_coul_port[$i].'" onclick="id_colorpick=\'colorbox-bord-img\';;change_colorpicker(\''.$lst_coul_port[$i].'\');"></span>';
							} ?>
							</div>
						</div>
						<div style="display:none;" class="color_list_div" id="color_list_txt">
							<br><?php _e( 'TEXT COLOR','woocommerce-awesome-designer' );?> : <br>
							<div style="height:70px;width:100%;;position:relative;overflow-y:hidden;overflow-x:auto;white-space:nowrap">
							<?php  
							for($i=0;$i < count($lst_coul_port);$i++) {
								echo '<span class="sp-thumb-el sp-thumb-light sp-thumb-el-o" style="background-color:'.$lst_coul_port[$i].'" onclick="id_colorpick=\'colorbox-craPxXxXx\';;change_colorpicker(\''.$lst_coul_port[$i].'\');"></span>';
							} ?>
							</div>
						</div>
						<div style="display:none;" class="color_list_div" id="couleur_svg">
							<br><?php _e( 'CLIPART COLOR','woocommerce-awesome-designer' );?> : <br>
							<div style="height:70px;width:100%;;position:relative;overflow-y:hidden;overflow-x:auto;white-space:nowrap">
							<?php  
							for($i=0;$i < count($lst_coul_port);$i++) {
								echo '<span class="sp-thumb-el sp-thumb-light sp-thumb-el-o" style="background-color:'.$lst_coul_port[$i].'" onclick="id_colorpick=\'colorbox-fill-svg\';;change_colorpicker(\''.$lst_coul_port[$i].'\');"></span>';
							} ?>
							</div>
						</div>
							<?php if ($color_list!='') { ?>	
							<br><?php _e( 'OBJECT COLOR', 'woocommerce-awesome-designer' );?> : <br>
							<div style="height:70px;width:100%;;position:relative;overflow-y:hidden;overflow-x:auto;white-space:nowrap">
							
								<p class="txt" style="text-align:left;margin:10px 4px"><?php echo $color_list;?></p>
							</div>
							
							<?php } ?>
						<br><?php _e( 'BACKGROUND COLOR','woocommerce-awesome-designer' );?> : <br>
						<div style="height:70px;width:100%;;position:relative;overflow-y:hidden;overflow-x:auto;white-space:nowrap">
						<?php  
							
							echo '<span class="sp-thumb-el sp-thumb-light sp-thumb-el-o bt-no-color" style="" onclick="id_colorpick=\'colorbox-background\';;change_colorpicker(\'\');"></span>';	
							for($i=0;$i < count($lst_coul_port);$i++) {
								echo '<span class="sp-thumb-el sp-thumb-light sp-thumb-el-o" style="background-color:'.$lst_coul_port[$i].'" onclick="id_colorpick=\'colorbox-background\';;change_colorpicker(\''.$lst_coul_port[$i].'\');"></span>';
							} ?>
						</div>
					<br><br>
							<div class="tagbox" style="width:20%"> 
								<a href="#" style="	color:#ffffff;text-decoration: none;" onclick="$('#color_par').addClass('cache');"><?php _e( 'Close', 'woocommerce-awesome-designer' );?></a>
							</div>
							<br><br>
				</div>
				
				
				
				
	<!-- outils photo --> 	
	
				<div class="sous-menu" id="image_par">
					
					<div style="padding:7px;display:none;width:100%;margin-bottom:5px;white-space: nowrap; display:none; " class="txt" id="info_uplo_drop">
							<?php _e( "You can touch your photos uploaded to add them");?>
						
						</div>	
					
		<!-- bouton upload --> 	
					<div  style="display:none;width: 100%;position: relative;background-color:#fff;width:100%;height:90px;white-space:nowrap;overflow-y:hidden;margin-bottom:0px;" name="div_lightbox" id="div_lightbox">
					</div>		
					<div class="photo-tools" style="justify-content:center; padding-top:5px;margin-bottom:5px;">	
						
						<div id="fine-uploader-gallery"></div>
						
						
						<img id="fin_cours" name="fin_cours">
						
						<div id="change_ss_menu" name="change_ss_menu" style="display:none; margin-top:5px; padding-left:10px;white-space:nowrap;">
							<div class="change_ss_menu" id="retour_2_img" name="retour_2_img" style="display:none;text-align:center">
								<a href="#" class="tagbox2"  onClick='show_sous_menu("canv")' class="txt"><?php _e( 'Collage options', 'woocommerce-awesome-designer' );?></a>
							
							</div> 
							<div class="change_ss_menu" id="retour_2_canv" name="retour_2_canv" style="display:none;text-align:center">
								<a href="#" class="tagbox2"  onClick='show_sous_menu("img")' class="txt"><?php _e( 'Photo options', 'woocommerce-awesome-designer' );?></a>
							
							</div> 
						</div>
					</div>

					
		<!-- bouton pelemele --> 	
					
					<div  id="img_menu" name="img_menu" class="menu-mid sous_sous_menu" style="display:none">
								
						

						<div class="menu-mid photo-tools">					
							<div class="left" style="width:25%;padding-left:10px;">
								<p class="txt-slider"><?php _e( 'Zoom', 'woocommerce-awesome-designer' );?></p>
							</div>
							<div class="left" style="width:50%;">
								<input id="slider-taille-zoom" type="range" min="0.3" max="1" step="0.02" value="1" data-rangeslider>
							</div>
							<div style=" width:33px;height:33px;float:left;"></div>
							<div style="clear:both"></div>
							
							
						</div>
			
						<div class="menu-mid photo-tools" >
							<div class="left" style="width:25%;padding-left:10px;">
								<p class="txt-slider"><?php _e( 'Border', 'woocommerce-awesome-designer' );?></p>
							</div>
							<div class="left" style="width:50%;">
								<input id="slider-crapx-2" step="0.2" type="range" min="0" max="20" value="0" data-rangeslider>
							</div>
							<div style=" width:33px;height:33px;float:left;"></div>
							<div style="clear:both"></div>
							
						</div>
	
								
		<!-- boutons formes -->									
						<div class="menu-mid photo-tools" >
							<div class="bt_edge on tooltip bt_edge_top" style="margin:0 5px; display:inline-block" tip="<?php _e( 'No shape', 'woocommerce-awesome-designer' );?>" onclick="change_value_object('pattern','');">
							</div>
							<div class="bt_edgecercle bt_edge_top" style="margin:0 5px; display:inline-block" onclick="change_value_object('pattern','cercle');">
							</div>
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
							<div class="bt_edgeetoile bt_edge_top" style="margin:0 5px; display:inline-block" onclick="change_value_object('pattern','etoile');">
							</div>
							<div class="bt_edgeetoile_2 bt_edge_top" style="margin:0 5px; display:inline-block" onclick="change_value_object('pattern','etoile_2');">
							</div>
							<div class="bt_edgeetoile_3 bt_edge_top" style="margin:0 5px; display:inline-block" onclick="change_value_object('pattern','etoile_3');">
							</div>
						</div>
					</div>
					
					<div class="menu-mid sous_sous_menu"  id="canv_menu" name="canv_menu" style="display:none">
		<!-- slider image spacing--> 		
						

										
						<div class="menu-mid photo-tools" >
							<div class="left" style="width:25%;">
								<p class="txt-slider"><?php _e( 'Photo spacing', 'woocommerce-awesome-designer' );?></p>
							</div>
							<div class="left" style="width:74%;">
								<input id="slider-spacing" type="range" min="0" max="10" value="0" step="1" data-rangeslider>
							</div>
							
							<div style="clear:both"></div>
							
						</div>				
						
						<div id="divpelemele" class="menu-mid" style="text-align:center; width:100%;overflow-x:auto;overflow-y:hidden; height:105px;white-space:nowrap;margin-top:10px;">
							
							<?php for ($i=0;$i < count($list_canv_predf);$i++) {
							echo '<div class="pelemele pelemele_'.$i.'"><canvas id="predef_'.$i.'" name="predef_'.$i.'"  ></div>';
							
							
						} ?>	
							
						</div>
					</div>
					
				</div>	
	<!-- outils texte principal --> 		
				<div class="sous-menu cache" id="texte_par">
					
					<div class="photo-tools2 texte_tools">
					
						<textarea name="text" id="text" class="textarea" onclick="if (this.value=='<?php _e( 'Your text...', 'woocommerce-awesome-designer' );?>') this.value='';" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false"><?php _e( 'Your text...', 'woocommerce-awesome-designer' );?></textarea>
					</div>
					
					<div class="left2 tools_div cache_visibility width-var">			
			<!-- outils texte : alignements  --> 
						<div class="photo-tools" style="padding:2px 3px;">
						
							<div class="bt-align bt-align-right" style=" width:40px; height:40px;" onclick="change_value_object('textAlign','right');">
								
							</div>
							
							<div class="bt-align bt-align-center" style=" width:40px; height:40px;" onclick="change_value_object('textAlign','center');">
								
							</div>
							
							<div class="bt-align bt-align-left on" style=" width:40px; height:40px;" onclick="change_value_object('textAlign','left');">
								
							</div>
						</div>	
						
			<!-- outils texte : arcs  --> 				
						<div class="photo-tools" style="padding:2px 3px;">			
						
							<div class="bt-arc bt-arc-plat on" style=" width:40px; height:40px;"  onclick="change_value_object('arc','plat');">
								
							</div>
							
							<div class="bt-arc bt-arc-arc" style=" width:40px; height:40px;"  onclick="change_value_object('arc','arc');">
								
							</div>
							
							<div class="bt-arc bt-arc-arc_inv" style=" width:40px; height:40px;"  onclick="change_value_object('arc','arc_inv');">
								
							</div>
						</div>	
					
							
						<div class="menu-arc photo-tools" style="display:none;" >	
			<!-- outils texte - arcs : slider 1  -->				
								
							<div class="slider" style="padding-top:10px; width:90%; ">
								<input type="range" id="slider-CraArCPacrA" min="0" max="1.9" value="1" step="0.1" val-dft="1" data-rangeslider>
							</div>	
						
			<!-- outils texte - arcs : slider 2  --> 
						
							<div class="slider" style="padding-top:20px; width:90%; ">
								<input type="range" id="slider-CraARCPacrA" min="0.1" max="2" step="0.1" value="1"  val-dft="1" data-rangeslider>
							</div>
						
						</div>
					</div>	
			<!-- google fonts -->
					<div class="right2 tools_div cache_visibility width-var">
						<div class="photo-tools">	
							<div class="menu-mid left overflow" style="height:400%; width:100%; border:1px solid #DEDEDE">
							<?php	for ($z=0;$z<count($font_liste);$z++) {
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
						</div>
					</div>						
					

				</div>
	<!-- outils cliparts  --> 			
				<div class="sous-menu cache" id="clipart_par">
					
					<div class="photo-tools">
								
							<div class="menu-mid" style="text-align: center; margin-top:6px">
													
													
													<div style="display:inline-block">
														<p class="txt"><?php _e( 'Category', 'woocommerce-awesome-designer' );?></p>
													</div>	
													
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
										echo '</div>';
										echo '</div>';
									}
													?>
																		
							</div>
						
						</div>
					
					</div>
				</div>
<!-- fin tools  --> 				
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
					<i class="icon-upload icon-white"></i> Upload
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