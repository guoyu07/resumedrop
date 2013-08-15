<?php

namespace resumedrop\Controller\Admin;

/**
 * The controller for resume administration.
 *
 * @author Matthew McNaney <mcnaney at gmail dot com>
 * @license http://opensource.org/licenses/lgpl-3.0.html
 */
class Students extends \Http\Controller {

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
        $template = new \Template;
        $template->addVariables($data);
        $template->setModuleTemplate('resumedrop', 'Admin/Students/List.html');
        return $template;
    }

}

?>
