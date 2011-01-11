<?php
/**
 * es-f specific template class to wrap Yuelo templating system
 *
 * @ingroup    es-f
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    $Id$
 */
class esf_Template {

  /**
   * Yuelo object
   */
  public $Template;

  /**
   * Adapter object
   */
  public $Adapter;

  /**
   * Singleton function
   */
  public static function getInstance() {
    if (!isset(self::$Instance)) self::$Instance = new self;
    return self::$Instance;
  }

  /**
   * Wrapper for Yuelo->Render, set variables etc.
   *
   * @param  string  $Template Template name
   * @param  boolean $dieOnError If not, return empty string
   * @param  string  $RootDir
   * @param  array   $Data Don't use global template data if set
   * @return string  Generated HTML code
   */
  public function Render( $Template, $dieOnError, $RootDir, $Data=NULL ) {
    $this->Adapter->RootDir = $RootDir;
    $this->Template->setData( isset($Data)
                            ? array_change_key_case($Data, CASE_UPPER)
                            : TplData::getAll() );
    // constants
    $this->Template->AssignConstant(TplData::getAllConstants());
    // internationalization
    $this->Template->AssignTranslation(Translation::getAll());
    // render template
    /// Yryie::Info('Render: '.$Template);
    $html = $this->Template->Render($Template, $dieOnError);
    /// if (!$dieOnError AND $this->Template->Error)
    ///   Yryie::Info($this->Template->Error);
    /// Yryie::Info($this->Adapter->getLastTemplate());
    return $html;
  }

  /**
   * Wrapper for Yuelo->Output()
   *
   * @param string $Template
   * @param boolean $dieOnError If not, return empty string
   * @param string $RootDir
   * @param array $Data Don't use global template data if set
   * @return void
   */
  public function Output( $Template, $dieOnError, $RootDir, $Data=NULL ) {
    echo $this->Render($Template, $dieOnError, $RootDir, $Data);
  }

  //---------------------------------------------------------------------------
  // PRIVATE
  //---------------------------------------------------------------------------

  /**
   * Singleton instance container
   */
  private static $Instance;

  /**
   * Class constructor
   */
  private function __construct() {
    // prepare Yuelo
    require_once LIBDIR.'/yuelo/yuelo.require.php';

    Yuelo_Cache::CacheDir(TEMPDIR, FALSE);
    // only static will be cached, therefore can be longer
    Yuelo_Cache::CacheLifeTime(3600);

    Yuelo::set('Language', Registry::get('Language'));
    Yuelo::set('ReuseCode', Registry::get('Template.ReuseCode', TRUE));
    Yuelo::set('Verbose', Registry::get('Template.Verbose', 0));
    Yuelo::set('CustomLayout', Registry::get('Template.CustomLayout', ''));
    Yuelo::set('CompileDir', TEMPDIR);

    Yuelo::set('VarNamesUppercase', TRUE);

    Yuelo::set('DateFormat',     Registry::get('Format.Date'));
    Yuelo::set('TimeFormat',     Registry::get('Format.Time'));
    Yuelo::set('DateTimeFormat', Registry::get('Format.DateTime'));

    Yuelo::set('DecimalChar',        Registry::get('Format.DecimalChar'));
    Yuelo::set('ThousandsSeparator', Registry::get('Format.ThousandsSeparator'));
    Yuelo::set('DecimalPlaces',      Registry::get('Format.DecimalPlaces'));

    Yuelo::set('_SaveConstantsAndVariables', Registry::get('Template._SaveConstantsAndVariables', FALSE));

    // register own extensions
    if (Yuelo::get('Autoload')) {
      // preload user custom extensions, can't be found by Yuelo
      require_once np(APPDIR.'/yuelo/button.extension.php');
      require_once np(APPDIR.'/yuelo/help.extension.php');
      require_once np(APPDIR.'/yuelo/translate.extension.php');
    }
    $complier = Yuelo_Compiler::getInstance();
    $complier->RegisterExtension('Button',    np(APPDIR.'/yuelo/button.extension.php'));
    $complier->RegisterExtension('Help',      np(APPDIR.'/yuelo/help.extension.php'));
    $complier->RegisterExtension('Translate', np(APPDIR.'/yuelo/translate.extension.php'));

    // register processors
    require_once YUELO_BASE_PROCESSOR.'removephp.class.php';
    $complier->RegisterProcessor(new Yuelo_Processor_RemovePHP);

#    require_once YUELO_BASE_PROCESSOR.'altif.class.php';
#    $complier->RegisterProcessor(new Yuelo_Processor_AltIf);

    require_once YUELO_BASE_PROCESSOR.'compress.class.php';
    $complier->RegisterProcessor(new Yuelo_Processor_Compress);

    // Use file based templates in this project
    $this->Adapter = Yuelo_Adapter::getInstance('file');
    $this->Adapter->TemplateExt = '.tpl';
    $this->Template = new Yuelo_Template($this->Adapter);

    $this->Template->AssignConstant('DEVELOP', DEVELOP);

    if (Session::getP('ClearCache', FALSE)) {
      Yuelo::ClearCache();
      Yuelo_Cache::Clear();
      Session::deleteP('ClearCache');
    }
  }
}
