<?php

/**
 *
 * @author Matthew McNaney <mcnaney at gmail dot com>
 * @package Global
 * @license http://opensource.org/licenses/lgpl-3.0.html
 */
var_dump($_SERVER);
echo str_replace('mod/resumedrop/javascript/fileupload/server/php/test.php', '',
        $_SERVER['SCRIPT_FILENAME']) . "files/resumedrop/<br>";

$https = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https://' : 'http://';

echo $https . $_SERVER['HTTP_HOST'] . preg_replace('%' . $_SERVER['DOCUMENT_ROOT'] . '|mod/resumedrop/javascript/fileupload/server/php/test.php%',
        '', $_SERVER['SCRIPT_FILENAME']) . 'files/resumedrop/';
?>
