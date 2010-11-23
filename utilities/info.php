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
 * @desc phpinfo()
 */

Header('Content-Type: text/html; charset=utf-8');

ob_start();

phpinfo();

$info = ob_get_clean();

$info = preg_replace('~(<title>)(.*?</title>)~i','$1|es|f| esniper frontend | $2',$info);

$info = preg_replace('~</head>~i','
                      <link rel="shortcut icon" href="../layout/favicon.ico">
                      <link rel="icon" type="image/x-icon" href="../layout/favicon.ico">
                      <link rel="stylesheet" type="text/css" href="../layout/default/style.css" />',
                     $info);

$info = preg_replace('~<body[^>]*>~i',
                     '<link rel="shortcut icon" href="../layout/favicon.ico">'."\n"
                    .'<link rel="icon" type="image/x-icon" href="../layout/favicon.ico">'."\n"
                    .'$0'
                    .'<div id="content"><p><a href="index.php">Utility index</a></p>',
                     $info);

$info = preg_replace('~</body>~i','</div>$0',$info);

$info = preg_replace('~(th\s*\{.*?)font-size\s*:[^;]+;?([^}]*\})~i','$1$2',$info);

echo $info;

?>