<?php namespace SCM\Controller;

use SCM\Classes\View as View;
use SCM\Model\Settings;
use SCM\Classes\SCMUtility;

/**
 * Class SettingsController
 * @package SCM\Controller
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
        SCMUtility::addFilterAdminOnly();
    }

    /**
     * displays the settings page
     *
     * @method GET
     */
    public function index()
    {
        // get settings
        $scm_settings = Settings::getScmSystemSettings();

        if( isset($_GET['updated']) ) SCMUtility::setFlashMessage('Settings Updated!');

        View::make('templates/admin/settings.php',compact('scm_settings'));
    }

    /**
     * handle update system settings
     *
     * @method POST
     */
    public function updateSystemSettings()
    {
        // make sure request is post
        if( ! SCMUtility::requestIsPost())
        {
            View::make('templates/system/error.php',array());
            return;
        }

        $data = array();
        foreach($_POST as $k => $v)
        {
            $data[$k] = SCMUtility::stripTags($v);
        }

        $result = Settings::updateSystemSettings($data);

        if($result)
        {
            SCMUtility::redirect('state=Settings&action=index&updated');
        }

        SCMUtility::setFlashMessage('Failed to update settings!');
        SCMUtility::redirect('state=Settings&action=index');
    }

}