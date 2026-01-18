function runImageEditor(){
		$( "#dialog:ui-dialog" ).dialog( "destroy" );
		var dialog_width = 800,
			dialog_height = 520;
		
	//	$ ("MyImage"). ImageEditor ();
		
		$( "#dialog-modal-image" ).dialog({
			height: dialog_height,
			width: dialog_width,
			modal: true,
			create: function(event, ui) {
			},
			open: function(event, ui) {
    //----------------------------------------------------------------------
	$('#dialog-modal-image p').imgAreaSelect({
        //aspectRatio: '300:200',
		x1: 10, y1: 10, x2: 300, y2: 200,
		minWidth: 100,
        handles: true,
		show: true,
		//resizable: true,
        //onSelectChange: preview,
		/*onSelectChange: function ( image, selection ) {
			$('#photo').css('top', selection.y1+'px');
			$('#photo').css('left', selection.x1+'px');
			$('#photo').css('width', selection.width+'px');
		},*/
        onSelectEnd: function ( image, selection ) {
            $('input[name=x1]').val(selection.x1);
            $('input[name=y1]').val(selection.y1);
            $('input[name=x2]').val(selection.x2);
            $('input[name=y2]').val(selection.y2);
            $('input[name=w]').val(selection.width);
            $('input[name=h]').val(selection.height);
        }
    });
			
			},
			resize: function(event, ui) {
				dialog_width = this.clientWidth;
				dialog_height = this.clientHeight;
			},
			buttons:[
			{
				text: ed_button['save'],
				click: function() {
					var options = { 
						target: '',
						beforeSubmit: showRequestPage, // функция, вызываемая перед передачей 
						success: showResponsePage, // функция, вызываемая при получении ответа
						timeout: 3000 // тайм-аут
					};
				//	$('#editpageheaderform').ajaxSubmit(options);
					$( this ).dialog( "close" );
				}
			},{
				text: ed_button['cancel'],
				click: function() {
					$( this ).dialog("close");
					$('#dialog-modal-image').remove();
				}
			}],
			close: function () {
				frameWindow("hide");
			}
		});

		function showRequestPage(formData, jqForm, options) { 
			var queryString = $.param(formData);			
			return true; 
		} 
 
		// вызов после получения ответа 
		function showResponsePage(responseText, statusText)  {
			var dialogmodal = $('#dialog-modal-contact');
			if (dialogmodal != null) dialogmodal.remove();
		} 


function preview(img, selection) {
    var scaleX = 100 / (selection.width || 1);
    var scaleY = 100 / (selection.height || 1);
    $('#photo + div > img').css({
        width: Math.round(scaleX * 600) + 'px',
        height: Math.round(scaleY * 400) + 'px',
        marginLeft: '-' + Math.round(scaleX * selection.x1) + 'px',
        marginTop: '-' + Math.round(scaleY * selection.y1) + 'px'
    });
} 

//$(document).ready(function () {
  /*  $('<div><img src="/skin/foto.jpg" style="position: relative;" /><div>') .css({
        float: 'left',
        position: 'relative',
        overflow: 'hidden',
        width: '100px',
        height: '100px'
    }) .insertAfter($('#photo')); */

//}); 	

var rotateBtn = document.getElementById("rotateImg");
rotateBtn.addEventListener("click",rotate,false);//добавляем «прослушиватель события нажатия»
  function rotate(e) {
	rotateImg(imgData[0], 100, 100, 90); 
  }

//var sendBtn = document.getElementById("sendfile");
//sendBtn.addEventListener("click",upload,false);//добавляем «прослушиватель события нажатия»
 
  function upload(e) {
	for(var i = 0; i < imgData.length; i++) {
	var canvas = imgData[i];
	var dataurl = canvas.toDataURL("image/jpeg");
 
 //для отправки изображения я воспользовался библиотекой jQuery
 alert(dataurl);
	jQuery.post("/get.php",{image:dataurl},function(data){
		div.innerHTML = data;
	});
  }
}
	
}
runImageEditor();