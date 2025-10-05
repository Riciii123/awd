<?php

require_once 'core/init.php';
/* Test files */
require_once 'core/lib/questions.php';
require_once 'core/lib/test.php';

if(empty($_POST))
{
    /* prvý prichod na stranku  */
    $_SESSION['question'] = 0;
    $_SESSION['answers'] = [];
    $question = $q[$_SESSION['question']];
    render( html_question($question), "Otázka č.". ($_SESSION['question'] + 1) );
}

switch($_POST['action']) {
    case 'answer':
        $btn = clicked_button($q[$_SESSION['question']]);

        if(!isset($q[$_SESSION['question'] + 1])) {
            // po poslednej otazke
            $_SESSION['answers'][] = [
                "qnum" => $_SESSION['question'],
                "answer" => $btn,
            ];
            render( html_evaluation($q));

        }

        $_SESSION['answers'][] = [
            "qnum" => $_SESSION['question'],
            "answer" => $btn,
        ];

        $_SESSION['question']++;
        render( html_question($q[$_SESSION['question']]), "Otázka č.". ($_SESSION['question'] + 1) );

    break;
}
