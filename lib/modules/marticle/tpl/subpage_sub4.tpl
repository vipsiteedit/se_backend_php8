<script type="text/javascript">
    var clearfield = {};
    function switchTxt(obj, code, swtxt) {
        $(document).ready(function(){
            clearfield[code] = {
                                    obj: $(obj),
                                    clear: 0
                                };
            var txt = $(clearfield[code]['obj']).val().toString().replace(/^\s+/, '').replace(/\s+$/, '');
            if (txt.length == 0) {
                $(clearfield[code]['obj']).val(swtxt);
            } else {
                clearfield[code]['clear'] = 1;
            }
            $(clearfield[code]['obj']).bind('click', function(){
                if (clearfield[code]['clear'] == 0) {
                    $(this).val('');
                    clearfield[code]['clear'] = -1;
                }
            });
            $(clearfield[code]['obj']).bind('blur', function(){
                clearfield[code]['clear'] = $(this).val().toString().replace(/^\s+/, '').replace(/\s+$/, '').length;                
                if (clearfield[code]['clear'] == 0) {
                    $(this).val(swtxt);
                }
            });
            $(clearfield[code]['obj']).bind('keypress', function(){
                clearfield[code]['clear'] = $(this).val().toString().replace(/^\s+/, '').replace(/\s+$/, '').length;
            });
        });
    }
    function dmnIsNotEmpty(code) {
        if (clearfield[code]['clear'] == -1) {
            return $(clearfield[code]['obj']).val().toString().replace(/^\s+/, '').replace(/\s+$/, '').length;
        } else {
            return clearfield[code]['clear'];
        }
    }
</script>
