<?php
require_once('core/init.php');
require_once('core/class/Upload.php');
require_once('core/class/Image.php');
$dir='gallery';
if(isAdmin() && $_GET['filename']){
    if(strpos($_GET['filename'],"/") !== false){
        redirect("gallery.php");
    }
    if(strpos($_GET['filename'],"\\") !== false){
        redirect("gallery.php");
    }
    if($_GET['filename']=="." || $_GET['filename']==".."){
        redirect("gallery.php");
    }
    $path=$dir."/".$_GET['filename'];
    if(file_exists($path)){
    @unlink($path);
    notifySet("Súbor bol úspešne zmazaný.","success");
    redirect("gallery.php");
    }
    redirect("gallery.php");
}
if(isAdmin() && $_POST['action'] === 'upload'){
    $error = upload_img($dir);
    if($error){
        notifySet($error,"danger");
    }
    else{
        notifySet("Súbor bol úspešne nahratý","success");
    }
    redirect("gallery.php");
}
$files=scandir($dir);
$html='';
$counter=0;
$total_size=0;
foreach($files as $file){
    if($file== '.' || $file=='..'){
        continue;
    }
    $path=$dir.'/'.$file;
    $result=getimagesize($path);
    if($result===false){
        continue;
    }
    $width=$result[0];
    $height=$result[1];
    $size=filesize($path);
    $total_size+= $size;
    $counter++;
    $html.='
        <div class="image-one">
            '.panelDelete($file).'
            <a class="fancy" rel="group" href="'.$path.'">
                <img src="'.$path.'" alt="" >
            </a>
            '.panelinfo($width,$height,$size).'
        </div>';
}
$title='Galéria' ;
$content='
    <div class="container">
    <h2>Galéria</h2>
    '.panelUpload().'
    <div class="gallery clearfix">
    '.$html.'
    </div>
    '.panelSummary($counter, $total_size).'
</div>';
render($content,$title);
function panelUpload(){
    if(!isAdmin()){
        return'';
    }
    return'
    <form action="gallery.php" method="post" enctype="multipart/form-data">
    <div class"row bg-info m-t-1 m-b-2">
        <div class="col-md-6 m-t-1">
            <div class="form-group">
                <label>Súbor:</label>
                <input type="file" name="img_file">
            </div>
            <div class="form-group">
                <label>Zmenšit obrázok</label>
                <input type ="checkbox" name ="resize">
            </div>
        </div>
        <div class="col-md-6 m-t-1">
            <div class="form-group">
                <input type="hidden" name="action" value="upload">
                <button type="submit" class="btn btn-outline-success">nahrať</button>
            </div>
        </div>
    </form>
    ';
}
function panelDelete($filename){
    if(!isAdmin()){
        return'';
    }
    return'<a href="gallery.php?filename='.$filename.'" class="delete"><b>X</b></a>';
}
function panelinfo($width,$height,$size){
    if(!isAdmin()){
        return'';
    }
    return'<div>
    <p>'.$width.'x'.$height.'<br>
    <span class="color">'.mySize($size).'</span>
    </p>
</div>';
}
function panelSummary($counter,$total_size){
    if(!isAdmin()){
        return'';
    }
    return'
    <div>
        Celkový počet obrázkov: '.$counter.'<br>
        Celková velkosť obrázkov: '.mySize($total_size,"MB").'
    </div>
    ';
}
function upload_img($dir) {
    $error = '';
   
    $upload = new Upload("img_file");
    $upload->allowedImageOnly();
    if($upload->check()){
        $path_orig = $upload->getTempName();
        $path = $dir."/". rand(100000000,999999999).".jpg";
        $r = Image::resize($path_orig, $path, 100 ,100,"png");
        if(!$r) {
            $error = "Chyba zmenšovania obrázka";
        } 
    }
    else{
        $error = $upload->error();
    }
    return $error;
}