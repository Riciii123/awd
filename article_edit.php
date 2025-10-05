<?php
require_once('core/init.php');
require_once('core/class/Upload.php');
require_once('core/class/Image.php');

if(!isAdmin()){
    exit;
}
if(!empty($_GET)) {
    switch($_GET['action']) {
        case 'remove':
            $id = (int) $_GET['id'];
            if($id) {
                $r = DB::getInstance() ->delete("articles", ["id","=", $id]);
                if($r){
                    $path = 'articles/news' . $id . '.png';
                    @unlink($path);
                    notifySet("Článok bol úspešne zmazaný.","success");
                }
                redirect("index.php"); 
            }
    }
}
if(!empty($_POST)){

    switch($_POST['action']) {
        
        case 'add':
            $r = DB::getInstance() ->insert("articles" , [
                "title" => $_POST['title'],
                "description" => $_POST['description'],
                "content" => $_POST['content'],
            ]);
            if($r){
                $id = DB::getInstance() ->lastinsertid();
                $error = upload_img($id);
                notifySet("Článok bol úspešne pridaný." . $error , "success");
            }
            redirect("index.php");
        break;

        case 'edit':
            $id = (int) $_POST['id'];
            $r = DB::getInstance() ->update("articles" , $id , [
            "title" => $_POST['title'],
            "description" => $_POST['description'],
            "content" => $_POST['content'],
            ]);
            if($r){
                $error = upload_img($id);
                notifySet("Článok bol úspešne editovaný." . $error ,"success");
            }
            redirect("index.php");
        break;

    }
}

$id = (int) $_GET['id'];
    
if($id){
    $db = DB::getInstance() ->query("SELECT * FROM articles WHERE id = ?;" , [$id]);
    if(!$db->count()) {
        notifySet("Článok neexistuje.","danger");
        redirect("index.php");
    }
    $row = $db->first();
    $action = 'edit';
    $title = 'Editácia článku';
    
}
else {
    $row = new stdClass();
    $action = 'add';
    $title = 'Pridanie článku'; 
}
$content = '
    <div class="container-b">
        <h2>'._e($title).'</h2>
        <form action="article_edit.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label>Názov článku:</label>
                <input class="form-control" type="text" name="title" value="'. _e($row->title) .'">
            </div>
            <div class="form-group">
                <label>Popis:</label>
                <input class="form-control"  type="text" name="description" value="'. _e($row->description) .'">
            </div>
            <div class="form-group">
                <label>Obsah:</label>
                <textarea name="content" id="page-content">'. $row->content .'</textarea>
            </div>
            <div class="form-group">
                <label>Obrázok:</label>
                <input type="file" class="form-control" name="img">
            </div>
            <div class="form-group">
                <input class="form-control" type="hidden" name="id" value="'. $id .'">
                <input class="form-control" type="hidden" name="action" value="'. $action .'">   
                <button class="btn btn-primary" type="submit">Uložiť</button>
            </div>
        </form>
    </div>    
    ';

$scripts = ['theme/js/article.js'];

render($content,$title,$scripts);

function upload_img($id) {
    $error = '';
    if(!$_FILES['img']['name']){
        return $error;
    }
    $upload = new Upload("img");
    $upload->allowedImageOnly();
    if($upload->check()){
        $path_orig = $upload->getTempName();
        $path = 'articles/news' . $id . '.png';
        $r = Image::thumbs($path_orig, $path, 100 ,100,"png");
        if(!$r) {
            $error = "Chyba zmenšovania obrázka";
        } 
    }
    else{
        $error = $upload->error();
    }
    return $error;
}