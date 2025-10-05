<?php
require_once('core/init.php');
$title = "Úvod";
$content = admin_html_add_article() . html_articles();
render($content,$title);