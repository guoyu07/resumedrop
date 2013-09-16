<?php

/**
 * Uninstall file for blog
 *
 * @author Matthew McNaney <mcnaney at gmail dot com>
 * @version $Id$
 */

function resumedrop_uninstall(&$content)
{
    $db = Database::newDB();
    $db->buildTable('rd_counselor')->drop();
    $db->buildTable('rd_college')->drop();
    $db->buildTable('rd_student')->drop();
    $db->buildTable('rd_ctocollege')->drop();
    return true;
}


?>
