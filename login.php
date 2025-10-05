<?php
require_once('core/init.php');
if(isset($_GET['logout'])){
    adminlogout();
}
if (!empty($_POST)){
    $pwd=$_POST['pwd'];
    if(loginAdmin($pwd)){
        notifySet("Si prihlásený","success");
    }
    else{
        notifySet("Nesprávne heslo!","danger");
    }
    redirect("login.php");
}
/*echo hashPwd($_POST['pwd']);
exit;*/
$title='Login';
$content='
<div class="container-b">
    <form action="login.php" method="post">
    <div class="form-group">
        <label>Password:</label>
        <input type="password" name="pwd" class="form-control">
    </div>
    <div class="form-group">
            <button type="submit" class="btn btn-warning">Login</button>
    </div>
    </form>
    '.(isAdmin() ? '<a href="page_log.php" class="btn btn-info">Návštevníci</a>' : '').'
</div>';

$scripts = ['login.js'];

render($content,$title,$scripts);