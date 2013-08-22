<?php

namespace resumedrop\Controller\Admin;

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
        $response = new \Http\SeeOtherResponse(\Server::getCurrentUrl(false));
        return $response;
    }

    public function getHtmlView($data, \Request $request)
    {
        javascript('jquery_ui');
        \Pager::prepare();
        \Layout::addToStyleList('mod/resumedrop/javascript/select2/select2.css');
        \Layout::addJSHeader("<script type='text/javascript' src='" .
                PHPWS_SOURCE_HTTP . "mod/resumedrop/javascript/select2/select2.js'></script>");
        \Layout::addJSHeader("<script type='text/javascript' src='" .
                PHPWS_SOURCE_HTTP . "mod/resumedrop/javascript/Counselor/script.js'></script>");
        \Layout::addStyle('resumedrop', 'style.css');
        $data['menu'] = $this->menu->get($request);

        $template = new \Template;

        $data['users'] = $this->getCounselorList();

        $template->addVariables($data);
        $template->setModuleTemplate('resumedrop', 'Admin/Counselors/List.html');
        return $template;
    }

    private function getCounselorList()
    {
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
            return $counselors;
        }
    }

    protected function getJsonView($data, \Request $request)
    {
        if ($request->isVar('command')) {
            switch ($request->getVar('command')) {
                case 'delete_counselor':
                    $counselor_id = $request->getVar('counselor_id');
                    $data['id'] = $counselor_id;
                    $db = \Database::newDB();
                    $c1 = $db->addTable('rd_counselor')->getFieldConditional('id',
                            $counselor_id);
                    $db->setConditional($c1);
                    $db->delete();
                    $db2 = \Database::newDB();
                    $db2->setConditional($db2->addTable('rd_ctocollege')->getFieldConditional('counselor_id',
                                    $counselor_id));
                    $data['sql'] = $db2->deleteQuery();
                    $db2->delete();
                    $data['counselors'] = $this->getCounselorList();
                    break;
            }
            return parent::getJsonView($data, $request);
        }

        $db = \Database::newDB();
        $coun = $db->addTable('rd_counselor');
        $coun->addField('id', 'cid');
        $cuid_field = $coun->getField('user_id');

        $users = $db->addTable('users');
        $ui_field = $users->addField('id');
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
        $pager->setCallback(array('resumedrop\Controller\Admin\Counselors', 'rowAdd'));
        $pager->setRowIdColumn('cid');
        $data = $pager->getJson();
        return parent::getJsonView($data, $request);
    }

    public static function rowAdd($array)
    {
        $array['action'] = '<button class="btn btn-danger delete-counselor">Delete</button>';
        return $array;
    }

}

?>
