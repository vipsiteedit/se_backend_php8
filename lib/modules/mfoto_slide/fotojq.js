$(document).ready (function(){  
	$(".photoAlbumSplash .obj .slide-show").click (function() {
		runFunction($(this).parents(".obj").find(".photoLink img"));
		function runFunction (obj) {
			var k = {ctrl:false, left:false, right:false};
			var showForm = false;
			var param = getParam(obj);
			function getParam(obj) {
				var cN = obj.parents(".content")[0].className;
				return contentParam[cN.substring(cN.lastIndexOf(" ")+1)];}
			function preLoader(c) {
			if (c == false) {
				$(".photoAlbumSplash .imageTable .loading").css({display: "none"});
				} else {
				$(".photoAlbumSplash .imageTable .imgCell img").css({opacity: 0});
				$(".photoAlbumSplash .imageTable .loading").css({display: "block"});
				}}
			function hideImg () {
				$(".photoAlbumSplash .showImgFon").animate({opacity: 0} ,400 );
				$(".photoAlbumSplash .imageTable").animate({opacity: 0} ,400 );
				$("body > .photoAlbumSplash").queue( function () {
					$(this).remove();
					$(this).dequeue();
				});
				$(document).unbind();
				showForm = false;}
			function navigateImgForm (obj, b) {
				var showImageNumber = parseInt($(".photoAlbumSplash .imageTable .now").text());
				var n;
				if ((((showImageNumber+1) > $(".photoAlbumSplash .imageTable .all").text())&&(b=="next")) || (((showImageNumber-1) < 1)&&(b=="prev"))) return false;
				if (b == "prev") {n=showImageNumber-2};
				if (b == "next") {n=showImageNumber}; 
				preLoader();
				var imageObj = obj.parents(".content").find(".obj .photoLink img:eq("+n+")");
				$(".photoAlbumSplash .imageTable .imgCell img").prop({src: imageObj[0].src.replace("_prev.",".") , alt: imageObj[0].alt , title: imageObj[0].title});
				$(".photoAlbumSplash .imageTable .objectTitle").html( getTitle(imageObj) );
				if (b == "prev") {$(".photoAlbumSplash .imageTable .now").text(parseInt(showImageNumber-1))};
				if (b == "next") {$(".photoAlbumSplash .imageTable .now").text(parseInt(showImageNumber+1))};
				showImageNumber = parseInt($(".photoAlbumSplash .imageTable .now").text());
				if ( showImageNumber == 1 ) {$(".photoAlbumSplash .imageTable .prev").css({visibility:"hidden"})} else {$(".photoAlbumSplash .imageTable .prev").css({visibility:"visible"})};
				if ( showImageNumber == $(".photoAlbumSplash .imageTable .all").text() ) {$(".photoAlbumSplash .imageTable .next").css({visibility:"hidden"})} else {$(".photoAlbumSplash .imageTable .next").css({visibility:"visible"})};
			}
			function animateImgForm (w,h,hide) {
				var gr=40;
				function a (w,h) {
					
					var hF = $(".photoAlbumSplash .imageTable")[0].offsetHeight - $(".photoAlbumSplash .imageTable .imgCell img")[0].offsetHeight;
					var wF = $(".photoAlbumSplash .imageTable")[0].offsetWidth - $(".photoAlbumSplash .imageTable .imgCell img")[0].offsetWidth;
					
					function animate(wA,hA){
						if(hide=="hide"){$(".photoAlbumSplash .imageTable .imgCell img").animate({ height: hA, width: wA},300)};
						$(".photoAlbumSplash .imageTable .imgCell img").animate({ height: hA, width: wA},300); 
						$(".photoAlbumSplash .imageTable").animate({ marginLeft: Math.round(-(wA+wF)/2)},300, 
								function(){if (hide!="hide") {$(this).find(".imgCell img").animate({opacity:1},300);}});}
					function resizeScreen(imW,imH){
						if ((brW / brH) > (imW /imH)){
							var imgW = Math.round(w*(brH-hF-gr))/h;
							h = brH-hF-gr;
							animate(imgW,(brH-hF-gr));
						} else {
							h = Math.round(h*(brW-wF-gr))/w;
							animate((brW-wF-gr),h);
						}}
					
					var brW = $("html")[0].offsetWidth;
					var brH = $("html")[0].clientHeight;
					
					if(((wF+w)>brW)||((hF+h)>brH)) { 
						resizeScreen(wF+w,hF+h);} 
					else {animate(w,h);};
					
					var t = Math.round($($.browser.webkit?"body":"html")[0].scrollTop + ($("html")[0].clientHeight/2 - (h+hF)/2 ));
					if (t < 0) t = $($.browser.webkit?"body":"html")[0].scrollTop + 5;
					$(".photoAlbumSplash .imageTable").animate({top: t},600);
				}
				if ((w > 0) || (h > 0)) {a(w,h);
					} else {
					var tI = new Image();
					tI.src = $(".photoAlbumSplash .imageTable .imgCell img")[0].src;
					function setImg() {
						if ((tI.width > 0)||(tI.height > 0)) {
							a(tI.width, tI.height); clearInterval(timer);
							}
						}
					var timer = setInterval(setImg,100);
					}};
			function getNumberText(obj,caseObj) {
				var objArray = obj.parents(".content").find(".obj .photoLink img");
				if (caseObj == "all") {return objArray.length;}
				else {
					for (i=0; i < objArray.length; i++) {
						if (objArray[i] == obj[0]) {
							return i+1;
							break;
						}}}};
			function getTitle (obj) {
				return obj.parents(".obj").find(".textLink").html();}
			if ($(".photoAlbumSplash .imageTable").length == 0) {
				showForm = true;
				$("body").append("<div class=\"photoAlbumSplash\"><div class=\" showImgFon \" style=\"display:none; position: fixed; top: 0px; left: 0px; width:100%; height:100%;\"></div>"+
							"<table class=\"imageTable\" style=\"display:none; margin-left: -25%; position: absolute; top: 0px; left: 50%; \" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">"+
							"<tbody><tr class=\"top\"><td class=\"left\"></td><td class=\"center\"></td><td class=\"rigth\"></td></tr>"+
							"<tr class=\"topImg\"><td class=\"left\"></td><td class=\"center\"><div><h4 class=\"objectTitle\">&nbsp;</h4></div><div class=\"hideImage\">" + param.close + "</div></td>"+
							"<td class=\"rigth\"></td></tr>"+
							"<tr class=\"center\"><td class=\"left\"></td><td class=\"imgCell\"><div style=\"display:none;\" class=\"loading\">" + param.loading + "</div><img style=\"width:0; height:0;\" alt=\"\" title=\"\" src=\"\" border=\"0\">"+
							"</td><td class=\"rigth\"></td></tr>"+
							"<tr class=\"bottomImg\"><td class=\"left\"></td><td class=\"center\"><div class=\"text\">" + param.textImage + "<span class=\"now\"></span>" + param.textOf + "<span class=\"all\"></span></div>"+
							"<div class=\"navigPanel\"><div class=\"prev\">"+param.prev+"</div><div class=\"next\">" + param.next + "</div></div></td><td class=\"rigth\"></td></tr>"+
							"<tr class=\"bottom\"><td class=\"left\"></td><td class=\"center\"></td><td class=\"rigth\"></td></tr></tbody></table></div>");
				$(".photoAlbumSplash .showImgFon , .photoAlbumSplash .imageTable").css({opacity:"0", display: "block"});
				animateImgForm (300,200,"hide");
			};
			preLoader();
			if (showForm)$(".photoAlbumSplash .imageTable .imgCell img").load ( function() {preLoader(false); animateImgForm ();});
			if (showForm)$(".photoAlbumSplash .imageTable .hideImage").click ( function() { hideImg ()});
			if (showForm)$(".photoAlbumSplash .imageTable .prev").click ( function() {navigateImgForm(obj,"prev")});
			if (showForm)$(".photoAlbumSplash .imageTable .next").click ( function() {navigateImgForm(obj,"next")});
			if (showForm)$(".photoAlbumSplash .imageTable .imgCell img").click( function() {navigateImgForm(obj,"next")});
			if (showForm)$(".photoAlbumSplash .showImgFon").click( function() {hideImg ()});
            if (showForm) $(document).keydown(function (event){
                if(event.keyCode == 17) k.ctrl = true;
                if(event.keyCode == 37) k.left = true;
                if(event.keyCode == 39) k.right = true;
                if(k.ctrl && k.left) navigateImgForm(obj,"prev");
                if(k.ctrl && k.right) navigateImgForm(obj,"next");});  
            if (showForm) $(document).keyup(function (event){
                if(event.keyCode == 17) k.ctrl = false;
                if(event.keyCode == 37) k.left = false;
                if(event.keyCode == 39) k.right = false;
                if(event.keyCode == 27) hideImg ();});
			$(".photoAlbumSplash .imageTable .imgCell img").prop({alt: obj[0].alt , title: obj[0].title , src: obj[0].src.replace("_prev.",".")});
			$(".photoAlbumSplash .imageTable .objectTitle").html( getTitle(obj) );
			$(".imageTable .now").text(getNumberText(obj));
			if ($(".photoAlbumSplash .imageTable .now").text() == "1") { $(".photoAlbumSplash .imageTable .prev").css({visibility:"hidden"}) } else { $(".photoAlbumSplash .imageTable .prev").css({visibility:"visible"}) };
			$(".photoAlbumSplash .imageTable .all").text(getNumberText(obj,"all"));
			if ($(".photoAlbumSplash .imageTable .now").text() == getNumberText(obj,"all")) { $(".photoAlbumSplash .imageTable .next").css({visibility:"hidden"}) } else { $(".photoAlbumSplash .imageTable .next").css({visibility:"visible"})};
			if (showForm) {
				$(".photoAlbumSplash .showImgFon").animate({opacity:param.opacity},300);
				$(".photoAlbumSplash .imageTable").animate({opacity:"1"},300);
				$(".photoAlbumSplash .imageTable").queue( function () {
					$(this).css({opacity: "none"});
					$(this).dequeue();
				});};
		};
		return false;
		});
})
