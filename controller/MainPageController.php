<?php namespace SCM\Controller;

use SCM\Classes\SCMUtility;
use SCM\Classes\View;

/**
 * Class MainPageController
 * @package SCM\Controller
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
        SCMUtility::addFilterAdminOnly();
    }

    /**
     * displays main page
     */
    public function index()
    {
        View::make('templates/admin/dashboard.php',compact(''));
    }

}