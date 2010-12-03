<?php
/**
 * Template generation and rendering class
 *
 * 'Compiles' HTML-Templates to PHP Code and render / show it
 *
 * @ingroup  Core
 * @version  2.1.0
 * @author   Knut Kohl <knutkohl@users.sourceforge.net>
 */
class Yuelo_Template {

  // -------------------------------------------------------------------------
  // PUBLIC
  // -------------------------------------------------------------------------

  /**
   * Last error message
   */
  public $Error = '';

  /**
   * Holds last compiled page content
   */
  public $LastCode;

  /**
   * Class constructor
   *
   * @param Yuelo_Adapter $adapter Adapter object
   */
  public function __construct( Yuelo_Adapter $adapter ) {
    $this->Adapter = $adapter;
    $this->Constants = array(
      'YUELO' => array(
        'VERSION'  => Yuelo::VERSION,
        'HOMEPAGE' => 'http://yuelo.sourceforge.net')
    );
  }

  /**
   * Assign Template Content
   *
   * @usage
   * @code
   * $template->Assign('TITLE', 'My Document Title');
   * $template->Assign('userlist', array(
   *                      array('ID' => 123, 'NAME' => 'John Doe'),
   *                      array('ID' => 124, 'NAME' => 'Jack Doe')
   *                                    )
   *                  );
   * @endcode
   *
   * @param string $name Parameter Name
   * @param mixed $value Parameter Value
   * @return void
   */
  public function Assign( $name, $value=NULL ) {
    if (is_array($name)) {
      $this->Data = array_merge($this->Data, $name);
    } elseif (is_object($name)) {
      foreach (get_object_vars($name) as $name => $value) $this->Data[$name] = $value;
    } else {
      $this->Data[$name] = $value;
    }
  }

  /**
   * Append Template Content
   *
   * @usage
   * @code
   * $template->Append( 'userlist',  array( 'ID' => 123,  'NAME' => 'John Doe' ) );
   * $template->Append( 'userlist',  array( 'ID' => 124,  'NAME' => 'Jack Doe' ) );
   * @endcode
   *
   * @param string $name Parameter Name
   * @param mixed $value Parameter Value
   * @return void
   */
  public function Append( $name, $value ) {
    if (!isset($this->Data[$name])) $this->Data[$name] = '';

    if (is_array($this->Data[$name]) OR is_array($value)) {
      $this->Data[$name][] = $value;
    } else {
      $this->Data[$name] .= $value;
    }
  }

  /**
   * Assign all template content at once, e.g. collected before
   *
   * @usage
   * @code
   * $data['TITLE'] = 'My Document Title';
   * $data['userlist'] = array(
   *                       array( 'ID' => 123,  'NAME' => 'John Doe' ),
   *                       array( 'ID' => 124,  'NAME' => 'Jack Doe' )
   *                     )
   * $template->setData($data);
   * @endcode
   *
   * @param array $data Bulk data
   * @return void
   */
  public function setData( $data ) {
    $this->Data = $data;
  }

  /**
   * Get all template content
   *
   * @return array
   */
  public function &getData() {
    return $this->Data;
  }

  /**
   * Assign template constant
   *
   * @usage
   * @code
   * $template->AssignConstant('TITLE', 'The Title');
   * @endcode
   *
   * @param string $name Parameter Name
   * @param mixed $value Parameter Value
   * @return void
   */
  public function AssignConstant( $name, $value=NULL ) {
    if (is_array($name)) {
      $this->Constants = array_merge($this->Constants, $name);
    } else {
      $this->Constants[$name] = $value;
    }
  }

  /**
   *
   */
  public function AssignTranslation( $name, $value=NULL ) {
    if (is_array($name))
      $this->I18n = array_merge($this->I18n, $name);
    else
      $this->I18n[$name] = $value;
  }

  /**
   * Clear Template Contents
   *
   * @return void
   */
  public function Reset() {
    $this->Data = $this->Constants = $this->I18n = array();
  }

  /**
   * Renderer Wrapper
   *
   * Returns Template Output as a String
   *
   * @param string|array $param Template file name or Content data array
   * @param boolean $dieOnError Die if template not found else return empty string
   * @return string  Rendered Template
   */
  public function Render( $param, $dieOnError=TRUE ) {
    ob_start();
    $this->Output($param, $dieOnError);
    return ob_get_clean();
  }

