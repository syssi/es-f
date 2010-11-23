<?php
/**
 * Template compiler class
 *
 * @ingroup  Core
 * @version  3.0.0
 * @author   Knut Kohl <knutkohl@users.sourceforge.net>
 */
class Yuelo_Compiler {

  /**
   * Holds last compiler error
   */
  public $Error = '';

  /**
   * @return Single instance of compiler object
   */
  public static function getInstance() {
    if (!is_object(self::$Instance)) self::$Instance = new self;
    return self::$Instance;
  }

  /**
   * @name Set Yuelo compiler delimiters
   * @{
   */

  /**
   * Set control delimiters
   *
   * Default: <!-- ... -->
   *
   * @param string $begin
   * @param string $end
   * @return void
   */
  public function setControlDelimiters( $begin, $end ) {
    $this->ControlTags = array($begin, $end);
  }

  /**
   * Get control delimiters
   *
   * @return array
   */
  public function getControlDelimiters() {
    return $this->ControlTags;
  }

  /**
   * Set variable delimiters
   *
   * Default: {...}
   *
   * @param string $begin
   * @param string $end
   * @return void
   */
  public function setVariableDelimiters( $begin, $end ) {
    $this->VariableTags = array($begin, $end);
  }

  /**
   * Get variable delimiters
   *
   * @return array
   */
  public function getVariableDelimiters() {
    return $this->VariableTags;
  }

  /**
   * Set translation delimiters
   *
   * Default: [[...]]
   *
   * @param string $begin
   * @param string $end
   * @return void
   */
  public function setTranslationDelimiters( $begin, $end ) {
    $this->TranslationTags = array($begin, $end);
  }

  /**
   * Get translation delimiters
   *
   * @return array
   */
  public function getTranslationDelimiters() {
    return $this->TranslationTags;
  }

  /**
   * Set comment delimiters
   *
   * Default: PHP style multi line comment inside HTML a comment
   *
   * @param string $begin
   * @param string $end
   * @return void
   */
  public function setCommentDelimiters( $begin, $end ) {
    $this->CommentTags = array($begin, $end);
  }

  /**
   * Get comment delimiters
   *
   * @return array
   */
  public function getCommentDelimiters() {
    return $this->CommentTags;
  }
  /** @} */

  /**
   * @name Extend Yuelo compiler with pre/post processors
   * @{
   */

  /**
   * Register user defined extension
   * See extension/_template.class.php for reference
   *
   * @param string $name Extension name (lowercase)
   * @param string $file Absolute file name of extension
   */
  public function RegisterExtension( $name, $file ) {
    $this->Externals['E'][strtolower($name)] = $file;
  }

  /**
   * Remove user defined extension
   *
   * @param string $name Extension name (lowercase)
   */
  public function RemoveExtension( $name ) {
    if ($position = array_search(strtolower($name), $this->Externals['E'], TRUE))
      unset($this->Externals['E'][$position]);
  }

  /**
   * Register user defined filter
   * See filter/_template.class.php for reference
   *
   * @param string $name Filter name (lowercase)
   * @param string $file Absolute file name of filter
   */
  public function RegisterFilter( $name, $file ) {
    $this->Externals['F'][strtolower($name)] = $file;
  }

  /**
   * Remove user defined filter
   *
   * @param string $name Filter name (lowercase)
   */
  public function RemoveFilter( $name ) {
    if ($position = array_search(strtolower($name), $this->Externals['F'], TRUE))
      unset($this->Externals['F'][$position]);
  }

  /**
   * Register compiler processor
   * See processor/_template.class.php for reference
   *
   * A pre processor will receive the template and can modify it before it's
   * parsed/compiled
   *
   * Usage example:
   * @code
   * $prPHP = new Yuelo_Processor_RemovePHP;
   * Yuelo_Compiler::getInstance()->RegisterProcessor($prPHP);
   * // or
   * Yuelo_Compiler::getInstance()->RegisterProcessor(new Yuelo_Processor_RemovePHP);
   * @endcode
   *
   * @param Yuelo_Processor $processor Processor instance
   * @param int $position Position of processor in the processors stack
   */
  public function RegisterProcessor( Yuelo_Processor $processor, $position=0 ) {
    while (isset($this->Processors[$position])) $position++;
    $this->Processors[$position] = $processor;
    sort($this->Processors);
  }

