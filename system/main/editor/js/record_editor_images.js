
function addPhotos(hide, sect_id){
    var linkhide = 'none', blockhide = 'block';
    if(hide == false) {
         linkhide = 'block', blockhide = 'none';
    }
    $('.editorLinkAddPhotos.block'+sect_id).css('display', linkhide);
    $('.editorRecordsPhotos.block'+sect_id).css('display', blockhide);
}