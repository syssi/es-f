<?php
/** @defgroup Module-Register Module Register

*/

/**
 * Module Register
 *
 * @ingroup    Module
 * @ingroup    Module-Register
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    1.0.0
 * @version    $Id: v2.4.1-51-gfeddc24 - Sun Jan 16 21:09:59 2011 +0100 $
 */
class esf_Module_Register extends esf_Module {

  // -------------------------------------------------------------------------
  // PUBLIC
  // -------------------------------------------------------------------------

  /**
   *
   */
  public function __construct() {
    parent::__construct();
    $this->RegisterPath = $this->Core['localpath'] . '/reg';
  }

  /**
   * @return array Array of actions handled by the module
   */
  public function handles() {
    return array('index', 'admin');
  }

  /**
   *
   */
  public function IndexAction() {
    if ($this->isPost()) {

      $pp = $this->Request('pass');

      if ($this->Request('user') AND
          !empty($pp['ebay'][0]) AND !empty($pp['esf'][0]) AND
          $pp['ebay'][0] === $pp['ebay'][1] AND
          $pp['esf'][0]  === $pp['esf'][1]) {
        File::write($this->RegisterPath . '/' . md5($this->Request('user')),
                    $this->Request('user') . "\n"
                  . MD5Encryptor::encrypt(md5($pp['esf'][0])."\x01".$pp['ebay'][0], md5($pp['esf'][0])) . "\n"
                  . $this->Request('cmt'));
        Messages::Info(Translation::get('Register.ThankYouForRegister'));
        if ($this->SendMail) {
          // send email about registration request
          $msg = 'User: '.$this->Request['user']."\n\n".'Comment: '.$this->Request('cmt');
          mail($this->SendMail, '['.ESF_TITLE.'] Registration request', $msg);
        }
        $this->redirect(STARTMODULE);
      }

      if (empty($this->Request['user']) OR
          empty($pp['ebay'][0]) OR empty($pp['ebay'][1]) OR
          empty($pp['esf'][0]) OR empty($pp['esf'][1])) {
        $this->Msgs[] = Translation::get('Register.FieldMissing');
      }

      if ($pp['ebay'][0] !== $pp['ebay'][1] OR
          $pp['esf'][0]  !== $pp['esf'][1]) {
        $this->Msgs[] = Translation::get('Register.PasswordsNotEqual');
      }
    }
    TplData::set('RegisterMsg', implode('<br>', $this->Msgs));
  }

  /**
   *
   */
  public function AdminAction() {
    // check auth.
    if (!esf_User::isValid() OR esf_User::getActual(TRUE) != strtolower(esf_User::$Admin)) {
      Messages::Error('Not allowed!');
      $this->redirect(Registy::get('StartModule'));
    }

    $regUsers = array();
    foreach (glob($this->RegisterPath.'/*') as $file) {
      $data = @file($file);
      $name = trim(array_shift($data));
      $regUsers[$name] = array (
        'passwords' => trim(array_shift($data)),
        'comment'   => implode($data),
      );
    }

    if ($this->isPost()) {
      $newUsers = array();
      foreach ((array)@$this->Request['process'] as $name => $mode) {
        switch ($mode) {
          case -1:
            // reject
            Messages::Info(Translation::get('Register.RejectedUser', $name));
            break;
          case  1:
            // accept
            $newUsers[$name] = $regUsers[$name]['passwords'];
            Messages::Success(Translation::get('Register.AcceptedUser', $name));
            break;
          default:
            // do nothing yet, hold request back
            break;
        }
        if ($mode != 0) { // accept / reject
          unset($regUsers[$name]);
          unlink($this->RegisterPath . '/' . md5($name));
        }
      }

      if (count($newUsers)) {
        $xml = file_get_contents('local/config/config.xml');
        $xml2 = array();
        foreach ($newUsers as $name=>$pass) {
          $xml2[] = '    <config type="array">';
          $xml2[] = sprintf('      <config name="name">%s</config>', $name);
          $xml2[] = sprintf('      <config name="auth">%s</config>', $pass);
          $xml2[] = '    </config>';
        }
        $xml = preg_replace('~(.*<section.*?name="users".*?)(\s*</section>.*)~s', '$1'."\n".implode("\n",$xml2).'$2', $xml);
        File::write('local/config/config.xml', $xml);
      }
    }

    TplData::set('Requests', array());
    foreach ($regUsers as $name => $data)
      TplData::add('Requests', array( 'Account' => $name,
                                      'Comment' => $data['comment']));
  }

  // -------------------------------------------------------------------------
  // PRIVATE
  // -------------------------------------------------------------------------

  /**
   *
   */
  private $Msgs = array();

  /**
   *
   */
  private $RegisterPath;

}