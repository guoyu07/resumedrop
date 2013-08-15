<?php

namespace resumedrop\Controller\Admin;

/**
 * The controller for resume administration.
 *
 * @author Matthew McNaney <mcnaney at gmail dot com>
 * @license http://opensource.org/licenses/lgpl-3.0.html
 */
class Resumes extends \Http\Controller {

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
        $template->setModuleTemplate('resumedrop', 'Admin/Resumes/List.html');
        return $template;
    }

    protected function getJsonView($data, \Request $request)
    {
          $db = \Database::newDB();
          $resume = $db->addTable('rd_resume');

          $pager = new \DatabasePager($db);
          $pager->setHeaders(array('title', 'created', 'version'));
          $tbl_headers['title'] = $resume->getField('title');
          $tbl_headers['created'] = $resume->getField('created');
          $tbl_headers['version'] = $resume->getField('version');
          $pager->setTableHeaders($tbl_headers);
          $pager->setId('resume-list');
          $data = $pager->getJson();
          return parent::getJsonView($data, $request);
    }

}

?>
