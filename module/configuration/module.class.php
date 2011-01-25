<?php
/**
@defgroup Module-Configuration Module configuration

*/

/**
 * Configuration module
 *
 * @ingroup    Module
 * @ingroup    Module-Configuration
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    1.0.0
 * @version    $Id: v2.4.1-51-gfeddc24 - Sun Jan 16 21:09:59 2011 +0100 $
 */
class esf_Module_Configuration extends esf_Module {

  /**
   *
   */
  public function __construct() {
    parent::__construct();

    // Set first defined frontend user as admin, if no other defined
    if (!$this->Admins) $this->Admins = esf_User::$Admin;
    // Is logged in user an admin?
    if (!in_array(esf_User::getActual(TRUE),
                  explode('|', strtolower($this->Admins)))) {
      Messages::Error(Translation::get('Configuration.YouArNotAllowed'));
      $this->Redirect(STARTMODULE);
    }

    $ext = @explode('-', @$this->Request['ext']);
    $this->EditScope = @$ext[0];
    $this->EditName  = @$ext[1];

    // reset not valid calls
    if (!$this->EditScope OR !$this->EditName) $this->Forward();
  }

  /**
   * @return array Array of actions handled by the module
   */
  public function handles() {
    return array('index', 'edit');
  }

  /**
   *
   */
  public function IndexAction() {
    TplData::set('Scope');

    foreach (esf_Extensions::$Types as $scope) {
      TplData::set('Scope.'.$scope.'.Header', Translation::get('Configuration.'.$scope.'s'));

      foreach (esf_Extensions::getExtensions($scope) as $extension) {
        if (esf_Extensions::checkState($scope, $extension, esf_Extensions::BIT_ENABLED) AND
            // only extensions with config definition file
            esf_Extensions::isConfigurable($scope, $extension)) {

          $name = Registry::get($scope.'.'.$extension.'.Name', '');
          $data = array (
            'Name'      => $name,
            'Title'     => Translation::getNVL($extension.'.ConfigurationName', ucwords($extension)),
            'Abstract'  => Translation::getNVL($extension.'.ConfigurationDescription', $name),
            'ConfigUrl' => Core::URL(array('action'=>'edit', 'params'=>array('ext'=>$scope.'-'.$extension))),
          );
          TplData::add('Scope.'.$scope.'.Extensions', $data);
        }
      }
    }
  }

