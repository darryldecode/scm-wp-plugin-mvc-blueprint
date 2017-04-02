<?php namespace SCM_BLUEPRINT;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once dirname(__FILE__) . '/vendor/autoload.php';
require_once(ABSPATH .'wp-includes/pluggable.php');

use SCM_BLUEPRINT\Classes\Bootstrap;
use SCM_BLUEPRINT\Classes\Router;

/**
 * Plugin Name: SCM_BLUEPRINT MVC
 * Plugin URI: https://github.com/darryldecode
 * Description: A plugin building blueprint that embraces MVC pattern
 * Version: 1.0
 * Author: Darryl Fernandez | darrylcoder
 * Author URI: https://github.com/darryldecode
 * License: GPLv2
 * Compatibility: Up to WordPress Version 3.9.1
 *
 */

// app
$scm = new Bootstrap(array(
    'PLUGIN_NAME' => 'SCM BLUEPRINT',
    'PLUGIN_SLUG' => 'scm_blueprint',
    'FOLDER_NAME' => 'scm',
    'ROUTE_HANDLE' => 'scm_blueprint',
    'FRONT_PAGE_VIEW_SHORTCODE' => 'scm_blueprint',
    'PATH' => dirname(__FILE__) . '/',
    'MASTER_FILE_PATH' => __FILE__,
));
$scm->init();

// router
$scmRouter = new Router();
$scmRouter->addCSRFExemptedRoutes(array(
    /*
     * example:
     *
     * array('state','action'),
     * array('state','action'),
     *
     * where the first index is state/controller and the
     * 2nd index is the action/method
     */
));
$scmRouter->boot();