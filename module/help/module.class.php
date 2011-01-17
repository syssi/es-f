<?php
/**
 * Auction Help module
 *
 * @ingroup    Module-Help
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    $Id: v2.4.1-51-gfeddc24 - Sun Jan 16 21:09:59 2011 +0100 $
 */
class esf_Module_Help extends esf_Module {

  /**
   *
   */
  public function __construct() {
    parent::__construct();

    if (isset($this->Request['ext'])) {
      $ext = @explode('-', @$this->Request['ext']);
      $this->Scope = @$ext[0];
      $this->Name  = strtolower(@$ext[1]);
    } else {
      $this->Scope = sprintf('{%s,%s}', esf_Extensions::MODULE, esf_Extensions::PLUGIN);
      $this->Name  = '*';
    }
    $path = $this->Scope . '/' . $this->Name;

    if (Registry::get('EnglishAsDefault'))
      foreach (glob($path.'/language/en.php') as $file)
        Loader::Load($file);

    foreach (glob($path.'/language/'.Session::get('Language').'.php') as $file)
      Loader::Load($file);

    $this->Dir = $path . '/help';
    $this->File = $this->Dir . '/' . Session::get('language').'.htm';
  }

  /**
   * @return array Array of actions handled by the module
   */
  public function handles() {
    return array('index', 'topic', 'show', 'edit');
  }

  /**
   *
   */
  public function IndexAction() {
    foreach (esf_Extensions::$Types as $type) {
      TplData::set('Scope.'.$type.'.Name', ucwords($type));
      foreach (esf_Extensions::getExtensions($type) as $extension) {
        TplData::add('Scope.'.$type.'.Data', array(
          'Name'    => Translation::getNVL($extension.'.Title', ucwords($extension)),
          'Desc'    => Registry::get($type.'.'.$extension.'.Name', ''),
          'ShowURL' => Core::URL(array('action'=>'show', 'params'=>array('ext'=>$type.'-'.$extension))),
        ));
      }
    }
  }

  /**
   *
   */
  public function ShowAction() {
    TplData::set('SubTitle2', Translation::getNVL($this->Name.'.TITLE', ucwords($this->Name)));
    // >> Debug
    if (file_exists(BASEDIR.'/DEVELOP/fckeditor/fckeditor.js')) {
      is_dir($this->Dir) || @mkdir($this->Dir);
      if (is_writable($this->Dir) OR is_writable($this->File)) {
        TplData::set('EditUrl', Core::URL(array('action'=>'edit', 'params'=>array('ext'=>$this->Scope.'-'.$this->Name))));
        TplData::set('HelpFile', $this->File);
      } else {
        Messages::Error('['.$this->File.'] is not writable');
      }
    }
    // << Debug
    if (!file_exists($this->File)) $this->File = $this->Dir.'/en.htm';
    TplData::set('HELPTEXT', @file_get_contents($this->File));
 
    TplData::set('SCOPE', ucwords($this->Scope));
    TplData::set('EXTENSION', TplData::get('SubTitle2'));
    TplData::set('CATEGORY', Registry::get($this->Scope.'.'.$this->Name.'.Category'));
    TplData::set('DESCRIPTION', Registry::get($this->Scope.'.'.$this->Name.'.Name'));
    TplData::set('AUTHOR', Core::Email(Registry::get($this->Scope.'.'.$this->Name.'.Email'),
                                       Registry::get($this->Scope.'.'.$this->Name.'.Author')));
    TplData::set('VERSION', Registry::get($this->Scope.'.'.$this->Name.'.Version', 0));
  }

  /**
   *
   */
  public function EditAction() {
    if ($this->isPost()) {
      $this->File = $this->Request('helpfile');
      if (file_put_contents($this->File, trim($this->Request('helptext')))) {
        @chmod($this->File, 0666);
        Messages::Success(Translation::get('Help.Saved'));
      } else {
        Messages::Error('Saving '.$this->File);
      }
      $this->Scope = $this->Request('scope');
      $this->Name = $this->Request('name');
      $this->forward('show');
    } else {
      TplData::set('SubTitle2', Translation::getNVL($this->Name.'.TITLE', ucwords($this->Name)));
      TplData::add('HtmlHeader.JS', 'DEVELOP/fckeditor/fckeditor.js');

      TplData::set('Scope', $this->Scope);
      TplData::set('Name', $this->Name);

      TplData::set('EditLanguage', Registry::get('Language'));

      if (!file_exists($this->File)) $this->File = $this->Dir.'/en.htm';
      $text = @file_get_contents($this->File);
      if (empty($text)) $text = file_get_contents(dirname(__FILE__).'/dist/template.htm');
      TplData::set('HelpFile', $this->File);
      TplData::set('HelpText', addslashes($text));
    }
  }

  /**
   *
   */
  public function TopicAction() {
    if (!isset($this->Request['t']))
      return;

    $topic = $this->Request['t'];

    Registry::set('esf.ContentOnly', TRUE);
    @list($title, $help) = explode('|', Translation::get($topic), 2);
    if (empty($help)) {
      // no title, help is in title
      $help = $title;
#      $title = explode(Translation::$NameSpaceSeparator, $topic);
#      $title = array_pop($title);
      $title = '';
    }
    TplData::set('Title', $title);
    TplData::set('Help', $help);
  }

  // -------------------------------------------------------------------------
  // PRIVATE
  // -------------------------------------------------------------------------

}