  /**
   *
   */
  public function EditAction() {
    $ConfigPath = $this->EditScope.'/'.$this->EditName;

    if (!file_exists($ConfigPath.'/configuration.xml')) {
      Messages::Error(ucwords($this->EditScope).' "'.$this->EditName.'" is not configurable!');
      $this->forward();
      return;
    }

    Core::ReadConfigs($ConfigPath);
    Core::ReadConfigs('local/'.$ConfigPath);

    $ConfigFile = 'local/'.$ConfigPath.'/config.xml';

    // Correct form posted? (and NOT e.g. layoutswitch)
    if ($this->isPost()) {

      if (isset($this->Request['confirm']) OR isset($this->Request['confirm_x'])) {

        $Exec = Exec::getInstance();
        $dir = dirname($ConfigFile);
        if ($Exec->MkDir($dir, $res)) {
          Messages::Error(Translation::get('Configuration.NotWritable',$dir));
        } else {
          $xml = array(
            '<!--',
            '  Don\'t change manually!',
            '  This file is written by module "Configuration"',
            '-->',
            '<configuration>',
          );

          foreach ((array)$this->Request('vars') as $var => $data) {
            $check = array(
              'scope'     => $this->EditScope,
              'extension' => $this->EditName,
              'var'       => $var,
              'value'     => $data['v']
            );
            Event::Process('ConfigurationSave', $check);

            $val = $check['value'];

            // set to new value if changed for redisplay
            Registry::set($var, $val);

            $val = $this->fmtValue($data['t'], $val);
            if (preg_match('~[<>&"\']~', $val)) $val = '<![CDATA[' . $val . ']]>';
            $xml[] = sprintf('  <config name="%s" type="%s">%s</config>', $var, $data['t'], $val);
          }
          $xml[] = '</configuration>';

          // save config file
          File::write($ConfigFile, $xml);
          Messages::Success(Translation::get('Configuration.Saved', $ConfigFile));
        }
	    }

      if ((isset($this->Request['reset']) OR isset($this->Request['reset_x'])) AND
          file_exists($ConfigFile)) {
        if (unlink($ConfigFile)) {
          // re-read defaults and original configuration
          Core::ReadConfigs($ConfigPath);
          Messages::Success(Translation::get('Configuration.Reseted'));
        } else {
          Messages::Error('Can\'t delete "'.$ConfigFile.'"!', E_USER_ERROR);
        }
      }
    }

    // prepare edit form, only if exists
    $langFile = 'module/configuration/language/configuration/'.$this->EditName.'.%s.tmx';
    $lang = 'en';
    $file = sprintf($langFile, $lang);
    if (file_exists($file)) Translation::LoadTMXFile($file, $lang, Core::$Cache);
    $lang = Session::get('language');
    $file = sprintf($langFile, $lang);
    if (file_exists($file)) Translation::LoadTMXFile($file, $lang, Core::$Cache);

    $name = Registry::get($this->EditScope.'.'.$this->EditName.'.Name', '');

    $cfgLangKey = $this->EditScope.'_'.$this->EditName.'.';

    TplData::set('Name', $name);
    TplData::set('Title', Translation::getNVL($cfgLangKey.'Name', ucwords($this->EditName)));
    TplData::set('SubTitle2', TplData::get('Title'));
    TplData::set('Abstract', Translation::getNVL($cfgLangKey.'Description', $name));
    TplData::set('Description', Translation::getNVL($cfgLangKey.'DescriptionLong'));

    TplData::set('Scope', $this->EditScope);
    TplData::set('Extension', $this->EditName);
    TplData::set('Changed', file_exists($ConfigFile));

    $xml = new XML_Array_Definition(Core::$Cache);
    if (!$DefData = $xml->ParseXMLFile($ConfigPath.'/configuration.xml')) {
      Messages::Error($xml->Error);
      $this->forward();
      return;
    }

##  _dbg($DefData);

    foreach ($DefData as $var => $data) {

      $data['type'] = isset($data['type']) ? substr($data['type'], 0, 1) : 's';
      $data['length'] = isset($data['length']) ? $data['length'] : ( $data['type']=='i' ? 5 : 75 );

      $FieldData = array(
        'Header'      => ($data['type'] == 'h'),
        'Measurement' => '',
        'ReadOnly'    => FALSE,
        'Input'       => array(),
      );

      $desc = '';
      // 1. from configuration.xml
      if (!empty($data['description'])) $desc = $data['description'];
      // 2. Search for extension specific TMX translation
      // replace 1st dot with an _
      $id = $var;
      $dot = strpos($var, '.');
      $id{$dot} = '_';
      $desc = Translation::getNVL($id, $desc);
      $FieldData['Description'] = $desc;

      if ($data['type'] !== 'h') {
        // no sub-header line

        $FieldData['VARIABLE'] = $var;
        $FieldData['VARTYPE'] = $data['type'];
        $values = $data['option'];

        if (preg_match('~(.*?)\[(.*?)\]~s', $FieldData['Description'], $args)) {
          $FieldData['Description'] = $args[1];
          $FieldData['MEASUREMENT'] = $args[2];
        }

        if ($FieldData['MEASUREMENT'] == 'readonly') {
          $FieldData['Description'] .= ' (readonly)';
          $FieldData['MEASUREMENT'] = '';
          $FieldData['ReadOnly'] = TRUE;
        }

        $value = Registry::get($var);

        // default translation for TRUE/FALSE
        if ($FieldData['VARTYPE'] == 'b' AND empty($values)) {
          $values = array( TRUE  => Translation::get('Configuration.True'),
                           FALSE => Translation::get('Configuration.False') );
        }

        if (!count($values)) {
          // if no values array, assume free text input. string or integer
          if (is_array($value)) {
            // array values can't edited yet by this module!
            $FieldData['Description'] .= ' <br>Please edit config.xml so far.';
            foreach ($value as $id => $val)
              $value[$id] = sprintf('<div>[%02d] %s</div>', $id, htmlspecialchars(print_r($val, TRUE)));
            $input = '<div style="white-space:nowrap;overflow:auto">' . implode($value) . '</div>';
          } else {
            $value = htmlspecialchars($value);
            $input = sprintf('<input class="configinput" type="text" id="%1$s_v" name="vars[%1$s][v]" '
                            .'value="%2$s" size="%3$d" ', $var, $value, $data['length'] );
            if ($FieldData['ReadOnly']) $input .= 'readonly ';
            if ($FieldData['VARTYPE'] == 'i') $input .= 'style="text-align:right" ';
            $input .= '>';
          }
          $FieldData['Input'][] = $input;

        } else {

          if (Registry::get('Module.Configuration.Options') == 1) {
            // build select options
            $select = sprintf('<select name="vars[%1$s][v]">'."\n", $var);
            foreach ($values as $val => $desc) {
              if ($FieldData['VARTYPE'] == 's' AND is_int($val)) $val = $desc;
              $desc = Translation::getNVL($id.'>'.$val, $desc);
              $select .= sprintf('  <option value="%s"%s>%s</option>',
                                 $val, ($val==$value?' selected="selected"':''), $desc)."\n";
            }
            $FieldData['Input'][] = $select.'</select>'."\n";
          } else {
            // data ratio
            $ratio = strlen(serialize($values)) / count($values);
            // build radio buttons
            foreach ($values as $val => $desc) {
              if ($FieldData['VARTYPE'] == 's' AND is_int($val)) $val = $desc;
              $desc = Translation::getNVL($id.'>'.$val, $desc);
              $FieldData['Input'][] = sprintf('<div style="%spadding-right:10px">'
                                            .'<input type="radio" name="vars[%s][v]" value="%s"%s>%s</div>',
                                             ($ratio<50?'float:left;':''), $var, $val,
                                             ($val==$value?' checked="checked"':''), $desc)."\n";
            }
          }
        }
      }
      
      TplData::add('Fields', $FieldData);
    }

  }

  //--------------------------------------------------------------------------
  // private
  //--------------------------------------------------------------------------

  /**
   *
   */
  private function fmtValue( $type, $value ) {
    switch ($type) {
      case 'b' : return $value ? 'TRUE' : 'FALSE';  break;
      case 'i' : return (int)$value;                break;
      default  : return $value;                     break;
    }
  }

}
