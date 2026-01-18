$(document).ready(function(){  
    $("#photo a").click(function() {
        runFunction($(this).find("img"));
		function runFunction(obj) {
            var k = {ctrl:false, left:false, right:false};
			var showForm = false;
			var param = getParam(obj);
			function getParam(obj) {
				//var cN = $(obj).parents(".content").attr("className");
				return contentParam;//[cN.substring(cN.lastIndexOf(" ") + 1)];
            };
			function preLoader(c) {
                if (c == false) {
    				$(".e_shopvit .imageTable .loading").css({display: "none"});
				} else {
    				$(".e_shopvit .imageTable .imgCell img").css({opacity: 0});
    				$(".e_shopvit .imageTable .loading").css({display: "block"});
				}
            };
			function hideImg() {
				$(".e_shopvit .showImgFon").animate({opacity: 0}, 400);
				$(".e_shopvit .imageTable").animate({opacity: 0}, 400);
				$(".e_shopvit .showImgFon").queue(function() {
					$(this).next().remove();
					$(this).remove();
					$(this).dequeue();
				});
				$(document).unbind();
				showForm = false;
            };
			function navigateImgForm(obj, b) {
				var showImageNumber = parseInt($(".e_shopvit .imageTable .now").text());
				var n;
				if ((((showImageNumber + 1) > $(".e_shopvit .imageTable .all").text()) && (b == "next")) || (((showImageNumber - 1) < 1) && (b == "prev"))) {
                    return false;
                }
				if (b == "prev") {
                    n = showImageNumber - 2;
                }
				if (b == "next") {
                    n = showImageNumber;
                } 
				preLoader();
				var imageObj = $(obj).parents("#photo").find("img:eq(" + n + ")");
				$(".e_shopvit .imageTable .imgCell img").attr({src: imageObj.attr("src").replace("_prev.", ".") , alt: imageObj.attr("alt") , title: imageObj.attr("title")});
				$(".e_shopvit .imageTable .objectTitle").html(getTitle(imageObj));
				if (b == "prev") {
                    $(".e_shopvit .imageTable .now").text(parseInt(showImageNumber - 1));
                }
				if (b == "next") {
                    $(".e_shopvit .imageTable .now").text(parseInt(showImageNumber + 1));
                }
				showImageNumber = parseInt($(".e_shopvit .imageTable .now").text());
				if (showImageNumber == 1) {
                    $(".e_shopvit .imageTable .prev").css({visibility:"hidden"});
                } else {
                    $(".e_shopvit .imageTable .prev").css({visibility:"visible"});
                }
				if (showImageNumber == $(".e_shopvit .imageTable .all").text()) {
                    $(".e_shopvit .imageTable .next").css({visibility:"hidden"});
                } else {
                    $(".e_shopvit .imageTable .next").css({visibility:"visible"});
                }
			};
			function animateImgForm(w, h, hide) {
				var gr = 40;
				function a(w, h) {
					var hF = $(".e_shopvit .imageTable").attr("offsetHeight") - $(".e_shopvit .imageTable .imgCell img").attr("offsetHeight");
					var wF = $(".e_shopvit .imageTable").attr("offsetWidth") - $(".e_shopvit .imageTable .imgCell img").attr("offsetWidth");
					function animate(wA, hA) {
						if (hide == "hide") {
                            $(".e_shopvit .imageTable .imgCell img").animate({height: hA, width: wA}, 300);
                        }
						$(".e_shopvit .imageTable .imgCell img").animate({height: hA, width: wA}, 300); 
						$(".e_shopvit .imageTable").animate({marginLeft: Math.round(-(wA + wF) / 2)}, 300, function() {
                            if (hide != "hide") {
                                $(this).find(".imgCell img").animate({opacity:1}, 300);
                            }
                        });
                    };
					function resizeScreen(imW, imH) {
						if ((brW / brH) > (imW / imH)) {
							var imgW = Math.round(w * (brH - hF - gr)) / h;
							h = brH - hF - gr;
							animate(imgW, (brH - hF - gr));
						} else {
							h = Math.round(h * (brW - wF - gr)) / w;
							animate((brW - wF - gr), h);
						}
                    };
					var brH = $("html").attr("offsetHeight");
					var brW = $("html").attr("offsetWidth");
					//alert('((' + wF + ' + ' + w + ') > ' + brW + ') || ((' + hF + ' + ' + h + ') > ' + brH + ')');
					if (((wF + w) > brW) || ((hF + h) > brH)) { 
						resizeScreen(wF + w, hF + h);
                    } else {
                        animate(w, h);
                    }
					var t = Math.round($($.browser.webkit ? "body" : "html").attr("scrollTop") + ($("html").attr("clientHeight") / 2 - (h + hF) / 2));
					if (t < 0) {
						t = $($.browser.webkit ? "body" : "html").attr("scrollTop") + 5;
					}
					$(".e_shopvit .imageTable").animate({top: t}, 600);
				};
				if ((w > 0) || (h > 0)) {
                    a(w, h);
				} else {
					var tI = new Image();
					tI.src = $(".e_shopvit .imageTable .imgCell img").attr("src");
					function setImg() {
					   if ((tI.width > 0)||(tI.height > 0)) {
							a(tI.width, tI.height); 
                            clearInterval(timer);
					   }
					};
					var timer = setInterval(setImg, 100);
				}
            };
			function getNumberText(obj, caseObj) {
				var objArray = $(obj).parents("#photo").find("img");//.parents(".content").find(".obj .photoLink img");
				if (caseObj == "all") {
                    return objArray.length;
                } else {
					for (i = 0; i < objArray.length; i++) {
						if (objArray[i] == obj[0]) {
							return i + 1;
//							break;
						}
                    }
                }
            };
			function getTitle(obj) {
				return $(obj).attr('title');//parents(".obj").find(".textLink").html();
            }
			if ($(".e_shopvit .imageTable").length == 0) {
				showForm = true;
				$(obj).parents(".content").append("<div class=\" showImgFon \" style=\"display:none; position: absolute; top: 0px; left: 0px;\"></div>"+
							"<table class=\"imageTable\" style=\"display:none; margin-left: -25%; position: absolute; top: 0px; left: 50%; \" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">"+
							"<tbody><tr class=\"top\"><td class=\"left\"></td><td class=\"center\"></td><td class=\"rigth\"></td></tr>"+
							"<tr class=\"topImg\"><td class=\"left\"></td><td class=\"center\"><div><h4 class=\"objectTitle\">&nbsp;</h4></div><div class=\"hideImage\">" + param.close + "</div></td>"+
							"<td class=\"rigth\"></td></tr>"+
							"<tr class=\"center\"><td class=\"left\"></td><td class=\"imgCell\"><div style=\"display:none;\" class=\"loading\">" + param.loading + "</div><img style=\"width:0; height:0;\" alt=\"\" title=\"\" src=\"\" border=\"0\">"+
							"</td><td class=\"rigth\"></td></tr>"+
							"<tr class=\"bottomImg\"><td class=\"left\"></td><td class=\"center\"><div class=\"text\">" + param.textImage + "<span class=\"now\"></span>" + param.textOf + "<span class=\"all\"></span></div>"+
							"<div class=\"navigPanel\"><div class=\"prev\">" + param.prev + "</div><div class=\"next\">" + param.next + "</div></div></td><td class=\"rigth\"></td></tr>"+
							"<tr class=\"bottom\"><td class=\"left\"></td><td class=\"center\"></td><td class=\"rigth\"></td></tr></tbody></table>");
				$(".e_shopvit .showImgFon , .e_shopvit .imageTable").css({opacity:"0", display: "block"});
				$(".e_shopvit .showImgFon").css({height : function() {
                    if ($.browser.opera || $.browser.webkit || (($.browser.msie) && ($.browser.version == '7.0'))) {
                        if ($("html").attr("clientHeight") >= $("html").attr("scrollHeight")) {
                            return $("html").attr("clientHeight");
                        } else {
                            return $("html").attr("scrollHeight");
                        }
                    } else {
                        return $("html").attr("scrollHeight");
                    }
                }});
				animateImgForm(300, 200, "hide");
			};
			preLoader();
			if (showForm) {
                $(".e_shopvit .imageTable .imgCell img").load(function() {
                    preLoader(false); 
					animateImgForm();
                });
            }
			if (showForm) {
                $(".e_shopvit .imageTable .hideImage").click(function() {
                    hideImg();
                });
            }
			if (showForm) {
                $(".e_shopvit .imageTable .prev").click(function() {
                    navigateImgForm(obj, "prev");
                });
            }
			if (showForm) {
                $(".e_shopvit .imageTable .next").click(function() {
                    navigateImgForm(obj, "next");
                });
            }
			if (showForm) {
                $(".e_shopvit .imageTable .imgCell img").click(function() {
                    navigateImgForm(obj, "next");
                });
            }
			if (showForm) {
                $(".e_shopvit .showImgFon").click(function() {
                    hideImg();
                });
            }
            if (showForm) { 
                $(document).keydown(function(event) {
                    if (event.keyCode == 17) {
                        k.ctrl = true;
                    }
                    if (event.keyCode == 37) {
                        k.left = true;
                    }
                    if (event.keyCode == 39) {
                        k.right = true;
                    }
                    if (k.ctrl && k.left) {
                        navigateImgForm(obj, "prev");
                    }
                    if (k.ctrl && k.right) {
                        navigateImgForm(obj, "next");
                    }
                });
            }  
            if (showForm) {
                $(document).keyup(function(event){
                    if(event.keyCode == 17) {
                        k.ctrl = false;
                    }
                    if(event.keyCode == 37) {
                        k.left = false;
                    }
                    if(event.keyCode == 39) {
                        k.right = false;
                    }
                    if(event.keyCode == 27) {
                        hideImg();
                    }
                });
            }
			$(".e_shopvit .imageTable .imgCell img").attr({alt: $(obj).attr("alt") , title: $(obj).attr("title") , src: $(obj).attr("src").replace("_prev.", ".")});
			$(".e_shopvit .imageTable .objectTitle").html(getTitle(obj));
			$(".imageTable .now").text(getNumberText(obj));
			if ($(".e_shopvit .imageTable .now").text() == "1") {
                $(".e_shopvit .imageTable .prev").css({visibility:"hidden"});
            } else {
                $(".e_shopvit .imageTable .prev").css({visibility:"visible"});
            };
			$(".e_shopvit .imageTable .all").text(getNumberText(obj, "all"));

			if ($(".e_shopvit .imageTable .now").text() == getNumberText(obj, "all")) {
                $(".e_shopvit .imageTable .next").css({visibility:"hidden"});
            } else {
                $(".e_shopvit .imageTable .next").css({visibility:"visible"});
            };
			if (showForm) {
				$(".e_shopvit .showImgFon").animate({opacity:param.opacity}, 300);
				$(".e_shopvit .imageTable").animate({opacity:"1"}, 300);
				$(".e_shopvit .imageTable").queue(function() {
					$(this).css({opacity: "none"});
					$(this).dequeue();
				});
            };
		};
		return false;
    });
})
