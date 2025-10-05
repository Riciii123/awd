<?php

require_once('core/init.php');
require_once("core/class/PHPMailer.php");

if(!empty($_POST)) {

    $name = rtrim($_POST['name']);
    $email = rtrim($_POST['email']);
    $message = rtrim($_POST['message']);

    if( empty($name) || empty($email) || empty($message) )
    {
        notifySet("Vyplň všetky položky!","danger");
        redirect("contact.php");
    }

    $msg = "Meno: ". _e($name) . "<br>";
    $msg .= "Email: ". _e($email) . "<br><br>";
    $msg .= nl2br(_e($message));

    $mail = new PHPMailer();
    $mail->setFrom("noreply@awebin.sk");
    $mail->AddAddress("risko9957@gmail.com");
    $mail->WordWrap = 100;
    $mail->IsHTML(true);
    $mail->CharSet = 'utf-8';
    $mail->Subject = "Formulár";
    $mail->Body = $msg;

    if($mail->Send()) {
        notifySet("Správa bola úspešne odoslaná.","success");
    }
    else{
        notifySet("Chyba pri odosielaní.","danger");
    }
}

$title = 'Kontakt';
$content = '
    <div class="container-b">
        <h2>Kontakt</h2>
        <form action="contact.php" method="post">
            <div class="form-group">
                <label>Meno:</label>
                <input class="form-control" type="text" name="name" placeholder="Meno">
            </div>
            <div class="form-group">
                <label>E-mail:</label>
                <input class="form-control" type="email" name="email" placeholder="E-mail">
            </div>
            <div class="form-group">
                <label>Správa</label>
                <textarea class="form-control" type="message" name="message" placeholder="Správa" ></textarea>
            </div>
            <div class="form-group">    
                <button class="btn btn-warning" type="submit">Odoslať</button>
            </div>
        </form>
    </div>';

render($content, $title);
