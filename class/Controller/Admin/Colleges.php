<?php

namespace resumedrop\Controller\Admin;

/**
 * The controller for resume administration.
 *
 * @author Matthew McNaney <mcnaney at gmail dot com>
 * @license http://opensource.org/licenses/lgpl-3.0.html
 */
class Colleges extends \Http\Controller {

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
        $college = new \resumedrop\College;
        $college->setId($request->getVar('college_id'));
        switch ($request->getVar('command')) {
            case 'save':
                $college->setName($request->getVar('college'));
                \ResourceFactory::saveResource($college);
                break;

            case 'delete':
                $this->deleteCollege($college);
                break;

            case 'update-counselors':
                $this->updateCounselors($request);
                break;
        }
        $response = new \Http\SeeOtherResponse(\Server::getCurrentUrl(false));
        return $response;
    }

    private function updateCounselors(\Request $request)
    {
        if ($request->isVar('counselors') && $request->isVar('college_id')) {
            $counselors = $request->getVar('counselors');
            $db = \Database::newDB();
            $ct = $db->addTable('rd_ctocollege');
            $db->setConditional($ct->getFieldConditional('college_id',
                            $request->getVar('college_id')));
            $db->delete();
            foreach ($counselors as $id) {
                $ct->resetValues();
                $ct->addValue('college_id', $request->getVar('college_id'));
                $ct->addValue('counselor_id', $id);
                $ct->insert();
            }
        }
    }

    private function deleteCollege(\resumedrop\College $college)
    {
        \ResourceFactory::deleteResource($college);
        $db = \Database::newDB();
        $db->setConditional($db->addTable('rd_ctocollege')->getFieldConditional('college_id',
                        $college->getId()));
        $db->delete();
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
                PHPWS_SOURCE_HTTP . "mod/resumedrop/javascript/College/script.js'></script>");
        \Layout::addStyle('resumedrop', 'style.css');
        $data['menu'] = $this->menu->get($request);
        $template = new \Template;
        $template->addVariables($data);
        $template->setModuleTemplate('resumedrop', 'Admin/Colleges/List.html');
        return $template;
    }

    protected function getJsonView($data, \Request $request)
    {

        if ($request->isVar('command')) {
            switch ($request->getVar('command')) {
                case 'counselors':
                    $data['counselors'] = $this->getCounselors($request->getVar('college_id'));
                    break;
            }
        } else {
            $db = \Database::newDB();
            $college = $db->addTable('rd_college');
            $name = $college->addField('name');
            $college->addField('id');
            $ct = $db->addTable('rd_ctocollege');
            $col_id = $ct->addField('college_id', 'assigned');
            $col_id->showCount();

            $db->join($college->getField('id'), $col_id, 'left');
            $db->setGroupBy($name);
            $pager = new \DatabasePager($db);
            $pager->setHeaders(array('name', 'assigned'));
            $tbl_headers['name'] = $name;
            $tbl_headers['assigned'] = $col_id;
            $pager->setTableHeaders($tbl_headers);
            $pager->setId('college-list');
            $pager->setRowIdColumn('id');
            $data = $pager->getJson();
        }
        return parent::getJsonView($data, $request);
    }

    private function getCounselors($college_id)
    {
        $db = \Database::newDB();
        $co = $db->addTable('rd_counselor');
        $us = $db->addTable('users');
        $dn = $us->addField('display_name');
        $co->addField('id');

        $us->addOrderBy($dn);

        $db->join($co->getField('user_id', null, false), $us->getField('id'));
        $counselors = $db->select();

        if (empty($counselors)) {
            return null;
        }
        $selected_list = array();
        $db2 = \Database::newDB();
        $cto = $db2->addTable('rd_ctocollege');
        $cto->addField('counselor_id');
        $db2->setConditional($cto->getFieldConditional('college_id', $college_id));
        while ($row = $db2->selectColumn()) {
            $selected_list[] = $row;
        }
        $content[] = '<option></option>';
        foreach ($counselors as $c) {
            extract($c);
            if (in_array($id, $selected_list)) {
                $selected = 'selected="selected"';
            } else {
                $selected = null;
            }
            $content[] = "<option $selected value='$id'>$display_name</option>";
        }
        return implode("\n", $content);
    }

}

?>
