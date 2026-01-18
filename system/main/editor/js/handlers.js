function fileQueueError(file, errorCode, message) {
	try {
		var imageName = "error.gif";
		var errorName = "";
		
		if (errorCode === SWFUpload.errorCode_QUEUE_LIMIT_EXCEEDED) {
			errorName = "You have attempted to queue too many files.";
		}

		if (errorName !== "") {
			alert(errorName);
			return;
		}

		switch (errorCode) {
		case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
			imageName = "zerobyte.gif";
			break;
		case SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT:
			imageName = "toobig.gif";
			break;
		case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
		case SWFUpload.QUEUE_ERROR.INVALID_FILETYPE:
		default:
			alert(message);
			break;
		}

		addImage("/system/main/editor/images/" + imageName);

	} catch (ex) {
		this.debug(ex);
	}

}

function fileDialogComplete(numFilesSelected, numFilesQueued) {
	try {
		if (numFilesQueued > 0) {
			this.startUpload();
		}
	} catch (ex) {
		this.debug(ex);
	}
}

function uploadProgress(file, bytesLoaded) {

	try {
		var percent = Math.ceil((bytesLoaded / file.size) * 100);

		var progress = new FileProgress(file,  this.customSettings.upload_target);
		    progress.setProgress(percent);
		if (percent === 100) 
           {
			progress.setStatus("Creating thumbnail...");
			progress.toggleCancel(false, this);
		   } 
        else 
           {
			progress.setStatus("Uploading...");
			progress.toggleCancel(true, this);
		   }
	} catch (ex) {
		this.debug(ex);
	}
}

function uploadSuccess(file, serverData) {
	try {
		var progress = new FileProgress(file,  this.customSettings.upload_target);
          
		if (serverData.substring(0, 7) === "FILEID:") 
            {
            //alert(serverData.substring(7));
            addImage(serverData.substring(7));

			progress.setStatus("Thumbnail Created.");
			progress.toggleCancel(false);
		    } 
        else 
            {
			addImage("/system/main/editor/images/error.gif");
			progress.setStatus("Error.");
			progress.toggleCancel(false);
			alert(serverData);
		  }
	    } 
         catch (ex) 
            {
		    this.debug(ex);
	        }
}


function uploadComplete(file) {
	try {
		if (this.getStats().files_queued > 0) {
			this.startUpload();
		} else {
			var progress = new FileProgress(file,  this.customSettings.upload_target);
			progress.setComplete();
			progress.setStatus("All images received.");
			progress.toggleCancel(false);
		}
	} catch (ex) {
		this.debug(ex);
	}
}


function uploadError(file, errorCode, message) {
	var imageName =  "error.gif";
	var progress;
	
	
	alert("ia ?iaeo uploadError"); 
	
	
	try {
		switch (errorCode) {
		case SWFUpload.UPLOAD_ERROR.FILE_CANCELLED:
			try {
				progress = new FileProgress(file,  this.customSettings.upload_target);
				progress.setCancelled();
				progress.setStatus("Cancelled");
				progress.toggleCancel(false);
			}
			catch (ex1) {
				this.debug(ex1);
			}
			break;
		case SWFUpload.UPLOAD_ERROR.UPLOAD_STOPPED:
			try {
				progress = new FileProgress(file,  this.customSettings.upload_target);
				progress.setCancelled();
				progress.setStatus("Stopped");
				progress.toggleCancel(true);
			}
			catch (ex2) {
				this.debug(ex2);
			}
		case SWFUpload.UPLOAD_ERROR.UPLOAD_LIMIT_EXCEEDED:
			imageName = "uploadlimit.gif";
			break;
		default:
			alert(message);
			break;
		}

		addImage("/system/main/editor/images/" + imageName);

	} catch (ex3) {
		this.debug(ex3);
	}

}


function addImage(src) {
	var newImg = document.createElement("img");
	newImg.style.margin = "5px";
	document.getElementById("thumbnails").appendChild(newImg);

	if (newImg.filters) {
		try {
			newImg.filters.item("DXImageTransform.Microsoft.Alpha").opacity = 0;
		} catch (e) {
			newImg.style.filter = 'progid:DXImageTransform.Microsoft.Alpha(opacity=' + 0 + ')';
		}
	} 
    else {
		newImg.style.opacity = 0;
	}

	newImg.onload = function () {
		fadeIn(newImg, 0);
	var elm = $("#thumbnails img");
    elm.click(function()
    {
     var src = $(this).attr("src");
           var regx = /mod_(.*?)_prev\./i;
     var img = regx.exec(src);
         img = img[1];
     var a = $(this).parent();  
    if(confirm('Вы уверены, что хотите удалить эту картинку '+img+'?')) 
    {
     $.get("/system/main/editor/upload.php?imgdel="+img, function(data) 
        {
        if(data)
         {
         // a.hide(400);
         }
        else alert("Ошибка! Изображение не удалено!");
        });
    }
     return false;
    });		
	};
	newImg.src = src;
}

