<?php

/**
 * class rssReader
 * reads and display rss news , chaching, definable html-layout
 *
 * @package      Package rdfrssNoDomReader
 * @author       Elmar Eigner <eigner@open-eye.de>
 * @copyright    Elmar Eigner, http://www.open-eye.de
 * @version      $Id: rdfrssNoDomReader.class.php,v 1.1 2004/02/27 10:13:40 ee Exp $
 * @access       public
 */

class RdfRssNoDomReader {

  /**
   * einstellbare Voreinstellung der Cache-Nutzung
   *
   * @access public
   * @var boolean $docaching
   */
  public $docaching  = TRUE;

  /**
   * einstellbarer Pfad zur Speicherung der Cache Dateien
   *
   * @access public
   * @var string $cachepath
   */
  public $cachepath  = './files';

  /**
   * einstellbarer Suffix der Cache Dateien
   *
   * @access public
   * @var string $cachesuff
   */
  public $cachesuff  = '.news';

  /**
   * einstellbare Cache Dauer in Sekunden
   *
   * @access public
   * @var integer $cachetime
   */
  public $cachetime  = 3600;

  /**
   * Debugging Ausgaben aktivieren/deaktivieren
   *
   * @access public
   * @var integer $debug Einstellungen: 0 deaktiviert, 1 aktiviert
   */
  public $debug      = 0;

  /**
   * interner Container mit Dateipfad auf Konstruktor-Aufruf
   *
   * @access private
   * @var string $xmlfile
   */
  private $xmlfile    = '';

  /**
   * Interner Container des Datei-Inhaltes
   *
   * @access private
   * @var string $fileblob
   */
  private $fileblob   = '';

  /**
   * Interner Container der XML Header (title, link, description)
   *
   * @access private
   * @var array $rdfmain
   */
  private $rdfmain    = array();

  /**
   * Class Constructor Funktion
   *
   * @param string $dofile Absolute URL zur XML Quelldatei
   * @access public
   */
  public function __construct( $dofile=NULL ) {
    if ($dofile) $this->xmlfile = $dofile;
    $this->delOldCacheFiles();
  }

  /**
   * Cache Dauer einstellen, optional
   * Wenn nicht aufgerufen, wird die Klassenvoreinstellung aus $cachetime verwendet
   *
   * @param integer $val  Caching Interval in Sekunden, 0 deaktiviert das Caching komplett
   * @access public
   */
  public function setCaching( $val )  {
    if ((int)$val > 0) {
      $this->docaching = TRUE;
      $this->cachetime = $val;
    } else {
      $this->docaching = FALSE;
    }
  }

