<?php
  require "albumcommon.php";
class INDEX_HTML extends HTML {
  protected $AlbumList;
  public function __construct() {
    $this->AlbumList = new AlbumList();
    $this->getHTML($this->AlbumList);
  }
  public function getContents() {
    $HEAD = new INDEX_HEAD($this->AlbumList);
    $BODY = new INDEX_BODY($this->AlbumList);
  }
}
class INDEX_HEAD extends HEAD {
  Const strDispTitle = "アルバム一覧 / Mc101";
  public function __construct($AlbumList) {
    $this->strDispTItle = self::strDispTitle;
    $this->strCSSFile = 'albumindex.css';
    $this->Disp_Head_Common();
    $OGP = new index_OGP($AlbumList);
    $this->Disp_Head_CSSLinkAndTitle();
  }
}
class index_OGP extends OGP {
  public function __construct($AlbumList) {
    $this->setOGPData($AlbumList);
    $this->DispOGP();
  }
  protected function setOGPData($AlbumList) {
    $this->strURL= self::BaseURL;
    $this->strImgURL = self::BaseURL.$AlbumList->getAlbumItemDir(0).'/'.$AlbumList->getAlbumItemDir(0).'-M-'.$AlbumList->getCoverSeq(0).'.jpg';
    $this->strOGPDesc = '週末等に撮影した写真たちをこちらでご覧ください。最新のアルバムは「'.$AlbumList->getAlbumItemTitle(0).'」です。';
    $this->strDispTItle = INDEX_HEAD::strDispTitle;
  }
}
class INDEX_BODY extends BODY {
  protected $AlbumList;
  public function __construct($AlbumList) {
    $this->AlbumList = $AlbumList;
    $this->DispBody();
  }
  public function constructContainer() {
	$header = new Index_Header();
    $main = new Index_Main($this->AlbumList);
  }
}
class Index_Main {
  public function __construct($AlbumList) {
    $this->DispMain($AlbumList);
  }
  protected function DispMain($AlbumList) {
?>
<div class="container">
<main>
<div class="panel panel-default center-block">
<div class="panel-heading"><h2 id="album-title" class="panel-title">アルバム一覧</h2></div>
<div class="panel-body"><p id="desc">週末等に撮影した写真たちをこちらでご覧ください。</p>
<p>まず、2017年に入ってからFacebookアルバムに上げた写真たちをこちらに再掲しています。</p>
<p>2016年以前の写真たちも来るべき時に備え、順次、こちらに上げ直す予定です。</p></div>
</div>
<div id="albumlist" class="row">
<?php
  $len=$AlbumList->getAlbumListLength();
  for($j=0;$j<$len;$j++) {
?>
    <div class="col-sm-3 thumbnail albumitem">
      <div class="panel panel-default">
        <div class="panel-body"><a href="<?php echo $AlbumList->getAlbumItemLink($j) ?>"><img src="<?php echo $AlbumList->getCoverImage($j) ?>" alt=""></a></div>
        <div class="panel-footer"><h3 class="panel-title"><a href="<?php echo $AlbumList->getAlbumItemLink($j) ?>"><?php echo $AlbumList->getAlbumItemTitle($j) ?></a></h3></div>
      </div>
    </div>
<?php
  }
?>

</div>
</main>
<?php
  }
}
class Index_Header extends Header {
  public function getNavi() {
    $this->getNaviList('', '../','Mc101トップ');
    $this->getNaviList('', '../about.html','Mc101とは');
    $this->getNaviList('active', './','アルバム');
    $this->getNaviList('', 'http://magazine101.jp/','フリーマガジンいちまるいち');
  }
}
?>
<?php
  $HTML = new INDEX_HTML();
?>
