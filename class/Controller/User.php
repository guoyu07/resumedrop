<?php

namespace resumedrop\Controller;

/**
 *
 * @author Matthew McNaney <mcnaney at gmail dot com>
 * @license http://opensource.org/licenses/lgpl-3.0.html
 */
class User extends \Http\Controller {

    /**
     *
     * @var \resumedrop\Student
     */
    private $student;

    private function checkShibLogin()
    {
        if (!isset($_SERVER['HTTP_SHIB_EP_AFFILIATION']) || !preg_match('/student@appstate.edu/',
                        $_SERVER['HTTP_SHIB_EP_AFFILIATION'])) {
            throw new \resumedrop\UserException('You must <a href="login/">log-in</a> with an ASU account to use this service.');
        }
    }

    private function loadStudent()
    {
        require_once PHPWS_SOURCE_DIR . 'mod/resumedrop/class/Student.php';
        $db = \Database::newDB();
        $rds = $db->addTable('rd_student');
        $db->addConditional($rds->getFieldConditional('user_id',
                        \Current_User::getId()));
        $result = $db->selectOneRow();
        if (!$result) {
            $this->createStudent();
        } else {
            $this->student = new \resumedrop\Student;
            $this->student->setVars($result);
        }
    }

    private function createStudent()
    {
        $student = new \resumedrop\Student;
        $student->user_id = (int) \Current_User::getId();
        $student->username = \Current_User::getUsername();
        $student->banner_id = str_replace('@appstate.edu', '',
                $_SERVER['HTTP_SHIB_CAMPUSPERMANENTID']);
        $student->first_name = $_SERVER['HTTP_SHIB_INETORGPERSON_GIVENNAME'];
        $student->last_name = $_SERVER['HTTP_SHIB_PERSON_SURNAME'];
        \ResourceFactory::saveResource($student);
        $this->student = $student;
    }

    public function get(\Request $request)
    {
        try {
            $this->checkShibLogin();
            $this->loadStudent();

            $token = $request->getCurrentToken();
            switch ($token) {
                case '/':
                    $data = $this->main();
                    break;
            }
        } catch (\resumedrop\UserException $ue) {
            $data['title'] = 'Sorry but there is a problem';
            $data['content'] = $ue->getMessage();
        }


        $view = $this->getView($data, $request);
        $response = new \Response($view);
        return $response;
    }

    public function post(\Request $request)
    {
        try {
            $this->checkShibLogin();
            $this->loadStudent();
            if (!$request->isVar('command')) {
                throw new \resumedrop\UserException('Colleges have not been created. Please contact the administrators.');
            }

            switch ($request->getVar('command')) {
                case 'update_student':
                    $this->updateStudent($request);
                    break;
            }
        } catch (\resumedrop\UserException $ue) {
            $data['title'] = 'Sorry but there is a problem';
            $data['content'] = $ue->getMessage();
            $view = $this->getView($data, $request);
            $response = new \Response($view);
            return $response;
        }
        $response = new \Http\SeeOtherResponse(\Server::getCurrentUrl(false));
        return $response;
    }

    private function updateStudent(\Request $request)
    {
        $first_name = $request->getVar('first_name');
        if (empty($first_name)) {
            throw new \resumedrop\UserException('Please review your information. Your first name must not be blank.');
        } else {
            $this->student->first_name = $first_name;
        }
        $last_name = $request->getVar('first_name');
        if (empty($last_name)) {
            throw new \resumedrop\UserException('Please review your information. Your last name must not be blank.');
        } else {
            $this->student->last_name = $request->getVar('last_name');
        }
        $college_id = $request->getVar('college');
        if (empty($college_id)) {
            throw new \resumedrop\UserException('Please review your information. You must pick a college.');
        } else {
            $this->student->college_id = $college_id;
        }
        $this->student->reviewed = true;
        \ResourceFactory::saveResource($this->student);
    }

    public function getHtmlView($data, \Request $request)
    {
        $template = new \Template($data);
        $template->setModuleTemplate('resumedrop', 'User/main.html');
        return $template;
    }

    private function main()
    {
        \Layout::addPageTitle('ResumeDrop');
        $title = 'Resume Drop';

        if (!\Current_User::isLogged()) {
            $content = $this->notLogged();
        } else {
            $content = $this->userMenu();
        }

        return array('title' => $title, 'content' => $content);
    }

    private function notLogged()
    {
        $data['login'] = PHPWS_HOME_HTTP . 'secure/';
        $template = new \Template($data);
        $template->setModuleTemplate('resumedrop', 'User/login.html');
        return $template;
    }

    private function userMenu()
    {
        if (!$this->student->isReviewed()) {
            return $this->reviewData();
        } else {
            return $this->uploadScreen();
        }
    }

    private function uploadScreen()
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
        \Layout::addJSHeader($scs . 'User/script.js' . $sce);

        $template = new \Template;
        $template->setModuleTemplate('resumedrop', 'User/upload.html');
        return $template->get();
    }

    private function reviewData()
    {
        $db = \Database::newDB();
        $college = $db->addTable('rd_college');
        $college->addField('id');
        $cname = $college->addField('name');
        $college->addOrderBy($cname);
        $colleges = $db->select();

        if (empty($colleges)) {
            throw new \resumedrop\UserException('There was a problem with our software. Colleges have not been created. Please contact the administrators.');
        }
        $sel[0] = '---';
        foreach ($colleges as $c) {
            $sel[$c['id']] = $c['name'];
        }

        \Form::requiredScript();

        $form = $this->student->pullForm();
        $form->getSingleInput('first_name')->setRequired();
        $form->getSingleInput('last_name')->setRequired();
        $form->addSelect('college', $sel, 'College of study')->setRequired();
        $form->setAction('/resumedrop/');
        $form->addHidden('command', 'update_student');
        $form->addSubmit('submit', 'The information above is correct');
        $vars = $form->getInputStringArray();
        $template = new \Template($vars);
        $template->setModuleTemplate('resumedrop', 'User/review_data.html');
        return $template->get();
    }

}

?>
