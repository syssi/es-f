<?php
/**
@defgroup Module-ExchangeRates Module ExchangeRates

*/

/**
 * Module ExchangeRates
 *
 * @ingroup    Module
 * @ingroup    Module-ExchangeRates
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    $Id: v2.4.1-62-gb38404e 2011-01-30 22:35:34 +0100 $
 * @revision   $Rev$
 */
class esf_Module_ExchangeRates extends esf_Module {

  /**
   * Handles index action
   */
  public function IndexAction() {
    if (time() > (Session::get('Module.ExchangeRates.TS')+$this->CacheLifespan) OR
        !($rates = Session::get('Module.ExchangeRates.Rates')) OR
        !($source = Session::get('Module.ExchangeRates.Source')) OR
        !($disclaimer = Session::get('Module.ExchangeRates.Disclaimer'))) {

      /// Yryie::Info('Read exchange rates from '.$this->DataURL);

      $cURL = new cURL;
      $cURL->setOpt(CURLOPT_CONNECTTIMEOUT, 3) // set very short timeouts
           ->setOpt(CURLOPT_TIMEOUT,        3)
           ->setOpt(CURLOPT_URL,            $this->DataURL)
           ->setOpt(CURLOPT_HEADER,         FALSE)
           ->setOpt(CURLOPT_RETURNTRANSFER, TRUE)
           ->exec($data);

      if ($data != '') $data = json_decode($data);

      // currency names
      $ini = parse_ini_file(dirname(__file__).'/currencies.ini', true);
      $curr = $ini['en'];
      $lang = Session::get('Language');
      if (isset($ini[$lang])) $curr = array_merge($curr, $ini[$lang]);

      $rates = array();
      foreach ($data->rates as $key=>$value) {
        $rates[$key]['rate'] = $value;
        $rates[$key]['name'] = array_key_exists($key, $curr) ? $curr[$key] : NULL;
      }

      $source = 'open source Exchange Rates - '.date('r', $data->timestamp);

      Session::set('Module.ExchangeRates.TS', time());
      Session::set('Module.ExchangeRates.Rates', $rates);
      Session::set('Module.ExchangeRates.Source', $source);
      Session::set('Module.ExchangeRates.Disclaimer', $data->disclaimer);

    /* ///
    } else {
      Yryie::Info('Use buffered exchange rates.');
    /// */
    }

    TplData::set('DataURL', $this->DataURL);
    TplData::set('URL', $this->URL);
    TplData::set('Rates', $rates);
    TplData::set('source', $source);
    TplData::set('Disclaimer', $disclaimer);

    if (!$this->isPost()) {
      TplData::set('Amount', 1);
      TplData::set('SCurr', 'EUR');
      TplData::set('Calced', 1);
      TplData::set('DCurr', 'EUR');
    } else {
      TplData::set('Amount', $this->Request('amount'));
      TplData::set('SCurr',  $this->Request('scurr'));
      TplData::set('Calced', toNum(iif($this->Request('amount'), $this->Request('amount'), 1))
                           * @$rates[$this->Request('dcurr')]['rate']
                           / @$rates[$this->Request('scurr')]['rate']);
      TplData::set('DCurr',  $this->Request('dcurr'));
    }
  }

}