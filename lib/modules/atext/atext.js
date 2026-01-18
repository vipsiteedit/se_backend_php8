var atext_execute = function(params){ 
    var inptxt = $(".cont-text.show .content-title span").html(); 
    new Ya.share({
        'element': 'ya_share1',
        'elementStyle': {
            'type': 'button',
            'linkIcon': true,   
            'border': false,
            'quickServices': ['facebook', 'twitter', 'vkontakte', 'moimir', 'yaru', 'odnoklassniki', 'lj']
        },
        'popupStyle': {
            'copyPasteField': true
        },
        'description': inptxt,
        onready: function(ins){
            $(ins._block).find(".b-share").append("<a href=\"http://siteedit.ru?rf="+params.p6+"\" class=\"b-share__handle b-share__link\" title=\"SiteEdit\" target=\"_blank\"  rel=\"nofollow\"><span class=\"b-share-icon\" style=\"background:url('http://siteedit.ru/se/skin/siteedit_icon_16x16.png') no-repeat;\"></span></a>");                    
        }
    });
}
