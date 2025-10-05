<?php

require_once 'core/init.php';
if (!isAdmin()){
    redirect('index.php');
}
if($_GET ['action']=='clear_log'){
    @file_put_contents(PATH_GLOBAL_LOG, $data);
    notifySet('Súbor bol vyčistený','succes');
    redirect('login.php');
}
$content =
    '<h2>ALT_F4</h2>
'.get_tables().'
 ';
render($content, $title);

 function get_tables()
{
    $data = file(PATH_GLOBAL_LOG);
    $data = array_reverse($data);
      $h = '';
    foreach ($data as $row) {
        $fields = explode("\t", $row);
  
        foreach ($fields as $field) {
            $h .= '<td>' . _e($field) . '<td>';
        }
        $h .= '</tr>';
    }
    return '
     <table class = "table">
' . $h . '
</table>
'; 
}

