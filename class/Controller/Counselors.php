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

    public function post(\Request $request)
    {
        switch ($request->getVar('command')) {
            case 'save_counselor':
                $db = \Database::newDB();
                $rd = $db->addTable('rd_counselor');
                $rd->addValue('user_id', $request->getVar('user_id'));
                $rd->insert();
                break;
        }
        $response = new \Http\RedirectResponse(\Server::getCurrentUrl(false));
        return $response;
    }

    public function getHtmlView($data, \Request $request)
    {
        // JQuery called in prepare
        \Pager::prepare();
        javascript('jquery_ui');
        \Layout::addToStyleList('mod/resumedrop/javascript/select2/select2.css');
        \Layout::addJSHeader("<script type='text/javascript' src='" .
                PHPWS_SOURCE_HTTP . "mod/resumedrop/javascript/select2/select2.js'></script>");
        \Layout::addJSHeader("<script type='text/javascript' src='" .
                PHPWS_SOURCE_HTTP . "mod/resumedrop/javascript/Counselor/script.js'></script>");
        \Layout::addStyle('resumedrop', 'style.css');
        $data['menu'] = $this->menu->get($request);
        \Pager::prepare();

        $template = new \Template;

        $db = \Database::newDB();
        $ut = $db->addTable('users');
        $co = $db->buildTable('rd_counselor');

        $ut->addField('id');
        $ut->addField('username');
        $ut->addField('display_name');

        $c_id = $co->getField('user_id');

        $db->join($ut->getField('id'), $c_id, 'left outer');
        $db->setConditional($db->createConditional($c_id, null, 'is'));

        $result = $db->select();

        if (!empty($result)) {
            foreach ($result as $c) {
                extract($c);
                $counselors[$id] = "$display_name ($username)";
            }
            $data['users'] = $counselors;
        }

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
