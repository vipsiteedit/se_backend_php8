<header:js>      
[lnk:bootstrap/css/bootstrap.min.css]
[js:bootstrap/bootstrap.min.js]
</header:js>      
<nav class="navbar navbar-default" role="navigation">
<div class="container-fluid"><!-- Brand and toggle get grouped for better mobile display -->
<div class="navbar-header"><button class="navbar-toggle" data-target="#bs-example-navbar-collapse-1" data-toggle="collapse"><span class="sr-only">Toggle navigation</span>
<span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>
<a class="navbar-brand" href="home.html">Главная</a>
</div><!-- Collect the nav links, forms, and other content for toggling -->
<div id="bs-example-navbar-collapse-1" class="collapse navbar-collapse">
<ul class="nav navbar-nav">
<?php foreach($__data->limitObjects($section, $section->objectcount) as $record): ?>

<li<?php if(seMultiDir().'/'.$_page.'/'==$record->field): ?> class="active"<?php endif; ?>><a href="<?php echo $record->field ?>"><?php echo $record->title ?></a></li>

<?php endforeach; ?>
</ul>
<?php echo $site->authorizeform ?>
</div></div>
</nav>
