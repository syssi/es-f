<?php
/**
 * @package es-f
 * @subpackage Core
 */

defined('_ESF_OK') || die('No direct call allowed.');

// ----------------------------------------------------------------------------
// Your prefered ebay homepage (e.g. of your land)
//
Registry::set('ebay.Homepage', 'ebay.'.Registry::get('EbayTLD'));
//
// if you have your own PHProxy running, you can access the auctions via
// your proxy using this type of URL: (see also docs/TIPS & TRICKS)
//
// 'http://<PROXY>/index.php?q='.urlencode('ebay.'.Registry::get('EbayTLD'));

// ----------------------------------------------------------------------------
// Your prefered ebay item page (e.g. of your land), %s for item number
//
Registry::set('ebay.ShowUrl', 'http://search.ebay.'.Registry::get('EbayTLD').'/%s');
//
// if you have your own PHProxy running, you can access the auctions via
// your proxy using this type of URL: (see also docs/TIPS & TRICKS)
//
// 'http://<PROXY>/index.php?q='.urlencode('http://search.ebay.'.Registry::get('EbayTLD').'/').'%s';