  /**
   * XML Quelldatei aus dem Web oder Cache einlesen
   *
   * @return boolean
   * @access public
   */
  public function getFile( $dofile=NULL ) {
    if ($dofile) $this->xmlfile = $dofile;
    $this->delOldCacheFiles();
    unset($this->fileblob);
    $cachewrite = FALSE;
    if ($this->debug) echo '<hr>'.$dofile.'<br>';
    if ($this->docaching) {
      $cachefile = $this->cachepath.'/'.md5($this->xmlfile).$this->cachesuff;
      if (!file_exists($cachefile) || filemtime($cachefile) < (time() - $this->cachetime)) {
        if ($this->debug) echo 'cache time expired ...';
        $cachewrite = true;
      } else {
        if ($this->debug) echo 'using cache ...';
        $this->xmlfile = $cachefile;
      }
    }
    if ($this->debug) echo '<hr>';
    $FH = @fopen($this->xmlfile, "r");
    if ($FH) {
      while(!feof($FH)) {
        $this->fileblob .= fgets($FH, 1024);
      }
      fclose($FH);
      if (function_exists('mb_detect_encoding')) {
        if (mb_detect_encoding($this->fileblob) == 'UTF-8') {
          $this->fileblob = utf8_decode($this->fileblob);
        }
      }
      if ($cachewrite)  {
        $FSH = @fopen($cachefile, 'w');
        if ($FSH) {
          fwrite($FSH, $this->fileblob, strlen($this->fileblob));
          fclose($FSH);
        }
      }
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Löscht alte Cache-Dateien mit den gleichen Parametern
   * Nach Möglichkeit ist jedoch ein cronjob vorzuziehen und
   * der Aufruf dieser Funktion zu deaktivieren!
   *
   * @access private
   */
  private function delOldCacheFiles() {
    $dh = @dir($this->cachepath);
    if ($dh !== FALSE) {
      while (FALSE !== ($entry = $dh->read())) {
        if ($entry == crc32($this->xmlfile).$this->cachesuff &&
            filemtime($this->cachepath.'/'.$entry) < (time() - $this->cachetime - 120)) {
          if ($this->debug == 1) echo "to del: ".$this->cachepath.'/'.$entry."<br>";
          unlink($this->cachepath.'/'.$entry);
        }
      }
      $dh->close();
    }
  }

  /**
   * Erstellt limitierten Array der Item Inhalts Elemente
   *
   * @param integer $max
   * @access public
   * @return array
   */
  public function getLinkArray( $max=100 ) {
    $ret = array();
    $xmlP = xml_parser_create();
    xml_parser_set_option($xmlP, XML_OPTION_CASE_FOLDING, FALSE);
    xml_parser_set_option($xmlP, XML_OPTION_TARGET_ENCODING, 'ISO-8859-1');
    if (!xml_parse_into_struct($xmlP, trim($this->fileblob), $vals, $index)) {
      if ($this->debug) echo 'Unable to struct xml. XML SOURCE not valid?';
      return FALSE;
    } else {
      #print_r($index);
      #print_r($vals);
      $lastopen = $feedtype = FALSE;
      $myicnt = 0;
      foreach ($vals AS $xv) {
        if ($myicnt >= $max)
          break;
          
        $mytag = strtolower($xv['tag']);

        if ($xv['type'] == 'open') {

          switch (TRUE) {
            case (preg_match('~^(rdf|rss)~', $mytag, $args)):
              $feedtype = $args[1];
              break;
            case ('channel' == $mytag):
              $lastopen = 'channel';
              break;
            case ('item' == $mytag):
              $lastopen = 'item';
              // init item
              $ret[$myicnt] = array('title'=>'','link'=>'','description'=>'','pubdate'=>'','category'=>'');
              break;
            case ('image' == $mytag):
              $lastopen = 'image';
              break;
          }

        } elseif ($xv['type'] == 'close') {

          switch (TRUE) {
            case ('item' == $mytag):
              $myicnt++;
              // DON'T break here!!
            case ('channel' == $mytag):
              $lastopen = FALSE;
              break;
          }

        } elseif ($xv['type'] == 'complete') {

          if ('channel' == $lastopen) {

            switch (TRUE) {
              case ('title' == $mytag):
                $this->rdfmain['title'] = trim($xv['value']);
                break;
              case ('link' == $mytag):
                $this->rdfmain['link'] = trim($xv['value']);
                break;
              case ('description' == $mytag):
                $this->rdfmain['description'] = trim($xv['value']);
                break;
            }

          } elseif ('item' == $lastopen) {

            switch (TRUE) {
              case ('title' == $mytag):
                $ret[$myicnt]['title'] = trim($xv['value']);
                break;
              case ('link' == $mytag):
                $ret[$myicnt]['link'] = trim($xv['value']);
                break;
              case ('description' == $mytag):
                $ret[$myicnt]['description'] = preg_replace('~^(<br.*>)*~iU','',trim($xv['value']));
                break;
              case ('pubdate' == $mytag):
                $ret[$myicnt]['pubdate'] = trim($xv['value']);
                break;
              case ('category' == $mytag):
                $ret[$myicnt]['category'] = trim($xv['value']);
                break;
            }
          }
        }
      }
    }
    if ($this->debug == 1) echo htmlspecialchars(print_r($ret, TRUE)).'<hr>';
    return $ret;
  }

  /**
   * Erstellt den Ausgabe String für die Feed Header
   *
   * @access public
   * @return string
   */
  public function getHeader() {
    $str = '';
    if (isset($this->rdfmain['link'])) {
      $str .= sprintf('<a href="%s" target="_blank"', $this->rdfmain['link']);
      if (isset($this->rdfmain['description']))    {
        $str .= sprintf(' title="%s"', htmlentities($this->rdfmain['description']));
      }
      $str .= '>';
    }
    if (isSet($this->rdfmain['title'])) {
      $str .= htmlentities($this->rdfmain['title']);
    }
    if (isSet($this->rdfmain['link'])) {
      $str .= '</a>';
    }
    return $str;
   }

  /**
   * Gibt die Daten im übergebenen HTML Layout aus
   *
   * @param array $la Muss der return von getLinkArray(x) sein
   * @param string $h Header-html
   * @param string $l Loop-html
   * @param string $f Footer-html
   * @access public
   */
  public function showNews( $la, $h, $l, $f ) {
    $buff = str_replace('%TITLE%', $this->getHeader(), $h);
    $loop = 1;
    foreach ($la AS $news) {
      $lfntext = str_replace('%LFN%', $loop++, $l);
      $thelink = str_replace('%URL%', isSet($news['link'])? $news['link'] : '', $lfntext);
      $thelink = str_replace('%DESC%', isSet($news['description'])? $news['description'] : '', $thelink);
      $thelink = str_replace('%PUBDATE%', isSet($news['pubdate'])? $news['pubdate'] : '', $thelink);
      $buff .= str_replace('%NEWS%', isSet($news['title'])? $news['title'] : '', $thelink);
    }
    $buff .= $f;
    return $buff;
  }

}