
		$('div.groupWrapper').sortable(
			{
				connectWith: "div.groupWrapper",
				handle: "div.group-header",
				items: "div.groupItem",
				stop: function(event, ui){
					processStop(this);
				}
			}
		).disableSelection();

		$('.recordsWrapper').sortable(
			{
				connectWith: ".recordsWrapper",
				handle: ".itemRecordHeader",
				items: ".object",
				stop: function(event, ui){
				
					//alert(this.id);
					processStopRec(this.id);
					//"recordsWrapper sortgroup-1
					//processStop(this);
				}
			}
		).disableSelection();



		$( ".groupItem" ).addClass( "ui-widget ui-widget-content ui-helper-clearfix ui-corner-all" )
			.find( ".group-header" )
			.addClass( "ui-widget-header ui-corner-all" )
			.prepend( "<span class='ui-icon ui-icon-minusthick'></span>")
			.end()
			.find( ".group-content" );
