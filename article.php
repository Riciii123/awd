<?php

require_once('core/init.php');

$id = (int) $_GET['id'];

$db = DB::getInstance() ->query("SELECT * FROM articles WHERE id = ?;" , [$id]);

if(!$db->count()) {
    notifySet("Článok neexistuje.","danger");
    redirect("index.php");
}

$row = $db->first();

$title = $row->title;

$content = '
    <div class="container">
        '. admin_html_edit($id) .'
        <h2>'._e($title).'</h2>
        '. nl2br($row->content) .'
    </div>
    '
    /*.htmlArticles()*/;
    

render($content,$title);