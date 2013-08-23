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
        $data['menu'] = $this->menu->get($request);
        \Form::requiredScript();
        $form = new \Form;
        $form->addEmail('contact_email',
                \PHPWS_Settings::get('resumedrop', 'contact_email'),
                'Site contact email')->setRequired();
        $form->addSubmit('submit', 'Save settings');

        $data = array_merge($data, $form->getInputStringArray());

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

        \PHPWS_Settings::set('resumedrop', 'contact_email', $request->getVar('contact_email'));
        \PHPWS_Settings::save('resumedrop');

        $response = new \Http\SeeOtherResponse(\Server::getCurrentUrl(false));
        return $response;
    }

}

?>
