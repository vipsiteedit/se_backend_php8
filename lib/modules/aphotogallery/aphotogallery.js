var aphotogallery_execute = function(params){ 
document.getElementById('links-photo'+params.id).onclick = function (event) {
    event = event || window.event;
    var target = event.target || event.srcElement,
        link = target.src ? target.parentNode : target,
        options = {index: link, event: event, transitionSpeed: params.p20, emulateTouchEvents: params.p21, hidePageScrollbars: params.p22},
        links = this.getElementsByTagName('a');
    blueimp.Gallery(links, options);
};  
    $(document).ready(function(){
        $('#links-photo'+params.id+' .object').each(function(){
            $(this).css('height',$(this).css('width'));
        });   
    });
    $(window).resize(function(){
        $('#links-photo'+params.id+' .object').each(function(){
            $(this).css('height',$(this).css('width'));
        });  
    })
}
