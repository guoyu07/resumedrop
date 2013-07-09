<?php

namespace resumedrop;

/**
 *
 * @author Matthew McNaney <mcnaney at gmail dot com>
 * @license http://opensource.org/licenses/lgpl-3.0.html
 */
class Menu {

    public function get(\Request $request)
    {
        $token = $request->getCurrentToken();

        $template = new \Template(array($token=>1));
        $template->setModuleTemplate('resumedrop', 'Menu/Main.html');
        return $template->get();
    }
}

?>
