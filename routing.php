<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// WP bug in calling verify_nonce | this should not be like this! sohai la WordPress!
require_once(ABSPATH .'wp-includes/pluggable.php');

// boot routing
$router = new \SCM\Classes\Router();
$router->addCSRFExemptedActions(array(

));
$router->boot();