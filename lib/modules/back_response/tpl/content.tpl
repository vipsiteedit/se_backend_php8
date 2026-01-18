<header:js>
[js:jquery/jquery.min.js]
<script type="text/javascript" src="[this_url_modul]countdown.js"></script>
    <script type="text/javascript">
    <!--       
        function comeon(){       
            var last = $('.l_nameinp').val();  
            var first = $('.f_nameinp').val();
            var second = $('.s_nameinp').val();
            var mail = $('.emailinp').val(); 
            var phone = $('.phoneinp').val();
//            var comm = $('.commenttextarea').val();
            if(last == ''){
                $('.errors').text('<?php echo $section->language->lang002 ?>');
                return false;
            } 
            if(first == ''){
                $('.errors').text('<?php echo $section->language->lang003 ?>'); 
                return false; 
            }
            if('<?php echo $section->parametrs->param13 ?>'=='Y'){
                if(mail == ''){
                    $('.errors').text('<?php echo $section->language->lang004 ?>');
                    return false;
                }             
            }       
            if('<?php echo $section->parametrs->param14 ?>'=='Y'){
                if(phone == ''){
                    $('.errors').text('<?php echo $section->language->lang005 ?>');
                    return false;
                }             
            }       
            $.post("?query_time",{selast: ""+last, sefirst: ""+first, sephone: ""+phone, sesecond: ""+second, semail: ""+mail},function(data){                        
            if(data == ''){                   
                $('#commons').fadeOut();
                $('.errors').fadeOut();
                $('.finish').fadeIn();
            } else {                      
                $('.errors').html(data).show();    
            }              
            });            
               
    }
    -->
    </script>
</header:js>
<div class="content notime main" <?php echo $section->style ?>>
    <?php if(!empty($section->title)): ?>
        <h3 class="contentTitle"<?php echo $section->style_title ?>>
            <span class="contentTitleTxt"><?php echo $section->title ?></span>
        </h3>
    <?php endif; ?>
    <?php if(!empty($section->image)): ?>
        <img border="0" class="contentImage"<?php echo $section->style_image ?> src="<?php echo $section->image ?>" alt="<?php echo $section->image_alt ?>">
    <?php endif; ?>
    <?php if(!empty($section->text)): ?>
        <div class="contentText"<?php echo $section->style_text ?>><?php echo $section->text ?></div>
    <?php endif; ?>
    <?php $__data->recordsWrapperStart($section->id) ?>
    <div class="contentBody">

        <?php if($flag==1): ?>
            <div class="surrent">
                <noindex>
                <a class="scrlink" href="<?php echo seMultiDir()."/".$_page."/".$razdel."/sub1/" ?>id/<?php echo $section->parametrs->param4 ?>/" rel="nofollow"><?php echo $section->language->lang009 ?></a>
                </noindex>
            </div>
        <?php endif; ?>

        
        <div class="errors sysedit"><?php echo $err ?></div>
        <div id="count_down_container">
               
        </div>
        <script type="text/javascript">
            var currentyear = new Date().getFullYear()
            var target_date = new cdtime("count_down_container", "<?php echo $date ?>");   
            target_date.displaycountdown("days", displayCountDown)
        </script>
        
        <div id="commons">
        <div class="main commmon_title"> 
            <?php echo $section->parametrs->param18 ?>
        </div>
        <div class="main l_name"> 
            <span class="maintxt l_nametxt"><?php echo $section->parametrs->param6 ?> <span class="stars l_namestar">*</span></span>
            <input type="text" class="maininp l_nameinp">        
        </div>
       
        <div class="main f_name">
            <span class="maintxt f_nametxt"><?php echo $section->parametrs->param7 ?><span class="stars f_namestar">*</span></span>
            <input type="text" class="maininp f_nameinp">        
        </div>
        
        <div class="main s_name">
            <span class="maintxt s_nametxt"><?php echo $section->parametrs->param8 ?></span>
            <input type="text" class="maininp s_nameinp">        
        </div>
       
            <div class="main email">
                <span class="maintxt emailtxt"><?php echo $section->parametrs->param9 ?>
                <?php if($section->parametrs->param13=='Y'): ?>
                    <span class="stars emailstar">*</span>
                <?php endif; ?></span>
                <input type="text" class="maininp emailinp">        
            </div>
            <div class="main phone">
                <span class="maintxt phonetxt"><?php echo $section->parametrs->param10 ?>
                <?php if($section->parametrs->param14=='Y'): ?>
                    <span class="stars phonestar">*</span>
                <?php endif; ?></span>
                <input type="text" class="maininp phoneinp">        
            </div>
        
         
        <div class="button_area">
            <input type="button" class="input_button buttonSend" onclick="comeon()" value="<?php echo $section->parametrs->param3 ?>" <?php echo $disabled_button ?>>
        </div>
        </div>
    </div>
    <div class="finish"  style="display:none;"><?php echo $section->parametrs->param12 ?></div>
    <?php $__data->recordsWrapperEnd() ?>
</div>
