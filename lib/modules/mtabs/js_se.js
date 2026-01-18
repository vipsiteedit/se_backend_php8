// Блок для эмуляции в SE

$(document).ready (function(){
	$(".vkladki .textBox .obj:not(:first-child)").hide(0);
	$(".vkladki .tab").click (function() {
		var obj = $(this).parents(".content").find(".textBox .obj a[name='"+$(this).find(".link").attr("href").replace("#","")+"']").parent(".obj");
		$(this).parents(".linkBox").find(".tab").removeClass("active");
		$(this).addClass("active");
		$(obj).parent(".textBox").find(".obj").hide(0);
		$(obj).fadeIn(0);
		return false;
		});
})
