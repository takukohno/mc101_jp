<?php
class AlbumList { //アルバム一覧クラス
  protected $List_JSON;  //アルバム一覧JSON収容配列
  protected $AlbumListLength;  //アルバムの数
  protected $strAlbumID; //ID(配列)
  protected $strAlbumDate; //日付(配列)
  protected $strAlbumName; //名称(配列)
  protected $CoverSeq;//表紙画像番号
  protected $CoverImage;//表紙画像パス
  protected $strAlbumLink;
  protected $strAlbumDir;
  protected $strAlbumTitle; //タイトル(表示用に日付を入れたもの)
  public function __construct() {
    $this->readAlbumList_JSON();
  }
  protected function readAlbumList_JSON() {
    $this->List_JSON = $this->readJSON('albumlist.json');
    $this->setAlbumListLength();
    for($i=0;$i<$this->AlbumListLength;$i++) {
      $AlbumList_Item=$this->List_JSON['Album'][$i];
      $this->strAlbumID[$i]=$AlbumList_Item['AlbumID'];
      $this->strAlbumDate[$i]=$AlbumList_Item['AlbumDate'];
      $this->strAlbumName[$i]=$AlbumList_Item['AlbumName'];
      $this->CoverSeq[$i]=$AlbumList_Item['Cover'];
      $this->strAlbumTitle[$i] = $this->strAlbumDate[$i].' '.$this->strAlbumName[$i];
      $this->strAlbumLink[$i]='album.php?id='.$this->strAlbumID[$i];
      $this->strAlbumDir[$i]=$this->setAlbumDir($this->strAlbumID[$i]);
      $this->strCoverImage[$i]=$this->strAlbumDir[$i].'/'.$this->strAlbumDir[$i].'-S-'.$this->CoverSeq[$i].'.jpg';
    }
  }
  protected function readJSON($jsonFN) {
    $handle = fopen($jsonFN, 'r');
    $array_JSON = json_decode(fread($handle, filesize($jsonFN)),true);
    fclose($handle);
    return $array_JSON;
  }
  protected function setAlbumListLength() {
    $this->AlbumListLength = count($this->List_JSON['Album']);
  }
  public function getAlbumListLength() {
    return $this->AlbumListLength;
  }
  public function getAlbumItemID($seq) {
    return $this->strAlbumID[$seq];
  }
  protected function setAlbumDir($id) {
    if(strlen($id)==8) {
      $Dir = $id;
    }
    else {
      $Dir = substr($id,0,8);
    }
    return $Dir;
  }
  public function getAlbumItemDir($seq) {
    return $this->strAlbumDir[$seq];
  }
  public function getAlbumItemDate($seq) {
    return $this->strAlbumDate[$seq];
  }
  public function getAlbumItemName($seq) {
    return $this->strAlbumName[$seq];
  }
  public function getAlbumItemTitle($seq) {
    return $this->strAlbumTitle[$seq];
  }
  public function getCoverSeq($seq) {
    return $this->CoverSeq[$seq];
  }
  public function getAlbumItemLink($seq) {
    return $this->strAlbumLink[$seq];
  }
  public function getCoverImage($seq) {
    return $this->strCoverImage[$seq];
  }
}

