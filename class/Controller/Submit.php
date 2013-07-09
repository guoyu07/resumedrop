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
        $student = new \resumedrop\Student;
        $db = \Database::newDB();
        $db->setConditional($db->addTable('rd_student')->getFieldConditional('user_id',
                \Current_User::getId()));
        $db->selectInto($student);

        $form = $student->pullForm();

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
