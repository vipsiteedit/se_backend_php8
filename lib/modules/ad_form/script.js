$(document).ready(function(){
	$(".popup_text .objectTitle")
		.on("click", function(e){

		var popup = $("<div class='popup_text_modal_window' />");
		var close_btn = $("<div class='close_btn' />");
		var popup_window = $("<div class='popup_window' />");
        var popup_wrapper = $("<div class='popup_wrapper' />");
		var popup_info = $(this).parent().find("xmp").html();
		
		popup
			.append(
				popup_window
                    .append(
                       popup_wrapper
					       .append(
		      			     	close_btn,
	       			     		popup_info
    				    	)
                    )
			)
			.appendTo("body");

		close_btn.add(popup, popup_window).on("click", function(){
			$("body > .popup_text_modal_window").remove();
			$("body").removeClass("popup_text_window_show");
		});
        
        $(popup_wrapper).on("click", function(e){
            e.stopPropagation();
        });

		$("body").addClass("popup_text_window_show");
        
        return false;
	});
});