  /**
   * Remove a pre processor from stack
   *
   * Usage example:
   * @code
   * $prPHP = new Yuelo_Processor_RemovePHP;
   * ...
   * Yuelo_Compiler::getInstance()->RemovePreProcessor($prPHP);
   * @endcode
   *
   * @param Yuelo_Processor $processor Processor class name
   */
  public function RemoveProcessor( Yuelo_Processor $processor ) {
    if ($position = array_search($name, $this->Processors, TRUE))
      unset($this->Processors[$position]);
  }

  /**
   * Compiles HTML template into PHP code
   *
   * @param string &$page HTML to transform to PHP code
   * @return bool TRUE on success, FALSE on error with error message in $this->Error
   */
  public function Compile( &$page ) {

    $this->ExtensionTagged = array( 'E' => array(), 'F' => array() );

    $TplHeader = $TplFooter = '';
    $TplInfo = array();

    //- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    //  Pre processors
    //- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    foreach ($this->Processors as $processor) $processor->PreProcess($page);

    //- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    //  extract template informations
    //- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    $regex = vsprintf('~%1$s.*?(^[\s*]*@\w+.*?)%2$s~ms', $this->CommentTags);
    if (preg_match_all($regex, $page, $args)) {
      foreach ($args[1] as $tag) {
        if (preg_match_all('~^[\s*]*@(\w+)\s+(.*?)$~m', $tag, $var)) {
          foreach ($var[1] as $id => $info) {
            $TplInfo[] = sprintf(' * %s: %s', ucwords($info), $var[2][$id]);
          }
        }
      }
    }

    //- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    //  remove template comment blocks
    //- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    $regex = vsprintf('~%1$s.*?%2$s~s', $this->CommentTags);
    $page = preg_replace($regex, '', $page);

    //- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    //  mask masked variable start/end tags
    //- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    $page = str_replace('\\'.$this->VariableTags[0],      "\x01" ,$page);
    $page = str_replace('\\'.$this->VariableTags[1],      "\x02" ,$page);
    $page = str_replace('\\'.$this->TranslationTags[0],   "\x03" ,$page);
    $page = str_replace('\\'.$this->TranslationTags[1],   "\x04" ,$page);

    //- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    //  Replace Internationalizations {...[[...]]...} INSIDE extension calls
    //  with I18N.... for processing just as array scalars
    //- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    $regex = sprintf('~(%s[^}]*?)%s(%s)%s([^}]*?%s)~',
                     preg_quote($this->VariableTags[0], '~'),
                     preg_quote($this->TranslationTags[0], '~'),
                     $this->VarRegexFilter,
                     preg_quote($this->TranslationTags[1], '~'),
                     preg_quote($this->VariableTags[1], '~'));
    while (preg_match_all($regex, $page, $args, PREG_SET_ORDER)) {
      foreach ($args as $arg) {
        $code = sprintf('%sI18N.%s%s', $arg[1], $arg[2], $arg[3]);
        $page = str_replace($arg[0], $code, $page);
      }
    }

