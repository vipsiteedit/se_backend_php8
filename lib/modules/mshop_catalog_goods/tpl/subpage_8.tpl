<?php if($showpages==0): ?>
    <script type='text/javscript'>
        function frmSubmit(frm) {
            if (((frm.elements[0].value) > <?php echo $allpages ?>) || (frm.elements[0].value < 1)) {
                return false; 
            }
            location.href='?db=sp&sheet='+(frm.elements[0].value);
            return false;   
        } 
    </script>
<?php endif; ?>
<table border="0" class="seNavigator" cellspacing="0" cellpadding="0">
    <?php if($showpages!=0): ?>
        <form style="margin:0px" onSubmit="frmSubmit(this);" method="get" enctype="multipart/form-data">   
            <tr>
                <?php if($startshow!=0): ?>
                    <td width="20px" align="center" class="pagen">
                        <a href="?db=sp&sheet=1">1</a>
                    </td>
                    <?php if($start!=2): ?>
                        <td width="20px" align="center" class="pagen">
                            <a href="?db=sp&sheet=2">2</a>
                        </td>
                    <?php endif; ?>
                    <?php if($startpg!=0): ?>
                        <td width="20px" align="center" class="pagen">...</td>            
                    <?php endif; ?>
                <?php endif; ?>
                <?php foreach($section->mypages as $pg): ?>
                    <td width="20px" align="center" class="pagen">
                        <?php if($pg->cur!=0): ?>
                            <input class="pagenactive" title="" name="sheet" type="text" size="2" maxlength="3" value="<?php echo $pg->pg ?>">
                        <?php else: ?>
                            <a href='?db=sp&sheet=<?php echo $pg->pg ?>'><?php echo $pg->pg ?></a>
                        <?php endif; ?>
                    </td>
                
<?php endforeach; ?>
                <?php if($stopshow!=0): ?>
                    <?php if($stoppg!=0): ?>
                        <td width="20px" align="center" class="pagen">...</td>      
                    <?php endif; ?>
                    <?php if($stopshow!=1): ?>
                        <td width="20px" align="center" class="pagen">
                            <a href="?db=sp&sheet=<?php echo $stopshow ?>"><?php echo $stopshow ?></a>
                        </td>
                    <?php endif; ?>
                    <td width="20px" align="center" class="pagen">
                        <a href="?db=sp&sheet=<?php echo $allpages ?>"><?php echo $allpages ?></a>
                    </td>
                <?php endif; ?>
            </tr>
        </form>
    <?php endif; ?>
</table> 
