<?php
function _e($string)
{
	return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
function mySize($size, $unit = "kB")
{
	switch ($unit) {
		case 'kB':
			return round($size / 1024, 0) . " " . "kB";
			break;
		case 'MB':
			return round($size / 1024 / 1024, 0) . " " . "MB";
	}
}
define("IMG_MAX_WIDTH", 1290);
define("IMG_MAX_HEIGHT", 720);
define("ADMIN_SALT", 'hesloooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo');
define("ADMIN_HASH", "efe2a63d08adc15883274ec863e0f983a871f7eb6ba84ac4f0b0bb13b2494aa3");
define("PATH_GAME_LOG", "log/game_data.dat");

function notifySet($text, $type)
{
	$_SESSION['notify'] = $text;
	$_SESSION['type'] = $type;
}
function notifyGet()
{
	if (empty($_SESSION['notify'])) {
		return '';
	};
	$html = '<div class="notify ' . $_SESSION['type'] . '">' . $_SESSION['notify'] . '</div>';
	unset($_SESSION['notify']);
	unset($_SESSION['type']);
	return $html;
}
function redirect($page)
{
	header("Location:" . $page);
	exit;
}

function ImgResizer($file_src, $file_dest, $MaxWidth, $MaxHeight)
{
	list($OrigWidth, $OrigHeight, $type) = getimagesize($file_src);

	if ($MaxWidth == 0) $MaxWidth = $OrigWidth;
	if ($MaxHeight == 0) $MaxHeight = $OrigHeight;

	$pw = $OrigWidth / $MaxWidth;
	$ph = $OrigHeight / $MaxHeight;

	if ($file_src == $file_dest && $pw <= 1 && $ph <= 1) return;
	if ($pw <= 1 && $ph <= 1 && $type == 2) {
		copy($file_src, $file_dest);
		return;
	}

	if ($pw > $ph) $p = $pw;
	else $p = $ph;
	if ($p < 1) $p = 1;

	$NewWidth = (int) round($OrigWidth / $p);
	$NewHeight = (int) round($OrigHeight / $p);

	$image_new = imagecreatetruecolor($NewWidth, $NewHeight);
	$white = imagecolorallocate($image_new, 255, 255, 255);
	imagefill($image_new, 0, 0, $white);
	switch ($type) {
		case 1:   //   gif -> jpg
			$image = imagecreatefromgif($file_src);
			break;
		case 2:   //   jpeg -> jpg
			$image = imagecreatefromjpeg($file_src);
			break;
		case 3:  //   png -> jpg
			$image = imagecreatefrompng($file_src);
			break;
	}

	imagecopyresampled($image_new, $image, 0, 0, 0, 0, $NewWidth, $NewHeight, $OrigWidth, $OrigHeight);
	imagejpeg($image_new, $file_dest, 90);
	imagedestroy($image);
	imagedestroy($image_new);
}

function footer()
{
	$url = 'https://nixo.intelle.pro/footer.json';
	$path = 'cache/footer.json';
	/*if (!file_exists($path)) {
		$d = file_get_contents($url);
		file_put_contents($path, $d);
	}
	$time = filemtime($path);
	if ($time < (time() - 60 * 60 * 24)) {
		$d = file_get_contents($url);
		file_put_contents($path, $d);
	}*/
	$d = file_get_contents($url);
	return json_decode($d, true);
}

function htmlFooter()
{
	$d = footer();
	$i = 0;
	$html = '';
	foreach ($d as $box) {
		$html .= '<div class="box">
					<img src="' . _e($box["image"]) . '" alt="">
					<h2><a href="#">' . _e($box["title"]) . '</a></h2>
					<p>' . _e($box["short"]) . '</p>
				</div>
		
		
		';
		$i++;
		if ($i === 3) {
			break;
		}
	}
	return $html;
}

function articles()
{
	$url = 'https://nixo.intelle.pro/articles.json';
	$path = 'cache/articlesdat.json';
	/*if (!file_exists($path)) {
		$d = file_get_contents($url);
		file_put_contents($path, $d);
	}
	$time = filemtime($path);
	if ($time < (time() - 60 * 60 * 24)) {
		$d = file_get_contents($url);
		file_put_contents($path, $d);
	}*/
	$d = file_get_contents($url);
	return json_decode($d, true);
}

function htmlArticles()
{
	$d = articles();
	$i = 0;
	$html = '';
	foreach ($d as $box) {
		$html .= '<div class="box">
					<img src="' . _e($box["image"]) . '" alt="">
					<h2><a href="#">' . _e($box["title"]) . '</a></h2>
					<p>' . _e($box["short"]) . '</p>
				</div>
				
		
		
		';
		$i++;
		if ($i === 3) {
			break;
		}
	}
	return $html;
}

function rss()
{
	$domOBJ = new DOMDocument();
	$d =  file_get_contents("https://www.sector.sk/rss/");
	$domOBJ->loadXML($d); //XML page URL
	$i = 0;
	$html = '';

	$content = $domOBJ->getElementsByTagName("item");

	foreach ($content as $data) {
		$i++;
		$title = $data->getElementsByTagName("title")->item(0)->nodeValue;
		$link = $data->getElementsByTagName("link")->item(0)->nodeValue;
		echo "$title :: $link<br>";
		$html .= '
		<p>
			<a href="' . _e($link) . '">' . _e($title) . '</a>

		<p>';

		if ($i === 10) {
			break;
		}
	}
	return $html;
}

function global_log()
{
		$data = date('d.m.Y H:i:s') . "\t" .
			$_SERVER['REMOTE_ADDR'] . "\t" .
			$_SERVER['HTTP_USER_AGENT'] . "\t" .
			$_SERVER['SCRIPT_NAME'] . "\n";
		file_put_contents(PATH_GAME_LOG, $data, FILE_APPEND);

}