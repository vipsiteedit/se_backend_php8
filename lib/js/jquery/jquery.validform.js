/**
 * @author charnad
 * @version 1.0
 * @description Проверяет форму на заполненность
 * @param {Array} options Содержит опции валидации
 */
jQuery.fn.validForm = function(userOptions) {

    var options = {
    	errorContainer: "#errorList",
    	errorContainerCssClass: {
    		'text-color': "#F00"
    	}
    }

    $.extend(options, userOptions);
    /**
     * @description Если в форме инпутов больше, чем 0, то валидируем ее
     */

    $('head').append("<style>.inputError{border: 2px solid #F00; background: #FAA;}</style>");

    $(this).each( function() {
    	var id = "#" + $(this).attr('id');
        if ($(id + " input").length > 0) {
            Validate(id);
        } else {
        	return false;
        }
    });

    /**
     * @argument {$} form Форма для валидации
     */
    function Validate(form) {

        $(form + " input[@type=submit]").click(function() {

        /**
         * @type int
         * @description Количество ошибок
         * @default 0
         */
        var errorAmount = 0;

        var collection = $(form + " input[@type=text]")
            .add(form +" input[@type=password]")
            .add(form +" input[@type=file]")
            .add(form +" textarea")
            .add(form +" select");

        collection.each(function() {
         	    var element = $(this);
         		var length = $.trim(element.attr('value'));

         		if (!length) {
         			element.addClass('inputError');
         			errorAmount++;
         		}
         		else {
                    element.removeClass('inputError');
         		}
     	});

     	collection.keypress( function (){
            $(this).removeClass('inputError');
        });

        collection.change( function() {
            if (($(this).attr('value')).length < 1) {
                $(this).addClass('inputError');
            }
            else {
            	$(this).removeClass('inputError');
            }
        });




        if (errorAmount) {
        	$(options.errorContainer).html('Заполните пожалуйста правильно выделенные поля');
        	$(options.errorContainer).show();
            return false;
        } else {
        	$(options.errorContainer).hide();
        }
        });
    }

}