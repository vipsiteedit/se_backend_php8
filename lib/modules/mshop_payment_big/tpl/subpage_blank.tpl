<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <title>Blank</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <script language=javascript>
            function saveAs (pic1) {
/*
                document.execCommand('SaveAs',true,'blank.html');
                if (typeof pic1 == 'object') {
                    pic1 = pic1.src;
                }
                window.win = open(pic1);
                setTimeout('win.document.execCommand("SaveAs")', 500);
                setTimeout('win.close();', 500);
//*/
            }
        </script>
        <style>
            .head {width:100%; height:30px;}
            #disk {}
            #print {}
        </style>
    </head>
    <body>
        <div class="head">
            <a href="javascript:window.print();">
                <img id="print" border="0" name="print" alt="<?php echo $section->language->lang002 ?>" src="<?php echo $img_src ?>icon_print_se.png" width="17" height="17">
            </a>
        </div>
        <?php echo $blank ?>
    </body>
</html>
