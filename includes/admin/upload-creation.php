<?php


include(THE_AWE_DES_AWESOME_INCLUDES.'wordpress_env.php');

if (the_awe_des_check_is_admin_w()) {
		require(THE_AWE_DES_AWESOME_INCLUDES."image-uploader.php");
	
	if (!empty($_FILES)) {	
		$tempFile = $_FILES['myfile']['tmp_name'];
		
		if ($tempFile !='') {
		
			$imageUploader = new THE_AWE_DES_Image_Uploader();
			
			$path_array  = wp_upload_dir();
			$path =  $path_array['basedir'].'/the-awe-des-awesome-product/base/';
			$imageUploader->setPath($path);  
			$name =  uniqid().rand(1,100);			
			
			try {
				
				$url_final = $imageUploader->upload($_FILES['myfile'],$name);
				/*$result['success']=true;
				$result['url']=$url_final;		
				*/
				$split_name = explode('.',$url_final);
				
				$result['success']=true;
				$result['message']=$name;
				$result['extension']=$split_name[1];
			
				$result['name']=$name;
				$result['uuid']=$url_final;				
				
			} catch(Exception $e){
				$result['success']=false;
				$result['url']=$e->getMessage();
			}
			
			$json = json_encode($result);

			header('content-type: application/json; charset=utf-8');
			echo $json;
			
		}
	}
	
}


?>