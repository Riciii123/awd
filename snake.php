<?php

require_once('core/init.php');

$title = "Hadík";
$content = '
    <div class="container-b">
        <h2>Hadík</h2>
        <div class="snake-score">   
                Skóre: <span id="score">0</span>
            <div class="float-right">
                Max Skóre: <span id="score_max">0</span>
            </div>
        </div>
        <canvas width="460" height="460" id="game" class="snake"></canvas>
    </div>

    ';
$scripts = ['theme/js/snake.js'];

render($content,$title,$scripts);