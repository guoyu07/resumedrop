<?php

namespace resumedrop\Controller;

/**
 *
 * @author Matthew McNaney <mcnaney at gmail dot com>
 * @license http://opensource.org/licenses/lgpl-3.0.html
 */
class User extends \Http\Controller {

    public function get(\Request $request)
    {
        $token = $request->getCurrentToken();
        switch ($token) {
            case '/':
                $data = $this->main();
                break;
        }

        $view = $this->getView($data, $request);
        $response = new \Response($view);
        return $response;
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

    }

}

?>
