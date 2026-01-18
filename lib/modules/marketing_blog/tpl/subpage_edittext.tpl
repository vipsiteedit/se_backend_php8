<header:css>
        <style type="text/css">
            #tabs2 li .ui-icon-close {
                float: left;
                margin: 0.4em 0.2em 0 0;
                cursor: pointer;
            }
            #add_tab, #uploadVideo {
                cursor: pointer;
            }
            .obj.anons .nums {position:absolute;margin-top:-14px;margin-left:3px;font-size:12px;color:#646464}
        </style>
    <?php if($section->parametrs->param13=='Y'): ?><link rel="stylesheet" href="[module_url]tinymce.css"> <?php endif; ?>
    [lnk:ui/css/ui-bootstrap/jquery-ui-1.10.3.custom.css]
</header:css>
<header:js>
    [js:jquery/jquery.min.js]
    <script type="text/javascript" src="[module_url]engine.js"></script>
    [js:ui/jquery.ui.min.js]
    <?php if($section->parametrs->param13=='Y'): ?> [js:tinymce/tinymce.min.js]<?php endif; ?>
    [js:ui/i18n/jquery-ui-i18n.js]
    <?php if($section->parametrs->param6=='tabs'): ?>
    <?php endif; ?>
</header:js>
    <script type='text/javascript'>
    $(window).load(function(){
    
    //  смайлики текста
        $(".obj.anons").find(".smile_list_fetch").on('click', function(){
            smile = $(this).attr("data-abbr");
            insert_text_cursor('anons',smile);
        });
        
    //  смайлики заголовка
        $(".obj.titlzag").find(".smile_list_fetch").on('click', function(){
            smile = $(this).attr("data-abbr");
            insert_text_cursor('title',smile);
        });
        function insert_text_cursor(_obj_name, _text) {
            var area=document.getElementsByName(_obj_name).item(0);
            if ((area.selectionStart)||(area.selectionStart=='0')) {
                var p_start=area.selectionStart;
                var p_end=area.selectionEnd;
                area.value=area.value.substring(0,p_start)+_text+area.value.substring(p_end,area.value.length);
            }
            if (document.selection) {
                area.focus();
                sel=document.selection.createRange();
                sel.text=_text;
            }
        }
        <?php if($section->parametrs->param13=='Y'): ?>
        tinymce.PluginManager.add("emotion",
                function(t,e){
                    function n(){
                        var t;
                        return t='<div style="overflow: scroll; height: 300px;" >',
                                tinymce.each(i,function(n){
                                    t+='<span class="emotion_span"  style="height: 18px; cursor: pointer; display: block;">',
                                            tinymce.each(n,function(n){
                                                var i="/lib/emotion/"+n;
                                                t+="<a href='#' data-mce-url='"+i+"' tabindex='-1'><img src='"+i+"'  style='width: 16px; height: 16px;'></a>"}),
                                            t+="</span>"}),
                                t+="</div>"
                    }
                    var i=[["D83DDE0A.png","D83DDE03.png","D83DDE09.png","D83DDE06.png","D83DDE1C.png","D83DDE0B.png","D83DDE0D.png","D83DDE0E.png","D83DDE12.png","D83DDE0F.png","D83DDE14.png","D83DDE22.png","D83DDE2D.png"],["D83DDE29.png","D83DDE28.png","D83DDE10.png","D83DDE0C.png","D83DDE04.png","D83DDE07.png","D83DDE30.png","D83DDE32.png","D83DDE33.png","D83DDE37.png","D83DDE02.png","D83DDE1A.png","D83DDE15.png"],["D83DDE2F.png","D83DDE26.png","D83DDE20.png","D83DDE21.png","D83DDE1D.png","D83DDE34.png","D83DDE18.png","D83DDE1F.png","D83DDE2C.png","D83DDE36.png","D83DDE2A.png","D83DDE2B.png","263A.png"],["D83DDE00.png","D83DDE25.png","D83DDE1B.png","D83DDE16.png","D83DDE24.png","D83DDE23.png","D83DDE27.png","D83DDE11.png","D83DDE05.png","D83DDE2E.png","D83DDE1E.png","D83DDE19.png","D83DDE01.png"],["D83DDE31.png","D83DDE08.png","D83DDC7F.png","D83DDC7D.png","D83DDCA9.png","D83DDC7A.png","D83CDFAA.png","D83DDE38.png","D83DDE39.png","D83DDE3C.png","D83DDE3D.png","D83DDE3E.png","D83DDE3F.png"],["D83DDE3B.png","D83DDE40.png","D83DDE3A.png","D83CDFAD.png","D83DDC7B.png","D83DDCAC.png","D83DDE46.png","D83DDE47.png","D83DDCA4.png","D83DDC4D.png","D83DDC4E.png","261D.png","270C.png"],["D83DDC4C.png","D83DDC4F.png","D83DDC4A.png","270B.png","D83DDE4F.png","D83DDC46.png","D83DDC47.png","D83DDC48.png","D83DDCAA.png","270A.png","D83DDC4B.png","D83DDC50.png","D83DDC81.png"],["D83DDE45.png","D83DDE4B.png","D83DDE4C.png","D83DDC49.png","D83DDC28.png","D83DDC0E.png","D83DDC0F.png","D83DDC1C.png","D83DDC2B.png","D83DDC2E.png","D83DDC3B.png","D83DDC3C.png","D83DDC05.png"],["D83DDC36.png","D83DDC31.png","D83DDC37.png","D83DDC11.png","D83DDC01.png","D83DDC02.png","D83DDC04.png","D83DDC07.png","D83DDC08.png","D83DDC0A.png","D83DDC0B.png","D83DDC0C.png","D83DDC0D.png"],["D83DDC10.png","D83DDC12.png","D83DDC14.png","D83DDC15.png","D83DDC16.png","D83DDC17.png","D83DDC19.png","D83DDC1B.png","D83DDC1D.png","D83DDC1E.png","D83DDC1F.png","D83DDC20.png","D83DDC21.png"],["D83DDC22.png","D83DDC23.png","D83DDC24.png","D83DDC25.png","D83DDC26.png","D83DDC27.png","D83DDC29.png","D83DDC2A.png","D83DDC2C.png","D83DDC2D.png","D83DDC2F.png","D83DDC30.png","D83DDC32.png"],["D83DDC33.png","D83DDC34.png","D83DDC35.png","D83DDC38.png","D83DDC39.png","D83DDC3A.png","D83DDC3E.png","D83DDE48.png","D83DDE49.png","D83DDE4A.png","D83CDF39.png","D83CDF84.png","D83CDF3B.png"],["D83CDF3C.png","D83CDF37.png","D83CDF38.png","D83CDF32.png","D83CDF33.png","D83CDF34.png","D83CDF35.png","D83CDF3E.png","D83CDF40.png","D83CDF41.png","D83CDF42.png","D83CDF43.png","D83DDC90.png"],["D83CDF4A.png","D83CDF7A.png","D83CDF7B.png","D83CDF45.png","D83CDF52.png","D83CDF82.png","D83CDF3D.png","D83CDF4B.png","D83CDF4D.png","D83CDF4E.png","D83CDF4F.png","D83CDF6D.png","D83CDF46.png"],["D83CDF49.png","D83CDF50.png","D83CDF51.png","D83CDF53.png","D83CDF54.png","D83CDF55.png","D83CDF56.png","D83CDF57.png","D83CDF69.png","2615.png","D83CDF30.png","D83CDF44.png","D83CDF47.png"],["D83CDF48.png","D83CDF5A.png","D83CDF5B.png","D83CDF5C.png","D83CDF5D.png","D83CDF5E.png","D83CDF5F.png","D83CDF62.png","D83CDF63.png","D83CDF65.png","D83CDF66.png","D83CDF68.png","D83CDF6A.png"],["D83CDF6B.png","D83CDF6C.png","D83CDF6E.png","D83CDF6F.png","D83CDF70.png","D83CDF71.png","D83CDF72.png","D83CDF73.png","D83CDF74.png","D83CDF75.png","D83CDF76.png","D83CDF79.png","D83CDF7C.png"],["D83CDF8F.png","D83CDFA3.png","2668.png","26F5.png","2708.png","D83DDE81.png","D83DDE82.png","D83DDE83.png","D83DDE86.png","D83DDE8C.png","D83DDE8D.png","D83DDE91.png","D83DDE92.png"],["D83DDE93.png","D83DDE94.png","D83DDE95.png","D83DDE96.png","D83DDE97.png","D83DDE98.png","D83DDE99.png","D83DDE9A.png","D83DDE9C.png","D83DDE9D.png","D83DDE9E.png","D83DDEA1.png","D83DDEA3.png"],["D83DDEA4.png","2693.png","D83DDE89.png","D83DDE8B.png","D83DDEB2.png","D83CDF02.png","D83CDF80.png","D83CDF92.png","D83CDF93.png","D83CDFA9.png","D83CDFBD.png","D83DDC51.png","D83DDC52.png"],["D83DDC53.png","D83DDC54.png","D83DDC55.png","D83DDC56.png","D83DDC57.png","D83DDC58.png","D83DDC59.png","D83DDC5A.png","D83DDC5B.png","D83DDC5C.png","D83DDC5D.png","D83DDC5E.png","D83DDC5F.png"],["D83DDC60.png","D83DDC61.png","D83DDC62.png","D83DDCBC.png","26BD.png","D83CDFC1.png","D83CDFC6.png","26BE.png","D83CDFBE.png","D83CDFC0.png","26F3.png","D83CDFBF.png","D83CDFC2.png"],["D83CDFC4.png","D83CDFC7.png","D83CDFC8.png","D83CDFC9.png","D83CDFCA.png","D83DDEB4.png","D83DDEB5.png","D83CDFB7.png","D83CDFB8.png","D83CDFA4.png","D83CDFA7.png","D83CDFB9.png","D83CDFBA.png"],["D83CDFBB.png","D83DDCEF.png","D83DDD14.png","D83CDFB5.png","D83CDFB6.png","D83CDFBC.png","D83DDCE3.png","D83DDCF6.png","D83DDD07.png","D83DDD08.png","D83DDD09.png","D83DDD0A.png","D83DDD15.png"],["D83DDE0D.png","D83DDE33.png","2764.png","D83DDE1A.png","D83DDE18.png","D83DDE19.png","D83DDC8B.png","D83DDC94.png","D83DDE3B.png","D83DDC8C.png","D83DDC8D.png","D83DDC8E.png","D83DDC93.png"],["D83DDC95.png","D83DDC96.png","D83DDC97.png","D83DDC98.png","D83DDC99.png","D83DDC9A.png","D83DDC9B.png","D83DDC9C.png","D83DDC9D.png","D83DDC9E.png","D83DDC9F.png","D83DDE17.png","D83CDF77.png"],["D83CDF78.png","D83CDF85.png","D83DDC7A.png","D83CDF1F.png","D83CDF81.png","D83CDF82.png","D83CDF84.png","D83CDF83.png","D83CDF88.png","D83CDF89.png","D83CDF8A.png","23F0.png","260E.png"],["D83DDCA1.png","D83DDCBB.png","D83DDCC7.png","D83DDCDF.png","D83DDCE0.png","D83DDCE1.png","D83DDCE2.png","D83DDCF1.png","D83DDCF7.png","D83DDCF9.png","D83DDCFA.png","D83DDCFB.png","D83DDD26.png"],["D83DDD27.png","D83DDD28.png","D83DDD29.png","D83DDD2C.png","231A.png","231B.png","D83CDFA5.png","D83DDCDE.png","D83DDD0B.png","D83DDD0C.png","D83DDD1E.png","267B.png","26A0.png"],["26D4.png","D83CDD98.png","D83DDEA7.png","00A9.png","00AE.png","203C.png","2049.png","2122.png","2139.png","24C2.png","2611.png","267F.png","2705.png"],["2714.png","2716.png","274C.png","274E.png","2753.png","2757.png","2795.png","2796.png","2797.png","D83CDD7F.png","D83DDCB2.png","D83DDCF5.png","D83DDEAB.png"],["D83DDEAD.png","D83DDEAE.png","D83DDEAF.png","D83DDEB0.png","D83DDEB1.png","D83DDEB3.png","D83DDEB7.png","D83DDEB8.png","D83DDEB9.png","D83DDEBA.png","D83DDEBB.png","D83DDEBC.png","D83DDEBE.png"],["003020E3.png","003120E3.png","003220E3.png","003320E3.png","003420E3.png","003520E3.png","003620E3.png","003720E3.png","003820E3.png","003920E3.png","D83DDD1F.png","D83DDCAF.png","2194.png"],["2195.png","2196.png","2197.png","2198.png","2199.png","21A9.png","21AA.png","23E9.png","23EA.png","23EB.png","23EC.png","25B6.png","25C0.png"],["27A1.png","2934.png","2935.png","2B05.png","2B06.png","2B07.png","D83DDD00.png","D83DDD01.png","D83DDD02.png","D83DDD03.png","D83DDD04.png","D83DDD19.png","D83DDD1A.png"],["D83DDD1B.png","D83DDD1C.png","D83DDD1D.png","D83DDD3C.png","D83DDD3D.png","2648.png","2649.png","264A.png","264B.png","264C.png","264D.png","264E.png","264F.png"],["2650.png","2651.png","2652.png","2653.png","26CE.png","2702.png","2709.png","270F.png","2712.png","D83DDC8C.png","D83DDCB0.png","D83DDCB3.png","D83DDCB4.png"],["D83DDCB5.png","D83DDCB6.png","D83DDCB7.png","D83DDCB8.png","D83DDCBD.png","D83DDCBE.png","D83DDCBF.png","D83DDCC4.png","D83DDCC5.png","D83DDCC8.png","D83DDCC9.png","D83DDCCA.png","D83DDCCB.png"],["D83DDCCC.png","D83DDCCD.png","D83DDCCE.png","D83DDCD0.png","D83DDCD1.png","D83DDCD2.png","D83DDCD3.png","D83DDCD4.png","D83DDCD5.png","D83DDCD6.png","D83DDCD7.png","D83DDCD8.png","D83DDCD9.png"],["D83DDCDA.png","D83DDCDC.png","D83DDCDD.png","D83DDCE6.png","D83DDCF0.png","D83DDD16.png","D83DDCC0.png","D83DDCC1.png","D83DDCC2.png","D83DDCC3.png","D83DDCC6.png","D83DDCE4.png","D83DDCE5.png"],["D83DDCE7.png","D83DDCE8.png","D83DDCE9.png","D83DDD17.png","D83CDDE8D83CDDF3.png","D83CDDE9D83CDDEA.png","D83CDDEAD83CDDF8.png","D83CDDEBD83CDDF7.png","D83CDDECD83CDDE7.png","D83CDDEED83CDDF9.png","D83CDDEFD83CDDF5.png","D83CDDF0D83CDDF7.png","D83CDDF7D83CDDFA.png"],["D83CDDFAD83CDDF8.png","D83DDD30.png","D83CDF8C.png","D83CDFC3.png","D83DDC66.png","D83DDC67.png","D83DDC68.png","D83DDC69.png","D83DDC6A.png","D83DDC6B.png","D83DDC6C.png","D83DDC6E.png","D83DDC6F.png"],["D83DDC70.png","D83DDC71.png","D83DDC72.png","D83DDC73.png","D83DDC74.png","D83DDC75.png","D83DDC76.png","D83DDC77.png","D83DDC78.png","D83DDC7C.png","D83DDC82.png","D83DDC83.png","D83DDC8F.png"],["D83DDC91.png","D83DDEB6.png","D83CDF1B.png","D83CDF1D.png","D83CDF1E.png","D83DDD2D.png","D83DDE80.png","2B50.png","D83CDF0F.png","D83CDF11.png","D83CDF12.png","D83CDF13.png","D83CDF14.png"],["D83CDF15.png","D83CDF16.png","D83CDF17.png","D83CDF18.png","D83CDF19.png","D83CDF1A.png","D83CDF1C.png","D83DDCAB.png","D83CDFAA.png","D83CDFE6.png","26EA.png","D83DDC92.png","D83CDFE0.png"],["D83CDFE1.png","D83CDFE2.png","D83CDFE3.png","D83CDFE4.png","D83CDFE5.png","D83CDFE8.png","D83CDFE9.png","D83CDFEA.png","D83CDFEB.png","D83CDFEC.png","2744.png","D83DDCA6.png","26C5.png"],["2600.png","2601.png","26A1.png","2728.png","D83DDCA7.png","D83DDCA8.png","D83DDD06.png","2614.png","D83DDD05.png","D83CDFB1.png","D83CDFB2.png","D83CDCCF.png","D83CDFAF.png"],["D83CDFB0.png","D83CDFB3.png","2660.png","2663.png","2665.png","2666.png","D83CDFAE.png","D83CDFB4.png","26F2.png","26FA.png","D83CDF01.png","D83CDF03.png","D83CDF04.png"],["D83CDF05.png","D83CDF06.png","D83CDF07.png","D83CDF08.png","D83CDF09.png","D83CDF0A.png","D83CDF0B.png","D83CDF0C.png","D83CDF20.png","D83CDF86.png","D83CDF87.png","D83CDF91.png","D83CDFA0.png"],["D83CDFA1.png","D83CDFA2.png","D83CDFED.png","D83CDFEF.png","D83CDFF0.png","D83DDDFB.png","D83DDDFC.png","D83DDDFD.png","D83DDEA2.png"]];
                    t.addButton("emotion", {
                                //text: "Emotion",
                                icon: "emoticons",
                                type:"panelbutton",
                                panel:{
                                    autohide:0,
                                    html:n,
                                    onclick:function(e){
                                        var n=t.dom.getParent(e.target,"a");
                                        n&&(t.insertContent('<img src="'+n.getAttribute("data-mce-url")+'" />'),this.hide())
                                    }
                                },
                                tooltip:"emotion"
                            }
                    )
                }
        );
            tinymce.init({
                selector: "textarea.old",
                document_base_url: "/",
                //content_css: "/system/main/editor/tiny.css",
                safari_warning : false,
                remove_script_host : false,
                convert_urls : false,
                theme : "modern",
                forced_root_block : false,
                menubar : false,
                browser_spellcheck : true,
                language: "ru",
                convert_fonts_to_spans : true,
                toolbar: "undo redo pastetext | bold italic underline | alignleft aligncenter alignright alignjustify | "+
                 " bullist numlist outdent indent | link unlink | table blockquote | removeformat fullscreen code | emotion",
                plugins: "link image paste code fullscreen media table emotion",
                link_list: "<?php echo seMultiDir()."/".$_page."/" ?>?getpagelist",
                image_advtab: true,

                external_plugins: { "filemanager" : "/admin/filemanager/plugin.js" },
                external_filemanager_path: "<?php echo seMultiDir()."/".$_page."/" ?>"

            });  
            
    
        <?php endif; ?>    
        <?php if($section->parametrs->param6=='text'): ?> $( "#imgUploadFiles" ).sortable(); <?php endif; ?>
        $.datepicker.setDefaults(
            $.extend($.datepicker.regional["ru"])
        );    
        $(".blogposts #dateinp").datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: 'yy-mm-dd',
                yearRange: '2014:2020',
                showOn: 'focus',
                showOtherMonths: true,
                monthNamesShort: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
                defaultDate: new Date()
        }); 
<?php if($section->parametrs->param6=='text'): ?>            
//  добавление видео
        $('#uploadVideo').on('click', function(){
            var tpl = '<li class="imgUploadFiles delFile[%foto.num%]">'
                            +'<div class="uploadFileDel" data-source="[%foto.num%]"><?php echo $section->language->lang025 ?></div>'
                            +'<div class="uploadFilesArea inpArea">'
                                +'<input type="text" name="uploadFiles[]" value="" alt="" required >'
                            +'</div>'
                            +'<div class="forAlt">'
                                +'<span class="titleImgAlt"><?php echo $section->language->lang064 ?></span>'
                                +'<input type="hidden" name="uploadFilesAlt[]" value="" alt="" />'
                            +'</div>'
                        +'</li>';
            tempNum = new Date().getTime();
            tpl = tpl.replace(/(\[%foto.num%\])/g, tempNum);
            $('#imgUploadFiles').append(tpl);
            
        });
<?php endif; ?>
            
//  удаление картинок/видео ссылок и уменьшение счетчика
        $('#imgUploadFiles').on('click','.uploadFileDel',function(){
            var del = 'delFile' + $(this).attr('data-source');
            $('.imgUploadFiles.'+del).remove();
            var num = $('#countFiles').text();
            num = parseInt(num) - 1;
            $('#countFiles').text(num);
        });
        
//  загрузка картинок 
      $('#file_upload').uploadify({
                'queueID'       : 'loading_img_info',
                'removeTimeout' : 1,
                'fileTypeDesc'  : 'Image Files',
                'fileTypeExts'  : <?php if($section->parametrs->param19=='J'): ?>'*.jpg'<?php else: ?>'*.gif; *.jpg; *.png'<?php endif; ?>,
                'buttonText'    : '<?php echo $section->language->lang065 ?>',
                'fileSizeLimit' : '<?php echo $section->parametrs->param14 ?>MB',
                'method'        : 'post',
                'removeCompleted' : true,
                'formData'      : {
                    'timestamp' : '<?php echo $timestamp ?>',
                'token'     : '<?php echo $token ?>',
                    'refferer'  : '<?php echo $refer ?>'
                },
                'swf'      : '[module_url]uploadify.swf',
                'uploader' : '<?php echo seMultiDir()."/".$_page."/" ?>?ajax_<?php echo $section->id ?>_uploadfile',
                'onUploadSuccess' : function(file, data, response) {
                    data = data.split('|');
                    $('#imgUploadFiles').append(data[1]);
                } 
      });
<?php if(($min_len_text>0)||($max_len_text>0)): ?>        
//  подсчет символов
    var tip = $('#couters');
    $('#anonceText').bind('keyup', function(e) {
        var pos = $(this).getCaretPosition();
        var bleft = this.offsetLeft + pos.left;
        var btop = this.offsetTop + pos.top;
        if (($('#anonceText').val().length > <?php echo $max_len_text ?>) || ($('#anonceText').val().length < <?php echo $min_len_text ?>)) {
            bnts = 'color:red;';
        } else {
            bnts = '';
        }
        tip.after('<div class="nums" id="nums'+$('#anonceText').val().length+'" style="'+bnts+'left: '+bleft+'px; top: '+btop+'px;">'+$('#anonceText').val().length+'</div>');
        $('#nums'+$('#anonceText').val().length).animate({
                marginLeft: '40px', marginTop: '-40px', opacity: '0'}, 2000 , function() {
                        $(this).hide().remove();
                }
        )
    });
<?php endif; ?>
    });   
    
    function showAlert(){
        alert("<?php echo $section->language->lang077 ?><?php echo $section->language->lang078 ?>");
    };    
    
