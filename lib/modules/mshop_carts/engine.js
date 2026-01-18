<!-- Заполняет поле адреса адресом, а поле почтового индекса индексом, соответствующим Id адреса (юзера) -->
function jGetUserAddr(razdel, user_addr_id) {
    $.post("?jquery"+razdel,{'getUserAddr': user_addr_id}, 
    function(data){
        var dvar = data.toString();
        if ((dvar != '') && (dvar != '|')) {
            var d = dvar.split('|');
            $('.inputinfo#post_index').val(d[0]);
            $('.inputinfo#addr').val(d[1]);
        }
    }); 
}
