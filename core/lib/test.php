<?php
function html_question($question){
 return'
    <div class="test">
        <div class="image">
        <h2>
            '._e($question['question']).'
        </h2>
        <img src="img/'.$question['image'].'" alt="">
        </div>
        <div class="buttons">
        <form action="test.php" method="post">
            <input type="hidden" name="action" value="answer">
            <button name="btn0" type="submit">'.$question['answers'][0].'</button> 
            <button name="btn1" type="submit">'.$question['answers'][1].'</button>
        </form>
        </div>
    </div>'
    ;
}
function html_evaluation($q){
    $success=0;
    $failed=0;
    foreach ($_SESSION['answers'] as $item) {
       if($item['answer'] === $q[$item['qnum']]['right']){
           $success++;
       }
       else {
           $failed++;
       }
    }
    unset($_SESSION['answers']);

    $perc = perc($success, $failed);

    return'
        <div class="test">
            <h2>Vyhodnotenie</h2>
            '.img_thumb($perc).'<br>
            percentá:<div class="center">'.color($perc).'</div>
            správne:<b>'.$success.'</b><br>
            nesprávne:<b>'.$failed.'</b>
        </div>    
    ';
}
function perc($success, $failed){
    return round(100 * $success / ($success + $failed));
}
function img_thumb($perc){
    if($perc > 50 ){
        return'<img src="/img/palechore.gif">';
    }
    return'<img src="/img/l.gif">';
}
function color($perc){
    if($perc > 50){
        return'<span class="success">'.$perc.'%</span>';
    }
    return'<span class="failed">'.$perc.'%</span>';
}
function count_answers($question){
    return count($question['answers']);
}
function clicked_button($question) {
    foreach($question['answers'] as $key => $value){
        if(isset($_POST['btn' . $key])) {
            return $key;
        }
    }
    return false;
}
function html_buttons($question){
    $html = '';
    foreach($question['answers'] as $key => $value){
        $html.= '<button name="btn'. $key .'" type="submit">'. _e($value) .'</button>';
    }
    return $html;
}