</script>
<script src="[module_url]jquery.uploadify.js" type="text/javascript"></script>
<?php if(($min_len_text>0)||($max_len_text>0)): ?>        
    <script src="[module_url]textcounter.js" type="text/javascript"></script>
<?php endif; ?>    
<?php if($section->parametrs->param6=='tabs'): ?>
    <script src="[module_url]addtab.js" type="text/javascript"></script>
<?php endif; ?>
<div class="content blogposts add_edit">
    <h3 class="contentTitle">
        <?php if($id==0): ?>
            <?php echo $section->language->lang018 ?>
        <?php else: ?>
            <?php echo $section->language->lang019 ?>
        <?php endif; ?>
    </h3>
    <?php if($errortext!=''): ?>
        <div class="errortext"><?php echo $errortext ?></div>
    <?php endif; ?>  
    <form style="margin:0px;" action="" method="post" enctype="multipart/form-data" <?php if($showAlert=='Y'): ?>onsubmit="showAlert();"<?php endif; ?> >
        <div class="obj titlzag">
            <span class="title"><?php echo $section->language->lang020 ?></span>
            <span class="field">
                <input class="tinp" name="title" value='<?php echo $title ?>' id="checkTitle" maxlength="255" required>
                    <div class="smile_carot"  onmouseover="$(this).find('.smile_list').show();" onmouseout="$(this).find('.smile_list').hide();"><span><?php echo $section->language->lang087 ?></span>
                        <div class="smile_list" style="display:none;">
                            
                            
                            <?php foreach($section->smiles as $smile): ?>
                                <span class="smile_list_fetch" data-abbr="<?php echo $smile->abbr ?>"  style="width: 16px; height: 16px; cursor: pointer;">
                                    <img src="/lib/emotion/<?php echo $smile->img ?>">
                                </span>
                            
