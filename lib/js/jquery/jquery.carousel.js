(function($){
	$.fn.Carousel = function(options) {
		var settings = {
			position: 'h',
			visible: 5,
			rotateAuto: 1,
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
			var hover = false,
				mousedown = false,
				restart = false,
				$this = $(this),
				$carousel = $this.children(':first'),
				itemWidth = $carousel.children().outerWidth()+settings.margin,
				itemHeight = $carousel.children().outerHeight()+settings.margin,
				itemsTotal = $carousel.children().length,
				running = false,
				intID = null,
				all_count = $carousel.children('li').size();
			
			settings.visible = parseInt(settings.visible);
			settings.rotateBy = parseInt(settings.rotateBy);
			settings.rotateAuto = parseInt(settings.rotateAuto);
			settings.delay = parseInt(settings.delay);
			settings.speed = parseInt(settings.speed);                      
			if (settings.visible > all_count) settings.visible = all_count;
			if (settings.rotateBy > all_count) settings.rotateBy = all_count;
			if (settings.rotateAuto > all_count) settings.rotateAuto = all_count;
			if (settings.delay < 1) settings.delay = 1;
			
			var size = (settings.position=='v') ? itemHeight : itemWidth; 
			
			$this.css({
				'position': 'relative',
				'overflow': 'hidden'
			})
			$carousel.css({
				'list-style': 'none',
				'position': 'relative',
				'left': 0, 
				'top': 0
			})
			function resamler() {
				itemWidth = $carousel.children().outerWidth()+settings.margin;
				itemHeight = $carousel.children().outerHeight()+settings.margin;
				size = (settings.position=="v") ? itemHeight : itemWidth;
				
				if(settings.position=="v"){
					$this.css({
						'height': settings.visible * size + 'px' ,
						'width': itemWidth-settings.margin
					});
				}
				else{
					$this.css({
						'width': settings.visible * size + 'px' ,
						'height': itemHeight-settings.margin
					});
				}
				
				if(settings.position=="v"){
					$carousel.css({
						'height': (all_count + settings.rotateBy) * size + 'px',
					});
				}
				else{
					$carousel.css({
						'width': (all_count + settings.rotateBy) * size + 'px',
					});
				}
			}
			resamler();
			
			$this.find('img').load(resamler);
            
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
			
			if (all_count > 1){
            function slide(dir, auto){
				var direction = !dir ? -1 : 1;
				var Indent = 0;
				var Rotate = settings.rotateBy;
				if (auto == true) Rotate = settings.rotateAuto;
				
				window.clearInterval(intID)
                if (!running){
					running = true;
					if (!dir){
						var orig = $carousel.children().slice(0,Rotate);
						var clone = orig.clone();
                        $carousel.children(':last').after(orig);
						$carousel.children(':first').before(clone);
					} 
					else{
						var orig = $carousel.children().slice(itemsTotal - Rotate, itemsTotal);
						var clone = orig.clone();
                        
                        $carousel.children(':first').before(orig);
						$carousel.children(':last').after(clone);
						if(settings.position=="v")
							$carousel.css('top', -size * Rotate + 'px');
						else 
							$carousel.css('left', -size * Rotate + 'px');
					}
					if(settings.position=="v")
						Indent = parseInt($carousel.css('top')) + (size * Rotate * direction);
					else
						Indent = parseInt($carousel.css('left')) + (size * Rotate * direction);
					

					if(settings.position=="v")
						var animate_data={'top': Indent};
					else
						var animate_data={'left': Indent};
					$carousel.animate(animate_data, 
						{queue: true, duration: settings.speed, complete: function(){
							if (!dir){
								$carousel.children().slice(0, Rotate).remove();
								if(settings.position=="v")
									$carousel.css('top', 0);
								else
									$carousel.css('left', 0);
							} 
							else{
								$carousel.children().slice(itemsTotal, itemsTotal + Rotate).remove();
							}
							var callback = settings.itemFirstCallback;
							if (callback && typeof(callback) === "function") {
								var itt = $carousel.children('li');
								callback($('.carouseleitem', itt).val());
							}

							setTime();
							running = false;
						}}
					);
				}
				return false;
			}
			
			$(settings.btnNext).click(function(){
				slide(!settings.direction, false);
			});
			$(settings.btnPrev).click(function(){
				slide(settings.direction, false);
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
					intID = window.setTimeout(function(){slide(settings.dirAutoSlide, true);}, settings.delay * 1000);                    
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