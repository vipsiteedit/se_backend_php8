<noempty:{$product_files}>
<h3 class="titleHead">
    <span>[lang107]</span>
</h3>
<div class="content">                    
    <div class="product-files">
        <repeat:files name=file>
            <div class="file-item">
                <a class="file-item-link" href="[file.link]">[file.name]</a>
                <button class="btn btn-default btn-sm" onClick="window.location.href='[file.link]'" title="[lang108]">
                    <i class="glyphicon glyphicon-download-alt"></i>
                    [lang108] ([file.size])
                </button>
            </div>
        </repeat:files>
    </div>
</div>
</noempty>
