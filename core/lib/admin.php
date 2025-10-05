<?php
function hashPwd($pwd){
    return hash("sha256",$pwd.ADMIN_SALT);
}
function checkPwd($pwd){
    if(hashPwd($pwd)===ADMIN_HASH){
        return true;
    }
    return false;
}
function loginAdmin($pwd) {
    if (checkpwd($pwd)) {
        $_SESSION['admin']=true;
        return true;
    }
    $_SESSION['admin']=false;
    return false;
}
function isAdmin() {
    if($_SESSION['admin']){
        return true;
    }
    return false;
}
function adminpanel(){
    if(!isAdmin()){
        return'';
    }
    return'<div class="adminpanel">Prihlásený: Admin <a href="login.php?logout"><button class="btn btn-success">Odhlásiť sa</button></a></div>';
}
function adminlogout(){
    unset($_SESSION['admin']);
    notifySet("Si úspešne odhlásený.","success");
    redirect('index.php');
}