	function reinit_imag(){
		
		$('#cont_canvas').html('<img id="image_crop" src="" style="display:none;">');
		$('#image_crop').cropper('destroy');
		
		$('#par_canv').remove();
		
		$('.menu_multi').addClass("cache");
		$('.div_crop').addClass('cache');
		$('.div_upload').removeClass('cache');
		
	}
	
	function charge_image(name,folder) {
		$('#awesome_parent').val(name.split('.')[0]);
		$('#awesome_extension').val(name.split('.')[1]);
		$('#awesome_name').val(new_name);		
		$('#awesome_folder').val(folder);		
		
		$('.chang_upload_multi').removeClass("cache");
		
		$('#image_crop').attr('src',PATH+folder+'/'+name);
		$('.div_upload').toggleClass("cache");
		
		$('.div_crop').toggleClass("cache");
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
	
	function cache_all_ligne(){}
	
	

	function change_canv_color(col){
		if ($('#awesome_type').val()!='multi') {
			CraPacrAxx.backgroundColor ='#'+col;
					CraPacrAxx.renderAll();
		}
	}
	
	function checkHex(value){
		return /^([A-Fa-f0-9]{6}$)/.test(value)
	}
	
	function add_color(col){
		
		
		if ($('#awesome_type').val()!='multi') color = $("#colors");
		else color = $("#colors_multi");
		
		var texte_col = color.val();
		if (texte_col=='') 	color.val(col);
		else color.val(color.val()+','+col);
		valid_color();
		
	}
	
	function valid_color(){
		
		if ($('#awesome_type').val()!='multi'){
			div_color = $('#list_colors')
			color = $("#colors");
		} else {
			color = $("#colors_multi");
			div_color = $('#list_colors_multi')
		}
		
		$('#valid_color').val('1');
		var texte_col = color.val();
		 
		div_color.empty();
		if (texte_col!='') {
			var col_ar = texte_col.split(',');			
			for (var i = 0; i < col_ar.length; i++) {
				var hexa = col_ar[i];
				if (!checkHex(hexa)) $('#valid_color').val('0');
				else {
					div_color.show(); 
					div_color.append('<div onclick="change_canv_color(\''+hexa+'\')" style="display:inline-block;width:15px;height:15px;background-color:#'+hexa+';margin-left:5px;"></div>');
					if ($('#awesome_type').val()!='multi' ) change_canv_color(hexa);
				}
			}
		}
		
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
	
	function valid_etap(name,value){	
			$('.'+name).removeClass('on');
			$('.'+value).addClass('on');
	
			if (name=='type') {
				$('.div_masq').removeClass("cache");				
				if (value !='multi') $('.multi_produc').addClass('cache');
				else  $('.multi_produc').removeClass('cache');
			} else if (name=='masq') $('.div_name').removeClass("cache");
			else if (name=='name') {
				$('.debut').addClass("cache");
				$('.fin').removeClass("cache");
				$('.input_name').addClass("cache");
				$('.div_'+$('#awesome_masq').val()).removeClass("cache");
				$('.reset').removeClass('cache');
				if ($('#awesome_type').val()=='multi') {
					$('.result_nbe').html('<p class="txt">'+$('#number_product').val()+'</p>');
					$('.multi_produc').addClass('cache');
					$('.nbe_prod').removeClass('cache');
					
					nbe_face = $('#number_product').val();
					$('#nbe_tot').val(nbe_face);				
					$('.num_current').html('<p class="txt">'+current_face+'/'+nbe_face+'</p>');
					$('.div_multi').removeClass('cache');
					$('.div_upload').addClass('cache');
				} else {
					
					//$('#id_multi').val('');
				}
			}
		
		$('#awesome_'+name).val(value);
	}
	
	
	function save_multi(){
		
		$( "#valid" ).val("ok");
		$( "#form_effet" ).submit();
		
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
	
	function valid_crop(){			
		
		$.ajax({
			url: url_crop_creation,
			type: "POST",
			data: $("input[name*='awesome_']").serialize(),			
			success: function(c) {
				
			
				if (c.trim()=='ok'){
					$('#image_crop').cropper('destroy');
					$('#image_crop').remove();

					width = 	$('#awesome_width').val();
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
					$('.div_canv').toggleClass("cache");
					$('.div_crop').toggleClass("cache");
					var d = new Date();
					var n = d.getTime();
					
					if ($('#awesome_masq').val()=='background')	CraPacrAxx.setBackgroundImage(PATH+$('#awesome_name').val()+'_crop.'+$('#awesome_extension').val()+'?time='+n,function (){CraPacrAxx.renderAll()},{width:CraPacrAxx.width,height:CraPacrAxx.height});
					else CraPacrAxx.setOverlayImage(PATH+$('#awesome_name').val()+'_crop.'+$('#awesome_extension').val()+'?time='+n,function (){CraPacrAxx.renderAll()},{width:CraPacrAxx.width,height:CraPacrAxx.height,opacity:0.7});
					
				}
			}
		})
	}
	
		function save(){
		
		
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
			
			if ($('#awesome_type').val()=='multi') $('#id_current').val(current_face+1);
			
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
	
	
	