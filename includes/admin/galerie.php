<?php

if (the_awe_des_check_is_admin_w()) {

	//Lightbox
	

//if (true) {
?>
<div id="lightbox" name="lightbox" style="position:absolute;top:0;left:0;right:0;background-color:#ffffff;z-index:10000000;overflow:hidden" class="galerie-cache">
	
	<div class="close" style="background-image: url('<?php echo $url_dossier_awesome;?>files/img/interface-admin/close.png');" onclick="$('#lightbox').removeClass('galerie-view');"> 
	</div>

	<div class="separator" style="margin-top:50px"></div>
	
	<div class="txt-orange" style="font-size:105%; margin:5px 0px 5px 50px">
		<?php _e('Your Gallery','woocommerce-awesome-designer');?>
	</div>	
	<br>
	
	<?php
	$folder = '';
	if (isSet($array_bonus)) {
		$list_bouton=$list_img='';
		for($i=0;$i<count($array_bonus);$i++) {
			if ($array_bonus[$i]["folder"]!=$folder) {
					if ($i!=0) $list_img .=  '</div>';
					else $list_bouton .= '<div class="wrapper_petit">';
					$folder = $array_bonus[$i]["folder"];	
					$folder_link = 	str_replace(' ','',$folder);			
					$list_bouton .= '<a  href="#" onclick="$(\'.sous_galerie\').addClass(\'cache\');$(\'#galerie_'.$folder_link.'\').removeClass(\'cache\');" class="admin-button" style="margin-right:5px;" >'.$folder.'</a>';
					$list_img .= '<div class="wrapper sous_galerie galerie-cache" id="galerie_'.$folder_link.'" name="galerie_'.$folder_link.'">';
					
					
			}
			$list_img .= '
			<div style="position: relative;display: inline-block;height:140px;">
				<div style="float:left;height:100px;top:0px;">
					<div style="margin:1px;border:1px solid #ccc;height:106px;width:106px;text-align:center">
						<img style="max-width:106px;;max-height:106px;margin:auto;" data-src="'.$path.$folder.'/'.$array_bonus[$i]["nom"].'" onclick="charge_image(\''.$array_bonus[$i]["nom"].'\',\''.$folder.'\')">
					</div>
					<div style="height:19px;text-align:center;max-width:106px;overflow:hidden" class="mini-txt">'.$array_bonus[$i]["nom"].'</div>
				</div>
			</div>
			';
		}
		$list_img .=  '</div>';
		$list_bouton .=  '</div>';
		echo $list_bouton.$list_img;
		
	} ?>
	
	
	<div class="separator"></div>	
	<div class="txt-orange" style="font-size:105%; margin:5px 0px 5px 50px">
		<?php _e('Images already used','woocommerce-awesome-designer');?>
	</div>
	<br>
	<div class="wrapper">
	<?php
		for ($i=0;$i<count($array_use);$i++) {
			
			//echo '<div class="img_galerie" data-original="'.$path_base.$array_use[$i].'" style="background-image: url(\''.$url_dossier_awesome.'img/interface/puff.svg\'); " onclick="charge_image(\''.$array_use[$i].'\',\'base\')"></div>';
			echo '
			<div style="margin:1px;border:1px solid #ccc;height:106px;width:106px;top:0px;text-align:center;relative;display: inline-block;">
				<img style="max-width:106px;;max-height:106px;margin:auto;" data-src="'.$path_base.$array_use[$i].'" onclick="charge_image(\''.$array_use[$i].'\',\'base\')">
			</div>
			';
			
		}
	
	?>	
	</div>
	
	<div class="separator"></div>
	
	<div class="txt-orange" style="font-size:105%; margin:5px 0px 5px 50px">
		<?php _e('Images downloaded','woocommerce-awesome-designer');?>
	</div>	
	<br>
	<div class="wrapper">
	<?php
		for ($i=0;$i<count($array_tel);$i++) {
		//echo '<img src="'.$path_base.$array_tel[$i].'" width="100" height="100" onclick="charge_image(\''.$array_tel[$i].'\',\'base\')">';
			//echo '<img data-original="'.$path_base.$array_tel[$i].'" style="background-image: url(\''.$url_dossier_awesome.'img/interface/puff.svg\'); " onclick="charge_image(\''.$array_tel[$i].'\',\'base\')"></div>';
			echo '
			<div style="margin:1px;border:1px solid #ccc;height:106px;width:106px;top:0px;text-align:center;relative;display: inline-block;">
				<img style="max-width:106px;;max-height:106px;margin:auto;" data-src="'.$path_base.$array_tel[$i].'" onclick="charge_image(\''.$array_tel[$i].'\',\'base\')">
			</div>	
			';
		}
	
	?>	
	</div>
	
	
	
</div>	
<?php //} 
} ?>