    //- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    //  Replace Internationalizations [[...]]
    //  with {I18N....} for processing just as array scalars
    //- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    $regex = $this->BuildRegex('('.$this->VarRegexFilter.')',
                               $this->TranslationTags[0], $this->TranslationTags[1]);
    if (preg_match_all($regex, $page, $args, PREG_SET_ORDER)) {
      foreach ($args as $arg) {
        $code = sprintf('%sI18N.%s%s', $this->VariableTags[0], $arg[1], $this->VariableTags[1]);
        $page = str_replace($arg[0], $code, $page);
      }
    }

    //- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    //  change assign constants to a variable
    //  {"0"[,x] > var} to {:"0"[,x] > var}
    //- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    $page = str_replace($this->VariableTags[0].'"', $this->VariableTags[0].':"', $page);

    //- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    //  change multiple var assigns to a variable to a "concatenate"
    //  {a[,b] > var} to {:a[,b] > var}
    //- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    $regex = $this->BuildRegex('([^:\s>}]+\s+>)', $this->VariableTags[0], '');
    $page = preg_replace($regex, $this->VariableTags[0].':$1', $page);

    //- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    //  'BEGIN - END' Blocks
    //- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    $regex = $this->BuildRegex(
    //                1
      sprintf('BEGIN (%s)', $this->VarRegex), $this->ControlTags[0], $this->ControlTags[1]);
    if (preg_match_all($regex, $page, $args, PREG_SET_ORDER)) {
      foreach ($args as $arg) {
        list($parent, $block, ) = $this->VarName($arg[1]);
        $loop = '$YueloLoop' . rand(10000, 99999);
        // BEGIN ...
        $code = sprintf($this->LoopBeginCode, $parent, $block, $loop);
        $this->ReplaceTag($arg[0], $code, $page, FALSE, TRUE);
        // END ...
        $arg[0] = str_replace('BEGIN', 'END', $arg[0]);
        $code = sprintf($this->LoopEndCode, $loop);
        $this->ReplaceTag($arg[0], $code, $page);
      }
    }

    //- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    //  'IF nnn="mmm"'    or  'IF nnn=mmm'  or
    //  'IF nnn = "mmm"'  or  'IF nnn = mmm' Blocks
    //  'ELSEIF nnn="mmm"'    or  'ELSEIF nnn=mmm'  or
    //  'ELSEIF nnn = "mmm"'  or  'ELSEIF nnn = mmm' Blocks
    //- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    $regex = $this->BuildRegex(
    //          1         2   3         4
      sprintf('(ELSE)?IF (%s)([ !=<>]+)([^ ]+)', $this->VarRegexFilter),
      $this->ControlTags[0], $this->ControlTags[1]
    );
    if (preg_match_all($regex, $page, $args, PREG_SET_ORDER)) {
      foreach ($args as $arg) {
        list($parent, $block, $filters) = $this->VarName($arg[2]);
        $cmp = $this->Comparison($arg[3]);
        $val = $arg[4];
        $const = preg_match('~^"([^"]+)"$~', $val, $constval);
        if ($const and is_numeric($val[1])) $val = $constval[1];
        if (!$const and !empty($val))
          list($parent1, $block1, $filters1) = $this->VarName($val);
        $code1 = '@' . $this->VarCode($parent, $block);
        if (!$this->addFilters($code1, $filters)) return FALSE;
        if ($cmp.$val == '==""') {
          $code2 = '\'\'';
        } elseif ($const) {
          $code2 = $val;
        } else {
          $code2 = '@' . $this->VarCode($parent1, $block1);
          if (!$this->addFilters($code2, $filters1)) return FALSE;
        }
        $code = sprintf('%sIF (%s %s %s):', $arg[1], $code1, $cmp, $code2);
        $this->ReplaceTag($arg[0], $code, $page);
      }
    }

    //- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    //  'IF nnn' Blocks
    //  'ELSEIF nnn' Blocks
    //- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    $regex = $this->BuildRegex(
    //          1         2      3
      sprintf('(ELSE)?IF (!)?\s*(%s)', $this->VarRegexFilter),
      $this->ControlTags[0], $this->ControlTags[1]
    );
    if (preg_match_all($regex, $page, $args, PREG_SET_ORDER)) {
      foreach ($args as $arg) {
        list($parent, $block, $filters) = $this->VarName($arg[3]);
        $code = '@' . $this->VarCode($parent, $block);
        if (!$this->addFilters($code, $filters)) return FALSE;
        $code = sprintf('%sIF (%s%s):', $arg[1], $arg[2], $code);
        $this->ReplaceTag($arg[0], $code, $page);
      }
    }

    //- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    //  'IF {extension:variable} = mmm' Blocks
    //  'ELSEIF {extension:variable} = mmm' Blocks
    //- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    $regex = $this->BuildRegex(
    //  1          2      3       4         5
      '(ELSE)?IF {(\w*?):([^}]*)}([ !=<>]*)([^ ]*)',
      $this->ControlTags[0], $this->ControlTags[1]
    );
    if (preg_match_all($regex, $page, $args, PREG_SET_ORDER)) {
      foreach ($args as $arg) {
        $cmp  = $this->Comparison($arg[4]);
        if ($res = $this->AnalyseExtensionCall($arg[2], $arg[3])) {
          // need only the generated code
          list(, $code) = $res;
        } else {
          return FALSE;
        }
        $code = sprintf('%sIF (%s %s %s):', $arg[1], $code, $cmp, $arg[5]);
        $this->ReplaceTag($arg[0], $code, $page);
      }
    }

    //- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    //  ELSE, ENDIF Blocks
    //- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    $tag = sprintf('%sELSE%s', $this->ControlTags[0], $this->ControlTags[1]);
    $this->ReplaceTag($tag, 'ELSE:', $page);

    $regex = $this->BuildRegex('ENDIF.*?', $this->ControlTags[0], $this->ControlTags[1]);
    $this->ReplaceTag($regex, 'ENDIF;', $page, TRUE);

    //- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    //  remove inplace comments in {.../*...*/...}
    //- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    $regex = '~('.$this->VariableTags[0].'.*?)\s*/\*.*?\*/\s*(.*?'.$this->VariableTags[1].')~';
    $page = preg_replace($regex, '$1$2', $page);

    //- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    //  INCLUDE statements
    //- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    $regex = $this->BuildRegex(
    //          1
      'INCLUDE ([^\s]+)', $this->ControlTags[0], $this->ControlTags[1]);
    if (preg_match_all($regex, $page, $args, PREG_SET_ORDER)) {
      // prepare subpage object
      $IncTpl = '$YueloInc' . rand(10000, 99999);
      // replace/insert code to parse subtemplates
      $Includes = array();
      $IncCode = '%1$s->setData($YueloData); %1$s->Output(\'%2$s\');';
      foreach ($args as $arg) {
        $Includes[] = ' * - '.$arg[1];
        $code = sprintf($IncCode, $IncTpl, $arg[1]);
        $this->ReplaceTag($arg[0], $code, $page);
      }
      $TplHeader .= '/**' . "\n"
                   .' * Includes:' . "\n"
                   .implode("\n", $Includes) . "\n"
                   .' */' . "\n"
                   .$IncTpl.' = clone($this);' . "\n";
      $TplFooter .= 'unset('.$IncTpl.');';
    }

    //- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    //  Hide HTML comments from compiling or remove them
    //- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    if (preg_match_all('~<!--.*?-->~s', $page, $comments)) {
      if (Yuelo::get('Verbose') & Yuelo::VERBOSE_COMMENTS) {
        foreach ($comments[0] as $id => $cmt) $comments[1][$id] = md5($cmt);
        $page = str_replace($comments[0], $comments[1], $page);
      } else {
        $page = str_replace($comments[0], '', $page);
        unset($comments);
      }
    }

    //- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    //  Replace Scalars
    //- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    $regex = $this->BuildRegex(
    //          1
      sprintf('(%1$s(?: > %1$s)?)', $this->VarRegexFilter),
      $this->VariableTags[0], $this->VariableTags[1]
    );
    if (preg_match_all($regex, $page, $args, PREG_SET_ORDER)) {
      foreach ($args as $arg) {
        // Determine Command (echo / $obj[n]=)
        list($cmd, $tag) = $this->CmdName($arg[1]);
        list($block, $skalar, $filters) = $this->VarName($tag);
        $code = $this->VarCode($block, $skalar);
        if ($cmd == 'echo' AND
            Yuelo::get('Verbose') & Yuelo::VERBOSE_MARKMISSING) {
          $code = sprintf('(isset(%1$s) ? %1$s : \'<span %2$s>\{%3$s\}</span>\')',
                          $code, $this->MissingVarMark, $arg[1]);
        } else {
          // suppress notices
          $code = '@'.$code;
        }
        if (!$this->addFilters($code, $filters)) return FALSE;
        $this->ReplaceTag($arg[0], $cmd.' '.$code.';', $page);
      }
    }

    //- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    //  Include Extensions
    //- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    $regex = $this->BuildRegex(
    //  1      2
      '(\w*?):([^}]*)', $this->VariableTags[0], $this->VariableTags[1]);
    if (preg_match_all($regex, $page, $args, PREG_SET_ORDER)) {
      foreach ($args as $arg) {
        if (!$res = $this->AnalyseExtensionCall($arg[1], $arg[2])) return FALSE;
        list($cmd, $code, $strictcode) = $res;
        $this->ReplaceTag($arg[0], $cmd.' '.$code.';'.$strictcode, $page);
      }
    }

    //- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    //  Unmask masked data
    //- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    $page = str_replace("\x01", '\\'.$this->VariableTags[0],    $page);
    $page = str_replace("\x02", '\\'.$this->VariableTags[1],      $page);
    $page = str_replace("\x03" ,'\\'.$this->TranslationTags[0], $page);
    $page = str_replace("\x04" ,'\\'.$this->TranslationTags[1],   $page);

    //- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    //  get back masked comments
    //- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    if (!empty($comments[1]))
      $page = str_replace($comments[1], $comments[0], $page);

    //- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    //  unmask masked brackets
    //- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    $page = str_replace("\x01", $this->VariableTags[0], $page);
    $page = str_replace("\x02", $this->VariableTags[1], $page);

    $page = str_replace('\\'.$this->VariableTags[0], $this->VariableTags[0], $page);
    $page = str_replace('\\'.$this->VariableTags[1], $this->VariableTags[1], $page);

    //- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    //  Post processors
    //- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    foreach ($this->Processors as $processor) $processor->PostProcess($page);

    //- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    //  is this page cachable, contains no PHP code
    //- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    if (stripos($page, '<?php') === FALSE) $page .= Yuelo::CACHETAG;

    //- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    //  add include header & footer
    //- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    if (!Yuelo::get('AutoLoad') AND
        (!empty($this->ExtensionTagged['E']) OR
         !empty($this->ExtensionTagged['F']))) {
      $code = '<?php' . "\n/**\n * Extensions / Filters\n */\n";
      $path = dirname(__FILE__);
      foreach ($this->ExtensionTagged['E'] as $file)
        $code .= 'require_once \'' . $file . '\';'. "\n";
      foreach ($this->ExtensionTagged['F'] as $file)
        $code .= 'require_once \'' . $file . '\';'. "\n";
      $page = $code . '?'.'>' . "\n" . $page;
    }

    if (!empty($TplHeader))
      $page = '<?php'    . "\n"
            . $TplHeader . "\n"
            . '?'.'>'    . "\n"
            . $page;
    if (!empty($TplInfo))
      $page = '<?php'                 . "\n"
            . '/**'                   . "\n"
            . implode("\n", $TplInfo) . "\n"
            . ' */'                   . "\n"
            . '?'.'>'                 . "\n"
            . $page;
    if (!empty($TplFooter))
      $page .=              "\n"
             . '<?php '   . "\n"
             . $TplFooter . "\n"
             . ' ?'.'>';

    // remove some line breaks...
    $page = preg_replace('~^\s+$~m',     '',   $page);  // mostly empty line
    $page = preg_replace('~\n\n+~',      "\n", $page);  // multiple line breaks
    $page = preg_replace('~\?'.'>\s*$~', '',   $page);
    $page = trim($page);

    //- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    // syntax checks
    //- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    // - orphan loop ENDs
    $regex = $this->BuildRegex(
    //              1
      sprintf('END (%s)', $this->VarRegex), $this->ControlTags[0], $this->ControlTags[1]);
    if (preg_match_all($regex, $page, $args, PREG_SET_ORDER)) {
      $errors = array();
      foreach ($args as $arg)
        $errors[] = sprintf('Missing BEGIN for block "%s"', $arg[1]);
      $this->Error = implode('; ', $errors);
      return FALSE;
    }

    //- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    // Success
    //- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    return TRUE;
  }

  //---------------------------------------------------------------------------
  // PRIVATE
  //---------------------------------------------------------------------------

  /**
   * Instance for Singleton
   */
  private static $Instance = NULL;

  /**
   * Control tags
   */
  private $ControlTags = array('<!-- ', ' -->');

  /**
   * Variable tags
   */
  private $VariableTags = array('{', '}');

  /**
   * Translation tags
   */
  private $TranslationTags = array('[[', ']]');

  /**
   * Comment tags (reg. exp.)
   */
  private $CommentTags = array('<!--\s*/\*', '\*/\s*-->');

  /**
   * Complete mask for variable names
   */
  private $VarRegex;

  /**
   * Complete mask for variable names inclucive filter functions
   */
  private $VarRegexFilter;

  /**
   * Style or class definition
   */
  private $MissingVarMark;

  /**
   * Extension / Filters directories
   */
  private $ExtensionDirs;

  /**
   * List of used Yuelo extensions by template to build include header
   */
  private $ExtensionTagged;

  /**
   * List of registered Yuelo extensions
   */
  private $Externals;

  /**
   *
   */
  private $Processors;

  /**
   * PHP code for loop starts
   */
  private $LoopBeginCode = '
