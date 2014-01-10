<?php

namespace resumedrop\Controller\Admin;

/**
 *
 * @author Matthew McNaney <mcnaney at gmail dot com>
 * @license http://opensource.org/licenses/lgpl-3.0.html
 */
class Settings extends \Http\Controller {

    private $menu;

    public function __construct(\Module $module)
    {
        parent::__construct($module);
        $this->menu = new \resumedrop\Menu;
    }

    public function get(\Request $request)
    {
        $data = array();
        $view = $this->getView($data, $request);
        $response = new \Response($view);
        return $response;
    }

    public function getHtmlView($data, \Request $request)
    {
        javascript('editors/ckeditor');
        $data['menu'] = $this->menu->get($request);
        \Form::requiredScript();
        $form = new \Form;
        $form->appendCSS(\Layout::getCurrentTheme());
        $form->addEmail('contact_email',
                \Settings::get('resumedrop', 'contact_email'),
                'Site contact email')->setRequired();
        $form->addSubmit('submit', 'Save settings');

        $form->addTextfield('intro_title',
                \Settings::get('resumedrop', 'intro_title'))->setRequired();
        $form->addTextarea('intro_content',
                \Settings::get('resumedrop', 'intro_content'))->setRequired();

        $data = array_merge($data, $form->getInputStringArray());

        $session = \Session::getInstance();
        if (isset($session->rd_alert)) {
            $data['alert'] = 'Settings updated';
            unset($session->rd_alert);
        }

        $template = new \Template;
        $template->addVariables($data);
        $template->setModuleTemplate('resumedrop', 'Admin/Settings/Form.html');
        return $template;
    }

    public function post(\Request $request)
    {
        if (!$request->isVar('contact_email')) {
            throw new \Exception('Contact email not set.');
        }

        \Settings::set('resumedrop', 'contact_email',
                $request->getVar('contact_email'));
        \Settings::set('resumedrop', 'intro_title',
                strip_tags($request->getVar('intro_title')));
        \Settings::set('resumedrop', 'intro_content',
                $request->getVar('intro_content'));

        $session = \Session::getInstance();
        $session->rd_alert = 'Settings updated';

        $response = new \Http\SeeOtherResponse(\Server::getCurrentUrl(false));
        return $response;
    }

}

?>
