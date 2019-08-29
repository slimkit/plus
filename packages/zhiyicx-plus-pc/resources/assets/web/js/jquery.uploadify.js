(function($){
    var loadgif = TS.RESOURCE_URL + '/images/loading.png';
    var maxSize = TS.FILES.upload_max_size || 2048;
	$.fn.Huploadify = function(opts){
		var itemTemp = '<a class="feed_picture_span uploadify-queue-item" id="${fileID}"><img class="imgload" src=" '+loadgif+' "/></a>';
		var defaults = {
			fileTypeExts:'*.jpg;*.JPG;*.jpeg;*.JPEG;*.png;*.PNG;*.gif;*.GIF',//允许上传的文件类型，格式'*.jpg;*.doc'
			auto:true,//是否开启自动上传
			method:'post',//发送请求的方式，get或post
			multi:true,//是否允许选择多个文件
			formData:null,//发送给服务端的参数，格式：{key1:value1,key2:value2}
			fileObjName:'file',//在后端接受文件的参数名称，如PHP中的$_FILES['file']
			fileSizeLimit:maxSize,//允许上传的文件大小，单位KB
			showUploadedPercent:true,//是否实时显示上传的百分比，如20%
			showUploadedSize:false,//是否实时显示已上传的文件大小，如1M/2M
			removeTimeout: 1000,//上传完成后进度条的消失时间，单位毫秒
			itemTemplate:itemTemp,//上传队列显示的模板
			onUploadStart:null,//上传开始时的动作
			onUploadSuccess:null,//上传成功的动作
			onUploadComplete:null,//上传完成的动作
			onUploadError:null, //上传失败的动作
			onInit:null,//初始化时的动作
			onCancel:null,//删除掉某个文件后的回调函数，可传入参数file
			onClearQueue:null,//清空上传队列后的回调函数，在调用cancel并传入参数*时触发
			onDestroy:null,//在调用destroy方法时触发
			onSelect:null,//选择文件后的回调函数，可传入参数file
			onQueueComplete:null,//队列中的所有文件上传完成后触发
		}

		var option = $.extend(defaults,opts);

		//定义一个通用函数集合
		var F = {
			//将文件的单位由bytes转换为KB或MB，若第二个参数指定为true，则永远转换为KB
			formatFileSize : function(size,withKB){
				if (size > 1024 * 1024 && !withKB){
					size = (Math.round(size * 100 / (1024 * 1024)) / 100).toString() + 'MB';
				}
				else{
					size = (Math.round(size * 100 / 1024) / 100).toString() + 'KB';
				}
				return size;
			},
			//将输入的文件类型字符串转化为数组,原格式为*.jpg;*.png
			getFileTypes : function(str){
				var result = [];
				var arr1 = str.split(";");
				for(var i=0, len=arr1.length; i<len; i++){
					result.push(arr1[i].split(".").pop());
				}
				return result;
			},
			////根据文件序号获取文件
			getFile : function(index,files){
				for(var i=0;i<files.length;i++){
					if(files[i].index == index){
						return files[i];
					}
				}
				return null;
			}
		};

		var returnObj = null;

		this.each(function(index, element){
			var _this = $(element);
			var instanceNumber = $('.uploadify').length+1;
			var uploadManager = {
				container : _this,
				filteredFiles : [],//过滤后的文件数组
				init : function(){
					var inputStr = '<input id="select_btn_'+instanceNumber+'" class="selectbtn" style="display:none;" type="file" name="fileselect[]"';
					inputStr += option.multi ? ' multiple' : '';
					inputStr += ' accept="';
					inputStr += F.getFileTypes(option.fileTypeExts).join(",");
					inputStr += '"/>';
					inputStr += '<a id="file_upload_'+instanceNumber+'-button" href="javascript:void(0)" class="uploadify-button">';
					inputStr += option.buttonText;
					inputStr += '</a>';
					var uploadFileListStr = '<div id="file_upload_'+instanceNumber+'-queue" class="uploadify-queue feed_picture"></div>';
					_this.append(inputStr);
					_this.after(uploadFileListStr);

					//初始化返回的实例
					returnObj =  {
						instanceNumber : instanceNumber,
						upload : function(fileIndex){
							if(fileIndex === '*'){
								for(var i=0,len=uploadManager.filteredFiles.length;i<len;i++){
									uploadManager._uploadFile(uploadManager.filteredFiles[i]);
								}
							}
							else{
								var file = F.getFile(fileIndex,uploadManager.filteredFiles);
								file && uploadManager._uploadFile(file);
							}
						},
						disable : function(instanceID){
							var parent = instanceID ? $('file_upload_'+instanceID+'-button') : $('body');
							$(document).off('click', '.ev-btn-feed-pic');
						},
						ennable : function(instanceID){
							//点击上传按钮时触发file的click事件
							var parent = instanceID ? $('file_upload_'+instanceID+'-button') : $('body');
						  $(document).on('click', '.ev-btn-feed-pic', function(){
								parent.find('.selectbtn').trigger('click');
							});
						},
						destroy : function(){
							uploadManager.container.html('');
							uploadManager = null;
							option.onDestroy && option.onDestroy();
						},
						settings : function(name,value){
							if(arguments.length==1){
								return option[name];
							}
							else{
								if(name=='formData'){
									option.formData = $.extend(option.formData, value);
								}
								else{
									option[name] = value;
								}
							}
						},
						Huploadify : function(){
							var method = arguments[0];
							if(method in this){
								Array.prototype.splice.call(arguments, 0, 1);
								this[method].apply(this[method], arguments);
							}
						}
					};

					//文件选择控件选择
					var fileInput = this._getInputBtn();
				  	if (fileInput.length>0) {
						fileInput.change(function(e) {
							uploadManager._getFiles(e);
					 	});
				 	}

					//点击选择文件按钮时触发file的click事件
					$(document).on('click', '.ev-btn-feed-pic', function(){
						_this.find('.selectbtn').trigger('click');
					});

				    $(".uploadify-queue").on("click", ".uploadify-queue-add", function() {
						_this.find('.selectbtn').trigger('click');
				    });

					option.onInit && option.onInit(returnObj);
				},
				_filter: function(files) {		//选择文件组的过滤方法
					var fileCount = $('.uploadify-queue .uploadify-queue-item').length;

					// 图片张数判断
					if ((fileCount + files.length) > 9) {
						noticebox('最多上传9张图片', 0);
						return false;
					}
					var arr = [];
					var typeArray = F.getFileTypes(option.fileTypeExts);
					if(typeArray.length>0){
						for(var i=0,len=files.length;i<len;i++){
							var f = files[i];
							if(parseInt(F.formatFileSize(f.size,true))<=option.fileSizeLimit){
								if($.inArray('*',typeArray)>=0 || $.inArray(f.name.split('.').pop(),typeArray)>=0){
									arr.push(f);
								}
								else{
									uploadManager._getInputBtn().val('');
									noticebox('文件 "'+f.name+'" 类型不允许！', 0);
								}
							}
							else{
								uploadManager._getInputBtn().val('');
								noticebox('文件 "'+f.name+'" 大小超出限制！', 0);
								continue;
							}
						}
					}
					return arr;
				},
				_getInputBtn : function(){
					return _this.find('.selectbtn');
				},
				_getFileList : function(){
					return $('.uploadify-queue');
				},
				//根据选择的文件，渲染DOM节点
				_renderFile : function(file){
					var $html = $(option.itemTemplate.replace(/\${fileID}/g,'fileupload_'+instanceNumber+'_'+file.index).replace(/\${fileName}/g,file.name).replace(/\${fileSize}/g,F.formatFileSize(file.size)).replace(/\${instanceID}/g,_this.attr('id')));

					uploadManager._getFileList().show();

					if ($('.uploadify-queue-add').length == 0) {
						// 图片添加按钮
						var add = '<a class="feed_picture_span uploadify-queue-add"></a>'
						$('.uploadify-queue').append(add);
					}

					// 图片加号处理
					if (file.index == 9) {
						$('.uploadify-queue-add').remove();
						uploadManager._getFileList().append($html);
					} else {
						$('.uploadify-queue-add').before($html);
					}


					//触发select动作
					option.onSelect && option.onSelect(file);

					uploadManager._uploadFile(file);
				},
				//获取选择后的文件
				_getFiles : function(e){
			  		var files = e.target.files;
			  		files = uploadManager._filter(files);
			  		var fileCount = $('.uploadify-queue .uploadify-queue-item').length;//队列中已经有的文件个数

		  			for(var i=0,len=files.length;i<len;i++){
		  				files[i].index = ++fileCount;
		  				files[i].status = 0;//标记为未开始上传
		  				uploadManager.filteredFiles.push(files[i]);
		  				var l = uploadManager.filteredFiles.length;
		  				uploadManager._renderFile(uploadManager.filteredFiles[l-1]);
		  			}
				},
				//上传文件
				_uploadFile : function(file){
					// new 上传方式
					var rs = fileUpload.init(file, option.onUploadSuccess);
					//清除文件选择框中的已有值
					uploadManager._getInputBtn().val('');
				}
			};

			uploadManager.init();
		});

		return returnObj;

	}
})(jQuery)