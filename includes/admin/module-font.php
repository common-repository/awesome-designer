<?php

include(THE_AWE_DES_AWESOME_INCLUDES.'wordpress_env.php');

if (the_awe_des_check_is_admin_w()) {
	
if (isset($_POST) && is_array($_POST)){
    foreach ($_POST as $key => $value){
		
        $POST_Secured[$key] = htmlentities($value, ENT_QUOTES);
    }
	if ($POST_Secured["action"] && $POST_Secured["action"] !='') {
		
		$action= $POST_Secured["action"];
		
		if ($POST_Secured["cible"] && $POST_Secured["cible"] !='') {
			
			$cible=$POST_Secured["cible"];
			
			if ($cible!='') {
				$table_name = $wpdb->prefix . 'the_awe_des_awesome_font';
				
				if ($action=='dft') {
					$sql = array( 'DFT' => '0' );
					$wpdb->update( 
						$table_name, 
						$sql, 
						array('DFT' => '1')	
					);
					$sql = array( 'DFT' => '1' );
					$wpdb->update( 
						$table_name, 
						$sql, 
						array( 'ID' => $cible )	
					);
					
				} else if ($action=='sup') {				
								
						$wpdb->delete( 
							$table_name, 						
							array( 'ID' => $cible )	
						);
					
				
				} else if ($action=='add') {
					
					if ($POST_Secured["new"] && $POST_Secured["new"] !='') {
						
						
						$wpdb->insert(
							$table_name ,
							array(
								'NOM_URL' => $POST_Secured["new_url"],
								'NOM' =>  $POST_Secured["new"],
								'TIME' => current_time( 'mysql' )
							)
						);
					}					
				}
			}
		} 		
	}
	
}
$table_name = $wpdb->prefix . 'the_awe_des_awesome_font';
$results_font = $wpdb->get_results( 'SELECT ID,NOM,NOM_URL,DFT FROM '.$table_name.' order by ID ASC ', OBJECT );
?>
<html><head>
<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1">
<meta http-equiv="Content-Language" content="fr"> 
<meta http-equiv="imagetoolbar" content="no">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="mobile-web-app-capable" content="yes">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<link href='https://fonts.googleapis.com/css?family=News+Cycle' rel='stylesheet' type='text/css'>
<link type="text/css" rel="stylesheet" href="<?php echo $url_dossier_awesome;?>files/css/fontselect.css">
<link type="text/css" rel="stylesheet" href="<?php echo $url_dossier_awesome;?>files/css/admin.css">
<?php	if ( $results_font ) { 
			echo "<link href='https://fonts.googleapis.com/css?family=";	
				foreach ( $results_font as $result_font ) {	
					echo $result_font->NOM_URL;
					if ($result_font !== end($results_font)) echo '|';
				}
				echo "' rel='stylesheet' type='text/css'>";
		}
?>	
<script src="//code.jquery.com/jquery-2.1.4.min.js"></script>
<script src="<?php echo $url_dossier_awesome;?>files/js/font.js"></script>
</head>

<body topmargin="0">
<!-- header --> 
<?php include(THE_AWE_DES_AWESOME_INCLUDES.'admin/module-header.php'); ?>

<form method="post" id="form_effet" name="form_effet" > 
			<input type="hidden" name="action" id="action" value="">
			<input type="hidden" name="cible" id="cible" value="">
			<input type="hidden" name="new" id="new" value="">
			<input type="hidden" name="new_url" id="new_url" value="">

<!-- contenu --> 
<!-- select --> 
<div class="menu-select">
	<div style="padding:26px 5px; float:left">
		<p class="txt"><?php _e('Choose a Font','woocommerce-awesome-designer');?> :</p>
	</div>

	<div style="padding:20px 5px; float:left">
		<input id="fonts" type="text" style="display: none;">
	</div>
<!-- bouton ADD FONT --> 
	<div style="padding:15px 2px; float:left">
		<a href="#" class="admin-button" onclick="javascript:action('fonts','add');"><?php _e('ADD FONT','woocommerce-awesome-designer');?></a>
	</div>
	<div style="clear:both"></div>
</div>

<!-- texte demo --> 
<div class="menu">
	<div style="padding:10px 0; width:90%; margin:auto;">
		<p class="txt" style="text-align:center; "><?php _e('Demo Text','woocommerce-awesome-designer');?></p>
			<div class="cadre">
			<p class="demo-txt" id="text-test">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt labore et dolore magna aliqua.</p>
			</div>
	</div>
	<div style="clear:both"></div>
</div>
<div class="menu">
	<div style="padding:10px 0; width:90%; margin:auto;">
		<p class="txt" style="text-align:center; "><?php _e('Default font for your customer','woocommerce-awesome-designer');?></p>
			<div class="cadre">
			<p class="demo-txt" id="text-test">
			<?php	if ( $results_font ) { 
			
						foreach ( $results_font as $result_font ) {	
							
							if ($result_font->DFT==1) {
								$font_style = $result_font->NOM;
								$font_name = 	$font_style;				
								$font_style = 'font-family:'.$font_style;
								echo '<p class="demo-txt" style="'.$font_style.';">'.$font_name.'</p>';
							}
						}
			}
			?>
			</p>
			</div>
	</div>
	<div style="clear:both"></div>
</div>
<!-- gestion des fontes --> 
<div class="menu">
	<div style="padding:10px 0; width:90%; margin:auto;">
		<p class="txt" style="text-align:center; "><?php _e('My Fonts','woocommerce-awesome-designer');?></p>
			<div class="cadre">
				<?php	if ( $results_font ) { 
			
				foreach ( $results_font as $result_font ) {	
					$font_style = $result_font->NOM;
					if (strrpos($font_style, ":")===false) {	
						$font_name = 	$font_style;				
						$font_style = 'font-family:'.$font_style;
						
					} else {
						$font_name = 	substr($font_style,0,strrpos($font_style, ":"));		
						$font_style = 'font-family:'.substr($font_style,0,strrpos($font_style, ":")).';font-weight:'.substr($font_style,strrpos($font_style, ":")+1,strlen($font_style));	
					} 
					echo '<div class="menu"><div class="left"><p class="demo-txt" style="'.$font_style.';">'.$font_name.'</p>
					</div>
					<div class="right">';
					if ($result_font->DFT!=1) echo '<input type="radio" name="font_dft" id="font_dft" value="'.$result_font->ID.'" />';
					else echo '<input type="radio" name="font_dft" id="font_dft" value="'.$result_font->ID.'" checked="checked" />';
					echo '
					</div>
					<div class="right"><a href="#" onclick="javascript:action(\''.$result_font->ID.'\',\'sup\');"><img src="'.$url_dossier_awesome.'files/img/interface-admin/trash.png" width="20" height="25"></a>
					</div>	
					<div style="clear:both"></div>
				</div>';
				}
				
				} ?>
				
				
				
				
			
			</div>
	</div> 
	<div style="clear:both"></div>
</div>
</form>
</body>
</html>

<script > 	
	var site_url = '<?php echo site_url();?>';
	$('input[type="radio"]').change(function() {
		
		action(this.value,'dft');
	});

	function test(e){
			window.parent.postMessage(e, "*");
		}	

			$('#fonts').fontselect().change(function(){
		
      // replace + signs with spaces for css
      $('#new_url').val($(this).val());
		  var font = $(this).val().replace(/\+/g, ' ');
		   $('#new').val(font);
		  if (font.indexOf(":")!=-1) {
			  $('#text-test').css("font-family",font.substr(0,font.indexOf(":"))); 
			  $('#text-test').css("font-weight",font.substr(font.indexOf(":")+1,font.size)); 
		  } else {
			 $('#text-test').css("font-family",font); 
			  $('#text-test').css("font-weight",''); 
		  }
		
    
    });
	
	function action (cible,action){
		$('#action').val(action);
		$('#cible').val(cible);
		$( "#form_effet" ).submit();
	
	}
		
</script>
<?php } ?>