<?php endforeach; ?>
                            
                        </div>
                    </div>
            </span>
        </div>
        <div class="obj categor">
            <span class="title"><?php echo $section->language->lang021 ?></span>
            <span class="field">
                <?php if($section->parametrs->param12=='list'): ?>
                    <?php foreach($section->categories as $ctgrs): ?>
                        <div class="category level<?php echo $ctgrs->level ?>">
                            <input class="cinp" type="checkbox" name="selidcat[]" value="<?php echo $ctgrs->id ?>" <?php echo $ctgrs->checked ?>>
                            <span class="cteg"><?php echo $ctgrs->name ?></span>
                        </div>
                    
<?php endforeach; ?>
                <?php else: ?>
                    <select class="categorylist" name='selidcat[]' multiple>
                        <!-- option class="copt" value=""><?php echo $section->language->lang062 ?></option -->
                        <?php foreach($section->categories as $ctgrs): ?>
                            <option class="copt level<?php echo $ctgrs->level ?>" value="<?php echo $ctgrs->id ?>" <?php echo $ctgrs->selected ?>><?php echo $ctgrs->name ?></option>
                        
<?php endforeach; ?>
                    </select>
                <?php endif; ?>
            </span>
        </div>
        <div class="obj dt_date">
            <span class="title"><?php echo $section->language->lang022 ?></span>
            <span class='field'>
                <input class="tinp" id='dateinp' type='text' name='dat' value='<?php echo $date ?>' required>
            </span>    
        </div>
        <div class="obj dt_time">
            <span class="title"><?php echo $section->language->lang023 ?> </span>
            <span class='field'>
                <input type='text' class="tinp ehour" name="event_hour" value="<?php echo $ehour ?>" maxlength=2>
                <span>:</span>
                <input type='text' class="tinp eminute" name="event_minute" value="<?php echo $eminute ?>" maxlength=2>
            </span>
        </div>
       <div class="obj anons">
            <span class="title"><?php echo $section->language->lang028 ?></span>
            <span class="field">                                                
                <textarea class="teinp field_anons old" name="anons" id="anonceText"><?php echo $anons ?></textarea>
                <?php if($section->parametrs->param13=='N'): ?>
                    <div class="smile_carot"  onmouseover="$(this).find('.smile_list').show();" onmouseout="$(this).find('.smile_list').hide();"><?php echo $section->language->lang087 ?>
                        <div class="smile_list" style="display:none;">
                            
                            
                            <?php foreach($section->smiles as $smile): ?>
                                <span class="smile_list_fetch" data-abbr="<?php echo $smile->abbr ?>"  style="width: 16px; height: 16px; cursor: pointer;">
                                    <img src="/lib/emotion/<?php echo $smile->img ?>">
                                </span>
                            
