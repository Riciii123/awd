<?php

require_once('core/init.php');

$title = 'Poker';
$content = '
<div class="place">
    <div class="field">
        <div class="package">
            <img src="cards/red_back.png" alt="" id="package">
        </div>
        <div class="right-box">
            <div class="wallet">
               200
            </div>
            <div class="buttons">
                <img src="cards/H.png" alt="" data-type="H">
                <img src="cards/D.png" alt="" data-type="D">
                <img src="cards/C.png" alt="" data-type="C">
                <img src="cards/S.png" alt="" data-type="S">
            </div>
            <div class="chips">
                <span class="chip b-green" data-price="5">5</span>
                <span class="chip b-purple" data-price="10">10</span>
                <span class="chip b-red" data-price="20">20</span>
                <span class="chip b-blue" data-price="50">50</span>
                <span class="chip b-black" data-price="100">100</span>
            </div>
        </div>
    </div>
    <div class="table">
        <img src="cards/red_back.png" alt="" data-id="0">
        <img src="cards/red_back.png" alt="" data-id="1">
        <img src="cards/red_back.png" alt="" data-id="2">
        <img src="cards/red_back.png" alt="" data-id="3">
        <img src="cards/red_back.png" alt="" data-id="4">
    </div>
</div>
';

$scripts = ['poker.js'];     

render($content,$title,$scripts);