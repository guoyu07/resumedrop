<?php

namespace resumedrop\Controller;

/**
 *
 * @author Matthew McNaney <mcnaney at gmail dot com>
 * @license http://opensource.org/licenses/lgpl-3.0.html
 */
class Submit extends \Http\Controller {

    public function get(\Request $request)
    {
        require_once PHPWS_SOURCE_DIR . 'Global/Form.php';
        javascript('jquery');
        javascript('jquery_ui');
        $scs = '<script type="text/javascript" src="' . PHPWS_SOURCE_HTTP .
                'mod/resumedrop/javascript/';
        $sce = '"></script>';

        \Layout::addToStyleList('mod/resumedrop/javascript/fileupload/css/jquery.fileupload-ui.css');
        \Layout::addJSHeader($scs . 'fileupload/js/jquery.iframe-transport.js' . $sce);
        \Layout::addJSHeader($scs . 'fileupload/js/jquery.fileupload.js' . $sce);
        \Layout::addJSHeader($scs . 'fileupload/js/jquery.fileupload-process.js' . $sce);
        \Layout::addJSHeader($scs . 'fileupload/js/jquery.fileupload-validate.js' . $sce);
        \Layout::addJSHeader($scs . 'Submit/script.js' . $sce);

        $student = new \resumedrop\Student;
        $db = \Database::newDB();
        $db->setConditional($db->addTable('rd_student')->getFieldConditional('user_id',
                        \Current_User::getId()));
        $db->selectInto($student);

        $form = $student->pullForm();

        $db2 = \Database::newDB();
        $ct = $db2->addTable('rd_college');
        $ct->addOrderBy($ct->getField('name'));
        $result = $db2->select();

        foreach ($result as $row) {
            $colleges[$row['id']] = $row['name'];
        }

        $form->addSelect('colleges', $colleges)->setLabel("Your major's college");

        $data = $form->getInputStringArray();

        $view = $this->getView($data);
        $response = new \Response($view);
        return $response;
    }

    public function getHtmlView($data, \Request $request)
    {
        $template = new \Template;
        $template->addVariables($data);
        $template->setModuleTemplate('resumedrop', 'Submit/Form.html');
        return $template;
    }

}

?>
