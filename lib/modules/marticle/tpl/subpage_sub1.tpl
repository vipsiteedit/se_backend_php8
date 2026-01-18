<div id="cont_art" class="content">

    <div id="commentTable">
        <form method="post" action="">
            <table class="tableTable" border="0" cellSpacing="0" cellPadding="0" width="350">
                <tbody class="tableBody">
                    <tr vAlign="top" align="left">
                        <td class="art_errtxt" colSpan="3"><?php echo $art_errtxt1 ?></td>
                    </tr>
                    <tr vAlign="top" align="left">
                        <td class="art_hspace" height="10" colSpan="3">
                            <img alt="image" src="#" width="0px" height="0px">
                        </td>
                    </tr>
                    <tr vAlign="top" align="left">
                        <td class="title"><?php echo $section->language->lang021 ?></td>
                        <td class="art_vspace" width="15">&nbsp;</td>
                        <td class="field" align="right">
                            <input class="art_inp" value="<?php echo $_name ?>" size="30" name="name">
                        </td>
                    </tr>
                    <tr>
                        <td class="art_hspace" height="10" colSpan="3">
                            <img alt="image" src="#" width="0px" height="0px">
                        </td>
                    </tr>
                    <tr vAlign="top" align="left">
                        <td class="title"><?php echo $section->language->lang022 ?></td>
                        <td class="art_vspace" width="15">&nbsp;</td>
                        <td class="field" align="right">
                            <input class="art_inp" value="{$_email]" size="30" name="email">
                        </td>
                    </tr>
                    <tr>
                        <td class="art_hspace" height="10" colSpan="3">
                            <img alt="image" src="#" width="0px" height="0px">
                        </td>
                    </tr>
                    <tr vAlign="top" align="left">
                        <td class="art_commnt" colSpan="3"><?php echo $section->language->lang023 ?></td>
                    </tr>
                    <tr vAlign="top" align="left">
                        <td class="art_cmmntfld" colSpan="3">
                            <textarea style="WIDTH:100%" class="art_cmmntarea" rows="5" cols="10" name="note"><?php echo $_note ?></textarea> 
                        </td>                           
                    </tr>
                    <tr>
                        <td class="art_hspace" height="10" colSpan="3">
                            <img alt="image" src="#" width="0px" height="0px">
                        </td>
                    </tr>
                    <tr vAlign="top" align="left">
                        <td class="art_commnt" colSpan="3"><?php echo $section->language->lang024 ?></td>
                        <td class="art_vspace" width="15">&nbsp;</td>
                        <td class="field">
                            <input type="checkbox" name="del">
                        </td>
                    </tr>                       
                    <tr>
                        <td class="art_hspace" height="10" colSpan="3">
                            <img alt="image" src="#" width="0px" height="0px">
                        </td>
                    </tr>
                    <tr>
                        <td class="art_hspace" height="10" colSpan="3">
                            <img alt="image" src="#" width="0px" height="0px">
                        </td>
                    </tr>
                    <tr>
                        <td class="art_tdbtn" colSpan="3">
                            <input class="buttonSend" value="<?php echo $section->language->lang013 ?>" type="submit" name="GoToEdit">
                        </td>
                    </tr>
                    <tr>
                        <td class="art_hspace" height="10" colSpan="3">
                            <img alt="image" src="#" width="0px" height="0px">
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>
</div>
<div>
</div>
