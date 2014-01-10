<?php

/**
 *
 * @author Matthew McNaney <mcnaney at gmail dot com>
 * @package Global
 * @license http://opensource.org/licenses/lgpl-3.0.html
 */
function resumedrop_update(&$content, $version)
{

    switch ($version) {
        case version_compare($version, '1.1.0', '<'):
            \Settings::set('resumedrop', 'contact_email',
                    \PHPWS_Settings::get('resumedrop', 'contact_email'));
            $content[] = '<pre>1.1.0 update
----------------
+ Added ability to set log on message
</pre>';
    }
    return true;
}

?>
