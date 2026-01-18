var PhotosResult = "";
var Count = 0;
var UploadedFiles = 0;
function photos_fileDialogComplete(numFilesSelected, numFilesQueued) {
    try {
        if (numFilesQueued > 0) {
            PhotosResult = numFilesQueued == '1' ? ' image' : ' images';
            PhotosResult = numFilesQueued + PhotosResult + " attached";
            Count = parseInt(numFilesQueued);
            $('#AddPhotos').val('Uploading...');
            $('#submitStatus')
                .attr('disabled', 'disabled')
                .addClass('disabled');
            this.startUpload();
        }
    } catch (ex) {
    }
}

function photos_uploadProgress(file, bytesLoaded) {
    try {
        var pw = 115;
        var w = Math.ceil(pw * (UploadedFiles / Count + (bytesLoaded / (file.size * Count))));
        $('#Progress').stop().animate({ width: w });
    } catch (ex) {
    }
}
function photos_uploadSuccess(file, serverData) {
    try {
        UploadedFiles++;
    } catch (ex) {

    }
}

function photos_uploadComplete(file) {
    try {
        if (this.getStats().files_queued > 0) {
            this.startUpload();
        } else {
            $('#UploadPhotos').hide();
            $('#Buttons').prepend('<span id="UploadResult" class="images">' + PhotosResult + '</span>');
        }
    } catch (ex) {
    }
}
function photos_fileQueueError(file, errorCode, message) {
    try {
        switch (errorCode) {
            case SWFUpload.QUEUE_ERROR.QUEUE_LIMIT_EXCEEDED:
                alert('Upload limit exceeded. Maximum 5 images per post');
                break;
            case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
            case SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT:
            case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
            case SWFUpload.QUEUE_ERROR.INVALID_FILETYPE:
                break;
        }
    } catch (ex) {
    }

}

function swfuploadLoaded() {
    $('#Buttons object').hover(
        function() {
            $(this).next().addClass('hover');
        }
        ,
        function() {
            $(this).next().removeClass('hover');
        });

}
var ASPSESSID = "";
var swfuPhotos;
function BindSWFUpload() {
    var swfuPhotosSettings = {
        file_dialog_complete_handler: photos_fileDialogComplete,
        upload_progress_handler: photos_uploadProgress,
        upload_success_handler: photos_uploadSuccess,
        upload_complete_handler: photos_uploadComplete,
        swfupload_loaded_handler: swfuploadLoaded,
        file_queue_error_handler: photos_fileQueueError,
        file_size_limit: "2 MB",
        file_types: "*.jpg;*.png;*.gif",
        file_types_description: "JPG, PNG, GIF images",
        file_upload_limit: "5",
        button_placeholder_id: "spanButtonPlaceholder"
    }

    var defaultSettings = {
        flash_url: "/lib/js/swfupload/swfupload.swf",
		flash9_url : "/lib/js/swfupload/swfupload_fp9.swf",
        upload_url: "/system/main/editor/upload.php",
        post_params: {
            "ASPSESSID": ASPSESSID
        },
		button_width: 250,
		button_height: 22,
        button_image_url: "/system/main/editor/images/white50.png",

        button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
        button_cursor: SWFUpload.CURSOR.HAND,
		custom_settings: { upload_target: "divFileProgressContainer" }, 
		debug: false
    }

    swfuPhotos = new SWFUpload($.extend(swfuPhotosSettings, defaultSettings));
}