<?php namespace SCM_BLUEPRINT\Controller;

use SCM_BLUEPRINT\Classes\Utility;
use SCM_BLUEPRINT\Classes\View;

/**
 * Class MainPageController
 * @package SCM_BLUEPRINT\Controller
 *
 * @since 1.0
 * @author Darryl Coder
 */

class MainPageController {

    /**
     * object constructor
     */
    public function __construct()
    {
        // filter for admin only
        Utility::addFilterAdminOnly();
    }

    /**
     * displays main page
     */
    public function index()
    {
        View::make('templates/admin/dashboard.php',compact(''));
    }

}