if (isset($%1$s[\'%2$s\']) AND !empty($%1$s[\'%2$s\'])):
  $YueloStack[$YueloStackCnt++] =& $YueloData;
  %3$sCnt = 0;  %3$sC = count($%1$s[\'%2$s\']);
  foreach ($%1$s[\'%2$s\'] as %3$sID => %3$s):
    %3$sCnt++;
    // force %3$s is an array
    if (!is_array(%3$s)) %3$s = array(\'%2$s\' => %3$s);
    // force array keys of %3$s are upper case
    %3$s = array_change_key_case(%3$s, CASE_UPPER);
    %3$s[\'$ROWID\'] = %3$sID;
    %3$s[\'$ROWBIT\'] = %3$sCnt %% 2;
    %3$s[\'$ROWFIRST\'] = (%3$sCnt == 1);
    %3$s[\'$ROWLAST\'] = (%3$sCnt == %3$sC);
    %3$s[\'TRUE\'] = TRUE;
    %3$s[\'FALSE\'] = FALSE;
    $YueloData =& %3$s;
    $YueloData = array_merge($YueloStack[0], $YueloData);
';

  /**
   * PHP code for loop ends
   */
  private $LoopEndCode = '
  endforeach;
  $YueloData =& $YueloStack[--$YueloStackCnt];
  unset(%1$s, %1$sId, %1$sC, %1$sCnt);
endif;
';

  /**
   * Initializes some internal variables
   *
   * @return void
   */
  private function __construct() {
    /*
     * Mask for internal variable names:
     * - loop variables: \$ROW\w+
     * - directory variables: \$\w+DIR
     */
    $this->VarRegex = sprintf('(?:%s|\$ROW\w+|_*\$\w+DIR)', Yuelo::get('VarRegexExternal'));
    $this->VarRegexFilter = $this->VarRegex.'[a-zA-Z0-9|_]*';

    $style = Yuelo::get('MissingVarStyle');
    $this->MissingVarMark = strstr($style, ':')
                            // ONLY CSS code must contain a ":"...
                          ? sprintf('style="%s"', $style)
                            // ...otherwise CSS class name
                          : sprintf('class="%s"', $style);

    // put into array for simple reference by type
    $path = dirname(__FILE__);
    $this->ExtensionDirs = array(
      'E' => $path. DIRECTORY_SEPARATOR . 'extension',
      'F' => $path. DIRECTORY_SEPARATOR . 'filter',
    );

    // external defined extensions/filters
    $this->Externals = array(
      'E' => array(),
      'F' => array()
    );

    $this->Macros = $this->Processors = array();

    // Register block processor
    require_once YUELO_BASE . 'yuelo' . DIRECTORY_SEPARATOR . 'processor' .
                 DIRECTORY_SEPARATOR . 'block.class.php';
    $this->RegisterProcessor(new Yuelo_Processor_Block);
  }

  /**
   * Build well formed reg. expressions
   *
   * @param $regex Reg. expression
   * @param $begin Prefix
   * @param $end Suffix
   * @param $modifier Pattern modifier
   * @return string
   */
  private function BuildRegex( $regex, $begin='', $end='', $modifier='' ) {
    return '~' . preg_quote($begin, '~') . $regex
               . preg_quote($end, '~') . '~' . $modifier;
  }

  /**
   * Analyse required extensions
   *
   * @param string $type Extension or filter
   * @param string $func Extension function
   * @return string|bool
   */
  private function FindExtension( $type, $func ) {
    // still tagged?
    if (isset($this->ExtensionTagged[$type][$func])) return TRUE;

    // User defined extension/filter OVERWRITE default extensions/filters!
    if (isset($this->Externals[$type][$func])) {
      $this->ExtensionTagged[$type][$func] = $this->Externals[$type][$func];
      return TRUE;
    }

    $file = sprintf('%s/%s.class.php', $this->ExtensionDirs[$type], $func);
    if (file_exists($file)) {
      // Default extension/filter
      $this->ExtensionTagged[$type][$func] = $file;
      return TRUE;
    }

    $this->Error = 'Missing Yuelo Extension/Filter ['.$type.']: '.$func.' ('.$file.')';
    return FALSE;
  }

  /**
   * Add filter functions
   *
   * @param string &$code Code to include in filter functions
   * @param array $filters Filter functions
   * @return bool TRUE on success, FALSE on error
   */
  private function addFilters( &$code, $filters ) {
    foreach ($filters as $filter) {
      if ($this->FindExtension('F', $filter) === FALSE) return FALSE;
      $code = sprintf('Yuelo_Filter_%s::Process(%s)', $filter, $code);
    }
    return TRUE;
  }

  /**
   * Replace placeholder with php code
   *
   * @param string $tag placeholder
   * @param string $code php code
   * @param string $page page HTML
   * @param boolean $regex replace using reg. expression
   * @param boolean $NL replace surrounded by newlines
   * @return void
   */
  private function ReplaceTag( $tag, $code, &$page, $regex=FALSE, $NL=FALSE ) {
    $code = '<?php '.$code.' ?'.'>';
    if ($NL) $code = "\n".$code."\n";
    $page = !$regex ? str_replace($tag, $code, $page)
                    : preg_replace($tag, $code, $page);
  }

  /**
   * Analyse extension code call
   *
   * @param string $function Extension function
   * @param string $params Function parameters
   * @return array|FALSE
   */
  private function AnalyseExtensionCall( $function, $params ) {
    $function = trim($function);

    //  Determin Command (echo / $obj[n]=)
    list($cmd, $tag) = $this->CmdName($params);

    // check existence of extension
    if (!empty($function) AND $function <> 'concat' AND
        $this->FindExtension('E', $function) === FALSE)
      return FALSE;

    // mask masked quote
    $tag = str_replace('\"', "\x06", $tag);

    preg_match_all('~("[^"]*"|[^,]+)?,~', $tag.',', $tags);
    $tags = $tags[1];

    $tagarr = array();
    $strictcode = '';
    foreach ($tags as $tag) {
      $tag = trim($tag);
      if (empty($tag)) {
        $tagarr[] = 'NULL';
      } elseif (preg_match('~^([\d.]+)$~', $tag, $args)) {
        // numeric constant
        $tagarr[] = $args[1];
      } elseif (preg_match('~^"(.*)"$~', $tag, $args)) {
        // constants
        $tagarr[] = '\''.str_replace('\'', '\\\'', $args[1]).'\'';
      } else {
        // variables
        list($block, $skalar, $filters) = $this->VarName($tag);

        $_var = $this->VarCode($block, $skalar);

        if (Yuelo::get('Verbose') & Yuelo::VERBOSE_MARKMISSING) {
          if (!$this->addFilters($_var, $filters)) return FALSE;

          if ($skalar !== '') {
            $_var = sprintf('(isset($%1$s[\'%2$s\']) ? %3$s : NULL)', $block, $skalar, $_var);
            if ($function != 'nvl') {
              // if function is not {nvl:...},
              // check that the function is called with existing values
              $span = sprintf('<span %1$s>%2$s%3$s%4$s</span>', $this->MissingVarMark, "\x01", $tag, "\x02");
              $strictcode .= sprintf(' if (!isset($%s[\'%s\'])): echo \'%s\'; endif;',
                                     $block, $skalar, $span);
            }
          }
        } else {
          // suppress notices
          $_var = '@'.$_var;
          if (!$this->addFilters($_var, $filters)) return FALSE;
        }
        $tagarr[] = $_var;
      }
    }

    if (empty($function) OR $function == 'concat') {
      $code = implode('.',$tagarr);
      $code = preg_replace('~\'\.\'~', '', $code);
    } else {
      $code = sprintf('Yuelo_Extension_%s::Process(%s)', $function, implode(',',$tagarr));
    }
    $code = str_replace("\x06", '"', $code);

    return array($cmd, $code, $strictcode);
  }

  /**
   * Splits Template-Style Variable Names into an Array-Name/Key-Name Components
   *
   * @code
   * {example}                 : array("YueloData",                    "example") -> $YueloData['example']
   * {example.value}           : array("YueloData['example']",         "value")   -> $YueloData['example']['value']
   * {example.0.value}         : array("YueloData['example'][0]",      "value")   -> $YueloData['example'][0]['value']
   * {_top.example}            : array("YueloStack[0]",                "example") -> $YueloStack[0]['example']
   * {__example}               : array("YueloStack[0]",                "example") -> $YueloStack[0]['example']
   * {_parent.example}         : array("YueloStack[$YueloStackCnt-1]", "example") -> $YueloStack[$YueloStackCnt-1]['example']
   * {_parent._parent.example} : array("YueloStack[$YueloStackCnt-2]", "example") -> $YueloStack[$YueloStackCnt-2]['example']
   * @endcode
   *
   * @param string $tag Variale Name used in Template
   * @return array Array(Name, Key Name, Filters)
  */
  private function VarName( $tag ) {
    $filters = explode('|', trim($tag));
    $tag = trim(array_shift($filters));

    // Force variables uppercase
    if (Yuelo::get('VarNamesUppercase')) $tag = strtoupper($tag);

    // analyse parent levels
    $ParentLevel = 0;
    while (preg_match('~^_PARENT\.?(.*)$~i', $tag, $args)) {
      $ParentLevel++;
      $tag = $args[1];
    }

    if ($ParentLevel) {
      $obj = 'YueloStack[$YueloStackCnt-'.$ParentLevel.']';
    } elseif (preg_match('~^(CONST|I18N)\.?(.*)$~i', $tag, $args)) {
      $obj = 'Yuelo'.strtoupper($args[1]);
      $tag = $args[2];
    } elseif (preg_match('~^(?:__|_TOP\.)(.*)$~i', $tag, $args)) {
      // tags beginning with "__" are interpreted as short form of _TOP level
      $obj = 'YueloStack[0]';
      $tag = $args[1];
    } else {
      $obj = 'YueloData';
    }

    while (strpos($tag,'.') !== FALSE) {
      list($parent, $tag) = explode('.', $tag, 2);
      $obj .= is_numeric($parent) ? "[$parent]" : "['$parent']";
    }
    return array($obj, $tag, $filters);
  }

  /**
   * Build variable name from block and skalar
   *
   * Return block if skalar is empty
   *
   * Example:
   * @code
   * VarCode('block', 'skalar')  ==>  $block['skalar']
   * VarCode('block', '')        ==>  $block
   * @endcode
   *
   * @param string $block Block array
   * @param string $skalar Variable inside $block
   * @return string
   */
  private function VarCode( $block, $skalar ) {
    return ($skalar == '') ? '$'.$block : '$'.$block.'[\''.$skalar.'\']';
  }

  /**
   * Determine Template Command from Variable Name
   *
   * @code
   * {variable}             :  array( "echo",                   "variable" )  ->  echo $YueloData['variable']
   * {variable > new_name}  :  array( "YueloData['new_name']=", "variable" )  ->  $YueloData['new_name'] = $YueloData['variable']
   * @endcode
   *
   * @param string $tag Variable Name used in Template
   * @return array  Array( Command, Variable )
   */
  private function CmdName( $tag ) {
    $tag = trim($tag);
    $regex = sprintf('~^(.+) > (%s)$~', $this->VarRegex);
    if (preg_match($regex, $tag, $tagvar)) {
      $tag = $tagvar[1];
      list($newblock, $newskalar) = $this->VarName($tagvar[2]);
      $cmd = sprintf('$%s[\'%s\'] = ', trim($newblock), trim($newskalar));
    } else {
      $cmd = 'echo';
    }
    return array($cmd, trim($tag));
  }

  /**
   * Normalize the comparison statement
   *
   * @param string &$cmp
   * @return string
   */
  private function Comparison( $cmp ) {
    switch (trim($cmp)) {
      case '=' :
      case '==':  return '===';
      case '!=':
      case '<>':  return '!==';
      default:    return $cmp;
    }
  }

  /**
   * Can't clone a Singleton
   */
  private function __clone() {}

}