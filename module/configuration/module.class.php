<?php
/**
 * @category   Module
 * @package    Module-Configuration
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    0.2.0
 */

/**
 * Bulk auction add module
 *
 * @category   Module
 * @package    Module-Configuration
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    Release: @package_version@
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
      Messages::addError(Translation::get('Configuration.YouArNotAllowed'));
      $this->Redirect(Registry::get('StartModule'));
    }

    $ext = @explode('-', @$this->Request['ext']);
    $this->EditScope = @$ext[0];
    $this->EditName  = @$ext[1];

    // reset not valid calls
    if (!$this->EditScope OR !$this->EditName) $this->Forward();
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
      Messages::addError(ucwords($this->EditScope).' "'.$this->EditName.'" is not configurable!');
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
          Messages::addError(Translation::get('Configuration.NotWritable',$dir));
        } else {
          $xml = array(
            '<!--           !!! ATTENTION !!!',
            '             Don\'t change manually!',
            '  This file is written by module "Configuration"',
            '-->',
            '<configuration>',
          );

          foreach ($this->Request['vars'] as $var => $data) {
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
          Messages::addSuccess(Translation::get('Configuration.Saved', $ConfigFile));
        }
	    }

      if ((isset($this->Request['reset']) OR isset($this->Request['reset_x'])) AND
          file_exists($ConfigFile)) {
        if (unlink($ConfigFile)) {
          // re-read defaults and original configuration
          Core::ReadConfigs($ConfigPath);
          Messages::addSuccess(Translation::get('Configuration.Reseted'));
        } else {
          Messages::addError('Can\'t delete "'.$ConfigFile.'"!', E_USER_ERROR);
        }
      }
    }

    // prepare edit form, only if exists
    Loader::Load($ConfigPath.'/language/configuration.en.php', TRUE, FALSE);
    Loader::Load($ConfigPath.'/language/configuration.'.Session::get('language').'.php', TRUE, FALSE);

    $name = Registry::get($this->EditScope.'.'.$this->EditName.'.Name', '');

    $cfgLangKey = $this->EditScope.$this->EditName.'Configuration.';

    TplData::set('Name', $name);
    TplData::set('Title', Translation::getNVL($cfgLangKey.'Name', ucwords($this->EditName)));
    TplData::set('SubTitle2', TplData::get('Title'));
    TplData::set('Abstract', Translation::getNVL($cfgLangKey.'Description', $name));
    TplData::set('Description', Translation::getNVL($cfgLangKey.'DescriptionLong'));

    TplData::set('Scope', $this->EditScope);
    TplData::set('Extension', $this->EditName);
    TplData::set('Changed', file_exists($ConfigFile));

    $DefData = array();
    if ($this->EditScope == esf_Extensions::MODULE) {
      $DefData[esf_Extensions::MODULE.'.'.$this->EditName.'.Layout'] = array(
        'description' => 'Layout',
        'option' => getLayouts()
      );
    }

    $xml = new XML_Array_Definition(Core::$Cache);
    if (!$data = $xml->ParseXMLFile($ConfigPath.'/configuration.xml')) {
      Messages::addError($xml->Error);
      $this->forward();
      return;
    }

##_dbg($data);

    $DefData = array_merge($DefData, $data);

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
      // 1. from *.def.ini
      if (!empty($data['description'])) $desc = $data['description'];
      // 2. Search for default (global) translation
      $desc = Translation::getNVL('Configuration.'.$var, $desc);
      // 3. Search for extension specific translation
      $desc = Translation::getNVL($cfgLangKey.$var, $desc);
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
              $desc = Translation::getNVL($this->EditName.'_Configuration.'.$var.'>'.$val, $desc);
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
              $desc = Translation::getNVL($this->EditName.'_Configuration.'.$var.'>'.$val, $desc);
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
