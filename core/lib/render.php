<?php
function render($content, $title = "No title", $scripts = []) {
    
    $html_scripts = '';
    if(is_array($scripts)){
        foreach ($scripts as $url) {
            $html_scripts .= '<script src="'. $url .'"></script>' . PHP_EOL;
        }
    }
    
    echo '<!DOCTYPE html>
    <html lang="sk">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>'.$title.' | Black pantherov web</title>
        <link rel="stylesheet" href="theme/plugins/fancy/jquery.fancybox.css?v=2.1.4" type="text/css" media="screen" />
        <link rel="stylesheet" href="theme/plugins/fancy/helpers/jquery.fancybox-buttons.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="theme/plugins/fancy/helpers/jquery.fancybox-thumbs.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="theme/plugins/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="theme/plugins/bootstrap/css/bootstrap-grid.min.css">
        <link rel="stylesheet" href="theme/css/main.css">
        <link rel="stylesheet" href="theme/css/test.css">
        <link rel="stylesheet" href="p.css">
        <link rel="stylesheet" href="sweetalert/sweetalert2.min.css">
        <link rel="shortcut icon" href="favicon.ico" type="image/x-icon"/>
    </head>
    <body>
        <div class="wrapper">
            <header>'.adminpanel().'</header>
            <nav>
                 <ul class="clearfix">
                    <li> <a href="index.php">Úvod</a> </li>
                    <li> <a href="gallery.php">Galéria</a></li>
                    <li> <a href="contact.php">Kontakt</a></li>
                    <li> <a href="login.php">Login</a></li>
                    <li> <a href="test.php">Test</a></li>
                    <li> <a href="snake.php">Hadík</a></li>
                    <li> <a href="game.php">Hádaj číslo</a></li>
                   <li> <a href="pacman.php">Pacman</a></li>
                   <li> <a href="game2.php">Vymysli si číslo</a></li>
                   <li> <a href="poker.php">Poker</a></li>
                   
                </ul>
                <br>
            </nav>
            <section class="content clearfix">
                '.notifyGet().'
                '.$content.'
            </section>
            <footer>
                <div class="wrapper clearfix">
                    '.htmlFooter().'    
            </footer>
        </div>
        <script type="text/javascript" src="theme/js/jquery-3.5.1.min(3).js"></script>
        <script type="text/javascript" src="theme/plugins/bootstrap/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="theme/plugins/fancy/jquery.fancybox.js"></script>
        <script type="text/javascript" src="theme/plugins/fancy/jquery.fancybox.pack.js"></script>
        <script type="text/javascript" src="theme/plugins/fancy/jquery.mousewheel-3.0.6.pack.js"></script>
        <script type="text/javascript" src="theme/plugins/fancy/helpers/jquery.fancybox-buttons.js"></script>
        <script type="text/javascript" src="theme/plugins/fancy/helpers/jquery.fancybox-media.js"></script>
        <script type="text/javascript" src="theme/plugins/fancy/helpers/jquery.fancybox-thumbs.js"></script>
        <script type="text/javascript" src="theme/plugins/tinymce/tinymce.min.js"></script>
        <script type="text/javascript" src="theme/js/config.js?v=2.0"></script>
        <script src="sweetalert/sweetalert2.min.js"></script>
        '.$html_scripts.'
    </body>
    </html>';
    exit;
}
