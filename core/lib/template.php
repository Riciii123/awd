<?php
function render($content,$title = "No title") {
    echo '<!DOCTYPE html>
    <html lang="sk">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>'._e($title).' | Black pantherov web</title>
        <link rel="stylesheet" href="theme/plugins/fancy/jquery.fancybox.css?v=2.1.4" type="text/css" media="screen" />
        <link rel="stylesheet" href="theme/plugins/fancy/helpers/jquery.fancybox-buttons.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="theme/plugins/fancy/helpers/jquery.fancybox-thumbs.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="theme/css/main.css">
        <link rel="stylesheet" href="theme/css/test.css">
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
                </ul>
                <br>
            </nav>
            <section class="content clearfix">
                '.notifyGet().'
                '.$content.'
            </section>
            <footer>
                <div class="wrapper clearfix">
                    <div class="box">
                        <img src="articles/news3.gif" alt="">
                        <h2><a href="#">Minecraft 1.16</a></h2>
                        <p> Nový nether update.</p>
                    </div>
                    <div class="box">
                        <img src="articles/news2.png" alt="">
                        <h2><a href="#"> 1.16.3 </a></h2>
                    </div>
                    <div class="box">
                        <img src="articles/news1.png" alt="">
                        <h2><a href="#"> 1.16.2 </a></h2>
                    </div>
                </div>
            </footer>
        </div>
        <script type="text/javascript" src="theme/js/jquery-1.9.0.min.js"></script>
        <script type="text/javascript" src="theme/plugins/fancy/jquery.fancybox.js"></script>
        <script type="text/javascript" src="theme/plugins/fancy/jquery.fancybox.pack.js"></script>
        <script type="text/javascript" src="theme/plugins/fancy/jquery.mousewheel-3.0.6.pack.js"></script>
        <script type="text/javascript" src="theme/plugins/fancy/helpers/jquery.fancybox-buttons.js"></script>
        <script type="text/javascript" src="theme/plugins/fancy/helpers/jquery.fancybox-media.js"></script>
        <script type="text/javascript" src="theme/plugins/fancy/helpers/jquery.fancybox-thumbs.js"></script>
        <script type="text/javascript" src="theme/js/config.js?v=2.0"></script>
    </body>
    </html>';
    exit;
}