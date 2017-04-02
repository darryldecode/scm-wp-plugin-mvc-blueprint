<?php namespace SCM_BLUEPRINT\Controller;

use SCM_BLUEPRINT\Classes\View as View;
use SCM_BLUEPRINT\Model\Settings;
use SCM_BLUEPRINT\Classes\Utility;

/**
 * Class SettingsController
 * @package SCM_BLUEPRINT\Controller
 *
 * @since 1.0
 * @author Darryl Coder
 */
class SettingsController {

    /**
     * object constructor
     */
    public function __construct()
    {
        // filter for admin only
        Utility::addFilterAdminOnly();
    }

    /**
     * displays the settings page
     *
     * @method GET
     */
    public function index()
    {
        // get settings
        $settings = Settings::getScmSystemSettings();

        if( isset($_GET['updated']) ) Utility::setFlashMessage('Settings Updated!');

        View::make('templates/admin/settings.php',compact('settings'));
    }

    /**
     * handle update system settings
     *
     * @method POST
     */
    public function updateSystemSettings()
    {
        // make sure request is post
        if( ! Utility::requestIsPost())
        {
            View::make('templates/system/error.php',array());
            return;
        }

        $data = array();
        foreach($_POST as $k => $v)
        {
            $data[$k] = Utility::stripTags($v);
        }

        $result = Settings::updateSystemSettings($data);

        if($result)
        {
            Utility::redirect('state=Settings&action=index&updated');
        }

        Utility::setFlashMessage('Failed to update settings!');
        Utility::redirect('state=Settings&action=index');
    }

}