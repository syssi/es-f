<?php
/**
 * Logfiles module
 *
 * @ingroup    Module-Logfiles
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    $Id$
 */

class esf_Module_Logfiles extends esf_Module {

  /**
   *
   */
  public function __construct() {
    parent::__construct();

    $this->Id = $this->Request('id');
    $this->Bug = @base64_decode($this->Request('bug'));
  }

  /**
   *
   */
  public function IndexAction() {
    foreach ($this->LogFile as $id => $log) {
      $p = array('id' => $id);
      TplData::add('LOGS', array(
        'NAME'         => (file_exists($log) ? str_replace($_SERVER['DOCUMENT_ROOT'].'/', '', realpath($log)) : $log),
        'FILESIZE'     => File::Size($log),
        'LASTMODIFIED' => File::MTime($log),
        'SHOWURL'      => Core::URL(array('action'=>'show', 'params'=>$p)),
        'DELETEURL'    => Core::URL(array('action'=>'delete', 'params'=>$p)),
      ));
    }

    if (esf_User::isValid()) {
      foreach (glob(esf_User::UserDir().'/esniper.bug/esniper.*.html') as $bug) {
        $p = array('bug' => base64_encode($bug));
        TplData::add('LOGS', array(
          'NAME'         => str_replace($_SERVER['DOCUMENT_ROOT'].'/', '', $bug),
          'FILESIZE'     => File::Size($bug),
          'LASTMODIFIED' => File::MTime($bug),
          'SHOWURL'      => Core::URL(array('action'=>'bug', 'params'=>$p)),
          'DELETEURL'    => Core::URL(array('action'=>'delete', 'params'=>$p)),
        ));
      }
    }
  }

  /**
   *
   */
  public function ShowAction() {
    if (isset($this->LogFile[$this->Id])) {
      $log = $this->LogFile[$this->Id];
      if (file_exists($log))
        $log = str_replace($_SERVER['DOCUMENT_ROOT'].'/', '', realpath($log));
      TplData::set('NAME', $log);
      TplData::set('FILESIZE', File::Size($log));
      $log = @file_get_contents($log);
      TplData::set('LOG', (!empty($log) ? htmlspecialchars($log) : '&lt;empty&gt;'));
      TplData::set('DELETEURL', Core::URL(array('action'=>'delete', 'params'=>array('id'=>$this->Id))));
    } else {
      $this->forward();
    }
  }
  /**
   *
   */
  public function BugAction() {
    if ($this->Bug) {
      TplData::set('NAME', str_replace($_SERVER['DOCUMENT_ROOT'].'/', '', $this->Bug));
      TplData::set('FILESIZE', File::Size($this->Bug));
      $p = array('bug' => base64_encode($this->Bug));
      TplData::set('LOGSRC',    Core::URL(array('action'=>'get',    'params'=>$p)));
      TplData::set('DELETEURL', Core::URL(array('action'=>'delete', 'params'=>$p)));
    } else {
      $this->forward();
    }
  }

  /**
   *
   */
  public function DeleteAction() {
    if (!empty($this->LogFile[$this->Id])) {
      $log = $this->LogFile[$this->Id];
      if (File::Delete($log)) {
        Messages::Success(Translation::get('Logfiles.Deleted', $log));
      } else {
        Messages::Error(Translation::get('Logfiles.DeleteError', $log));
      }
    } elseif ($this->Bug) {
      File::Delete($this->Bug);
      Messages::Success(Translation::get('Logfiles.BugDeleted', $log));
    }
    $this->forward();
  }

  /**
   *
   */
  public function GetAction() {
    if ($this->Bug) die(@readfile($this->Bug));
    $this->forward();
  }

  //--------------------------------------------------------------------------
  // PRIVATE
  //--------------------------------------------------------------------------

  /**
   *
   */
  private $LogFiles;

  /**
   *
   */
  private $Id;

  /**
   *
   */
  private $Bug;

}