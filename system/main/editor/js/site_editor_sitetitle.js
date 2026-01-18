$('#editSiteTitleSmileForm').submit(function(){
    $(this).ajaxSubmit({target: '#siteTitle'}); 
    return false;
});