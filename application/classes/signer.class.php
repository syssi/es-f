<?php
/**
 * Signer to handle (mostly) signed session / cookie data
 *
 * Inspired by Nelmio SecurityBundle, (c) Nelmio <hello@nelm.io>
 *
 * https://github.com/nelmio/NelmioSecurityBundle
 *
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2007-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id$
 * @revision   $Rev$
 *
 */
class Signer implements ISigner {

  // -------------------------------------------------------------------------
  // PUBLIC
  // -------------------------------------------------------------------------

  /**
   *
   */
  public $Separator = '.';

  /**
   *
   */
  public function __construct( $key=NULL, $algo='sha256' ) {
    if (!isset($key)) {
      // generate $key from system environment
      $key = base64_encode(__FILE__.filemtime(__FILE__));
    }
    $this->_key = $key;

    if (!in_array($algo, hash_algos()))
      throw new \InvalidArgumentException(sprintf('Hashing algorithm "%s" is not supported.',
                $this->_algo));
    $this->_algo = $algo;
  }

  /**
   *
   */
  public function sign( $value ) {
    $value = serialize($value);
    return $value.$this->Separator.$this->_hash($value);
  }

  /**
   *
   */
  public function get( $signed ) {
    if ($signed == '') return $signed;

    if (!$this->verify($signed))
      throw new \InvalidArgumentException(sprintf('The signature for "%s" was invalid.', $signed));

    list($value, $signature) = $this->_split($signed);
    $value = unserialize($value);
    return $value;
  }

  /**
   *
   */
  public function verify( $signed )  {
    if ($signed == '') return TRUE;

    list($value, $sig1) = $this->_split($signed);
    $sig2 = $this->_hash($value);

    if (strlen($sig1) !== strlen($sig2)) return FALSE;

    $result = 0;
    for ($i=0, $j=strlen($sig1); $i < $j; $i++)
      $result |= ord($sig1[$i]) ^ ord($sig2[$i]);

    return ($result === 0);
  }

  // -------------------------------------------------------------------------
  // PRIVATE
  // -------------------------------------------------------------------------

  /**
   * @var string $_key
   */
  private $_key;

  /**
   * @var string $_algo
   */
  private $_algo;

  /**
   *
   */
  private function _hash($value) {
    return substr(hash_hmac($this->_algo, $value, $this->_key), 0, 7);
  }

  /**
   *
   */
  private function _split($signed) {
    $pos = strrpos($signed, $this->Separator);
    if (FALSE === $pos) return array($signed, null);
    return array(substr($signed, 0, $pos), substr($signed, $pos+1));
  }

}