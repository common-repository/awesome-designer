<?php

include(THE_AWE_DES_AWESOME_INCLUDES.'wordpress_env.php');

if (the_awe_des_check_is_admin_w()) {




if (isset($_POST) && is_array($_POST)){
    foreach ($_POST as $key => $value){
		
        $POST_Secured[$key] = @htmlentities($value, ENT_QUOTES);
    }
	
	
	
	if ($POST_Secured["action"] && $POST_Secured["action"] !='') {
		
		$action= $POST_Secured["action"];
	//	print_r ($POST_Secured);
		if ($POST_Secured["cible"] && $POST_Secured["cible"] !='') {
			
			$cible=$POST_Secured["cible"];
			
			if ($cible!='') {
				$table_name = $wpdb->prefix . 'the_awe_des_awesome_clipart';
				
				if ($action=='sho' or  $action=='hid' or $action == 'mov') {
					
					if ($action=='sho') $sql = array( 'VISIB' => 1);
					else if ($action=='hid') $sql = array( 'VISIB' => 0);
					else $sql = array( 'ID_CAT' => $POST_Secured['chang_select'] );
					
					foreach($_POST["check_prod"] as $valeur){
						
						$wpdb->update( 
							$table_name, 
							$sql, 
							array( 'ID' => $valeur )	
						);
					}
				} else if ($action=='sup') {				
					foreach($_POST["check_prod"] as $valeur){
						
						$wpdb->delete( 
							$table_name, 						
							array( 'ID' => $valeur )	
						);
					}
				} else if ($action=='chang') {	
					if ($POST_Secured[$cible."_chang"] && $POST_Secured[$cible."_chang"] !='') {	
						$table_name = $wpdb->prefix . 'the_awe_des_awesome_clipart_cat';
						
						$results_cat_exist = $wpdb->get_results( 'SELECT NOM FROM '.$table_name.' WHERE NOM="'.sanitize_text_field($POST_Secured["add_categorie"]).'" order by ID ASC ', OBJECT );	
						if ( !$results_cat_exist ) { 	
							$wpdb->update( 
								$table_name, 
								array( 'NOM' => $POST_Secured[$cible."_chang"],
									'URL' => urlencode( $POST_Secured[$cible."_chang"])), 
								array( 'ID' => $cible )	
							);
						} else {
							$error = 'Categorie existant.';	
						}
					} else {
						$error = 'Give a name.';
					}
				} else if ($action=='hid_cat') {	
					
						$wpdb->update( 
							$table_name, 
							array( 'VISIB' => 0), 
							array( 'ID_CAT' => $cible )	
						);
				
				} else if ($action=='sup_cat') {	
													
					$wpdb->delete( 
							$table_name, 						
							array( 'ID_CAT' => $cible )	
						);
									 
					$table_name = $wpdb->prefix . 'the_awe_des_awesome_clipart_cat';
					$wpdb->delete( 
							$table_name, 						
							array( 'ID' => $cible )	
						);
					
				
				
				} else if ($action=='new') {
				
					if ($POST_Secured["add_categorie"] && $POST_Secured["add_categorie"] !='') {
						$table_name = $wpdb->prefix . 'the_awe_des_awesome_clipart_cat';
						$results_cat_exist = $wpdb->get_results( 'SELECT NOM FROM '.$table_name.' WHERE NOM="'.sanitize_text_field($POST_Secured["add_categorie"]).'" order by ID ASC ', OBJECT );	
						if ( !$results_cat_exist ) { 
							$wpdb->insert(
								$table_name ,
								array(
									'NOM' => $POST_Secured["add_categorie"],
									'URL' => urlencode( $POST_Secured["add_categorie"]),
									'TIME' => current_time( 'mysql' )
								)
							);
						} else {
							$error = 'Categorie existant.';	
						}
						
						
					} else {
						$error = 'Give a name.';
					}
				}
			}
		} 		
	}
}


$table_name = $wpdb->prefix . 'the_awe_des_awesome_clipart';
$results = $wpdb->get_results( 'SELECT ID,TYPE,NOM,VISIB,ID_CAT FROM '.$table_name.' ORDER BY TIME DESC ', OBJECT );	
$table_name = $wpdb->prefix . 'the_awe_des_awesome_clipart_cat';
$results_cat = $wpdb->get_results( 'SELECT ID,NOM,URL FROM '.$table_name.' order by ID ASC ', OBJECT );	

$upload = wp_upload_dir();   
$upload_clip = $upload['baseurl'] . '/the-awe-des-awesome-clipart/';

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
<link type="text/css" rel="stylesheet" href="<?php echo $url_dossier_awesome;?>files/css/file_upload.css">
<script src="//code.jquery.com/jquery-2.1.4.min.js"></script>
<script src="<?php echo $url_dossier_awesome;?>files/js/file_upload.js"></script>
	
</head>

<body topmargin="0">
<!-- header --> 
<?php include(THE_AWE_DES_AWESOME_INCLUDES.'admin/module-header.php'); ?>
<form method="post" id="form_effet" name="form_effet" > 

<input type="hidden" name="action" id="action" value="">
			<input type="hidden" name="cible" id="cible" value="">
			<input type="hidden" name="id_cible" id="id_cible" value="">

<div class="full">
 	<p class="txt"><?php _e('MANAGE YOUR CLIPARTS','woocommerce-awesome-designer');?></p>
</div>
<?php	if ( isSet($error) ) { ?>
<div class="full">
 	<p class="txt error"><?php echo $error;?></p>
</div>
<?php } ?>

<?php echo '<div class="flex-outils menu_clipa" style="position:absolute;right:0px;top:140px;width:360px;height:140px;display:none;z-index:1000000;background-color:white;">
				<div class="left2">
					<div style="margin:3 0 2 5;">
						<p class="petit-txt" style="font-weight: 400;text-align:right;">'.__('SELECT ALL','woocommerce-awesome-designer').'</p>
					</div>
					<div style="margin:5 0 2 5">
						<p class="petit-txt" style="font-weight: 400;text-align:right;">'.__('DELETE SELECTED','woocommerce-awesome-designer').'</p>
					</div>
					<div style="margin:5 0 2 5">
						<p class="petit-txt" style="font-weight: 400;text-align:right;">'.__('HIDE SELECTED','woocommerce-awesome-designer').'</p>
					</div>
					<div style="margin:5 0 2 5">
						<p class="petit-txt" style="font-weight: 400;text-align:right;">'.__('SHOW SELECTED','woocommerce-awesome-designer').'</p>
					</div>
					<div style="margin:5 0 2 5">
						<p class="petit-txt" style="font-weight: 400;text-align:right;">'.__('MOVE SELECTED TO','woocommerce-awesome-designer').'</p>
					</div>			
				</div>
			
			<div class="right2">
				<div class="mini-bt" style="margin:0 100 0 0" onclick="select_all(\'t\')"></div>
				<div class="mini-bt" style="margin:0 100 0 0" onclick="action(\'t\',\'sup\')"></div>
				<div class="mini-bt" style="margin:0 100 0 0" onclick="action(\'t\',\'hid\')"></div>
				<div class="mini-bt" style="margin:0 100 0 0" onclick="action(\'t\',\'sho\')"></div>';
				if ( $results_cat ) {  
					echo '<div style="margin-top:3px">	
						  <div style="display:inline">	
						<select name="chang_select" id="chang_select">';
					
					foreach ( $results_cat as $result ) {		
						
						echo '<option value="'.$result->ID.'">'.$result->NOM.'</option>';
			
					}  
					echo '</select>
						</div>
						<div style="float:right;margin-right:8px;">	
					<a href="#" onclick="action(\'t\',\'mov\')"><img src="'.$url_dossier_awesome.'files/img/interface-admin/bt_ok.png" width="30" height="20"></a>
					</div>
					<div style="clear:both"></div>	
					</div>
				</div>
			';
				}
				echo '
</div></div>';
?>
	
<!-- bouton UPLOAD -->
<div class="full">
 	<div style="padding:10 5 10 2; float:left;width:80%;">
		<div id="waiting_telechargement"  style="width:90%;margin:auto;margin-top:20px;z-index:10000000;opacity:1;" ></div>
		<div id="fileuploader"><?php _e( 'Upload', 'woocommerce-awesome-designer' );?></div>
	</div>
	
	<div style="clear:both"></div>
	<p class="txt-ital" style=" line-height:100%"><?php _e('SVG or PNG format only, make sure the size is large enough for printing.<br>All the new cliparts will be placed in the first category.','woocommerce-awesome-designer');?></p>
</div>

<!-- add cat -->
<div class="full menu_clipa">
 	<div style="padding:30 5 10 2; float:left">
		<p class="txt" style="font-weight: 700;"><?php _e('Add a new Category','woocommerce-awesome-designer');?> :</p>
	</div>
	<div style="padding:28 5 10 2; float:left">
	
		<input type="text" name="add_categorie" id="add_categorie" value="" style="margin-top:5px"> 
	</div>
	<div style="padding:31 5 10 2; float:left">
		<a href="#" onclick="javascript:action('new','new');"><img src="<?php echo $url_dossier_awesome;?>files/img/interface-admin/bt_ok.png" width="30" height="25"></a>
	</div>
	<div style="clear:both"></div>
</div>


<?php	if ( $results_cat ) { 
		
		foreach ( $results_cat as $result_cat ) {		
			$is_empty=true;
			echo '
<!-- PREMIERE CATEGORIE -->
<div class="full menu_clipa" style="margin-top:25px" >
<!-- cadre gris --> 
	<div class="cadre-gris-cliparts">
		<div class="left2" style="padding:10 0 5 0;">
			<p class="txt" style="font-weight: 700;">'.__('CATEGORY','woocommerce-awesome-designer').' :</p>
		</div>
		<div class="left2" style="padding:10 0;">
			<p class="txt" style="font-weight: 400;color:#838383;">'.$result_cat->NOM.'</p>
		</div>';
		if ($result_cat->ID !=1) {
		echo '<div class="right2" style="padding:10 10;">
			<a href="#" onclick="javascript:action(\''.$result_cat->ID.'\',\'sup_cat\')"><p class="txt" style="font-weight: 400;">'.__('delete category and cliparts','woocommerce-awesome-designer').'</p></a>
		</div>
		<div class="right2" style="padding:10 10;">
			<a href="#" onclick="javascript:action(\''.$result_cat->ID.'\',\'hid_cat\')"><p class="txt" style="font-weight: 400;">'.__('hide category','woocommerce-awesome-designer').'</p></a>
		</div>
		';
		}
		echo '
		<div class="left2" style="margin-left:30px;">
			<div class="right2" style="padding:0;">
				<div class="right2" style="padding:10 0;">
					<a href="#" onclick="javascript:action(\''.$result_cat->ID.'\',\'chang\')"><img src="'.$url_dossier_awesome.'files/img/interface-admin/bt_ok.png" width="30" height="25"></a>
				</div>
				<div class="right2" style="padding:7 2 0 0;">
					
						<input type="text" name="'.$result_cat->ID.'_chang" value="'.$result_cat->NOM.'" style="margin-top:5px">
					
				</div>
				<div class="right2" style="padding:10 2 0 0;">
					<p class="txt" style="font-weight: 400;">'.__('Change Name','woocommerce-awesome-designer').' :</p>
				</div>
			</div>	
		</div>
		<div style="clear:both"></div>		
	</div>
	<div class="flex-container">
<!-- cliparts --> 
		<div class="flex-cliparts" id="'.$result_cat->ID.'_div" name="'.$result_cat->ID.'_div">';
				if ( $results ) { 					
					foreach ( $results as $result ) {	
						if ($result->ID_CAT== $result_cat->ID) 	{
								$is_empty=false;
								
								echo '
								<div class="cliparts">
									<div class="float_clipart" onclick="javascript:add_forme($(this),\''.$result_cat->ID.'\')" style="background-image:url(\''.$upload_clip.$result->NOM.'.'.$result->TYPE.'\');"  id="'.$result->ID.'" >
										<div class="select_clipart" style="display:none;"></div>
										<input type="checkbox" name="check_prod[]" value="'.$result->ID.'" style="bottom:5px;right:5px;z-index:100000; visibility: hidden;"> 
									</div>
									<div style="position:relative; width:76px; height:16px; text-align:center; ';
									if ($result->VISIB == 1) echo 'visibility: hidden;';
									echo '">
										<img src="'.$url_dossier_awesome.'files/img/interface-admin/hide.png" width="16" height="16">
									</div>
								</div>';
								
						}
					} 
				}
				if ($is_empty) echo '<div class="empty_result">EMPTY</div>';
				else echo '<div  style="clear:both"></div>';
			echo '</div>
			
		
			<div style="clear:both"></div>	
		</div>	';
				
				
				
				
		} 
	}	
	?>



</div>
</form>
</body>
</html>

<script >
	var upload_back = '<?php echo site_url();?>/index.php?awesome_designer_back_upload_back=clipart';
	
	function action (cible,action){
		$('#action').val(action);
		$('#cible').val(cible);
		$( "#form_effet" ).submit();
	
	}
	
	function select_all(id){
		$('input').prop('checked', true);
		$('.select_clipart').css('display','');
		
	}
	function add_forme(id,div){
		var check = id.find('input');
		var cache = id.find('div')
		if (check.is(':checked')) {
			check.prop('checked', false);
			cache.css('display','none');
		} else {
				check.prop('checked', true);
				cache.css('display','');
		}
		$('.flex-outils').css('display','block');
		//$('.'+div+'_menu').css('display','block');
	}
	
	function test(e){
		window.parent.postMessage(e, "*");
	}
		
	var nbe_fichier = 0;
	var nbe_fini = 0;
	var is_mobile = 0;
	var text_drag_drop = "<?php _e( "Drag'n drop your photos", 'woocommerce-awesome-designer' );?>";
	$(document).ready(function() {
	
		$("#fileuploader").uploadFile({
			url:upload_back,
			fileName:"myfile",
			acceptFiles:".svg, .png",
			showPreview:false,
			showAbort:true,
			spec_module:'clipart',
			showQueueDiv:'waiting_telechargement',
			afterUploadAll:function(obj)
			{
				$( "#form_effet" ).submit();	
			},
			onSubmit:function(files)
				{
				   $('.menu_clipa').css("display","none");
				}
		});
	});
		
	/*	
		 request: {
                endpoint: '<?php echo $url_dossier_awesome;?>includes/admin/upload-clipart.php'
            },
           validation: {
				acceptFiles: ['image/*'],
				allowedExtensions: ['svg', 'png'],
				sizeLimit: 1024 * 1024 * 30
			},callbacks: {
				onAllComplete: function(fini,echec) {   					
						
				},onSubmit : function (id,name){
					$('.menu_clipa').css("display","none");
				}
			}	
	*/	
		
		
		
		
	</script>
<?php } ?>