  /**
   * Assign template content, using parsing result
   *
   * Store the rendered result internal into $Data[$var]
   *
   * There are 2 possible usages for the 2nd parameter.
   * @code
   * 1. Call with template file name
   * Searches for correct template file and parses it using existing data assigned yet
   * 2. Call with an array of data
   * Output the last used template with this provided data array
   * @endcode
   *
   * The call
   * @code
   * $Tpl->AssignRendered('content', 'content');
   * @endcode
   * is a short form and equivalent to
   * @code
   * $Tpl->Assign('content', $Tpl->Render('content'));
   * @endcode
   *
   * @param string $var Destination data variable
   * @param string|array $param Template file name or Content data array
   * @param boolean $dieOnError Die if template not found else return empty string
   * @return void
   */
  public function AssignRendered( $var, $param, $dieOnError=TRUE ) {
    ob_start();
    $this->Output($param, $dieOnError);
    $this->Data[$var] = ob_get_clean();
  }

  /**
   * Output rendered template
   * Prints Parsing Results to Standard Output
   *
   * @param string|array $param Template file name or Content data array
   * @param boolean $dieOnError Die if template not found else return empty string
   * @return void
   */
  public function Output( $param, $dieOnError=TRUE ) {
    if (is_array($param)) {
      // use last template with new data
      $YueloData = $param;
    } else {
      // got template file name, use still assigned data
      $this->TemplateID = $param;
      $YueloData =& $this->Data;
    }

    if (!$this->Adapter->checkTemplate($this->TemplateID)) {
      $this->Error = $this->Adapter->Error;
      if ($dieOnError) {
        die(sprintf($this->ErrorHTML, $this->Error, print_r(debug_backtrace(),1)));
      } else {
        return FALSE;
      }
    }

    // Check if template is already compiled
    $CplDir = Yuelo::get('CompileDir');
    $CplFile = is_writable($CplDir)
             ? sprintf('%s/%s.php', $CplDir, $this->Adapter->BuildFilename($this->TemplateID))
             : FALSE;

    if (!Yuelo::get('ReuseCode') OR !is_file($CplFile) OR
        $this->Adapter->TemplateTimestamp($this->TemplateID) > $this->MTime($CplFile)) {

      $page = $this->Adapter->LoadTemplate($this->TemplateID);
      if (!$page) {
        $this->Error = $this->Adapter->Error;
        return FALSE;
      }

      $CplTime = microtime(TRUE);

      if (!Yuelo_Compiler::getInstance()->Compile($page)) {
        $error = '';
        foreach (debug_backtrace() as $dbgInfo) {
          $dbgInfo['file'] = str_replace($_SERVER['DOCUMENT_ROOT'].'/', '', $dbgInfo['file']);
          $error .= sprintf('- %s [%d] -> %s(%s)<br>',
                             $dbgInfo['file'], $dbgInfo['line'],
                             $dbgInfo['function'], join(',',$dbgInfo['args']));
        }
        die(sprintf($this->ErrorHTML, Yuelo_Compiler::getInstance()->Error, $error));
      }

      $CplTime = microtime(TRUE) - $CplTime;

      $page = str_replace("\r", '', $page);
      // Generator header
      $page = '<?php' . "\n"
            . '/**' . "\n"
            . ' * Generated by: Yuelo Version '.Yuelo::VERSION . "\n"
            . ' * Generated at: '.date('r') . "\n"
            . ' * Compile time: '.number_format($CplTime*1000, 2).'ms' . "\n"
            . ' *' . "\n"
            . ' * Template: '.$this->Adapter->getLastTemplate() . "\n"
            . ' * Version : '.date('r', $this->Adapter->TemplateTimestamp($this->TemplateID)) . "\n"
            . ' */' . "\n"
            . '?'.'>' . "\n"
            . $page;

      // compress some PHP tags
      $page = preg_replace('~\?'.'>\s*<\?php\s*~i', ' ', $page);
      $page = preg_replace('~ *\*/\s*/\*\*?~i', ' * ', $page);

      // Store code to temp. dir.
      if (!empty($CplFile)) {
        if (!file_put_contents($CplFile, $page)) {
          $this->Error = 'Could not write compiled file ['.$CplFile.'].';
          return FALSE;
        }
      }
      // remove cached content, if exists!
      Yuelo_Cache::Delete($this->TemplateID);
    }

    // used in compiled templates
    $YueloCONST = &$this->Constants;
    $YueloI18N = &$this->I18n;

    // some default vars
    $YueloData['TRUE']  = TRUE;
    $YueloData['FALSE'] = FALSE;

    $this->Adapter->PrepareOutputData($YueloData, $YueloCONST);

    $YueloStackCnt = 0;
    $YueloStack[$YueloStackCnt++] = &$YueloData;

    // begin output
    $trace = Yuelo::get('Verbose') & Yuelo::VERBOSE_TRACE;

    while(Yuelo_Cache::Save($this->TemplateID)) {
      if ($trace) echo '<!-- Start ', $this->TemplateID, ' -->', "\n";
      $this->LastCode = '';

      // execute compiled template
      if (!$CplFile) {
        eval('?'.'>' . $page);
        $this->LastCode = $page;
      } else {
        include $CplFile;
        if (Yuelo::get('_SaveConstantsAndVariables')) {
          $TplFile = $this->Adapter->BuildFilename($this->TemplateID);
          $file = $CplDir . DIRECTORY_SEPARATOR . $TplFile . '.txt';
          $file = str_replace($_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR, '', $file);
          $txt = $this->ShowDataCommented($this->Constants, 'constants in '.$TplFile);
          $txt .= $this->ShowDataCommented($this->Data, 'variables in '.$TplFile);
          file_put_contents($file, $txt);
        }
        $this->LastCode = file_get_contents($CplFile);
      }
      if ($trace) echo "\n", '<!-- End ', $this->TemplateID, ' -->', "\n";
    }
  }

