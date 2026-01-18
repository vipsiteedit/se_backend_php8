<?php
  require_once dirname(__FILE__).'/classes/sitemap.class.php';
  function se_sitemap() {
     $sitemap = new siteMap();
     $sitemap->execute();
  }
?>