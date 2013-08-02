<?php

/**
 *
 * @author Matthew McNaney <mcnaney at gmail dot com>
 * @package Global
 * @license http://opensource.org/licenses/lgpl-3.0.html
 */
function resumedrop_install(&$content)
{
    //require_once PHPWS_SOURCE_DIR . 'mod/resumedrop/class/Student';
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

        $ctoc = $db->buildTable('rd_ctocollege');
        $c1 = $ctoc->addDataType('college_id', 'integer');
        $c2 = $ctoc->addDataType('counselor_id', 'integer');
        $ctoc->create();
        $index = new \Database\Index(array($c1, $c2), 'ctoc');
        $index->create();
    } catch (\Exception $e) {
        $db->rollback();
        throw $e;
    }
    $db->commit();

    $content[] = 'Tables created';
    return true;
}

?>
