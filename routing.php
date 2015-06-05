<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// WP bug in calling verify_nonce | this should not be like this! sohai la WordPress!
require_once(ABSPATH .'wp-includes/pluggable.php');

// boot routing
$router = new \SCM\Classes\Router();
$router->addCSRFExemptedRoutes(array(

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
$router->boot();