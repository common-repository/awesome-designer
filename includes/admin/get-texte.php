<?php

include(THE_AWE_DES_AWESOME_INCLUDES.'wordpress_env.php');


if (the_awe_des_check_is_admin_w()) {
	if (isset($_GET) && is_array($_GET)){
		foreach ($_GET as $key => $value){
			
			$GET_Secured[$key] = htmlentities($value, ENT_QUOTES);
		}
		
		if (isset( $GET_Secured['id']) and ctype_alnum($GET_Secured['id']) and is_numeric($GET_Secured['choix']) 
		 and is_numeric($GET_Secured['r'] ) and is_numeric($GET_Secured['ratio'] ) 	) {
			
			$path_array  = wp_upload_dir();
		
			$path =  $path_array['basedir'].'/the-awe-des-awesome-commande/'.$GET_Secured['id'].'/sav-'.$GET_Secured['id'].'.txt';		
			
			$path_img = $path_array['baseurl'].'/the-awe-des-awesome-commande/'.$GET_Secured['id'].'/rendu-'.$GET_Secured['id'].'.png';		
			
			$fp = fopen ($path, "r");  
			$info_commande = fread($fp, filesize($path));  
			fclose ($fp);  
			
			$liste_param = explode('--seprainf--',$info_commande);
			
			if (strpos($liste_param[0],'--sepracanv--')!=false) {
				if (isset( $GET_Secured['choix'])) $choix = $GET_Secured['choix'];
				$is_multi = true;	
				$list_multi = explode('--sepracanv--',$liste_param[0]);
				$canv_sav = json_decode (utf8_encode(base64_decode($list_multi[$choix])), true);
				$canv_javascript = 	$list_multi[$choix];							
				
	
			} else {
				$canv_sav = json_decode (utf8_encode(base64_decode($liste_param[0])), true);
				$canv_javascript = 	$liste_param[0];		
				
				
			
			}
		
			$r = $GET_Secured["r"];
					
			
			
			$obj = $canv_sav["objects"][$r];
			
			$ratio = $GET_Secured["ratio"];
		
	
	?>
	<html><head>
	<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1">
	<meta http-equiv="Content-Language" content="fr"> 
	<meta http-equiv="imagetoolbar" content="no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="mobile-web-app-capable" content="yes">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	<script src="//code.jquery.com/jquery-2.1.4.min.js"></script>
	<script src="<?php echo $url_dossier_awesome;?>files/js/fabric.js"></script>	
	<link href="https://fonts.googleapis.com/css?family=<?php echo str_replace(' ','+',$obj["fontFamily"]);?>" rel="stylesheet" type="text/css">
	</head>
	<body topmargin="0">
	<canvas id='c' name="c" style="border:1px solid black;"></canvas>
	<script type="text/javascript">

	var items = JSON.parse(atob('<?php echo $canv_javascript;?>'));

	var new_canv = {};
	var objects = [];

	new_canv.objects = objects;
	new_canv.objects.push(items.objects[<?php echo $r ?>]);
	new_canv.background=''; 
//	console.log(JSON.stringify(items));
	
	var IMAGE_INTERF = '<?php echo $url_dossier_groovefx.'files/img/interface-canvas/';?>';
	function check_nbe(){}	
		var objet_max=1;
		var left_magnetic='';
		function cache_all_ligne(){}		  
	var CraPacrAxx = this.__canvas = new fabric.Canvas('c');

	CraPacrAxx.selection=false;


	CraPacrAxx.loadFromJSON(new_canv,function(){
		
		setTimeout(function(){
				var ratio = <?php echo $ratio ?>;
				var item = CraPacrAxx.getObjects();	
				item[0].selectable=false;
				item[0].setLeft(0);
				item[0].setTop(0);
				item[0].set('angle',0);
				item[0].set('scaleY',(item[0].scaleY*ratio));
				item[0].set('scaleX',(item[0].scaleX*ratio));
				item[0].set('fontFamily','<?php echo $obj["fontFamily"];?>');
				CraPacrAxx.renderAll();
				CraPacrAxx.setDimensions({ width: (item[0].getWidth()+20), height:(item[0].getHeight()+20) });
				
				window.location = CraPacrAxx.toDataURL("image/png");
			},2000);
		
	});


	 

	</script>
	</body>
	</html>
<?php 	}
 	}	
	}?>