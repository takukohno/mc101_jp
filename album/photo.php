<?php
  require "albumcommon.php";
class PHOTO_HTML extends HTML {
  protected $Photo;
  public function __construct() {
    $id=$_GET['id'];
    $seq=$_GET['seq'];
    $this->Photo = new TrainPhoto($id,$seq);
    $this->getHTML($this->Photo);
  }
  public function getContents() {
    $HEAD = new PHOTO_HEAD($this->Photo);
    $BODY = new PHOTO_BODY($this->Photo);
  }
}
class PHOTO_HEAD extends HEAD {
  public function __construct($Photo) {
    $this->strDispTItle = $Photo->strDispTitle;
    $this->strCSSFile = 'albumphoto.css';
    $this->Disp_Head_Common();
    $OGP = new PHOTO_OGP($Photo);
    $this->Disp_Head_CSSLinkAndTitle();
  }
}
class PHOTO_OGP extends OGP {
  public function __construct($Photo) {
    $this->setOGPData($Photo);
    $this->DispOGP();
  }
  protected function setOGPData($Photo) {
    $this->strURL= self::BaseURL.$Photo->getHTMLFileURL();
    $this->strImgURL = $Photo->getImgURLM();
    $this->strOGPDesc = str_replace('</p><p>',' ',$Photo->getPhotoDesc());
    $this->strDispTItle = $Photo->getDispTitle();
  }
}
class PHOTO_BODY extends BODY {
  protected $Photo;
  public function __construct($Photo) {
    $this->Photo = $Photo;
    $this->DispBody();
  }
  public function constructContainer() {
	$header = new Photo_Header($this->Photo->getAlbumID());
    $main = new Photo_Main($this->Photo);
  }
}
class Photo_Main {
  protected $Photo;
  public function __construct($Photo) {
    $this->Photo = $Photo;
    $this->DispMain($Photo);
  }
  protected function DispMain($Photo) {
?>
<main>
<div id="photopanel" class="panel panel-default center-block">
<div class="panel-heading">
<h2 class="panel-title">アルバム「<a id="album-title" href="<?php echo $Photo->getAlbumLink() ?>"><?php echo $Photo->getAlbumTitle() ?></a>」の写真(<span id="photono"><?php echo $Photo->getPos()+1 ?></span>枚目/<span id="albumsum"><?php echo $Photo->getAlbumLength() ?></span>枚中)</h2>
</div>
<div class="panel-body">
<a id="photoLarge" href="<?php echo $Photo->getImgURLL() ?>"><img id="photoMiddle" class="img-responsive" alt="[写真]" src="<?php echo $Photo->getImgURLM() ?>"></a>
</div>
<div class="panel-footer">
<p id="description"><?php echo $Photo->getPhotoDesc() ?></p>
<?php $this->getNavi($Photo->getPos(), $Photo->getSeq()) ?>
</div>
</div>
</main>
<?php
  }
  public function getNavi($pos, $seq) {
  ?>
<nav>
<ul class="pager">
<?php
    if ($pos > 0) {
      $this->getPager('mae', 'previous', $this->getSeqfromPos($pos-1), 'p', '前');
    }
    if ($pos < $this->Photo->getAlbumLength()-1) {
      $this->getPager('tsugi', 'next', $this->getSeqfromPos($pos+1), 'n', '次');
    }
?>
</ul>
</nav>
<?php
  }
  protected function getPager($id, $class, $seq, $key, $kanji) {
?>
<li id="<?php echo $id ?>" class="<?php echo $class ?>"><a href="photo.php?id=<?php echo $this->Photo->getAlbumID().'&seq='.$seq ?>#photopanel" accesskey="<?php echo $key ?>"><?php echo $kanji ?>へ(<span class="pcAkey"><?php echo strtoupper($key) ?></span>)</a></li>
<?php
  }
  protected function getSeqfromPos($pos) {
    $seq=$this->Photo->album_JSON['AlbumPhoto'][$pos]['seq'];
    return $seq;
  }
}
class PHOTO_Header extends Header {
  protected $id;
  public function __construct($id) {
    $this->id = $id;
    $this->DispHeader();
  }
  public function getNavi() {
    $this->getNaviList('', '../','Mc101トップ');
    $this->getNaviList('', './','アルバム一覧');
    $this->getNaviList('', 'album.php?id='.$this->id,'このアルバムのトップ');
  }
}
?>
<?php
  $HTML = new PHOTO_HTML();
?>
