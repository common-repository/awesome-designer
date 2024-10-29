function affich_upload(tag){  
	 
		if (tag) {
			$( "#info_uplo_drop" ).css("display","none");
			$('.tools').addClass('tools-max');
			$('#fine-uploader-gallery').removeClass('fine_uploader_norm').addClass('qq-upload-max');
			$('.qq-uploader > .buttons').addClass('qq-hide');	
			$('.qq-upload-list').removeClass('qq-hide');	
		
		} else {
			
			$('.tools').removeClass('tools-max');
			$('#fine-uploader-gallery').addClass('fine_uploader_norm').removeClass('qq-upload-max');
			$('.qq-uploader > .buttons').removeClass('qq-hide');
			$('.qq-upload-list').addClass('qq-hide');
			
		}
	
	}
	
	function check_quality(){
		
		if (quality_ratio!=4 && quality_action !=1) {
			var qualit = true;
			for (var z=0;z<objet_max;z++) {		
				CraPacrAxx = array_canv[z];					
				var item = CraPacrAxx.getObjects('imagepattern');
				if (item && item.length>0) {				
					for(t=0;t<item.length;t++){
						if (item[t].qualit_flag!=0) qualit = false;		
					}
				}
			}			
			if (qualit) return true;
			else {
				if (quality_action==2) {
					return confirm(text_confirm);
				} else {
					alert(text_confirm);
					return false;
				}	
			}
		} else return true;
		
	}
	function demande_finition(){
	
	
		if (check_quality()) {
			is_finish = true;
			command_fin = true;			
			desactivate();
			
			$('#cont_canvas').css("display","none");
			$('#waiting_mini').css("display","block");
			
			//CraPacrAxx.deactivateAll().renderAll();
			var arr_img_pat = new Array();
			for (var z=0;z<objet_max;z++) {		
				CraPacrAxx = array_canv[z];	
				CraPacrAxx.deactivateAll().renderAll();			
				var item = CraPacrAxx.getObjects('imagepattern');
				if (item && item.length>0) {				
					for(t=0;t<item.length;t++){
						arr_img_pat.push(item[t].imageId);										
					}
					
				}
			}
			$(".canv_thumb").each(function() {			
				var find=false;
				/*console.log($(this).attr("id_upload"))
				if (!!$(this).attr("id_upload")) {} 
				else  {*/
					for(t=0;t<arr_img_pat.length;t++){				
						if ($(this).attr("id_compteur")==arr_img_pat[t] && $(this).attr("id_upload")!='') find=true;
					}
					if (!find) {				
						//$('#thumbnail_'+$(this).attr("val_set")).remove();			
						$('.qq-file-id-'+$(this).attr("id_upload")).addClass('qq-hide');				
					} else {
						$('.qq-file-id-'+$(this).attr("id_upload")).removeClass('qq-hide');		
					}
				//}
			});
			
			affich_upload(true);
			finition();
		}
	}
	
	function RGBtoHEX (color) {
	  return "#"+$.map(color.match(/\b(\d+)\b/g),function(digit){
		return ('0' + parseInt(digit).toString(16)).slice(-2)
	  }).join('');
	}	
	
	function finition(){			
		var success_pro = true;
		var liste_canv = '';
		var rendu = '';
		
		for (var z=0;z<objet_max;z++) {		
			CraPacrAxx = array_canv[z];					
			var item = CraPacrAxx.getObjects('imagepattern');			
			if (item && item.length>0) {				
				for(t=0;t<item.length;t++){					
					object_cours = $("#img_light_"+item[t].imageId);
					if (object_cours.attr("id_parent")==''){
						if (object_cours.attr("id_src")!='') {
							item[t].urlImage=object_cours.attr("id_src")+'.'+object_cours.attr("id_extens");
						} else {						
							success_pro = false;
						}
					} else {
						item[t].urlImage=object_cours.attr("id_src")+'.'+object_cours.attr("id_extens");
						item[t].urlSrc=object_cours.attr("id_parent");
					}					
				}				
			}
			item = CraPacrAxx.getObjects('line');				
			if (item) {			
				for(t=0;t<item.length;t++){
					item[t].remove();
				}
			}			
		}
		if (success_pro && command_fin) {
			
			


			liste_canv = resize_bef_save('canv');
			rendu = resize_bef_save('rendu');

		/*	CraPacrAxx = array_canv[0];	
			CraPacrAxx.renderAll();*/
			affich_upload(false);
		
			$('#waiting_photos').css("display","none");
			$('#waiting_donnees').css("display","block");
			is_finish = false;
			$.ajax({ 
				type: "POST", 
				url: url_back,
				dataType: 'text',
				data: {
					id_com : uniq,
					base64data : rendu,
					canv_sav : liste_canv+'--seprainf--'+liste_taille,
					info_rendu : info_rend,					
					cool : RGBtoHEX($('.div_fond_couleur').css("background-color"))
					
				},
				success: function(c) {
					if (command_fin && !is_finish) {
						if (c.trim()=='success') {								
							
							if (affich_rendu) {
								$('#waiting_donnees').css('display','none'); 
								$('#div_rendu_img').css('background-image','url("'+url_commande_rendu+uniq+'/rendu-'+uniq+'-1.png?'+$.now()+'")');
								$('#div_rendu').css('display','flex'); 
							}else {
								groovefx(); 
							}
						}
						else { alert("An error occured = "+c) }
					}
					
				},
				error:function(c) {
					alert("An error occured = "+c);				
				}
			});
		}				
	}
	
	
	
	
	
	function simule_click(name){			
		if (is_mobile!=1) {
			if (name=="image_par") $('#specific_image').css("display","block");
			else $('#specific_image').css("display","none");	
			$('#cssmenu li').removeClass('active');
			$('#'+name).addClass('active');	
			$('.menu-high').css('display','none');
			$('#'+name+" .menu-high").css('display','block');	
		} else {
			if (!is_zoom) {
				
				if (name=="image_par") change_bouton('bt-menu-header','image');
				else if (name=="texte_par") change_bouton('bt-menu-header','texte');
				else change_bouton('bt-menu-header','clipart');
				$(".sous-menu").addClass("cache");
			//	$("."+id).addClass("photo-tools");
				$("#"+name).removeClass("cache");
				$(".tools_div").removeClass("cache_visibility");
				$(".tools_select").css('display','none');
				$("."+name).css('display','');	
				
			}
		}
	}
		
	function show_sous_menu(div){
		if (is_mobile==1) {
			
			$('.change_ss_menu').css('display','none');
			$('#retour_2_'+div).css('display','block');
		}
		$('.tab-select').removeClass('tab-on').addClass('tab-off');
		$('#sous_menu_'+div).removeClass('tab-off').addClass('tab-on');
		$('.sous_sous_menu').css('display','none');
		$('#'+div+'_menu').css('display','block');		
	}
		
		
	function desactivate(){
		document.getElementById("text").value = texte_defaut;
		if (is_mobile!=1) {
			$('#object_active').css("display","none");
			show_sous_menu("canv");
			$('#sous_menu_img').css('display','none');
			$('.tab-25').css('display','block');
		} else {
			 $('.change_ss_menu').css('display','none');
			$(".tools_div").addClass("cache_visibility");
			$(".tools_select").css('display','none');
		}
				
		$('#img_menu').css('display','none');
		$('.txt_menu').css('display','none');
		CraPacrAxx.deactivateAll().renderAll();
		check_nbe();				
	}
	
		    
    function save() {
         
		redo = [];
		state = JSON.stringify(CraPacrAxx);
		//console.log(undo);
		$('#redo').removeClass('on').addClass('off');          
		if (state) {
			undo.push(state);
			$('#undo').removeClass('off').addClass('on'); 
		}
		
    }
	
	
		
		
	
	function add_forme(id,type) {	
		var idform = id;
		var property = $("#" + idform).attr("id_src");
		
		var value = 0.3;
		if (is_mobile==1) value = 0.1;
		
		if (type == "png") {
			fabric.Image.fromURL(property, function(img) {
				
				img.id = idform;
				img.idImage = idform;
				img.urlImage = property;
				img.typeImage = type;
				img.set("scaleX", value);
				img.set("scaleY", value);
				img.top = (CraPacrAxx.height / 5);
				img.left = (CraPacrAxx.width / 5);
				CraPacrAxx.add(img);
				CraPacrAxx.renderAll();
			})
		} else {
			fabric.loadSVGFromURL(property, function(group) {
				var loadedObjects = new fabric.Group(group);

				
				loadedObjects.id = idform;
				loadedObjects.idImage = idform;
				loadedObjects.urlImage = property;
				loadedObjects.typeImage = type;
				loadedObjects.urlImage = property;
				loadedObjects.set("scaleX", value);
				loadedObjects.set("scaleY", value);
				
				loadedObjects.set("fill", "#"+color_base);
				loadedObjects.top = (CraPacrAxx.height / 5);
				loadedObjects.left = (CraPacrAxx.width / 5);
				CraPacrAxx.add(loadedObjects);

				CraPacrAxx.renderAll();

			})
		}
		save();
	}
	
	
	
    
	function replay(playStack, saveStack, buttonsOn, buttonsOff) {
		desactivate();	
		saveStack.push(JSON.stringify(CraPacrAxx));
		state = playStack.pop();
		var on = $(buttonsOn);
         var off = $(buttonsOff);		
		wait_undo = true;
		CraPacrAxx.clear();
		CraPacrAxx.loadFromJSON(state, function() {
		   create_magnetic_grille(true);
			CraPacrAxx.renderAll();
			on.removeClass('off').addClass('on'); 
			
			if (playStack.length) {
				off.removeClass('off').addClass('on'); 
			} else {
				off.removeClass('on').addClass('off'); 
			}
			wait_undo = false;
			desactivate();
		});
	}
	
		  
		
	function flip() {
		
		CraPacrAxx.getActiveObject().flipX = CraPacrAxx.getActiveObject().flipX ? false : true;
		CraPacrAxx.renderAll();
		
	}
	
	function poubelle(){
		
		if (CraPacrAxx.getActiveObject()["type"]=='group') {
			CraPacrAxx.getActiveObject().set("left",CraPacrAxx.getWidth()).set("top",CraPacrAxx.getHeight());
		} else {
			CraPacrAxx.remove(CraPacrAxx.getActiveObject());
		}
		//CraPacrAxx.getActiveObject().remove();
		CraPacrAxx.renderAll(); 
		desactivate();	
		
	}
	
	function change_bouton(className,value) {
			
		if (className=='bt_edge') {
			
			$(".bt_edge_top").removeClass('on');
			$("."+className+value).addClass('on');
			
		} else {
			$("."+className).removeClass('on');
			$("."+className+"-"+value).addClass('on');
		}
		
	}

	function change_color_product(color){
		$('.div_fond_couleur').css("background-color","#"+color);
		
		col_chx = "#"+color;
	}

		
	
	
	function change_value_object(id,value){
		if (id== "slider-spacing")  {
			if (!is_changing) {
				is_changing = true;
				save();
			}	
			CraPacrAxx.imageSpace = value; 
			CraPacrAxx.renderAll();
		} else {
			var obj = CraPacrAxx.getActiveObject();
			if (obj) {	
				if ( id== "pattern" || id== "fontFamily" || id== "textAlign" || id== "arc" ) 	save();
				else if (id == "slider-crapx" || id == "slider-crapx-2" || id == "slider-CraPaX" || id == "slider-CraArCPacrA" || id == "slider-CraARCPacrA" || id == "slider-opac" || 
				id == "slider-taille-zoom" ) {
					if (!is_changing) {
						is_changing = true;
						save();
					}				
				}
				
				if (id == "stroke" || id == "fill" ||  id == "fontWeight") obj.set(id, value);
				else if (id == "slider-crapx") obj.set("strokeWidth", value);
				else if (id == "slider-crapx-2") obj.set("borderSize", value);
				else if (id == "slider-CraPaX") obj.set("lineHeight", value);
				else if (id == "slider-CraArCPacrA") obj.set("CraArCPacrA", value);
				else if (id == "slider-CraARCPacrA") obj.set("CraARCPacrA", value);
				else if (id== "slider-opac") obj.set("opacity", value);			
				else if (id== "slider-taille-zoom") {					
					old_zoom = obj.scaleImg;
					obj.set("scaleImg", value);
					
					obj.zoomObject(old_zoom);
					
				} else if (id== "pattern") {
					obj.set("masque", value);
					change_bouton('bt_edge',value); 
				} else if (id == "fontFamily") {
					obj.set(id, value);
					$(".fontFamil.ftActive").toggleClass("ftActive");
					$("#font_" + value.replace(/ /g, "_")).toggleClass("ftActive");
				} else if (id=='textAlign') {
					change_bouton('bt-align',value); 
					obj.set(id, value);
				} else if (id=='arc') {
					change_bouton('bt-arc',value) 
					if (value == "arc" || value == "arc_inv") {
						$(".menu-arc").css("display","block");
						obj["CraArcPacrA"] = value;
						obj["CraArCPacrA"] = 1;
						obj["CraARCPacrA"] = 1;						
					} else {
						$(".menu-arc").css("display","none");					
						obj["CraArcPacrA"] = value;
					}
				}			
				CraPacrAxx.renderAll();
			} 
		}
	}
	
	

		
		
		
	function test(e){
			window.parent.postMessage(e, "*");
		}	


	
		
		
		function f() {
			var v = document.getElementById("text").value;
			
			var obj = CraPacrAxx.getActiveObject();
			if (is_mobile==1)$(".tools_div").removeClass("cache_visibility");
			else $('.txt_menu').css('display','block');
			if (!obj) {
				
				if (is_mobile==1){
					$('#color_list_txt').css('display','block');
					var normScripta = new fabric.Text(v, {
						fontSize: 25,
						fontFamily: font_creapex,
						stroke: "#"+color_base,
						fill: "#"+color_base,							
						strokeWidth: 0,
						lockUniScaling:true
					});
				} else {
					var normScripta = new fabric.Text(v, {
						fontSize: 40,
						fontFamily: font_creapex,	
						stroke: "#"+color_base,
						fill: "#"+color_base,		
						strokeWidth: 0,
						lockUniScaling:true
					});
				
				}
				normScripta.set('left',5);
				normScripta.set('top',(CraPacrAxx.getHeight()-normScripta["fontSize"])/2);
				CraPacrAxx.add(normScripta);
				CraPacrAxx.setActiveObject(normScripta);
				
				change_bouton('bt-align','left');
				change_bouton('bt-arc','plat');
				$(".menu-arc").css("display","none");
				
				value = font_creapex;
				
				$(".fontFamil.ftActive").toggleClass("ftActive");
				$("#font_" + value.replace(/ /g, "_")).toggleClass("ftActive");
				
				$('#slider-CraPaX').val($('#slider-CraPaX').attr("val-dft")).change();
				$('#slider-crapx').val($('#slider-crapx').attr("val-dft")).change();
				$('#slider-CraArCPacrA').val($('#slider-CraArCPacrA').attr("val-dft")).change();
				$('#slider-CraARCPacrA').val($('#slider-CraARCPacrA').attr("val-dft")).change();				
				
				change_color("colorbox-crapX", "#"+color_base);
				change_color("colorbox-craPxXxXx", "#"+color_base);				
			
				CraPacrAxx.renderAll();
				save();
			} else {
				obj.set("text", v);
				CraPacrAxx.renderAll();
				save();
			}
		}
		

		
		
		$(function() {

			var $document = $(document);
			var selector = '[data-rangeslider]';
			var $element = $(selector);
		   
			var textContent = ('textContent' in document) ? 'textContent' : 'innerText'; 

			$document
				.on('click', '#js-example-hidden button[data-behaviour="toggle"]', function(e) {
					var $container = $(e.target.previousElementSibling);
					$container.toggle();
				});
		   
			$element.rangeslider({

				polyfill: false,

				onInit: function() {
				  
				},
				
				onSlide: function(position, value) {
				  if (!block_slide) change_value_object(this.$element[0]["id"],value);
				},

				// Callback function
				onSlideEnd: function(position, value) {
					//console.log('onSlideEnd');
					is_changing = false;
				}
			});

		});
		
		
	 
	 
	 
	 window.addEventListener("orientationchange", function() {
		setTimeout(function(){
			if (!is_finish) { 
				resize_canv(false);		
				if ($('#par_drop_zone').length) generate_grille_drag();
			}
		},500);
	}, false);

	$(window).bind('resize', function(e){
		window.resizeEvt;
		$(window).resize(function()	{
		clearTimeout(window.resizeEvt);
		window.resizeEvt = setTimeout(function(){
			if (!is_finish)  resize_canv(false);						
		}, 100);
		});
	});
	
	function remove_image(id){
		liste_id_img.splice( liste_id_img.indexOf(id), 1 );
		
		var item = CraPacrAxx.getObjects('imagepattern');	
			
		if (item && item.length>0) {				
			for(t=0;t<item.length;t++){					
				if (item[t].imageId==id) {
					CraPacrAxx.remove(item[t]);	
				}					
			}				
		}
		
		
		
	}
	
	$('#fine-uploader-gallery').fineUploader({
            template: 'qq-template-validation-groo',
            request: { 
                endpoint:  upload_back	 
				},
           validation: {
				acceptFiles: ['image/*'],
				allowedExtensions: ['jpg', 'jpeg', 'png'],
				sizeLimit: max_uplod,	
				itemLimit: 50,
				autoUpload:true,
				minSizeLimit:min_uplod,
			}, camera: { ios: true },
			multiple: true,
			  cors: {       
			   expected: true,
			   sendCredentials: false,
			   allowXdr : true
			 },	
			messages:{
				/*typeError : TEXTE_ERR_TYPE,
				tooManyItemsError : TEXTE_ERR_LIM*/
			},	
 failedUploadTextDisplay: {
        mode: 'default',
        responseProperty: 'error',
    },			
			callbacks: {
				
				onComplete: function(id,name,response,request) { 	
					
					if (response["success"]) {
						
						
						id_up_end = $( "img[id_upload='"+id+"']" ).attr('id_compteur');							
						
						$( "#img_light_"+id_up_end ).attr('id_upload','');
						
						$('.qq-file-id-'+id).css("display","none");
						
						var res = response["url"].split('.');
						$( "#img_light_"+id_up_end ).attr('id_src',res[0]);
						$( "#img_light_"+id_up_end ).attr('id_extens',res[1]);
					
						if (is_finish) finition();
						
					} else {
						console.log(response["error"]);
						$('#thumbnail_'+id_up_end).remove();
						remove_image(id_up_end)
					}
				},				
				onError: function(id, name, errorReason, xhrOrXdr) {
					console.log(errorReason);
					id_up_end = $( "img[id_upload='"+id+"']" ).attr('id_compteur');
					$('#thumbnail_'+id_up_end).remove();
					remove_image(id_up_end)
				},
				onSubmitted : function (id,name){
					
					param = name.toLowerCase().split(".");
					
					
					$( "#div_lightbox" ).css("display","block");
					$( "#info_uplo" ).css("display","none");
					//$( "#info_uplo_drop" ).css("display","block");
					
					$( "#div_lightbox" ).append( '<div class="thumbnail_picture"  name="thumbnail_'+compteur_img+'" id="thumbnail_'+compteur_img+'" ><img class="canv_thumb" onclick="chargeimage('+compteur_img+',true);" id_extens="" id_taille_qual="" id_compteur="'+compteur_img+'"  name="img_light_'+compteur_img+'"  id_parent="" id="img_light_'+compteur_img+'" id_upload="'+id+'" id_src=""  ><div id="max_put_'+compteur_img+'"  name="max_put_'+compteur_img+'" onclick="javascript:open_effet('+compteur_img+')" class="attente_upload"></div><div id="mini_chiffre_'+compteur_img+'"  name="mini_chiffre_'+compteur_img+'" class="mini_chiffre" style="position:absolute;top:-10px;right:-10px;width:20px;height:20px;background-color:green;color:white;line-height:20px;font-size:20px;text-align:center; overflow:hidden; -webkit-border-radius:50px;-moz-border-radius:50px; border-radius:50px;">0</div></div>' );
					
					var fut_size = 600;
					
					if (is_mobile==1) fut_size = 350; 
					
					$('#fine-uploader-gallery').fineUploader("drawThumbnail", id,  document.getElementById('img_light_'+compteur_img), fut_size, false);
					
					$( '#img_light_'+compteur_img ).load(function() {
						id_compt_temp = $(this).attr('id_compteur');
						
						if (img_first) {
							chargeimage(id_compt_temp,false);
							img_first = false;					
						} 
						liste_id_img.push(id_compt_temp);
						recharge_drag(id_compt_temp);
						
					});
					compteur_img++;
				}
			}
	 });
	//$('#fine-uploader-gallery').fineUploader('setParams', { 'nom':NOM_BUCKET,'taille_min':TAILL_IMAGE_MIN});
	
	
	
	$(document).ready( function () {
		
		
		if (is_mobile!=1) {
			$('#cssmenu > ul > li > a').click(function() {
				desactivate();
				  $('#cssmenu li').removeClass('active');
				  $(this).closest('li').addClass('active');	
				  var checkElement = $(this).next();
				  if((checkElement.is('ul')) && (checkElement.is(':visible'))) {
					$(this).closest('li').removeClass('active');
					checkElement.slideUp('normal');
				  }
				  if((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
					$('#cssmenu ul ul.menu-high:visible').slideUp('normal');
					checkElement.slideDown('normal');
				  }
				  if($(this).closest('li').find('ul').children().length == 0) {
					return true;
				  } else {
					return false;	
				  }		
			});
		}
		
		setTimeout(function(){
			resize_canv(false);							
			
			setTimeout(function(){	
				resize_canv();	
				$("#waiting_charg").addClass("cache");		
			},500);
		
		},1000);
				
	} ) ;
	
		$("#flat").spectrum({			
			flat: true,
			showButtons: false,						
			move: function(color) {						
				change_colorpicker(color.toHexString());					
			}
		});
		if (is_mobile==1) $(".sp-container").css("display","none");
		$(".colorbox").each(function(){
			
			couleur_color = $(this).val();
			if (couleur_color.length==6) couleur_color = '#'+couleur_color;
			$(this).after('<div class="sp-replacer sp-light" onclick="open_colorpicker(\''+this.id+'\')"><div class="sp-preview"><div class="sp-preview-inner" id="div-'+this.id+'" style="background-color: '+couleur_color+'"></div></div></div>') ;
		});  
		
		

		
		
	
	document.getElementById('cont_canvas_surcouch').onclick = function(e) {
		if(e.target.className != 'upper-canvas ') desactivate();		
	}
	
	$('#undo').click(function() {
		if (wait_undo==false){
			if (undo.length!=0){
				is_undo = true;
				replay(undo, redo, '#redo', this);
			}
		}
	});
	$('#redo').click(function() {
	   if (wait_undo==false){
			if (redo.length!=0){
				is_undo = true;
				replay(redo, undo, '#undo', this);
			}
	   }
	})

	
	if (is_mobile!=1) {		
		simule_click($('.has-sub').filter(function() {
				return $(this).css('display') != 'none';
			}).first().attr('id'));
	} else {
		display($('.verif_class').filter(function() {
				return $(this).css('display') != 'none';
			}).first().attr('id-cible'));
		
	}
	rempli_canvas();
	
	document.getElementById("text").onkeyup = f;
	document.getElementById("text").onkeydown = f;
	
		// Handles dragover events.
	function onDragover(mouseEvent) {
		mouseEvent.preventDefault();
		
		if (mouseEvent.target.getAttribute("class")!='dropzone par_drop_zone'){
		
			$('.dropzone').removeClass('highlight');
			var id = mouseEvent.target.getAttribute("id");	
			$('#'+id).addClass('highlight');
		} else {
			$('.dropzone').removeClass('highlight');
		}
	}

	// Handles drop events.
	function onDrop(mouseEvent) {

		mouseEvent.preventDefault();
		var targetCanvas = mouseEvent.target;
		
		var cible = targetCanvas.getAttribute("class");
		
		if (cible.indexOf("par_drop_zone")!=-1 || cible=='dropzone' ) {
			drop_canvas(mouseEvent);			
		} else {	
			var id = targetCanvas.getAttribute("id");		
			dropimage(id_drag,id.substring(2, id.length),id.substring(0, 2));
		}
			
		mouseEvent.stopPropagation();
		mouseEvent.preventDefault();
		$('#par_drop_zone').remove();
	}
	
		
	function handleDragStart(e) {
		
		$('.canv_thumb').removeClass('img_dragging');
		this.classList.add('img_dragging');
		id_drag = $(this).attr('id_compteur');
		$('#par_drop_zone').remove();
		generate_grille_drag();
		
	
	}

	
	
	
	function handleDragStop(e) {
		
		$('#par_drop_zone').remove();
	}
	
	

	function drop_canvas(e) {
			
			save();
			
			var pointer  = CraPacrAxx.getPointer(e);
		 
			w = CraPacrAxx.getWidth()/4;
			h = CraPacrAxx.getHeight()/4;	
			l = pointer.x-(w/2);
			if (l<0) l=0;
			else if ((l+w)>CraPacrAxx.getWidth()) l = CraPacrAxx.getWidth()-w;
			t = pointer.y-(h/2);
			if (t<0) t=0;
			else if ((t+h)>CraPacrAxx.getHeight()) t = CraPacrAxx.getHeight()-h;
			
			
			elemt_sel = $('#div_lightbox img.img_dragging');
			fabric.Imagepattern.fromURL(elemt_sel.attr('src'), function (img) {
				img.imageId = elemt_sel.attr('id_compteur');				
				img.qualit = elemt_sel.attr('id_taille_qual');			
				img.widthImg = img.getWidth();
				img.heightImg = img.getHeight();
				img.transparentCorners= false;
				img.borderScaleFactor = 2;
				img.borderColor='#8C7272';
				img.cornerColor='#8C7272';
				img.left=l;		
				img.top=t;
				img.ismove=true;
				img.width = w;
				img.height = h;					
				img._changeAndAnimateObject('init');
			});
			
			$('#par_drop_zone').remove();			
	}
	
	function recharge_drag(id){

			document.getElementById('img_light_'+id).addEventListener('dragstart', handleDragStart, false);
			document.getElementById('img_light_'+id).addEventListener('dragend', handleDragStop, false);	

	}
	