<?php endforeach; ?>
                            
                        </div>
                    </div>
                <?php endif; ?>
                <div id="couters" style="position:absolute;display:none">0</div>
                
                <?php if($min_len_text!=0): ?>
                    <span class="infoMin">Минимальное кол-во символов - <?php echo $min_len_text ?></span>
                <?php endif; ?>
                <?php if($max_len_text!=0): ?>
                    <span class="infoMax">Максимальное кол-во символов - <?php echo $max_len_text ?></span>
                <?php endif; ?>
            </span>
       </div>
        <?php if($section->parametrs->param6=='tabs'): ?>
            <div class="obj full">
                <span class="title "><?php echo $section->language->lang029 ?></span>
                <span class="field">
                    <div id="tabs2">
                        <ul>
                            <li>
                                <a href="#tabs-1">
                                    <button id="add_tab" class="ui-button-default">+</button>
                                </a>
                            </li>
                            <?php foreach($section->tabname as $name): ?>
                                <li>
                                    <a href='#tabs-<?php echo $name->id ?>'><?php echo $name->title ?></a> 
                                    <span class='ui-icon ui-icon-edit'><?php echo $section->language->lang030 ?></span>
                                    <span class='ui-icon ui-icon-close'><?php echo $section->language->lang031 ?></span>
                                    <input type='hidden' value='<?php echo $name->title ?>' name='tabName[<?php echo $name->id ?>]'>
                                </li>
                            
