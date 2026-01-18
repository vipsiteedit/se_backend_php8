$(function() {
    
});

$(function() {
		/*$( "#tabs_header" ).tabs();
		$("#tabs-modules").scrollable({horizontal:true});
		$("#editor_save" ).click(function(){
			console.log('save_click');
			getEditWindow('editpage_save');
		});
		$("#editor_addpage").click(function(){ parent.content.getEditWindow('editpage_addcontent'); });
		$("#editor_editpage").click(function(){ parent.content.getEditWindow('editpage_content'); });
		$("#editor_delpage").click(function(){ parent.content.getEditWindow('editpage_delete', pagename); });
		$("#editor_contacts").click(function(){ parent.content.getEditWindow('editpage_contacts'); });
		$("#editor_menu").click(function(){ parent.content.getEditWindow('editpage_menu'); });
		$("#editor_cancel").click(function(){ 
			parent.content.getEditWindow('editpage_cancel');
		});*/
});

var addressFormatting = function(text){
	var newText = text;
	var findreps = [
		{find:/^([^\|]+)\|/g, rep: '<span class="ui-selectmenu-item-header">$1</span>'}
	];
			
	for(var i in findreps){
		newText = newText.replace(findreps[i].find, findreps[i].rep);
	}
	return newText;
}

/*������ �������*/
$('select#filePages').change(function(){
	document.location.href='/'+$(this).val()+'/';
});
$('select#filePages').selectmenu({
	style:'dropdown',
	menuWidth: 400,
	maxHeight: 400,
	format: addressFormatting			
});

$('select#thislang').change(function(){
	document.location.href='?interfacelang='+$(this).val();
});

$('select#thislang').selectmenu({
	style:'dropdown',
	menuWidth: 100,
	maxHeight: 300,
	format: addressFormatting			
});

$('select#modulegroup').change(function(){
	document.location.href='?modulegroup='+$(this).val();
});
$('select#modulegroup').selectmenu({
	style:'dropdown',
	menuWidth: 150,
	maxHeight: 300,
	format: addressFormatting			
});


var elem = $("#tabs_header .hints");   
elem.live("mouseenter", function(ev)
{
	var title = $(this).attr("title");
				$(this).attr("title", "");
	var hint = $("#sepastlivehint");
	   hint.hide("fast");
	   hint.html(title);
	   
   var left = Math.floor($(this).offset().left + $(this).width()*0.5);
   
   var leftsecond = Math.floor(left - hint.width()*0.5);
	if(leftsecond <= 0)
	   leftsecond = 20;
 
	   hint.css({"left": left + "px"}).show();//.animate({"left": leftsecond + "px"}, "fast");	   
   	elem.live("mouseleave", function(){
	    hint.empty();
	    $(this).attr("title", title);
	});
 
});