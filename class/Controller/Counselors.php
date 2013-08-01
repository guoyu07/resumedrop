<?php

namespace resumedrop\Controller;

/**
 * The controller for resume administration.
 *
 * @author Matthew McNaney <mcnaney at gmail dot com>
 * @license http://opensource.org/licenses/lgpl-3.0.html
 */
class Counselors extends \Http\Controller {

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
        \Pager::prepare();

        $template = new \Template;
        $template->addVariables($data);
        $template->setModuleTemplate('resumedrop', 'Counselors/List.html');
        return $template;
    }

    protected function getJsonView($data, \Request $request)
    {
        $db = \Database::newDB();
        $coun = $db->addTable('rd_counselor');
        $cuid_field = $coun->getField('user_id');

        $users = $db->addTable('users');
        $ui_field = $users->getField('id');
        $un_field = $users->addField('username');
        $dn_field = $users->addField('display_name');

        $c1 = $db->createConditional($ui_field, $cuid_field, '=');
        $db->setConditional($c1);

        $pager = new \DatabasePager($db);
        $pager->setHeaders(array('username', 'display_name'));
        $tbl_headers['username'] = $un_field;
        $tbl_headers['display_name'] = $dn_field;
        $pager->setTableHeaders($tbl_headers);
        $pager->setId('counselor-list');
        $data = $pager->getJson();
        return parent::getJsonView($data, $request);
    }

}

?>
