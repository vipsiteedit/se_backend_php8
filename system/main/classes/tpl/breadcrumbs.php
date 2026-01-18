<ol class="breadcrumb">
 <?php foreach($this->breadcrumbs as $item): ?>
    <li <?php if (!empty($item['active'])): ?>class="active"<?php endif ?> itemscope itemtype="http://schema.org/BreadcrumbList">
    <span itemprop="itemListElement">
    <?php if (!empty($item['lnk'])): ?>
        <a href="<?php echo $item['lnk'] ?>" itemprop="item"><span itemprop="name"><?php echo $item['name'] ?></span></a>
    <?php elseif: ?>
        <span itemprop="name"><?php echo $item['name'] ?></span>
    <?php endif ?>
    </span>
    </li>
 <?php endforeach ?>
</ol>
