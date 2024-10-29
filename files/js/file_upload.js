/*!
 * jQuery Upload File Plugin
 * version: 4.0.10
 * @requires jQuery v1.5 or later & form plugin
 * Copyright (c) 2013 Ravishanker Kusuma
 * http://hayageek.com/
 */
(function ($) {
    if($.fn.ajaxForm == undefined) {
        $.getScript(("https:" == document.location.protocol ? "https://" : "http://") + "malsup.github.io/jquery.form.js");
    }
    var feature = {};
    feature.fileapi = $("<input type='file'/>").get(0).files !== undefined;
    feature.formdata = window.FormData !== undefined;
    $.fn.uploadFile = function (options) {
        // This is the easiest way to have default options.
        var s = $.extend({
            // These are the defaults.
            url: "",
            method: "POST",
            enctype: "multipart/form-data",
            returnType: null,
            allowDuplicates: true,
            duplicateStrict: false,
            allowedTypes: "*",
            //For list of acceptFiles
            // http://stackoverflow.com/questions/11832930/html-input-file-accept-attribute-file-type-csv
            acceptFiles: "*",
            fileName: "file",
            formData: false,
            dynamicFormData:false,
            maxFileSize: -1,
            maxFileCount: -1,
            multiple: true,
            dragDrop: true,
            autoSubmit: true,
            showCancel: false,
            showAbort: false,
            showDone: false,
            showDelete: false,
            showError: true,
            showStatusAfterSuccess: true,
            showStatusAfterError: true,
            showFileCounter: true,
			spec_module:'',
            fileCounterStyle: "). ",
            showFileSize: true,
            showProgress: false,
            nestedForms: true,
            showDownload: false,
            onLoad: function (obj) {},
			previewMax : 500,
            onSelect: function (files) {
                return true;
            },
            onSubmit: function (files, xhr) {},
            onSuccess: function (files, response, xhr, pd) {},
            onError: function (files, status, message, pd) {},
            onCancel: function (files, pd) {},
            onAbort: function (files, pd) {},            
            downloadCallback: false,
            deleteCallback: false,
            afterUploadAll: false,
            serialize:true,
            sequential:false,
            sequentialCount:2,
            customProgressBar: false,
            abortButtonClass: "ajax-file-upload-abort",
            cancelButtonClass: "ajax-file-upload-cancel",
            dragDropContainerClass: "ajax-upload-dragdrop",
            dragDropHoverClass: "state-hover",
            errorClass: "ajax-file-upload-error",
            uploadButtonClass: "ajax-file-upload",
			uploadButtonClassMobi: "ajax-file-upload-mobile",
            dragDropStr: "<span style='margin-left:15px;'><b>"+text_drag_drop+"</b></span>",
            uploadStr:"Upload",
            abortStr: "Abort",
            cancelStr: "Cancel",
            deletelStr: "Delete",
            doneStr: "Done",
            multiDragErrorStr: "Multiple File Drag &amp; Drop is not allowed.",
            extErrorStr: "is not allowed. Allowed extensions: ",
            duplicateErrorStr: "is not allowed. File already exists.",
            sizeErrorStr: "is not allowed. Allowed Max size: ",
			 sizeErrorStr2: "is not allowed. Allowed Min size: ",
            uploadErrorStr: "Upload is not allowed",
            maxFileCountErrorStr: " is not allowed. Maximum allowed files are:",
            downloadStr: "Download",
            customErrorKeyStr: "jquery-upload-file-error",
            showQueueDiv: false,
            statusBarWidth: 400,
            dragdropWidth: 400,
            showPreview: false,
            previewHeight: "auto",
            previewWidth: "100%",
            extraHTML:false,
            uploadQueueOrder:'top'
        }, options);
		
        this.fileCounter = 1;
        this.selectedFiles = 0;
        var formGroup = "ajax-file-upload-" + (new Date().getTime());
        this.formGroup = formGroup;
        this.errorLog = $("<div></div>"); //Writing errors
        this.responses = [];
        this.existingFileNames = [];
        if(!feature.formdata) //check drag drop enabled.
        {
            s.dragDrop = false;
        }
        if(!feature.formdata || s.maxFileCount === 1) {
            s.multiple = false;
        }

        $(this).html("");

        var obj = this;
        
        var uploadLabel = $('<div>' + s.uploadStr + '</div>');
		
		
		if (is_mobile==0) $(uploadLabel).addClass(s.uploadButtonClass);
       else  $(uploadLabel).addClass(s.uploadButtonClassMobi);
        
        // wait form ajax Form plugin and initialize
        (function checkAjaxFormLoaded() {
            if($.fn.ajaxForm) {

                if(s.dragDrop && is_mobile==0) {
                    var dragDrop = $('<div class="' + s.dragDropContainerClass + '" style="vertical-align:top;"></div>');
                    $(obj).append(dragDrop);
                    $(dragDrop).append(uploadLabel);
                    $(dragDrop).append($(s.dragDropStr));
                    setDragDropHandlers(obj, s, dragDrop);

                } else {
                    $(obj).append(uploadLabel);
                }
                $(obj).append(obj.errorLog);
                
   				if(s.showQueueDiv)
		        	obj.container =$("#"+s.showQueueDiv);
        		else
		            obj.container = $("<div class='ajax-file-upload-container'></div>").insertAfter($(obj));
        
                s.onLoad.call(this, obj);
                createCustomInputFile(obj, formGroup, s, uploadLabel);

            } else window.setTimeout(checkAjaxFormLoaded, 10);
        })();

	   this.startUpload = function () {
	   		$("form").each(function(i,items)
	   		{
	   			if($(this).hasClass(obj.formGroup))
	   			{
					mainQ.push($(this));
	   			}
	   		});

            if(mainQ.length >= 1 )
	 			submitPendingUploads();

        }

        this.getFileCount = function () {
            return obj.selectedFiles;

        }
        this.stopUpload = function () {
            $("." + s.abortButtonClass).each(function (i, items) {
                if($(this).hasClass(obj.formGroup)) $(this).click();
            });
             $("." + s.cancelButtonClass).each(function (i, items) {
                if($(this).hasClass(obj.formGroup)) $(this).click();
            });
        }
        this.cancelAll = function () {
            $("." + s.cancelButtonClass).each(function (i, items) {
                if($(this).hasClass(obj.formGroup)) $(this).click();
            });
        }
        this.update = function (settings) {
            //update new settings
            s = $.extend(s, settings);
        }
        this.reset = function (removeStatusBars) {
			obj.fileCounter = 1;
			obj.selectedFiles = 0;
			obj.errorLog.html("");
					//remove all the status bars.
			if(removeStatusBars != false)
			{
				obj.container.html("");
			}
        }
		this.remove = function()
		{
			obj.container.html("");
			$(obj).remove();
	
		}
        //This is for showing Old files to user.
        this.createProgress = function (filename,filepath,filesize) {
            var pd = new createProgressDiv(this, s);
            pd.progressDiv.show();
            pd.progressbar.width('100%');

            var fileNameStr = "";
            fileNameStr = filename;
            
            
            if(s.showFileSize)
				fileNameStr += " ("+getSizeStr(filesize)+")";


            pd.filename.html(fileNameStr);
            obj.fileCounter++;
            obj.selectedFiles++;
            if(s.showPreview)
            {
                pd.preview.attr('src',filepath);
                pd.preview.show();
            }
            
            if(s.showDownload) {
                pd.download.show();
                pd.download.click(function () {
                    if(s.downloadCallback) s.downloadCallback.call(obj, [filename]);
                });
            }
            if(s.showDelete)
            {
	            pd.del.show();
    	        pd.del.click(function () {
        	        pd.statusbar.hide().remove();
            	    var arr = [filename];
                	if(s.deleteCallback) s.deleteCallback.call(this, arr, pd);
	                obj.selectedFiles -= 1;
    	            updateFileCounter(s, obj);
        	    });
            }

            return pd;
        }

        this.getResponses = function () {
            return this.responses;
        }
        var mainQ=[];
        var progressQ=[]
        var running = false;
          function submitPendingUploads() {
			if(running) return;
			running = true;
            (function checkPendingForms() {
                
                	//if not sequential upload all files
                	if(!s.sequential) s.sequentialCount=99999;
                	
					if(mainQ.length == 0 &&   progressQ.length == 0)
					{
						if(s.afterUploadAll) s.afterUploadAll(obj);
						running= false;
					}              
					else 
					{
						if( progressQ.length < s.sequentialCount)
						{
							var frm = mainQ.shift();
							if(frm != undefined)
							{
				    	    	progressQ.push(frm);
				    	    	//Remove the class group.
				    	    	frm.removeClass(obj.formGroup);
    	    					frm.submit();
        					}
						}						
						window.setTimeout(checkPendingForms, 100);
					}
                })();
        }
        
        function setDragDropHandlers(obj, s, ddObj) {
            ddObj.on('dragenter', function (e) {
                e.stopPropagation();
                e.preventDefault();
                $(this).addClass(s.dragDropHoverClass);
            });
            ddObj.on('dragover', function (e) {
                e.stopPropagation();
                e.preventDefault();
                var that = $(this);
                if (that.hasClass(s.dragDropContainerClass) && !that.hasClass(s.dragDropHoverClass)) {
                    that.addClass(s.dragDropHoverClass);
                }
            });
            ddObj.on('drop', function (e) {
                e.preventDefault();
                $(this).removeClass(s.dragDropHoverClass);
                obj.errorLog.html("");
                var files = e.originalEvent.dataTransfer.files;
                if(!s.multiple && files.length > 1) {
                    if(s.showError) $("<div class='" + s.errorClass + "'>" + s.multiDragErrorStr + "</div>").appendTo(obj.errorLog);
                    return;
                }
                if(s.onSelect(files) == false) return;
                serializeAndUploadFiles(s, obj, files);
            });
            ddObj.on('dragleave', function (e) {
                $(this).removeClass(s.dragDropHoverClass);
            });

            $(document).on('dragenter', function (e) {
                e.stopPropagation();
                e.preventDefault();
            });
            $(document).on('dragover', function (e) {
                e.stopPropagation();
                e.preventDefault();
                var that = $(this);
                if (!that.hasClass(s.dragDropContainerClass)) {
                    that.removeClass(s.dragDropHoverClass);
                }
            });
            $(document).on('drop', function (e) {
                e.stopPropagation();
                e.preventDefault();
                $(this).removeClass(s.dragDropHoverClass);
            });

        }

        function getSizeStr(size) {
            var sizeStr = "";
            var sizeKB = size / 1024;
            if(parseInt(sizeKB) > 1024) {
                var sizeMB = sizeKB / 1024;
                sizeStr = sizeMB.toFixed(2) + " MB";
            } else {
                sizeStr = sizeKB.toFixed(2) + " KB";
            }
            return sizeStr;
        }

        function serializeData(extraData) {
            var serialized = [];
            if(jQuery.type(extraData) == "string") {
                serialized = extraData.split('&');
            } else {
                serialized = $.param(extraData).split('&');
            }
            var len = serialized.length;
            var result = [];
            var i, part;
            for(i = 0; i < len; i++) {
                serialized[i] = serialized[i].replace(/\+/g, ' ');
                part = serialized[i].split('=');
                result.push([decodeURIComponent(part[0]), decodeURIComponent(part[1])]);
            }
            return result;
        }
		

        function serializeAndUploadFiles(s, obj, files) {
            for(var i = 0; i < files.length; i++) {
                if(!isFileTypeAllowed(obj, s, files[i].name)) {
					if(s.showError) alert(files[i].name +' '+ s.extErrorStr + s.allowedTypes  );
					continue;
                }
                if(!s.allowDuplicates && isFileDuplicate(obj, files[i].name)) {
					if(s.showError) alert(files[i].name +' '+ s.duplicateErrorStr  );
                    continue;
                }
				if (s.spec_module=='') {
					if( files[i].size > max_uplod) {
						if(s.showError) alert(files[i].name +' '+ s.sizeErrorStr + getSizeStr(max_uplod) );
						continue;
					}
					 if( files[i].size < min_uplod) {
						if(s.showError) alert(files[i].name +' '+ s.sizeErrorStr2 + getSizeStr(min_uplod) );
						continue;
					}
				}
                if(s.maxFileCount != -1 && obj.selectedFiles >= s.maxFileCount) {
					if(s.showError) alert(files[i].name +' '+ s.maxFileCountErrorStr + s.maxFileCount );							
                    continue;
                }
                obj.selectedFiles++;
                obj.existingFileNames.push(files[i].name);
                var ts = s;
                var fd = new FormData();
                var fileName = s.fileName.replace("[]", "");
                fd.append(fileName, files[i]);
                var extraData = s.formData;
                if(extraData) {
                    var sData = serializeData(extraData);
                    for(var j = 0; j < sData.length; j++) {
                        if(sData[j]) {
                            fd.append(sData[j][0], sData[j][1]);
                        }
                    }
                }
                ts.fileData = fd;
				
			
                var pd = new createProgressDiv(obj, s);
                var fileNameStr = "";
                fileNameStr = files[i].name;

				if(s.showFileSize)
				fileNameStr += " ("+getSizeStr(files[i].size)+")";
				
				pd.filename.html(fileNameStr);
                var form = $("<form style='display:block; position:absolute;left: 150px;' class='" + obj.formGroup + "' method='" + s.method + "' action='" +
                    s.url + "' enctype='" + s.enctype + "'></form>");
                form.appendTo('body');
                var fileArray = [];
                fileArray.push(files[i].name);
                
                ajaxFormSubmit(form, ts, pd, fileArray, obj, files[i]);
                obj.fileCounter++;
            }
        }

        function isFileTypeAllowed(obj, s, fileName) {
            var fileExtensions = s.allowedTypes.toLowerCase().split(/[\s,]+/g);
            var ext = fileName.split('.').pop().toLowerCase();
            if(s.allowedTypes != "*" && jQuery.inArray(ext, fileExtensions) < 0) {
                return false;
            }
            return true;
        }

        function isFileDuplicate(obj, filename) {
            var duplicate = false;
            if (obj.existingFileNames.length) {
                for (var x=0; x<obj.existingFileNames.length; x++) {
                    if (obj.existingFileNames[x] == filename
                        || s.duplicateStrict && obj.existingFileNames[x].toLowerCase() == filename.toLowerCase()
                    ) {
                        duplicate = true;
                    }
                }
            }
            return duplicate;
        }

        function removeExistingFileName(obj, fileArr) {
            if (obj.existingFileNames.length) {
                for (var x=0; x<fileArr.length; x++) {
                    var pos = obj.existingFileNames.indexOf(fileArr[x]);
                    if (pos != -1) {
                        obj.existingFileNames.splice(pos, 1);
                    }
                }
            }
        }

        function getSrcToPreview(file,obj, id,max) {
            if(file) {
               // obj.show();
                var reader = new FileReader();
                reader.onload = function (e) {
				
					var image = new Image();
					image.onload = function() {
						
						var new_width=new_height=0;
						var ratio = image.width/image.height;
						
						var fut_size = 600;
						if (is_mobile==0 && is_ff==1) fut_size = 300;
						if (is_mobile==1) fut_size = 350; 
												
						if (ratio > 1) {
							new_height = fut_size/ ratio;
							new_width = fut_size;
						} else {
							new_width = fut_size*ratio ;
							new_height = fut_size;
						}
						
						
						$('#precharge_canvas').append('<canvas id="canvas_'+id+'" width="'+new_width+'" height="'+new_height+'">');						
						var cnvs=document.getElementById('canvas_'+id);
						var ctx=cnvs.getContext("2d");	
						ctx.drawImage(image,0,0, new_width, new_height);
												
						$( "#div_lightbox" ).css("display","block");
						$( "#info_uplo" ).css("display","none");
						$( "#div_lightbox" ).append( '<div class="thumbnail_picture"  name="thumbnail_'+id+'" id="thumbnail_'+id+'" ><img name="'+id+'" id="'+id+'" class="canv_thumb" onclick="chargeimage('+id+');"><div id="mini_chiffre_'+id+'"  name="mini_chiffre_'+id+'" class="mini_chiffre" style="position:absolute;top:0px;right:0px;width:20px;height:20px;background-color:green;color:white;line-height:20px;font-size:20px;text-align:center; overflow:hidden; -webkit-border-radius:50px;-moz-border-radius:50px; border-radius:50px;">0</div></div>' );
						$( "#form_creation" ).append( '<input type="hidden" id="img_src_'+id+'" name="img_src_'+id+'" class="imagesource" val_set="'+id+'" value="">');					
						$('#'+id).attr('src',cnvs.toDataURL());
						if (img_first) {
							chargeimage(id);
							img_first = false;					
						} 
						liste_id_img.push(id);
						images = document.querySelectorAll('#div_lightbox img');
						[].forEach.call(images, function (img) {
							img.addEventListener('dragstart', handleDragStart, false);
							img.addEventListener('dragend', handleDragStop, false);
						});	
					};				   
					image.src = e.target.result;
				};
                reader.readAsDataURL(file);
            }
        }

        function updateFileCounter(s, obj) {
            if(s.showFileCounter) {
                var count = $(obj.container).find(".ajax-file-upload-filename").length;
                obj.fileCounter = count + 1;
                $(obj.container).find(".ajax-file-upload-filename").each(function (i, items) {
                    var arr = $(this).html().split(s.fileCounterStyle);
                    var fileNum = parseInt(arr[0]) - 1; //decrement;
                    var name = count + s.fileCounterStyle + arr[1];
                    $(this).html(name);
                    count--;
                });
            }
        }

        function createCustomInputFile (obj, group, s, uploadLabel) {

            var fileUploadId = "ajax-upload-id-" + (new Date().getTime());

            var form = $("<form method='" + s.method + "' action='" + s.url + "' enctype='" + s.enctype + "'></form>");
            var fileInputStr = "<input type='file' id='" + fileUploadId + "' name='" + s.fileName + "' accept='" + s.acceptFiles + "'/>";
            if(s.multiple) {
                if(s.fileName.indexOf("[]") != s.fileName.length - 2) // if it does not endwith
                {
                    s.fileName += "[]";
                }
                fileInputStr = "<input type='file' id='" + fileUploadId + "' name='" + s.fileName + "' accept='" + s.acceptFiles + "' multiple/>";
            }
            var fileInput = $(fileInputStr).appendTo(form);

            fileInput.change(function () {

                obj.errorLog.html("");
                var fileExtensions = s.allowedTypes.toLowerCase().split(",");
                var fileArray = [];
                if(this.files) //support reading files
                {
                    for(i = 0; i < this.files.length; i++) {
                        fileArray.push(this.files[i].name);
                    }

                    if(s.onSelect(this.files) == false) return;
                } else {
                    var filenameStr = $(this).val();
                    var flist = [];
                    fileArray.push(filenameStr);
                    if(!isFileTypeAllowed(obj, s, filenameStr)) {
                        if(s.showError) $("<div class='" + s.errorClass + "'><b>" + filenameStr + "</b> " + s.extErrorStr + s.allowedTypes + "</div>").appendTo(
                            obj.errorLog);
                        return;
                    }
                    //fallback for browser without FileAPI
                    flist.push({
                        name: filenameStr,
                        size: 'NA'
                    });
                    if(s.onSelect(flist) == false) return;

                }
                updateFileCounter(s, obj);

                uploadLabel.unbind("click");
                form.hide();
                createCustomInputFile(obj, group, s, uploadLabel);
                form.addClass(group);
                if(s.serialize && feature.fileapi && feature.formdata) //use HTML5 support and split file submission
                {
                    form.removeClass(group); //Stop Submitting when.
                    var files = this.files;
                    form.remove();
                    serializeAndUploadFiles(s, obj, files);
                } else {
                    var fileList = "";
                    for(var i = 0; i < fileArray.length; i++) {
                        fileList += fileArray[i] + "<br>";;
                        obj.fileCounter++;

                    }
                    if(s.maxFileCount != -1 && (obj.selectedFiles + fileArray.length) > s.maxFileCount) {
                        if(s.showError) $("<div class='" + s.errorClass + "'><b>" + fileList + "</b> " + s.maxFileCountErrorStr + s.maxFileCount + "</div>").appendTo(
                            obj.errorLog);
                        return;
                    }
                    obj.selectedFiles += fileArray.length;

                    var pd = new createProgressDiv(obj, s);
                    pd.filename.html(fileList);
                    ajaxFormSubmit(form, s, pd, fileArray, obj, null);
                }



            });

            if(s.nestedForms) {
                form.css({
                    'margin': 0,
                    'padding': 0
                });
                uploadLabel.css({
                    position: 'relative',
                    overflow: 'hidden',
                    cursor: 'default'
                });
                fileInput.css({
                    position: 'absolute',
                    'cursor': 'pointer',
                    'top': '0px',
                    'width': '100%',
                    'height': '100%',
                    'left': '0px',
                    'z-index': '100',
                    'opacity': '0.0',
                    'filter': 'alpha(opacity=0)',
                    '-ms-filter': "alpha(opacity=0)",
                    '-khtml-opacity': '0.0',
                    '-moz-opacity': '0.0'
                });
                form.appendTo(uploadLabel);

            } else {
                form.appendTo($('body'));
                form.css({
                    margin: 0,
                    padding: 0,
                    display: 'block',
                    position: 'absolute',
                    left: '-250px'
                });
                if(navigator.appVersion.indexOf("MSIE ") != -1) //IE Browser
                {
                    uploadLabel.attr('for', fileUploadId);
                } else {
                    uploadLabel.click(function () {
                        fileInput.click();
                    });
                }
            }
        }


		function defaultProgressBar(obj,s,id)
		{
	
			this.statusbar = $("<div class='ajax-file-upload-statusbar' id='statut-bar-"+id+"'></div>");
            this.preview = $("<img class='ajax-file-upload-preview'  />").height(s.previewHeight).appendTo(this.statusbar).hide();
            this.filename = $("<div class='ajax-file-upload-filename'></div>").appendTo(this.statusbar);
            this.progressDiv = $("<div class='ajax-file-upload-progress'>").appendTo(this.statusbar).hide();
            this.progressbar = $("<div class='ajax-file-upload-bar'></div>").appendTo(this.progressDiv);
            this.abort = $("<div>" + s.abortStr + "</div>").appendTo(this.statusbar).hide();
            this.cancel = $("<div>" + s.cancelStr + "</div>").appendTo(this.statusbar).hide();
            this.done = $("<div>" + s.doneStr + "</div>").appendTo(this.statusbar).hide();
            this.download = $("<div>" + s.downloadStr + "</div>").appendTo(this.statusbar).hide();
            this.del = $("<div>" + s.deletelStr + "</div>").appendTo(this.statusbar).hide();

            this.abort.addClass("ajax-file-upload-red");
            this.done.addClass("ajax-file-upload-green");
			this.download.addClass("ajax-file-upload-green");            
            this.cancel.addClass("ajax-file-upload-red");
            this.del.addClass("ajax-file-upload-red");
            
			return this;
		}
        function createProgressDiv(obj, s) {
	        var bar = null;
        	
			
			
			bar =  new defaultProgressBar(obj,s,obj.fileCounter);

			bar.abort.addClass(obj.formGroup);
            bar.abort.addClass(s.abortButtonClass);        	
			
            bar.cancel.addClass(obj.formGroup);
            bar.cancel.addClass(s.cancelButtonClass);    
            bar.idCurrent = obj.fileCounter;
            if(s.extraHTML)
	            bar.extraHTML = $("<div class='extrahtml'>"+s.extraHTML()+"</div>").insertAfter(bar.filename);    	
            
            if(s.uploadQueueOrder == 'bottom')
				$(obj.container).append(bar.statusbar);
			else
				$(obj.container).prepend(bar.statusbar);
            return bar;
        }


        function ajaxFormSubmit(form, s, pd, fileArray, obj, file) {
            var currentXHR = null;
            var options = {
                cache: false,
                contentType: false,
                processData: false,
                forceSync: false,
                type: s.method,
                data: s.formData,
                formData: s.fileData,
                dataType: s.returnType,
                beforeSubmit: function (formData, $form, options) {
                    if(s.onSubmit.call(this, fileArray) != false) {
                        if(s.dynamicFormData) 
                        {
                            var sData = serializeData(s.dynamicFormData());
                            if(sData) {
                                for(var j = 0; j < sData.length; j++) {
                                    if(sData[j]) {
                                        if(s.fileData != undefined) options.formData.append(sData[j][0], sData[j][1]);
                                        else options.data[sData[j][0]] = sData[j][1];
                                    }
                                }
                            }
                        }

                    
                        return true;
                    }
                    pd.statusbar.append("<div class='" + s.errorClass + "'>" + s.uploadErrorStr + "</div>");
                    pd.cancel.show()
                    form.remove();
                    pd.cancel.click(function () {
                    	 mainQ.splice(mainQ.indexOf(form), 1);
                        removeExistingFileName(obj, fileArray);
                        pd.statusbar.remove();
                        s.onCancel.call(obj, fileArray, pd);
                        obj.selectedFiles -= fileArray.length; //reduce selected File count
                        updateFileCounter(s, obj);
                    });
                    return false;
                },
                beforeSend: function (xhr, o) {

                    pd.progressDiv.show();
                    pd.cancel.hide();
                    pd.done.hide();
                    if(s.showAbort) {
                        pd.abort.show();
                        pd.abort.click(function () {
                            removeExistingFileName(obj, fileArray);
                            xhr.abort();
                            obj.selectedFiles -= fileArray.length; //reduce selected File count
							s.onAbort.call(obj, fileArray, pd);

                        });
                    }
                    if(!feature.formdata) //For iframe based push
                    {
                        pd.progressbar.width('5%');
                    } else pd.progressbar.width('1%'); //Fix for small files
                },
                uploadProgress: function (event, position, total, percentComplete) {
                    //Fix for smaller file uploads in MAC
                    if(percentComplete > 98) percentComplete = 98;

                    var percentVal = percentComplete + '%';
                    if(percentComplete > 1) pd.progressbar.width(percentVal)
                    if(s.showProgress) {
                        pd.progressbar.html(percentVal);
                        pd.progressbar.css('text-align', 'center');
                    }

                },
                success: function (data, message, xhr) {
									
					if (s.spec_module=='') {
						if (data["success"]) {						
							$( "#img_src_"+pd.idCurrent ).val(data["url"]);
							if (is_finish) finition();									
							$('#statut-bar-'+pd.idCurrent).css("display","none");
						} else {
							alert(data["url"]);
							$('#thumbnail_'+pd.idCurrent).remove();
							$('#img_src_'+pd.idCurrent).remove();
							$('#statut-bar-'+pd.idCurrent).remove();
						}
					} else if (s.spec_module=='clipart') {
						pd.cancel.remove();
						progressQ.pop();
						//For custom errors.
						if(s.returnType == "json" && $.type(data) == "object" && data.hasOwnProperty(s.customErrorKeyStr)) {
							pd.abort.hide();
							var msg = data[s.customErrorKeyStr];
							s.onError.call(this, fileArray, 200, msg, pd);
							if(s.showStatusAfterError) {
								pd.progressDiv.hide();
								pd.statusbar.append("<span class='" + s.errorClass + "'>ERROR: " + msg + "</span>");
							} else {
								pd.statusbar.hide();
								pd.statusbar.remove();
							}
							obj.selectedFiles -= fileArray.length; //reduce selected File count
							form.remove();
							return;
						}
						obj.responses.push(data);
						pd.progressbar.width('100%')
						if(s.showProgress) {
							pd.progressbar.html('100%');
							pd.progressbar.css('text-align', 'center');
						}

						pd.abort.hide();
						s.onSuccess.call(this, fileArray, data, xhr, pd);
						if(s.showStatusAfterSuccess) {
							if(s.showDone) {
								pd.done.show();
								pd.done.click(function () {
									pd.statusbar.hide("slow");
									pd.statusbar.remove();
								});
							} else {
								pd.done.hide();
							}
							if(s.showDelete) {
								pd.del.show();
								pd.del.click(function () {
									removeExistingFileName(obj, fileArray);
									pd.statusbar.hide().remove();
									if(s.deleteCallback) s.deleteCallback.call(this, data, pd);
									obj.selectedFiles -= fileArray.length; //reduce selected File count
									updateFileCounter(s, obj);

								});
							} else {
								pd.del.hide();
							}
						} else {
							pd.statusbar.hide("slow");
							pd.statusbar.remove();

						}
						if(s.showDownload) {
							pd.download.show();
							pd.download.click(function () {
								if(s.downloadCallback) s.downloadCallback(data, pd);
							});
						}
						form.remove();
					} else if  (s.spec_module=='creation') {
						
						obj.selectedFiles = 0; //reduce selected File count
						updateFileCounter(s, obj);
						$('#statut-bar-'+pd.idCurrent).css("display","none");
						$('#awesome_extension').val(data["extension"]);
						$('#awesome_name').val(data["name"]);
						$('#awesome_folder').val('base');
						
						$('#awesome_parent').val(data["name"]);
						
						$('.chang_upload_multi').removeClass("cache");
						
						$('#image_crop').attr('src',PATH+'base/'+data["name"]+'.'+data["extension"]);
						$('.div_upload').toggleClass("cache");
						
						$('.div_crop').toggleClass("cache");
						$('#image_crop').cropper({					 
						  viewMode:2,
						  preview: '.img-preview',
						  zoomOnWheel:false,
						  crop: function(e) {					
							$('#awesome_left').val(e.x);
							$('#awesome_top').val(e.y);
							$('#awesome_width').val(e.width)
							$('#awesome_height').val(e.height)
							
						  }
						});
						
						
						
						
						
					} else if  (s.spec_module=='modif') {
						
						obj.selectedFiles = 0; //reduce selected File count
						updateFileCounter(s, obj);
						$('#statut-bar-'+pd.idCurrent).css("display","none");
						$('#awesome_parent').val(data["name"]);
						//$('#awesome_new').val('');
						$('#awesome_extension').val(data["extension"]);
						$('#awesome_name').val(data["name"]);
						$('#awesome_folder').val('base');											
						$('#cont_canvas').html('<img id="image_crop" src="'+IMAGE_PROD+'base/'+data["name"]+'.'+data["extension"]+'" style="display:none;">');
						
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
						
						
						
						
						
					}
                },
                error: function (xhr, status, errMsg) {
                	pd.cancel.remove();
                	progressQ.pop();
                    pd.abort.hide();
                    if(xhr.statusText == "abort") //we aborted it
                    {
                        pd.statusbar.hide("slow").remove();
                        updateFileCounter(s, obj);

                    } else {
						$('#statut-bar-'+pd.idCurrent).css("display","none");
                        s.onError.call(this, fileArray, status, errMsg, pd);
                        if(s.showStatusAfterError) {
                            pd.progressDiv.hide();
                            pd.statusbar.append("<span class='" + s.errorClass + "'>ERROR: " + errMsg + "</span>");
                        } else {
                            pd.statusbar.hide();
                            pd.statusbar.remove();
                        }
                        obj.selectedFiles -= fileArray.length; //reduce selected File count
                    }

                    form.remove();
                }
            };

            if(s.showPreview && file != null) {
                if(file.type.toLowerCase().split("/").shift() == "image") getSrcToPreview(file, pd.preview,pd.idCurrent,s.previewMax);
            }

            if(s.autoSubmit) {
	            form.ajaxForm(options);
                mainQ.push(form);
            	submitPendingUploads();
	            
            } else {
                if(s.showCancel) {
                    pd.cancel.show();
                    pd.cancel.click(function () {
	                     mainQ.splice(mainQ.indexOf(form), 1);
                        removeExistingFileName(obj, fileArray);
                        form.remove();
                        pd.statusbar.remove();
                        s.onCancel.call(obj, fileArray, pd);
                        obj.selectedFiles -= fileArray.length; //reduce selected File count
                        updateFileCounter(s, obj);
                    });
                }
	            form.ajaxForm(options);
            }

        }
        return this;

    }
}(jQuery));
