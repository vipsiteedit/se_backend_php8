<?php

//  SEO
    if (isRequest('blog')) {
        $cat = new seTable('blogcategories');
        $cat->where("`url` like '?'", getRequest('blog', 3));
        $cat->fetchOne();
        $__data->page->keywords = $cat->keywords;
        $__data->page->description = $cat->description;
        $__data->page->titlepage = $cat->name;
        unset($cat); 
    }

?>