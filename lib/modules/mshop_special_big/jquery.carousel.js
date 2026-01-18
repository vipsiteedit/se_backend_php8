(function($){
	$.fn.Carousel = function(options) {
		var settings = {
			position: "h",
			visible: 5,
			rotateBy: 1,
			direction: true,
            speed: 1000,
			btnNext: null,
			btnPrev: null,
			auto: true,
			delay: 5,
			margin: 0,
			dirAutoSlide: false
		};
		
		return this.each(function() {
			if (options) {
				$.extend(settings, options);
			}
			var hover = false;
			var mousedown = false;
			var restart = false;
			var $this = $(this);
			var $carousel = $this.children(':first');
			var itemWidth = $carousel.children().outerWidth()+settings.margin;
			var itemHeight = $carousel.children().outerHeight()+settings.margin;
			var itemsTotal = $carousel.children().length;
			var running = false;
			var intID = null;
			var all_count = $carousel.children("li").size();
			settings.visible = parseInt(settings.visible);
			settings.rotateBy = parseInt(settings.rotateBy);
			settings.delay = parseInt(settings.delay);
			settings.speed = parseInt(settings.speed);                      
			if (settings.visible > all_count) settings.visible = all_count;
			if (settings.rotateBy > all_count) settings.rotateBy = all_count;
			if (settings.delay < 1) settings.delay = 1;
			
			var size = itemWidth; 
			if (settings.position=="v") size = itemHeight;
			if(settings.position=="v"){
				$this.css({
					'position': 'relative',
					'overflow': 'hidden',
					'height': settings.visible * size + 'px' ,
					'width': itemWidth-settings.margin
				});
			}
			else{
				$this.css({
					'position': 'relative',
					'overflow': 'hidden',
					'width': settings.visible * size + 'px' ,
					'height': itemHeight-settings.margin
				});
			}
			if(settings.position=="v"){
				$carousel.children('li').css({
					'margin-top': settings.margin/2+ 'px',
					'margin-bottom': settings.margin/2+ 'px',
					'float': 'left'
				});
			}
			else{
				$carousel.children('li').css({
					'margin-left': settings.margin/2+ 'px',
					'margin-right': settings.margin/2+ 'px',
					'float': 'left'
				});
			}					   
			
			if(settings.position=="v"){
				$carousel.css({
					'list-style': 'none',
                    'position': 'relative',
					'height': (all_count + settings.rotateBy) * size + 'px',
					'left': 0, 
					'top': 0
				});
			}
			else{
				$carousel.css({
					'list-style': 'none',
                    'position': 'relative',
					'width': (all_count + settings.rotateBy) * size + 'px',
					'top': 0,
					'left': 0
				});
			}
			
			if (all_count > 1){
            function slide(dir){
				var direction = !dir ? -1 : 1;
				var Indent = 0;
				
				window.clearInterval(intID)
                if (!running){
					running = true;
					/*
					window.clearInterval(intID);
					if (intID) {
					window.clearInterval(intID);                                      	
					}
					*/
					if (!dir){
						var orig = $carousel.children().slice(0,settings.rotateBy);
						var clone = orig.clone();
                        $carousel.children(':last').after(orig);
						$carousel.children(':first').before(clone);
					} 
					else{
						var orig = $carousel.children().slice(itemsTotal - settings.rotateBy, itemsTotal);
						var clone = orig.clone();
                        
                        $carousel.children(':first').before(orig);
						$carousel.children(':last').after(clone);
						if(settings.position=="v")
							$carousel.css('top', -size * settings.rotateBy + 'px');
						else 
							$carousel.css('left', -size * settings.rotateBy + 'px');
					}
					if(settings.position=="v")
						Indent = parseInt($carousel.css('top')) + (size * settings.rotateBy * direction);
					else
						Indent = parseInt($carousel.css('left')) + (size * settings.rotateBy * direction);
					
					if(settings.position=="v")
						var animate_data={'top': Indent};
					else
						var animate_data={'left': Indent};
					$carousel.animate(animate_data, 
						{queue: true, duration: settings.speed, complete: function(){
							if (!dir){
								$carousel.children().slice(0, settings.rotateBy).remove();
								if(settings.position=="v")
									$carousel.css('top', 0);
								else
									$carousel.css('left', 0);
							} 
							else{
								$carousel.children().slice(itemsTotal, itemsTotal + settings.rotateBy).remove();
							}
							setTime();
							running = false;
						}}
					);
				}
				return false;
			}
			
			$(settings.btnNext).click(function(){
				slide(!settings.direction);
			});
			$(settings.btnPrev).click(function(){
				slide(settings.direction);
			});
			
			$this.parent().hover(function(){
				window.clearTimeout(intID);
				hover=true;
				restart = true;
			},function(){
				if (hover){
					hover=false;
					setTime();
				}
			});
			
			$this.parent().mousedown(function(){
				window.clearTimeout(intID);
				mousedown = true;
			});
			$('*').mouseup(function(){
				if(mousedown){
					mousedown = false;
					setTime();
				}
			});
			
			function setTime(){
				if (settings.auto && !mousedown && !hover){
					intID = window.setTimeout(function(){slide(settings.dirAutoSlide);}, settings.delay * 1000);                    
				}
				else{
					window.clearInterval(intID);    
				}
				return false;
			}
			setTime();
			}

		});
	};
})(jQuery);