  /**
   * Remove compiled PHP files
   *
   * @return void
   */
  public function ClearCache() {
    foreach (glob(Yuelo::get('CompileDir').'/*.php') as $file) unlink($file);
  }

  //--------------------------------------------------------------------------
  // PRIVATE
  //--------------------------------------------------------------------------

  private $ErrorHTML = '<div style="clear:both;padding:5px;background-color:DarkOrange;color:black">
                        <strong>Yuelo Error: %s</strong>
                        <pre>%s</pre></div>';
  /**
   * Last used template
   */
  public $TemplateID = '';

  /**
   * Template content array
   */
  private $Data = array();

  /**
   * Template constants like translations
   */
  private $Constants = array();

  /**
   * Template translation definition
   */
  private $I18n = array();

  /**
   * Adapter instance
   */
  private $Adapter = NULL;

  /**
   * Include extensions
   */
  private $ExtensionHeader = '';

  /**
   * Determine last file change date (if file exists)
   *
   * @param string $filename
   * @return mixed
   */
  private function MTime( $filename ) {
    return @is_file($filename) ? filemtime($filename) : 0;
  }

  /**
   *
   */
  private function ShowDataCommented( $data, $caption='', $prefix='') {
    $return = '';
    // First call
    if ($caption)
      $return .= "\n"
               . str_repeat('-', 78) . "\n"
               . 'Available ' . $caption . "\n"
               . str_repeat('-', 78) . "\n"
               . "\n";

    foreach ($data as $key=>$value) {
      if (is_array($value)) {
        $return .= $this->ShowDataCommented($value, '', $prefix.$key.'.');
      } else {
        switch (TRUE) {
          case is_bool($value):
            $value = $value ? 'TRUE' : 'FALSE';
            break;
          case (is_numeric($value)):
            // do nothing
            break;
          case (is_null($value)):
            $value = 'NULL';
            break;
          default:
            // treat as string
            $v = substr($value, 0, 50);
            // trimed?
            if ($v != $value) $v .= '...';
            $value = '"'.preg_replace('~\s+~', ' ', $v).'"';
            break;
        } // switch
        $return .= $prefix . $key . ' => ' . $value . "\n";
        $hash = '';
      }
    }

    return $return;
  }

}