<?php endforeach; ?>
                        </ul>
                        <div id="tabs-1"></div> 
                        <?php foreach($section->tabtext as $texts): ?>
                            <div id='tabs-<?php echo $texts->id ?>' class="tcontents">
                                <textarea class='old' name='tabText[<?php echo $texts->id ?>]'><?php echo $texts->text ?></textarea>
                            </div>
                        
<?php endforeach; ?>
                        <input type="hidden" class="checkType" value="<?php if($section->parametrs->param13=='Y'): ?>1<?php else: ?>0<?php endif; ?>">
                    </div>
                </span>
            </div>
        <?php endif; ?>
        <div class="obj imgUpload"> 
            <span class="title"><?php echo $section->language->lang024 ?></span>
            <span class="field">
                <div id="preview" >
                    <ul id="imgUploadFiles" >
                        <?php if($isFotoList>0): ?>
                            <?php foreach($section->fotos as $foto): ?>
                                <?php if($foto->foto_video=='foto'): ?>
                                    <?php if(file_exists($__MDL_ROOT."/php/subpage_addphoto.php")) include $__MDL_ROOT."/php/subpage_addphoto.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_addphoto.tpl")) include $__data->include_tpl($section, "subpage_addphoto"); ?>
                                <?php endif; ?>
                                <?php if($foto->foto_video=='video'): ?>
                                    <?php if(file_exists($__MDL_ROOT."/php/subpage_addvideo.php")) include $__MDL_ROOT."/php/subpage_addvideo.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_addvideo.tpl")) include $__data->include_tpl($section, "subpage_addvideo"); ?>
                                <?php endif; ?>
                            
