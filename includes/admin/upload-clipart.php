<?php


	
	include(THE_AWE_DES_AWESOME_INCLUDES.'wordpress_env.php');
	
	if (the_awe_des_check_is_admin_w()) {
		
		$table_name = $wpdb->prefix . 'the_awe_des_awesome_clipart';		
		
		if (!empty($_FILES)) {			
		
			$tempFile = $_FILES['myfile']['tmp_name'];
			
			if ($tempFile !='') {
		
				$name =  uniqid().rand(1,100);			
				
				
				$path_array  = wp_upload_dir();	
				$path =  $path_array['basedir'].'/the-awe-des-awesome-clipart/';
				$path_url = $path_array['baseurl'].'/the-awe-des-awesome-clipart/';
				$image_info = getimagesize($_FILES["myfile"]["tmp_name"]);
				$mime_type = $image_info["mime"];
				
				if ($mime_type == 'image/png') {
					
					
					$file_name = $name.'.png';
					$file_name_thumb =  $name.'_2.png';
					//move_uploaded_file($_FILES["myfile"]["tmp_name"],$path.$file_name);
					$type='png';
					
					$source = imagecreatefrompng($_FILES["myfile"]["tmp_name"]);
					imagealphablending($source,false);
					imagesavealpha($source,true);
					imagepng($source,$path.$file_name);
					
					$width = imagesx($source);
					$height = imagesy($source);
					$thumbWidth = 590;
					// calculate thumbnail size
					if ($width > $height) {
						$newwidth = $thumbWidth;
						$newheight = floor($height * ( $thumbWidth / $width ));
					} else {
						$newwidth = floor($width * ( $thumbWidth / $height ));
						$newheight = $thumbWidth;
					}
					
					$thumb = imagecreatetruecolor($newwidth, $newheight);
					imagealphablending($thumb, false);
					imagesavealpha($thumb, true);  
					imagealphablending($source, true);
					imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
					imagepng($thumb,$path.$file_name_thumb);
					 imagedestroy($thumb);				
					 imagedestroy($source);
					
				} else {
					$type='svg';
					$file_name_thumb = $file_name = $name.'.svg';					
					move_uploaded_file($_FILES["myfile"]["tmp_name"],$path.$file_name);	
					
				}
				
				
				if (isSet($type)){
					$wpdb->insert( 
						$table_name, 
						array( 
							'TIME' => current_time( 'mysql' ), 
							'NOM' => $name ,
							'TYPE' => $type,
							'VISIB'=> 1,'ID_CAT' =>1
						) 
					);
					
					$result['success']=true;
					$result['message']=$name;			
					$result['uuid']=$name;
				} else {
					$result['success']=false;
					
				}
				
			}
		}
	}

if (!isSet($result['success'])) $result['success']=false;
$json = json_encode($result);

header('content-type: application/json; charset=utf-8');
echo $json;



?>