<?php

namespace resumedrop\Controller;

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
        $data['add'] = '<button class="btn">Add College</button>';
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
                \ResourceFactory::deleteResource($college);
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
        \Layout::addToStyleList('mod/resumedrop/javascript/chosen/chosen.min.css');
        \Layout::addJSHeader("<script type='text/javascript' src='" .
                PHPWS_SOURCE_HTTP . "mod/resumedrop/javascript/chosen/chosen.jquery.min.js'></script>");
        \Layout::addJSHeader("<script type='text/javascript' src='" .
                PHPWS_SOURCE_HTTP . "mod/resumedrop/javascript/College/script.js'></script>");
        \Layout::addStyle('resumedrop', 'style.css');
        $data['menu'] = $this->menu->get($request);
        $template = new \Template;
        $template->addVariables($data);
        $template->setModuleTemplate('resumedrop', 'Colleges/List.html');
        return $template;
    }

    protected function getJsonView($data, \Request $request)
    {
        $db = \Database::newDB();
        $college = $db->addTable('rd_college');

        $pager = new \DatabasePager($db);
        $pager->setHeaders(array('name'));
        $tbl_headers['name'] = $college->getField('name');
        $pager->setTableHeaders($tbl_headers);
        $pager->setId('college-list');
        //$pager->processRows();
        $pager->setRowIdColumn('id');
        $data = $pager->getJson();
        return parent::getJsonView($data, $request);
    }

}

?>
