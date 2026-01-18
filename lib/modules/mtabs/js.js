$(document).ready (function(){
	$(".vkladki .tab").click (function() {
		var ajaxUrl = $(this).find(".link").attr("href").replace("obj","load");
		$(this).parents(".content").find(".textBox").load(ajaxUrl);
		$(this).parents(".linkBox").find(".tab").removeClass("active");
		$(this).addClass("active");	
		return false;
		});
});
