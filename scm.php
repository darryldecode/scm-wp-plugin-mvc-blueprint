<?php namespace SCM;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

use SCM\Classes\SCMInstaller as Installer;
use Illuminate\Database\Capsule\Manager as Capsule;
use SCM\View\SCMAdminPageHandle;
use SCM\View\SCMFrontPageHandle;

/**
 * Plugin Name: SCM MVC Blueprint
 * Plugin URI: https://github.com/darryldecode
 * Description: A plugin building blueprint that embraces MVC pattern
 * Version: 1.0
 * Author: Darryl Fernandez | darrylcoder
 * Author URI: https://github.com/darryldecode
 * License: GPLv2
 * Compatibility: Up to WordPress Version 3.9.1
 *
 */

$scmBootstrap = new SCMBootstrap();
$scmBootstrap->init();

/**
 * Online Course Module Core Bootstrap Class
 *
 * @author: Darryl Fernandez
 */
class SCMBootstrap {

    public function init()
    {
        self::sessionInit();
        self::defineConstants();
        self::adminRequiredFiles();
        self::establishDBConnection();
        register_activation_hook(__FILE__, array($this, 'install'));
        register_deactivation_hook(__FILE__, array($this, 'unInstall'));
        add_action('admin_menu', array($this, 'displayAdminMenu'));
        add_action('admin_enqueue_scripts', array($this, 'loadAdminScripts'));
        add_action('admin_enqueue_scripts', array($this, 'printAdminScripts'));
        add_action('wp_enqueue_scripts', array($this, 'loadFrontScripts'));
        add_action('wp_head', array($this, 'printFrontScripts'));
        add_shortcode( 'scm', array($this, 'doFrontDisplay'));
    }

    /**
     * establish Database Connection
     */
    public static function establishDBConnection()
    {
        $capsule = new Capsule;
        $capsule->addConnection( array(
            'driver'    => 'mysql',
            'host'      => DB_HOST,
            'database'  => DB_NAME,
            'username'  => DB_USER,
            'password'  => DB_PASSWORD,
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => ''
        ));
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
    }

    /**
     * Define all application constants
     * @since 1.0
     */
    public static function defineConstants()
    {
        define('SCM_PLUGIN_NAME',   'SCM PLUGIN MVC BLUEPRINT');
        define('SCM_FOLDER_NAME',   'scm-wp-plugin-mvc-blueprint');
        define('SCM_ROUTE_HANDLE',	'scmModule');
        define('SCM_PATH',	    dirname(__FILE__) . '/' );
        define('SCM_URI',	    plugins_url().'/'.SCM_FOLDER_NAME.'/');
        define('SCM_URI_CSS',	plugins_url().'/'.SCM_FOLDER_NAME.'/resources/css/');
        define('SCM_URI_JS',	plugins_url().'/'.SCM_FOLDER_NAME.'/resources/js/');
        define('SCM_URI_IMG',	plugins_url().'/'.SCM_FOLDER_NAME.'/resources/img/');
    }

    /**
     * Require all needed files
     * @since 1.0
     */
    public static function adminRequiredFiles()
    {
        // composer autoload
        require_once 'vendor/autoload.php';

        // require all master views for Admin & FrontEnd ( this handles dynamic view )
        foreach (glob(SCM_PATH . "view/*.php") as $filename)
        {
            require_once $filename;
        }

        // require route
        require_once 'routing.php';
    }

    /**
     * Run session if hasn't run yet, this
     * will be use for captcha lib
     * @since 1.0
     */
    public static function sessionInit()
    {
        if( ! session_id())
            session_start();
    }

    /**
     * Installs SCM schema and  needed options
     * @since 1.0
     */
    public function install()
    {
        $installer = new Installer();
        $installer->setOptions();
    }

    /**
     * Process uninstall
     * @since 1.0
     */
    public function unInstall()
    {
        $safe_mode  = unserialize( get_option('scm_settings') );
        $safe_mode  = $safe_mode['scm_safe_mode'];

        if( $safe_mode == 'disabled' )
        {
            $installer = new Installer();
            $installer->deleteOption();
        }
    }

    /**
     * Displays the admin main menu
     * @since 1.0
     */
    public function displayAdminMenu()
    {
        $page_title		= 'SCM MVC Blueprint';
        $menu_title		= 'SCM MVC Blueprint';
        $capability		= 'activate_plugins';
        $menu_slug		= SCM_ROUTE_HANDLE;
        $function		= array($this,'doAdminDisplay');
        $icon_url		= SCM_URI_IMG.'book-icon.png';
        $position		= 4;

        add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
    }

    /**
     * Load scripts only in SCM admin area
     * @since 1.0
     */
    public static function loadAdminScripts()
    {
        wp_register_style( 'scm_bootstrap_css', SCM_URI.'resources/css/bootstrap.min.css', false, '1.0.0' );
        wp_register_style( 'scm_jqueryui_css', SCM_URI.'resources/css/ui-lightness/jquery-ui-1.10.3.custom.min.css', false, '1.0.0' );
        wp_register_style( 'scm_main_css', SCM_URI.'resources/css/style.css', false, '1.0.0' );
        wp_register_script( 'scm_jquery', SCM_URI.'resources/js/jquery.min.js', false, '1.0.0' );
        wp_register_script( 'scm_jqueryui', SCM_URI.'resources/js/jquery-ui-1.10.3.custom.min.js', false, '1.0.0' );
        wp_register_script( 'scm_bootstrap_js', SCM_URI.'resources/js/bootstrap.min.js', false, '1.0.0' );
        wp_register_script( 'scm_main_js', SCM_URI.'resources/js/main.js', false, '1.0.0' );

        $scmPagesArray = array(
            SCM_ROUTE_HANDLE
        );

        if( isset($_GET['page']) )
        {
            if( in_array($_GET['page'],$scmPagesArray) )
            {
                wp_enqueue_style( 'scm_bootstrap_css' );
                wp_enqueue_style( 'scm_jqueryui_css' );
                wp_enqueue_style( 'scm_main_css' );
                wp_enqueue_script( 'scm_jquery' );
                wp_enqueue_script( 'scm_jqueryui' );
                wp_enqueue_script( 'scm_bootstrap_js' );
                wp_enqueue_script( 'scm_main_js' );
            }
        }
    }

    /**
     * load scripts on front end
     *
     * @since 1.0
     */
    public static function loadFrontScripts()
    {
        wp_register_script( 'scm_jqueryui_front', SCM_URI_JS.'jquery-ui-1.10.3.custom.min.js', array( 'jquery' ), '1.0.0', true );
        wp_register_style( 'scm_front_css', SCM_URI_CSS.'front-end.css', true, '1.0.0' );
        wp_enqueue_script( 'scm_jqueryui_front' );
        wp_enqueue_style( 'scm_front_css' );
    }

    /**
     * Print relevant scripts on header ( admin )
     *
     * @since 1.0
     */
    public static function printAdminScripts()
    {

    }

    /**
     * Print relevant scripts on header ( front end )
     * Mostly use for ajax transactions
     *
     * @since 1.0
     */
    public static function printFrontScripts()
    {

    }

    /**
     * handle the front end routing and dynamic views
     * This is triggered by the [scm] shortcode
     *
     * @since 1.0
     */
    public static function doFrontDisplay()
    {
        new SCMFrontPageHandle();
    }

    /**
     * handle the admin page display and dynamic view
     *
     * @since 1.0
     */
    public static function doAdminDisplay()
    {
        new SCMAdminPageHandle();
    }


}