class Album extends AlbumList {
  protected $id;
  protected $strAlbumDesc;
  protected $AlbumLength;
  protected $array_json;
  protected $array_json_item;
  protected $strHTMLFileLink;
  protected $strImgFileNameS;
  protected $strImgFileNameM;
  protected $strImgFileNameL;
  protected $strPhotoDesc;
  protected $strPhotoDescForIndex;
  public function __construct($id) {
    $this->readAlbumJSON($id);
  }
  protected function readAlbumJSON($id) {
    $this->setAlbumID($id);
    $this->strAlbumDir = $this->setAlbumDir($id);
    $this->album_JSON = $this->setArray_JSON();
    $this->strAlbumDate = $this->album_JSON['AlbumDate'];
    $this->strAlbumName = $this->album_JSON['AlbumName'];
    $this->setAlbumTitle();
    $this->setDispTitle();
    $this->setAlbumDesc();
    $this->CoverSeq = $this->album_JSON['Cover'];
    $this->AlbumLength = count($this->album_JSON['AlbumPhoto']);
  }
  public function setAlbumID($id) {
    $this->strAlbumID = $id;
  }
  public function setAlbumTitle() {
    $this->strAlbumTitle = $this->strAlbumDate .' '.$this->strAlbumName ;
  }
  public function setDispTitle() {
    $this->strDispTitle = 'アルバム「'.$this->strAlbumTitle.'」/ Mc101';
  }
  protected function setArray_JSON() {
    $jsonFN = $this->strAlbumDir.'/'.$this->strAlbumID.'.json';
    $array_json = $this->readJSON($jsonFN);
    return $array_json;
  }
  protected function setAlbumDesc() {
    $this->strAlbumDesc = $this->album_JSON['Description'];
  }
  public function getAlbumID() {
    return $this->strAlbumID;
  }
  public function getAlbumDir() {
    return $this->strAlbumDir;
  }
  public function getAlbumDesc() {
    return $this->strAlbumDesc;
  }
  public function getAlbumTitle() {
    return $this->strAlbumTitle;
  }
  public function getDispTitle() {
    return $this->strDispTitle;
  }
  public function getAlbumLength() {
    return $this->AlbumLength;
  }
  public function getCover() {
    return $this->CoverSeq;
  }
  public function setTrainPhotoData($pos) {
    $this->array_json_item = $this->album_JSON['AlbumPhoto'][$pos];
    $ItemList=array("seq", "AdditionalInfo", "Comment", "Formation", "Series", "TrainName", "TrainNumber", "Section", "LineName");
    list($strSeq, $strAdditionalInfo, $strComment, $strFormation, $strSeries, $strTrainName, $strTrainNumber, $strSection, $strLineName)=$this->getValue($this->array_json_item,$ItemList);
    $strDescPlace = '['. $strLineName.$strSection.'] ';
    $strDescTrain = $strTrainNumber.$strTrainName;
    $strDescVehicle = $strSeries.$strFormation;
    $strDescData = $this->connectDesc($strDescTrain,'、',$strDescVehicle);
    $strDescData = $this->connectDesc($strDescData,'、',$strAdditionalInfo);
    $strDescData = $this->connectDesc($strDescData,'</p><p>',$strComment);
    $this->strPhotoDesc[$pos] = $strDescPlace.$strDescData;
    $this->strHTMLFileLink[$pos]='photo.php?id='.$this->strAlbumID.'&seq='.$strSeq;
    list($this->strImgFileNameS[$pos], $this->strImgFileNameM[$pos], $this->strImgFileNameL[$pos]) = $this->setImgURL($this->strAlbumDir,array('S','M','L'),$strSeq);
  }
  private function getValue($destArray, $ItemList) {
    foreach($ItemList as $key=>$ItemName) {
      if (isset($destArray[$ItemName])) {
        $strValue[$key] = $destArray[$ItemName];
      }
      else {
        $strValue[$key] = '';
      }
    }
    return $strValue;
  }
  private function setImgURL($Dir,$LetterList, $seq) {
    foreach($LetterList as $key=>$Letter) {
      $ImgURL[$key]=$Dir.'/'.$Dir.'-'.$Letter.'-'.$seq.'.jpg';
    }
    return $ImgURL;
  }
  private function connectDesc($strDescA, $strConnect, $strDescB) {
    if($strDescB == '') {
      $strDescC = $strDescA;
    }
    else if($strDescA == '') {
      $strDescC = $strDescB;
    }
    else {
      $strDescC = $strDescA.$strConnect.$strDescB;
    }
    return $strDescC;
  }
  public function getHTMLFileLink($pos) {
    return $this->strHTMLFileLink[$pos];
  }
  public function getImgFileS($pos) {
    return $this->strImgFileNameS[$pos];
  }
  public function getImgFileM($pos) {
    return $this->strImgFileNameM[$pos];
  }
  public function getImgFileL($pos) {
    return $this->strImgFileNameL[$pos];
  }
  public function getPhotoLinkDesc($pos) {
    return str_replace('</p><p>',' ',$this->strPhotoDesc[$pos]);
  }
  public function getPhotoFBDesc($pos) {
    return str_replace('</p><p>',"\n",$this->strPhotoDesc[$pos]);
  }
}
class TrainPhoto extends Album {
  private $pos;
  private $seq;
  protected $strAlbumLink;
  public function __construct($id,$seq) {
    $this->readAlbumJSON($id);
    $this->seq=$seq;
    $this->pos=$this->getPhotoPosition($seq);
    $this->setTrainPhotoData($this->pos);
    $this->strDispTitle = '写真表示:アルバム「'.$this->getAlbumTitle().'」の写真('.($this->pos + 1).'枚目/'.$this->getAlbumLength().'枚中) / Mc101';
    $this->strAlbumLink = 'album.php?id='.$this->getAlbumID();
  }
  public function getSeq() {
    return $this->seq;
  }
  public function getPos() {
    return $this->pos;
  }
  public function getAlbumLink() {
    return $this->strAlbumLink;
  }  
  public function getPhotoDesc() {
    return $this->strPhotoDesc[$this->pos];
  }
  public function getHTMLFileURL() {
    return $this->strHTMLFileLink[$this->pos];
  }
  public function getImgURLS() {
    return $this->strImgFileNameS[$this->pos];
  }
  public function getImgURLM() {
    return $this->strImgFileNameM[$this->pos];
  }
  public function getImgURLL() {
    return $this->strImgFileNameL[$this->pos];
  }
  public function getPhotoPosition($seq) {
    $pos=0;
    for($j=0;$j<$this->getAlbumLength();$j++) {
      if ($this->album_JSON['AlbumPhoto'][$j]['seq'] == $seq) {
        $pos=$j;
      }
    }
    return $pos;
  }
}

?>