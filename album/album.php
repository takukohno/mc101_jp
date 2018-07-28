<?php
  require "albumcommon.php";
class ALBUM_HTML extends HTML {
  protected $Album;
  public function __construct() {
    $id=$_GET['id'];
    $this->Album = new Album($id);
    $this->getHTML();
  }
  public function getContents() {
    $HEAD = new ALBUM_HEAD($this->Album);
    $BODY = new ALBUM_BODY($this->Album);
  }
}

class ALBUM_HEAD extends HEAD {
  public function __construct($Album) {
    $this->strDispTItle = $Album->strDispTitle;
    $this->strCSSFile = 'albumindex.css';
    $this->Disp_Head_Common();
    $OGP = new ALBUM_OGP($Album);
    $this->Disp_Head_CSSLinkAndTitle();
  }
}
class ALBUM_OGP extends OGP {
  public function __construct($Album) {
    $this->setOGPData($Album);
    $this->DispOGP();
  }
  protected function setOGPData($Album) {
    $this->strURL= self::BaseURL.'album.php?id='.$Album->getAlbumID();
    $this->strImgURL = self::BaseURL.$Album->getAlbumDir().'/'.$Album->getAlbumDir().'-M-'.$Album->getCover().'.jpg';
    $this->strOGPDesc = str_replace('</p><p>','',$Album->getAlbumDesc());
    $this->strDispTItle = $Album->getDispTitle();
  }
}
class ALBUM_BODY extends BODY {
  protected $Album;
  public function __construct($Album) {
    $this->Album = $Album;
    $this->DispBody();
  }
  public function constructContainer() {
	$header = new Album_Header();
    $main = new Album_Main($this->Album);
  }
}
class Album_Main {
  public function __construct($Album) {
    $this->DispMain($Album);
  }
  protected function DispMain($Album) {
?>
<main>
 <div class="panel panel-default center-block">
  <div class="panel-heading"><h2 id="album-title" class="panel-title"><?php echo $Album->getAlbumTitle() ?></h2></div>
  <div class="panel-body"><p id="desc"><?php echo $Album->getAlbumDesc() ?></p></div>
 </div>
 <div id="photolist" class="row">
<?php
  $len=$Album->getAlbumLength();
  for($j=0;$j<$len;$j++) {
    $Album->setTrainPhotoData($j);
?>
  <div class="col-sm-3 thumbnail photoitem"><a href="<?php echo $Album->getHTMLFileLink($j) ?>"><img src="<?php echo $Album->getImgFileS($j) ?>" alt="<?php echo $Album->getPhotoLinkDesc($j) ?>" title="<?php echo $Album->getPhotoLinkDesc($j) ?>"></a></div>
<?php } ?>
 </div>
<?php
 if ($_SERVER["HTTP_HOST"]=='127.0.0.1') {
?>
<!--
<?php
  echo "\n";
  for($j=0;$j<$len;$j++) {
   echo $Album->getPhotoFBDesc($j)."\n\n";
  }
?>
-->
<?php
 }
?>
</main>
<?php
  }
}
class Album_Header extends Header {
  public function getNavi() {
    $this->getNaviList('', '../','Mc101トップ');
    $this->getNaviList('', '../about.html','Mc101とは');
    $this->getNaviList('', './','アルバム一覧');
    $this->getNaviList('', 'http://magazine101.jp/','フリーマガジンいちまるいち');
  }
}
?>
<?php
  $HTML = new ALBUM_HTML();
?>
