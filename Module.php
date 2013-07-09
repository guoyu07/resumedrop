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
        $controllers = array(
            'resumes' => '\resumedrop\Controller\Resumes',
            'submit' => '\resumedrop\Controller\Submit'
        );

        $token = $request->getCurrentToken();

        if (!array_key_exists($token, $controllers)) {
            throw new \Http\NotFoundException($request);
        }

        $class = $controllers[$token];
        return new $class($this);
    }

    public function runTime(\Request $request)
    {
        $template = new \Template;
        $template->add('title', \Settings::get('resumedrop', 'hook_title'));
        $template->add('content', \Settings::get('resumedrop', 'hook_content'));

        if (!\Current_User::isLogged()) {
            $auth = \Current_User::getAuthorization();
            if (!empty($auth->login_link)) {
                $url = $auth->login_link;
            } else {
                $url = 'index.php?module=users&action=user&command=login_page';
            }
            $template->add('login', "<a href='$url'>Log in to the site</a>");
        } else {
            $template->add('submit_link', "<a href='resumedrop/submit'>Submit my resume</a>");
        }
        $template->setModuleTemplate('resumedrop', 'Sidepanel/hook.html');
        \Layout::add($template->get(), 'resumedrop', 'rdrop-box');
    }

    public function getSettingDefaults()
    {
        $settings['hook_title'] = 'Need Resume Help';
        $settings['hook_content'] = 'Log-in to our site, upload your PDF resume,
            and one of our counselors will contact you.';

        return $settings;
    }

}

?>
