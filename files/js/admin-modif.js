function charge_image(name,folder) {
			$('#awesome_parent').val(name.split('.')[0]);
			$('#awesome_extension').val(name.split('.')[1]);
			$('#awesome_folder').val(folder);		
			
			$('#cont_canvas').html('<img id="image_crop" src="'+IMAGE_PROD+folder+'/'+name+'"  style="display:none;">');			
			$('#image_crop').cropper('destroy');
			
			
			$('#image_crop').cropper({					 
			  viewMode:2,
			  preview: '.img-preview',
			  zoomOnWheel:false,
			  crop: function(e) {
				// Output the result data for cropping image.
				$('#awesome_left').val(e.x);
				$('#awesome_top').val(e.y);
				$('#awesome_width').val(e.width)
				$('#awesome_height').val(e.height)
				
			  }
			});
			
			$('#lightbox').removeClass('galerie-view');
		}
			
		
		function close_change(){
			$('#change_none').toggleClass("cache");
			$('#change_total').toggleClass("cache");	
			$('.div_upload').removeClass("cache");
			$('.div_crop').removeClass("cache");
			$('.div_wtp').addClass("cache");
			en_cours = '';
		}
		
		
		function change(compteur) {
			
			en_cours = $('#recover_'+compteur).val().split(',');
			
			$('#change_none').toggleClass("cache");
			$('#change_total').toggleClass("cache");
							
			$('#awesome_folder').val(en_cours[12]);	
			$('#awesome_name').val(new_name);
			$('#awesome_extension').val(en_cours[0].split('.')[1]);
			
			$('#awesome_parent').val(en_cours[0].split('.')[0]);
			
			$('#cont_canvas').html('<img id="image_crop" src="'+IMAGE_PROD+en_cours[12]+'/'+en_cours[0]+'"  style="display:none;">');
			
			$('#image_crop').cropper('destroy');
			
			$('#image_crop').cropper({					 
			  viewMode:2,
			  preview: '.img-preview',
			  zoomOnWheel:false,
			  crop: function(e) {
				// Output the result data for cropping image.
				$('#awesome_left').val(e.x);
				$('#awesome_top').val(e.y);
				$('#awesome_width').val(e.width);
				$('#awesome_height').val(e.height);
				
			  }
			});
			
		}
		
		function strech(action,cible){
			
			if (cible=='width'){
				var_wi = $("#div_canv").attr("data-width");
				
				if (action=='moins') $("#div_canv").attr("data-width",(var_wi-1));
				else $("#div_canv").attr("data-width",((var_wi*1)+1));
			} else {
				var_wi = $("#div_canv").attr("data-height");
				
				if (action=='moins') $("#div_canv").attr("data-height",(var_wi-1));
				else $("#div_canv").attr("data-height",((var_wi*1)+1));
			}
			resize_canv();
		}
		
		function save(){
				
			$('#action' ).val('modif_crop');
			$('#awesome_id_prod').val(en_cours[4]);
			
			var limit = taille_crea;
			if ($("#div_canv").attr("data-width")>limit) {
				
				$("#div_canv").attr("data-height",Math.round(($("#div_canv").attr("data-height")*limit)/$("#div_canv").attr("data-width")))
				$("#div_canv").attr("data-width",limit);
			}
			if ($("#div_canv").attr("data-height")>limit) {
				
				$("#div_canv").attr("data-width",Math.round(($("#div_canv").attr("data-width")*limit)/$("#div_canv").attr("data-height")))
				$("#div_canv").attr("data-height",limit);
			}
			
			var item = CraPacrAxx.getObjects();
			var canv_with = CraPacrAxx.getWidth();
			var canv_height = CraPacrAxx.getHeight();
		
			var img_with = $("#div_canv").attr("data-width");
			var img_height = $("#div_canv").attr("data-height");
			
			
			var new_ratio = canv_with/img_with;			
			
			$('#save_height').val(img_height);
			$('#save_width').val(img_with);
			
			for (i in item) {
			
				$('#save_canv_left').val(Math.round(item[i].getLeft()/new_ratio));
				$('#save_canv_top').val(Math.round(item[i].getTop()/new_ratio));
				$('#save_canv_height').val(Math.round(item[i].getHeight()/new_ratio));
				$('#save_canv_width').val(Math.round(item[i].getWidth()/new_ratio));			
						
			}
									
			if ($('#awesome_masq').val()!='background')	{
							
				$.ajax({ 
					type: "POST", 
					url: url_sav_masq,
					dataType: 'text',
					 data: $("input[type=hidden]").serialize(),
					success: function(c) {
						$( "#form_effet" ).submit();
					}
				});
				
			}else {
				$( "#form_effet" ).submit();
			}
		
		}
	
		function checkHex(value){
		return /^([A-Fa-f0-9]{6}$)/.test(value)
	}
	
	function add_color(col){
		
		
		color = $("#colors");
		
		var texte_col = color.val();
		if (texte_col=='') 	color.val(col);
		else color.val(color.val()+','+col);
		valid_color();
		
	}
	
	function valid_color(){
		
		div_color = $('#list_colors')
		color = $("#colors");
				
		$('#valid_color').val('1');
		var texte_col = color.val();
		
		div_color.empty();
		if (texte_col!='') {
			var col_ar = texte_col.split(',');			
			for (var i = 0; i < col_ar.length; i++) {
				var hexa = col_ar[i];
				if (!checkHex(hexa)) $('#valid_color').val('0');
				else {
					
					div_color.append('<div  style="display:inline-block;width:15px;height:15px;background-color:#'+hexa+';margin-left:5px;"></div>');
					
				}
			}
		}
		
	}
	
	function valid_crop(){			
		
			$.ajax({
				url: url_crop_creation,
				type: "POST",
				data: $("input[name*='awesome_']").serialize(),			
				success: function(c) {
					
				
					if (c.trim()=='ok'){
						$('#image_crop').cropper('destroy');
						$('#image_crop').remove();

						width = $('#awesome_width').val();
						height = $('#awesome_height').val();

						if (width>height) {						
							height = Math.round((taille_crea * height)/width);
							width = taille_crea;
						} else {
							width = Math.round((taille_crea * width)/height);
							height = taille_crea;
						}

						$('#cont_canvas').append('<div id="par_canv" data-width="'+taille_crea+'" data-height="'+taille_crea+'" name="par_canv" class="disparition" style="position:relative;margin-top:0px;margin-left:0px;width:'+taille_crea+'px;height:'+taille_crea+'px;"><div id="div_canv" class="" data-width="'+width+'" data-height="'+height+'" style="position:absolute;" ><canvas id="CraPacrA_0" width="'+width+'" height="'+height+'"  style="border:1px solid black;"></div></div>');
						CraPacrAxx = this.__canvas = new fabric.Canvas('CraPacrA_0');
						resize_canv();
						$('.div_upload').toggleClass("cache");
						$('.div_crop').toggleClass("cache");
						$('.div_wtp').toggleClass("cache");
						var d = new Date();
						var n = d.getTime();
					
						if ($('#awesome_masq').val()=='background')	CraPacrAxx.setBackgroundImage(IMAGE_PROD+$('#awesome_name').val()+'_crop.'+$('#awesome_extension').val()+'?time='+n,function (){CraPacrAxx.renderAll()},{width:CraPacrAxx.width,height:CraPacrAxx.height});
						else CraPacrAxx.setOverlayImage(IMAGE_PROD+$('#awesome_name').val()+'_crop.'+$('#awesome_extension').val()+'?time='+n,function (){CraPacrAxx.renderAll()},{width:CraPacrAxx.width,height:CraPacrAxx.height,opacity:0.7});
						$('#width').val(en_cours[1]);
						$('#height').val(en_cours[2]);
						$('#mesure').val(en_cours[3]);
						valid_wtp();
						
					}
				}
			})
		}
		
		
		function change_wtp(compteur) {
			
			en_cours = $('#recover_'+compteur).val().split(',');
			
			$('#change_none').toggleClass("cache");
			$('#change_total').toggleClass("cache");
			$('#awesome_folder').val(en_cours[12]);					
			
			$('#awesome_name').val(en_cours[11].split('_')[0]);
			$('#awesome_extension').val(en_cours[11].split('.')[1]);
			
			$('#awesome_parent').val(en_cours[0].split('.')[0]);
			
			$('#image_crop').cropper('destroy');
			
			$('#cont_canvas').html('<img id="image_crop" src="" style="display:none">');
			
			
			
			$('#cont_canvas').append('<div id="par_canv" data-width="'+en_cours[5]+'" data-height="'+en_cours[6]+'" name="par_canv" class="disparition" style="position:relative;margin-top:0px;margin-left:0px;width:'+en_cours[5]+'px;height:'+en_cours[6]+'px;"><div id="div_canv" class="" data-width="'+en_cours[5]+'" data-height="'+en_cours[6]+'" style="position:absolute;" ><canvas id="CraPacrA_0" width="'+en_cours[5]+'" height="'+en_cours[6]+'"  style="border:1px solid black;"></div></div>');
			CraPacrAxx = this.__canvas = new fabric.Canvas('CraPacrA_0');
			
			resize_canv();
			
			
			height_area = en_cours[10];
			width_area = en_cours[9];
			
			ratio = CraPacrAxx.getWidth()/en_cours[5];
			if ($('#awesome_masq').val()=='background')  color = 'rgba(33,33,33,0.3)';
			else {
				color = 'rgba(123,185,35,1)';
				CraPacrAxx.backgroundColor ='red';
			}
			var rect = new fabric.Rect({
			
				width: width_area*ratio,
				height: height_area*ratio,			
				fill: color,
			  originX: 'center',
			  originY: 'center'
				
			 });
			
			var text = new fabric.Text(msg_wtp, {
				fontSize: 25,
			  fontFamily: 'News Cycle',
			  originX: 'center',
			  originY: 'center',			  	 
			  fill: '#000000'
			});
			
			
			if (text.width>rect.width) text.angle=270;
			if ((width_area/height_area) > 7 || (height_area/width_area) > 7) {
				text.scaleToWidth(0);
			}
			text.scaleToWidth(0);	
			var group = new fabric.Group([ rect, text ], {
			  left: en_cours[8]*ratio,
				top: en_cours[7]*ratio,
				transparentCorners: true,
				hasRotatingPoint: false,
				lockUniScaling :true,
				hasBorders:true,
				transparentCorners: false,
				borderColor:'#ff6000',
				cornerColor:'#ff6000'
			});
			//console.log(group);
			
			CraPacrAxx.add(group).setActiveObject(group);
			
			
			
			$('.div_upload').toggleClass("cache");
			$('.div_crop').toggleClass("cache");
			$('.div_wtp').toggleClass("cache");
			
			if ($('#awesome_masq').val()=='background')	CraPacrAxx.setBackgroundImage(IMAGE_PROD+en_cours[11],function (){CraPacrAxx.renderAll()},{width:CraPacrAxx.width,height:CraPacrAxx.height});
			else CraPacrAxx.setOverlayImage(IMAGE_PROD+en_cours[11],function (){CraPacrAxx.renderAll()},{width:CraPacrAxx.width,height:CraPacrAxx.height,opacity:0.7});
			$('#width').val(en_cours[1]);
			$('#height').val(en_cours[2]);
			$('#mesure').val(en_cours[3]);
			$('#save_prod_width').val($('#width').val());
			$('#save_prod_height').val($('#height').val());
			$('#save_prod_unit').val($('#mesure').val());
			
		}
		
		

	
		
		
		function valid_wtp(){
	 
			if ($('#width').val()!='' && $('#height').val()!=''){
		 
				CraPacrAxx.clear();
				
				height_area = CraPacrAxx.height -20;
				width_area = ((CraPacrAxx.height -20)*$('#width').val())/$('#height').val();
				
				if (width_area>CraPacrAxx.width) {
					width_area = CraPacrAxx.width -20;
					height_area = ((CraPacrAxx.width -20)*$('#height').val())/$('#width').val();
				}
				
				$('#save_prod_width').val($('#width').val());
				$('#save_prod_height').val($('#height').val());
				$('#save_prod_unit').val($('#mesure').val());
				
				if ($('#awesome_masq').val()=='background')  color = 'rgba(33,33,33,0.3)';
				else {
					color = 'rgba(123,185,35,1)';
					CraPacrAxx.backgroundColor ='red';
				}
				var rect = new fabric.Rect({
				
					width: width_area*1,
					height: height_area*1,			
					fill: color,
				  originX: 'center',
				  originY: 'center'
					
				 });
				
				var text = new fabric.Text(msg_wtp, {
					fontSize: 25,
				  fontFamily: 'News Cycle',
				  originX: 'center',
				  originY: 'center',			  	 
				  fill: '#000000'
				});
				
				
				text.scaleToWidth(0);
				
				
				var group = new fabric.Group([ rect, text ], {
				  left: 10,
					top: 10,
					transparentCorners: true,
					hasRotatingPoint: false,
					lockUniScaling :true,
					hasBorders:true,
					transparentCorners: false,
					borderColor:'#ff6000',
					cornerColor:'#ff6000'
				});

				
				//group.scaleToWidth(rect.width);
			  
			  CraPacrAxx.add(group).setActiveObject(group);
				
			 
			}
		}
		function resize_canv() {
			w_c = 0;
			h_c = 0;

			w = $('#cont_canvas').css("width").substr(0,($('#cont_canvas').css("width").length-2));
			h = $('#cont_canvas').css("height").substr(0,($('#cont_canvas').css("height").length-2));				
										
			for (var z=0;z<objet_max;z++) {						
				
				width_min = $("#div_canv").attr("data-width")*1;
				height_min = $("#div_canv").attr("data-height")*1;

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
										
				$("#par_canv").css("width",w);
				$("#par_canv").css("height",h);
				$("#div_canv").css("width",w_c);
				$("#div_canv").css("height",h_c);
				$("#div_canv").css("left",((w-w_c)/2));
				$("#div_canv").css("top",((h-h_c)/2));
				
				var item = CraPacrAxx.getObjects();		
				var new_ratio = CraPacrAxx["width"]/w_c;						
				CraPacrAxx.setDimensions({ width: w_c, height: h_c });					
				if (CraPacrAxx.backgroundImage && CraPacrAxx.backgroundImage != "") CraPacrAxx.backgroundImage.setWidth(w_c*1).setHeight(h_c*1);								
				if (CraPacrAxx.overlayImage && CraPacrAxx.overlayImage != "") CraPacrAxx.overlayImage.setWidth(w_c*1).setHeight(h_c*1);	
			
				CraPacrAxx.renderAll();
			}					

		}
		
		
	function change_multi(id) {
		$('.div_multi').addClass('cache');
		$('#div_'+id).removeClass('cache');
		
	}