<?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div> 
                <div id="loading_img_info">
                    
                </div>
                <div id="uploadFoto">

                    
                    <input id="file_upload" name="file_upload" type="file" multiple="true">
                    
                </div>
                <?php if($section->parametrs->param6=='text'): ?>
                    <div id="uploadVideo"><?php echo $section->language->lang066 ?></div>
                <?php endif; ?>
                <div id="status" ></div>
                <div class="info" ><?php echo $section->language->lang068 ?></div>
                <div id="countFiles" style="display:none !important">0</div>
            </span>
        </div>
        <?php if($section->parametrs->param1=='goods'): ?>
            <div class="obj price">
                <span class="title"><?php echo $section->language->lang026 ?></span>
                <span class="field">
                    <input class="tinp priinp" name="price" value='<?php echo $price ?>' maxlength="255">
                </span>
            </div>
            <div class="obj hits">
                <span class="title"><?php echo $section->language->lang027 ?></span>
                <span class="field">
                    <input type="checkbox" class="cinp priinp" name="hit" <?php echo $hit ?>>
                </span>
            </div>
            <div class="obj countr">
                <span class="title"><?php echo $section->language->lang034 ?></span>
                <span class="field">
                    <input class="tinp" name="country" value="<?php echo $country ?>" maxlength="255">
                </span>
            </div>
       <?php endif; ?>
        <div class="obj keywords">
            <span class="title"><?php echo $section->language->lang032 ?></span>
            <span class="field">
                <input class="tinp" name="keywords" value="<?php echo $keywords ?>" maxlength="255">
            </span>
        </div>
        <div class="obj description">
            <span class="title"><?php echo $section->language->lang033 ?></span>
            <span class="field">
                <input class="tinp" name="description" value="<?php echo $description ?>" maxlength="255">
            </span>
        </div>
        <div class="obj tegi">
            <span class="title"><?php echo $section->language->lang005 ?></span>
            <span class="field">
                <input class="tinp" name="tegi" value="<?php echo $tegi ?>" maxlength="255">
                <div class="info" ><?php echo $section->language->lang069 ?></div>
            </span>
        </div>
        <?php if($section->parametrs->param22=='Y'): ?>
            <div class="obj model">
                <span class="title"><?php echo $section->language->lang081 ?></span>
                <span class="field">
                    <input class="tinp" name="model" value="<?php echo $model ?>" maxlength="255">
                </span>
            </div>
            <div class="obj vidergka">
                <span class="title"><?php echo $section->language->lang082 ?></span>
                <span class="field">
                    <input class="tinp" name="vidergka" value="<?php echo $vidergka ?>" maxlength="255">
                </span>
            </div>
            <div class="obj diafragma">
                <span class="title"><?php echo $section->language->lang083 ?></span>
                <span class="field">
                    <input class="tinp" name="diafragma" value="<?php echo $diafragma ?>" maxlength="255">
                </span>
            </div>
            <div class="obj iso">
                <span class="title"><?php echo $section->language->lang084 ?></span>
                <span class="field">
                    <input class="tinp" name="iso" value="<?php echo $iso ?>" maxlength="255">
                </span>
            </div>
            <div class="obj obectiv">
                <span class="title"><?php echo $section->language->lang085 ?></span>
                <span class="field">
                    <input class="tinp" name="obectiv" value="<?php echo $obectiv ?>" maxlength="255">
                </span>
            </div>
            <div class="obj vspishka">
                <span class="title"><?php echo $section->language->lang086 ?></span>
                <span class="field">
                    <input class="tinp" name="vspishka" value="<?php echo $vspishka ?>" maxlength="255">
                </span>
            </div>
        <?php endif; ?>
        <div class="obj razcom">
            <span class="title"><?php echo $section->language->lang035 ?></span>
            <span class="field">
                <select name="razcom">
                    <option value="Y"><?php echo $section->language->lang036 ?></option>
                    <option value="N" <?php if($razcom=="N"): ?> selected <?php endif; ?>><?php echo $section->language->lang037 ?></option>
                </select>
            </span>
        </div>
        <?php if($section->parametrs->param1=='goods'): ?>
            <div class="obj show_anons">
                <span class="title"><?php echo $section->language->lang038 ?></span>
                <span class="field">
                    <select name="show_anons">
                        <option value="N"><?php echo $section->language->lang039 ?></option>
                        <option value="Y"<?php if($show_anons=="Y"): ?> selected<?php endif; ?>><?php echo $section->language->lang040 ?></option>
                    </select>
                </span>
            </div>
        <?php endif; ?>
        <?php if($changeVisiblePost=='Y'): ?>
            <div class="obj show_post">
                <span class="title"><?php echo $section->language->lang041 ?></span>
                <span class="field">
                    <select name="show_post">
                        <option value="Y"><?php echo $section->language->lang040 ?></option>
                        <option value="N"<?php if($show_post=="N"): ?> selected<?php endif; ?>><?php echo $section->language->lang039 ?></option>
                    </select>
                </span>
            </div>
        <?php endif; ?>
        <div class="groupButton">
            <input type='hidden' name='id' value='<?php echo $id ?>'>
            <input class="buttonSend goButton" name="GoTonewblog" type="submit" value="<?php echo $section->language->lang042 ?>">
            <input class="buttonSend backButton" onclick="document.location.href='<?php echo seMultiDir()."/".$_page."/" ?>';" type="button" value="<?php echo $section->language->lang043 ?>">
            <?php if($id!=0): ?>
                <input class="buttonSend delButton" name="delpost" type="submit" value="<?php echo $section->language->lang044 ?>">
            <?php endif; ?>
        </div>
    </form>
    
    <div id="dialog2" title="Добавление вкладки" style="display:none;">
        <form>
            <fieldset class="ui-helper-reset">
                <div class="input-group">
                    <span class="input-group-addon"><?php echo $section->language->lang045 ?></span>
                    <input type="text" name="tab_title" id="tab_title" value="" class="ui-widget-content ui-corner-all">
                </div>
            </fieldset>
        </form>
    </div>
</div>
