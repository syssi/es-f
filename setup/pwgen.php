<?php
/**
 * http://www.anyexample.com/programming/php/php__password_generation.xml
 */

// This function returns random array element
function ae_arr(&$arr) {
  return $arr[rand(0, sizeof($arr)-1)];
}

$syllables = isset($_GET['syllables']) ? $_GET['syllables'] : 3;

// 20 prefixes
$prefix = array('aero', 'anti', 'auto', 'bi',   'bio',
                'cine', 'deca', 'demo', 'dyna', 'eco',
                'ergo', 'geo',  'gyno', 'hypo', 'kilo',
                'mega', 'tera', 'mini', 'nano', 'duo');

// 10 random suffixes
$suffix = array('dom',  'ity', 'ment', 'sion', 'ness',
                'ence', 'er',  'ist',  'tion', 'or');

// 8 vowel sounds
$vowels = array('a', 'o', 'e', 'i', 'y', 'u', 'ou', 'oo');

// 20 random consonants
$consonants = array('w', 'r', 't', 'p', 's', 'd', 'f', 'g', 'h', 'j',
                    'k', 'l', 'z', 'x', 'c', 'v', 'b', 'n', 'm', 'qu');

$password = ae_arr($prefix);
$password_suffix = ae_arr($suffix);

for ($i=0; $i<$syllables; $i++) {
  // selecting random consonant
  $doubles = array('n', 'm', 't', 's');
  $c = ae_arr($consonants);
  // maybe double it
  if (in_array($c, $doubles) && ($i!=0)) {
    // 33% probability
    if (rand(0, 2) == 1) $c .= $c;
  }
  $password .= $c;

  // selecting random vowel
  $password .= ae_arr($vowels);

  if ($i == $syllables - 1) // if suffix begin with vovel
    if (in_array($password_suffix[0], $vowels)) // add one more consonant
      $password .= ae_arr($consonants);
}

// selecting random suffix
echo $password, $password_suffix;
