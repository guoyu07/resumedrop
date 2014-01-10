<?php
namespace resumedrop;

/**
 *
 * @author Matthew McNaney <mcnaney at gmail dot com>
 * @license http://opensource.org/licenses/lgpl-3.0.html
 */
class Module extends \Module implements \SettingDefaults {

    public function __construct()
    {
        parent::__construct();
        $this->setTitle('resumedrop');
        $this->setProperName(t('Resume Drop'));
    }

    public function getController(\Request $request)
    {
        $token = $request->getCurrentToken();
        if (!\Current_User::isLogged() || $token == '/' || $token == 'user' || $token == 'upload') {
            // not logged, let User controller handle log in
            $controller = new \resumedrop\Controller\User($this);
        } elseif ($token == 'admin' && \Current_User::allow('resumedrop')) {
            $admin = new \resumedrop\Controller\Admin($this);
            $controller = $admin->getController($request);
        } else {
            throw new \Http\NotFoundException($request);
        }
        return $controller;
    }

    public function getSettingDefaults()
    {
        $settings['intro_title'] = 'Sorry';
        $settings['intro_content'] = 'You will need to log in with a student account to get started.';
        return $settings;
    }

}

?>
