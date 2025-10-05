<?php

function html_articles() {
    //Select - výber riadkov
    //Order by - triedenie podľa stĺpca
    //ASC - vzostupné poradie
    //DESC - zostupné poradie
    $db = DB::getInstance() ->query("SELECT * FROM articles ORDER BY id DESC;;");
    $html ='';
    foreach ($db->all() as $row) {
        $id = $row->id;
        $filename ="news{$id}.png"; 
        $html .='<div class="article clearfix">
                <img src="articles/'.$filename.'" alt="">
                <h2><a href="article.php?id='. $id .'">'. _e($row->title) .'</a></h2>
                <p>'. _e($row->description) .'</p>
            </div>';
    }
    return $html;
}

function admin_html_edit($id) {
    if(!isAdmin()){
        return'';
    }
    return'
        <a href="article_edit.php?id="'. $id .'"&action=edit" class="btn btn-success">Editovať</a>
        <a href="article_edit.php?id='. $id .'&action=remove" class="btn btn-danger">Odstrániť</a>
    ';    
}
function admin_html_add_article() {
    if(!isAdmin()){
        return'';
    }
    return'
    <div class="row">
        <div class="col-md-12 text right">
            <a href="article_edit.php" class="btn btn-warning">Pridať</a>
        </div>
    </div>
    ';    
}