function fadeIn(element, opacity) {
	var reduceOpacityBy = 5;
	var rate = 30;	
  
	if (opacity < 100) {
		opacity += reduceOpacityBy;
		if (opacity > 100) {
			opacity = 100;
		}

		if (element.filters) {
			try {
				element.filters.item("DXImageTransform.Microsoft.Alpha").opacity = opacity;
			} catch (e) {
				element.style.filter = 'progid:DXImageTransform.Microsoft.Alpha(opacity=' + opacity + ')';
			}
		} else {
			element.style.opacity = opacity / 100;
		}
	}

	if (opacity < 100) {
		setTimeout(function () {
			fadeIn(element, opacity);
		}, rate);
	}
}

/* ******************************************
 *	FileProgress Object
 *	Control object for displaying file info
 * ****************************************** */

function FileProgress(file, targetID) {

	this.fileProgressID = "divFileProgress";
	this.fileProgressWrapper = document.getElementById(this.fileProgressID);
	if (!this.fileProgressWrapper) {
		this.fileProgressWrapper = document.createElement("div");
		this.fileProgressWrapper.className = "progressWrapper";
		this.fileProgressWrapper.id = this.fileProgressID;

		this.fileProgressElement = document.createElement("div");
		this.fileProgressElement.className = "progressContainer";

		var progressCancel = document.createElement("a");
		progressCancel.className = "progressCancel";
		progressCancel.href = "#";
		progressCancel.style.visibility = "hidden";
		progressCancel.appendChild(document.createTextNode(" "));

		var progressText = document.createElement("div");
		progressText.className = "progressName";
		progressText.appendChild(document.createTextNode(file.name));

		var progressBar = document.createElement("div");
		progressBar.className = "progressBarInProgress";

		var progressStatus = document.createElement("div");
		progressStatus.className = "progressBarStatus";
		progressStatus.innerHTML = "&nbsp;";

		this.fileProgressElement.appendChild(progressCancel);
		this.fileProgressElement.appendChild(progressText);
		this.fileProgressElement.appendChild(progressStatus);
		this.fileProgressElement.appendChild(progressBar);

		this.fileProgressWrapper.appendChild(this.fileProgressElement);

		document.getElementById(targetID).appendChild(this.fileProgressWrapper);
		fadeIn(this.fileProgressWrapper, 0);

	} else {
		this.fileProgressElement = this.fileProgressWrapper.firstChild;
		this.fileProgressElement.childNodes[1].firstChild.nodeValue = file.name;
	}

	this.height = this.fileProgressWrapper.offsetHeight;

}
FileProgress.prototype.setProgress = function (percentage) {
	this.fileProgressElement.className = "progressContainer green";
	this.fileProgressElement.childNodes[3].className = "progressBarInProgress";
	this.fileProgressElement.childNodes[3].style.width = percentage + "%";
};
FileProgress.prototype.setComplete = function () {
	this.fileProgressElement.className = "progressContainer blue";
	this.fileProgressElement.childNodes[3].className = "progressBarComplete";
	this.fileProgressElement.childNodes[3].style.width = "";

};
FileProgress.prototype.setError = function () {
	this.fileProgressElement.className = "progressContainer red";
	this.fileProgressElement.childNodes[3].className = "progressBarError";
	this.fileProgressElement.childNodes[3].style.width = "";

};
FileProgress.prototype.setCancelled = function () {
	this.fileProgressElement.className = "progressContainer";
	this.fileProgressElement.childNodes[3].className = "progressBarError";
	this.fileProgressElement.childNodes[3].style.width = "";

};
FileProgress.prototype.setStatus = function (status) {
	this.fileProgressElement.childNodes[2].innerHTML = status;
};

FileProgress.prototype.toggleCancel = function (show, swfuploadInstance) {
	this.fileProgressElement.childNodes[0].style.visibility = show ? "visible" : "hidden";
	if (swfuploadInstance) {
		var fileID = this.fileProgressID;
		this.fileProgressElement.childNodes[0].onclick = function () {
			swfuploadInstance.cancelUpload(fileID);
			return false;
		};
	}
};

var swfuPhotos;
function BindSWFUpload() {
swfuPhotos = new SWFUpload({
upload_url: "/system/main/editor/upload.php",
flash_url: "/lib/js/swfupload/swfupload.swf",
flash9_url : "/lib/js/swfupload/swfupload_fp9.swf",
//post_params: {"PHPSESSID": "{$sid}"},
file_size_limit: "2 MB",
file_types: "*.jpg;*.png;*.gif",
file_types_description: "JPG, PNG images, GIF",
file_upload_limit: "0",
file_queue_error_handler: fileQueueError,
file_dialog_complete_handler: fileDialogComplete,
upload_progress_handler: uploadProgress,
upload_error_handler: uploadError,
upload_success_handler: uploadSuccess,
upload_complete_handler: uploadComplete,
button_image_url: "/system/main/editor/images/SmallSpyGlassWithTransperancy_17x18.png",//http://demo.swfupload.org/v220/applicationdemo/images/SmallSpyGlassWithTransperancy_17x18.png",
button_placeholder_id: "spanButtonPlaceholder",
button_width: 250,
button_height: 22,
button_text: '<span class="button">Еще фото <span class="buttonSmall">(не более 2Mb каждое)</span></span>',
button_text_style: '.button { font-family: Arial, Helvetica, sans-serif; font-size: 16pt; } .buttonSmall { font-size: 12pt; }',
button_text_top_padding: 0,
button_text_left_padding: 22,
button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
button_cursor: SWFUpload.CURSOR.HAND,
custom_settings: { upload_target: "divFileProgressContainer" }, debug: false });
}
