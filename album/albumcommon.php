<?php
  require "albumdata.php";
class HTML {
  protected $strURL; //ページのURL
  public function getHTML() {
?>
<!DOCTYPE html>
<html lang="ja">
<?php
  $this->getContents(); //各継承先にて定義
?>
</html>
<?php
  }

  public function getDispTItle() { //表示用タイトルの取得
    return $this->strDispTitle;
  }
  public function getURL() {
    return $this->strURL;
  }
  public function getImgURL() {
    return $this->strImgURL;
  }
  public function getOGPDesc() {
    return $this->strOGPDesc;
  }
  public function getCSSFile() {
    return $this->strCSSFile;
  }
}
class HEAD {
  protected $strDispTitle; //表示用タイトル(OGPとtitle要素に使用)
  protected $strImgURL; //OGP用画像URL
  protected $strOGPDesc;  //OGP用説明
  protected $strCSSFile;  //適用するCSSファイル名
  
  protected function Disp_Head_Common() {
?>
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# article: http://ogp.me/ns/article#">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- BootstrapのCSS読み込み -->
<link href="../css/bootstrap.min.css" rel="stylesheet">
<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
<?php
  }
  protected function Disp_Head_CSSLinkAndTitle() {
?>
<link href="../mc101.css" rel="stylesheet">
<link href="<?php echo $this->strCSSFile ?>" rel="stylesheet">
<title><?php echo $this->strDispTItle ?></title>
</head>
<?php
  }
}

class OGP {
  const FB_APP_ID = "308846039518168";
  const Twitter_ID = "@mc101_jp";
  const BaseURL="http://mc101.jp/album/";
  protected function DispOGP() {
?>
<!--OGP関係-->
<meta id="ogTitle" property="og:title" content="<?php echo $this->getDispTitle() ?>">
<meta property="og:type" content="Website">
<meta id="ogURL" property="og:url" content="<?php echo $this->getURL() ?>">
<meta id="ogImg" property="og:image" content="<?php echo $this->getImgURL() ?>">
<meta property="og:site_name"  content="Mc101">
<meta id="ogDesc" property="og:description" content="<?php echo $this->getOGPDesc() ?>">
<!--Facebook用-->
<meta property="fb:app_id" content="<?php echo self::FB_APP_ID?>">
<!--Twitter用-->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:site" content="<?php echo self::Twitter_ID?>">
<meta name="twitter:creator" content="<?php echo self::Twitter_ID?>">

<meta id="twTitle" name="twitter:title" content="<?php echo $this->getDispTitle() ?>">
<meta id="twDesc" name="twitter:description" content="<?php echo $this->getOGPDesc() ?>">
<meta id="twImg" name="twitter:image:src" content="<?php echo $this->getImgURL() ?>">

<?php
  }
  public function getDispTItle() { //表示用タイトルの取得
    return $this->strDispTItle;
  }
  public function getURL() {
    return $this->strURL;
  }
  public function getImgURL() {
    return $this->strImgURL;
  }
  public function getOGPDesc() {
    return $this->strOGPDesc;
  }
  public function getCSSFile() {
    return $this->strCSSFile;
  }
}
class BODY {
  public function DispBody() {
?>
<body>
<div class="container">
<?php
  $this->constructContainer();
  $footer = new Footer();
?>
</div>
<?php
  $footjs = new FootJS();
?>
</body>
<?php
  }
  public function constructHeader() {
	$header = new Header();
  }
  public function constructMAIN() {
    $main = new MAIN();
  }

}

class Header {
  public function __construct() {
    $this->DispHeader();
  }
  public function DispHeader() {
?>
<header>
<h1 id="top"><a href="../"><img class="img-responsive" src="../mc101logo50.gif" alt="[ローカルに生きる人たちを写真でつなぐ Mc101]"></a></h1>
<nav>
<ul class="nav nav-tabs nav-justified">
<?php $this->getNavi() ?>
</ul>
</nav>
</header>
<?php
  }
  public function getNaviList($class,$link,$title) {
    if ( $class=='') {
?>
<li role="presentation"><a href="<?php echo $link ?>"><?php echo $title ?></a></li>
<?php
    }
    else {
?>
<li role="presentation" class="<?php echo $class ?>"><a href="<?php echo $link ?>"><?php echo $title ?></a></li>
<?php
    }
  }
}
class Footer {
  public function __construct() {
    $this->DispFooter();
  }
  protected function DispFooter() {
?>
<footer>
<p><a rel="license" href="http://creativecommons.org/licenses/by-nc-nd/4.0/"><img alt="クリエイティブ・コモンズ・ライセンス" style="border-width:0" src="https://i.creativecommons.org/l/by-nc-nd/4.0/88x31.png" /></a><br />この 作品 は <a rel="license" href="http://creativecommons.org/licenses/by-nc-nd/4.0/">クリエイティブ・コモンズ 表示 - 非営利 - 改変禁止 4.0 国際 ライセンスの下に提供されています。</a></p>
<nav><p><a href="#top">このページのトップへ</a></p></nav>
<address>Mc101 河野 拓(Taku KOHNO) / mc101&#64;mc101.jp</address>
</footer>
<?php
  }
}
class FootJS {
  public function __construct() {
    $this->DispFootJS();
  }
  protected function DispFootJS() {
?>
<!-- jQuery読み込み -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<!-- BootstrapのJS読み込み -->
<script src="../js/bootstrap.min.js"></script>
<?php
  }
}
?>