<?php

/**
 *
 * @author Matthew McNaney <mcnaney at gmail dot com>
 * @package Global
 * @license http://opensource.org/licenses/lgpl-3.0.html
 */
function resumedrop_install(&$content)
{
    Database::phpwsDSNLoader(PHPWS_DSN);
    $db = Database::newDB();
    $db->begin();

    try {
        $student = new resumedrop\Student;
        $student->createTable($db);

        $resume = new resumedrop\Resume;
        $resume->createTable($db);

        $college = new resumedrop\College;
        $college->createTable($db);

        $counselor = new resumedrop\Counselor;
        $counselor->createTable($db);
    } catch (\Exception $e) {
        $db->rollback();
        throw $e;
    }
    $db->commit();
    $content[] = 'Tables created';
    return true;
}

?>
