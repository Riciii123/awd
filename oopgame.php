<?php

class GuessNumber  {
    const MIN_NUM = 1;
    const MAX_NUM = 100;
    private
        $random_num,
        $counter,
        $msg = '',
        $input_field_name = "num";
    
    public function __construct()
    {
        $this->random_num = $_SESSION["random_num"];
        $this->counter = $_SESSION["counter"];
        if(!empty($_POST[$this->input_field_name])){
           if(!$this->checkNum($_POST[$this->input_field_name])){
                $this->msg .= "Čo je číslo ?";

           }
           else {
            $this->num($_POST[$this->input_field_name]);
           }
        }
        else{
            $this->generateRandomNum();
            $this->counter = 0;
        }

    }
    public function __destruct()
    {
        $_SESSION["random_num"] = $this->random_num;
        $_SESSION["counter"] = $this->counter;
    }

    public function generateRandomNum() {
        $this->random_num = rand(self::MIN_NUM, self::MAX_NUM);
    }

    public function checkNum($num) {
        if($num === 'egg') {
            $this->msg .= 'Je to číslo <span class="badge badge-info">'.$this->random_num.'</span>';
            return true;
        }
        else {
            return $this->isDigit($num);
        }
    }
    public function isDigit($num) {
        if(is_int($num)) {
            if($num = 0) {
                return true;
            }
            else {
                return false;
            }
        }
        else if(is_string($num)){
            return ctype_digit($num);
        }
        return false;
    }

    public function num($num) {
        $this->checkNum($num);
        if($num === 'egg') {
            $this->msg .= 'Je to číslo <span class="badge badge-info">'.$this->random_num.'</span>';
            $this->counter = 0;
        }
        else{
        $num = (int) $num;
        if($num < $this->random_num) {
            $this->counter++;
            $this->msg .= 'Myslel som si <span class="badge badge-success">väčšie</span> číslo ako '.$num;
        }
        else if($num > $this->random_num) {
            $this->counter++;
            $this->msg .= 'Myslel som si <span class="badge badge-danger">menšie</span> číslo ako '.$num;
        }
        else if($num == $this->random_num) {
            $this->counter++;
            $this->msg .= 'Uhádol si to na <span class="badge badge-primary">'. $this->counter .'.</span> pokus! ';
            $this->msg .= 'Bolo to číslo <span class="badge badge-info">'.$num.'</span>';
            $this->msg .= '<br>Už som si vymyslel nové číslo. Hádaj znovu!';
            $this->generateRandomNum();
            $this->counter = 0;
        }
    }
    }
    public function html() {
        return'<form class="col-md-3" action="oopgame.php" method="post">
                <p>'. $this->msg .'</p>
                <div class=form-group>
                    <label>Číslo:</label>
                    <input type="text" name="'. $this->input_field_name .'" class="form-control" autofocus>
                </div>
                <div class=form-group>
                    <button type="submit" class="btn btn-warning">Odoslať</button>
                </div>
            </form>
        </div>';
    }
}

session_start();
$Game = new GuessNumber;
echo  $Game->html(); 
//$Game->checkNum("heslo");                                   