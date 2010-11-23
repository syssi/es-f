<?php 
/**
 * Copyright (c) 2006-2008 Knut Kohl <knutkohl@users.sourceforge.net>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * GPL: http://www.gnu.org/licenses/gpl.txt
 *
 * @package es-f
 * @subpackage Utilities
 * @desc Utilities
 */

Header('Content-Type: text/html; charset=utf-8');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<HTML lang="en" xml:lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title>Utilities</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta http-equiv="Content-Style-Type" content="text/css">
  <link rel="shortcut icon" href="../layout/favicon.ico">
  <link rel="icon" type="image/x-icon" href="../layout/favicon.ico">
  <style type="text/css">
    body { font-size: 62.5%; }
    html, body { font-size:smaller; voice-family: "\"}\""; voice-family: inherit; font-size: small; }
    h1 { font-size: 1.7em; }
    li { margin-bottom: 5px; }
    tt, code { font-size: 125%; }
    a { text-decoration: none; color: maroon; }
  </style>
</head>
<body>
<h1>Utilities</h1>
<?php

$desc = @parse_ini_file('utilities.cfg');

echo '<ul>';
foreach (glob('*.php') as $file) {
  if ($file == 'index.php') continue;
  if (empty($desc[$file])) $desc[$file] = $file;
  printf('<li><a href="%s">%s</a></li>',$file,$desc[$file]);
}
echo '</ul>';

?>
<br />
<p><a href="../index.php">&lt;&lt;&lt; Back to frontend</a></p>

</body>
</html>