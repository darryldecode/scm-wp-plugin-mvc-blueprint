<?php namespace SCM_BLUEPRINT\Classes;

use SCM_BLUEPRINT\Classes\Utility;
use SCM_BLUEPRINT\View\AdminPageHandle;
use SCM_BLUEPRINT\View\FrontPageHandle;
use Illuminate\Database\Capsule\Manager as Capsule;

/**
 * Online Course Module Core Bootstrap Class
 *
 * @author: Darryl Fernandez
 */
class Bootstrap {

    /**
     * @var string
     */
    private $pluginName = '';

    /**
     * @var string
     */
    private $pluginSlug = '';

    /**
     * @var string
     */
    private $folderName = '';

    /**
     * @var string
     */
    private $routeHandle = '';

    /**
     * @var string
     */
    private $scmPath = '';

    /**
     * @var string
     */
    private $masterFilePath = '';

    /**
     * @var string
     */
    private $frontPageShortCode = '';

    /**
     * @var
     */
    private $dbCon;

    /**
     * the SCM_BLUEPRINT instance
     *
     * @var
     */
    public static $instance;

    public function __construct(array $config) {
        $this->pluginName = $config['PLUGIN_NAME'];
        $this->pluginSlug = $config['PLUGIN_SLUG'];
        $this->folderName = $config['FOLDER_NAME'];
        $this->routeHandle = $config['ROUTE_HANDLE'];
        $this->scmPath = $config['PATH'];
        $this->masterFilePath = $config['MASTER_FILE_PATH'];
        $this->frontPageShortCode = $config['FRONT_PAGE_VIEW_SHORTCODE'];
        self::$instance = $this;
    }

    public static function getInstance($config = array()) {
        if (self::$instance === null) {
            self::$instance = new self($config);
        }
        return self::$instance;
    }

    public function init()
    {
        $this->sessionInit();
        $this->defineConstants();
        $this->adminRequiredFiles();
        $this->setDBConnection();
        register_activation_hook($this->masterFilePath, array($this, 'install'));
        register_deactivation_hook($this->masterFilePath, array($this, 'unInstall'));
        add_action('admin_menu', array($this, 'displayAdminMenu'));
        add_action('admin_enqueue_scripts', array($this, 'loadAdminScripts'));
        add_action('admin_enqueue_scripts', array($this, 'printAdminScripts'));
        add_action('wp_enqueue_scripts', array($this, 'loadFrontScripts'));
        add_action('wp_head', array($this, 'printFrontScripts'));
        add_shortcode( $this->frontPageShortCode, array($this, 'doFrontDisplay'));
    }

    /**
     * set the database connection
     */
    public function setDBConnection()
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

        $this->dbCon = $capsule->getConnection('default');
    }

    /**
     * get the current database connection
     *
     * @return mixed
     */
    public function getDBConnection()
    {
        return $this->dbCon;
    }

    /**
     * Define all application constants
     * @since 1.0
     */
    public function defineConstants()
    {
        define('SCM_BLUEPRINT_PLUGIN_NAME',   $this->pluginName);
        define('SCM_BLUEPRINT_PLUGIN_SLUG',   $this->pluginSlug);
        define('SCM_BLUEPRINT_FOLDER_NAME',   $this->folderName);
        define('SCM_BLUEPRINT_ROUTE_HANDLE',	$this->routeHandle);
        define('SCM_BLUEPRINT_PATH',	        $this->scmPath);
        define('SCM_BLUEPRINT_URI',	    plugins_url().'/'.SCM_BLUEPRINT_FOLDER_NAME.'/');
        define('SCM_BLUEPRINT_URI_CSS',	plugins_url().'/'.SCM_BLUEPRINT_FOLDER_NAME.'/resources/css/');
        define('SCM_BLUEPRINT_URI_JS',	plugins_url().'/'.SCM_BLUEPRINT_FOLDER_NAME.'/resources/js/');
        define('SCM_BLUEPRINT_URI_IMG',	plugins_url().'/'.SCM_BLUEPRINT_FOLDER_NAME.'/resources/img/');
    }

    /**
     * Require all needed files
     * @since 1.0
     */
    public function adminRequiredFiles()
    {
        // require all master views for Admin & FrontEnd ( this handles dynamic view )
        foreach (glob(SCM_BLUEPRINT_PATH . "view/*.php") as $filename)
        {
            require_once $filename;
        }
    }

    /**
     * Run session if hasn't run yet, this
     * will be use for captcha lib
     * @since 1.0
     */
    public function sessionInit()
    {
        if( ! session_id())
            session_start();
    }

    /**
     * Installs SCM_BLUEPRINT schema and  needed options
     * @since 1.0
     */
    public function install()
    {
        $options = array(
            $this->pluginSlug.'_settings' => array(
                $this->pluginSlug.'_company_name' => 'My Company',
                $this->pluginSlug.'_safe_mode' => 'disabled',
                $this->pluginSlug.'_front_page_url' => "/{$this->pluginSlug}/",
                $this->pluginSlug.'_debug_mode' => 0,
                $this->pluginSlug.'_admin_email' => 'webmaster@email.com',
                $this->pluginSlug.'_mailer_engine' => 'Default',
                $this->pluginSlug.'_use_app_style' => 1,
                $this->pluginSlug.'_api_key' => 'SaMpLeApIKeYHeRe~@@',
            )
        );

        foreach ($options as $key => $value){
            $value = serialize($value);
            add_option($key,$value );
        }
    }

    /**
     * Process uninstall
     * @since 1.0
     */
    public function unInstall()
    {
        $safe_mode  = unserialize( get_option($this->pluginSlug.'_settings') );
        $safe_mode  = $safe_mode[$this->pluginSlug.'_safe_mode'];

        if( $safe_mode == 'disabled' )
        {
            delete_option($this->pluginSlug.'_settings');
        }
    }

    /**
     * Displays the admin main menu
     * @since 1.0
     */
    public function displayAdminMenu()
    {
        $page_title		= SCM_BLUEPRINT_PLUGIN_NAME;
        $menu_title		= SCM_BLUEPRINT_PLUGIN_NAME;
        $capability		= 'activate_plugins';
        $menu_slug		= SCM_BLUEPRINT_ROUTE_HANDLE;
        $function		= array($this,'doAdminDisplay');
        $icon_url		= SCM_BLUEPRINT_URI_IMG.'book-icon.png';
        $position		= 4;

        add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
    }

    /**
     * Load scripts only in SCM_BLUEPRINT admin area
     * @since 1.0
     */
    public static function loadAdminScripts()
    {
        wp_register_style( 'SCM_BLUEPRINT_bootstrap_css', SCM_BLUEPRINT_URI.'resources/libs/bootstrap-4.0.0-alpha.6-dist/css/bootstrap.min.css', false, '1.0.0' );
        wp_register_style( 'SCM_BLUEPRINT_font_awesome_css', SCM_BLUEPRINT_URI.'resources/libs/font-awesome-4.7.0/css/font-awesome.min.css', false, '1.0.0' );
        wp_register_style( 'SCM_BLUEPRINT_animate_css', SCM_BLUEPRINT_URI.'resources/libs/animate.css', false, '1.0.0' );
        wp_register_style( 'SCM_BLUEPRINT_jqueryui_css', SCM_BLUEPRINT_URI.'resources/css/ui-lightness/jquery-ui-1.10.3.custom.min.css', false, '1.0.0' );
        wp_register_style( 'SCM_BLUEPRINT_angular_ui_tree_css', SCM_BLUEPRINT_URI.'resources/libs/angular-ui-tree-master/dist/angular-ui-tree.min.css', false, '1.0.0' );
        wp_register_style( 'SCM_BLUEPRINT_main_css', SCM_BLUEPRINT_URI.'resources/css/style.css', false, '1.0.0' );
        wp_register_script( 'SCM_BLUEPRINT_jquery', SCM_BLUEPRINT_URI.'resources/js/jquery.min.js', false, '1.0.0' );
        wp_register_script( 'SCM_BLUEPRINT_angular', SCM_BLUEPRINT_URI.'resources/libs/angular-1.6/angular.min.js', false, '1.0.0' );
        wp_register_script( 'SCM_BLUEPRINT_jqueryui', SCM_BLUEPRINT_URI.'resources/js/jquery-ui-1.10.3.custom.min.js', false, '1.0.0' );
        wp_register_script( 'SCM_BLUEPRINT_ui_bootstrap_js', SCM_BLUEPRINT_URI.'resources/libs/ui-bootstrap/ui-bootstrap-tpls-2.5.0.min.js', false, '1.0.0' );
        wp_register_script( 'SCM_BLUEPRINT_bootstrap_js', SCM_BLUEPRINT_URI.'resources/js/bootstrap.min.js', false, '1.0.0' );
        wp_register_script( 'SCM_BLUEPRINT_angular_ui_tree_js', SCM_BLUEPRINT_URI.'resources/libs/angular-ui-tree-master/dist/angular-ui-tree.min.js', false, '1.0.0' );
        wp_register_script( 'SCM_BLUEPRINT_main_js', SCM_BLUEPRINT_URI.'resources/js/main.js', false, '1.0.0' );

        // module custom scripts and styles
        wp_register_script( 'SCM_BLUEPRINT_scripts_common_services', SCM_BLUEPRINT_URI.'resources/js/script-common-services.js', false, '1.0.0' );

        $scmPagesArray = array(
            SCM_BLUEPRINT_ROUTE_HANDLE
        );

        if( isset($_GET['page']) )
        {
            if( in_array($_GET['page'],$scmPagesArray) )
            {
                wp_enqueue_media();
                wp_enqueue_style( 'SCM_BLUEPRINT_bootstrap_css' );
                wp_enqueue_style( 'SCM_BLUEPRINT_font_awesome_css' );
                wp_enqueue_style( 'SCM_BLUEPRINT_animate_css' );
                wp_enqueue_style( 'SCM_BLUEPRINT_jqueryui_css' );
                wp_enqueue_style( 'SCM_BLUEPRINT_angular_ui_tree_css' );
                wp_enqueue_style( 'SCM_BLUEPRINT_main_css' );
                wp_enqueue_script( 'SCM_BLUEPRINT_jquery' );
                wp_enqueue_script( 'SCM_BLUEPRINT_angular' );
                wp_enqueue_script( 'SCM_BLUEPRINT_jqueryui' );
                wp_enqueue_script( 'SCM_BLUEPRINT_ui_bootstrap_js' );
                wp_enqueue_script( 'SCM_BLUEPRINT_bootstrap_js' );
                wp_enqueue_script( 'SCM_BLUEPRINT_angular_ui_tree_js' );
                wp_enqueue_script( 'SCM_BLUEPRINT_main_js' );

                // custom scripts and styles
                wp_enqueue_script( 'SCM_BLUEPRINT_scripts_common_services' );
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
        wp_register_script( 'SCM_BLUEPRINT_jqueryui_front', SCM_BLUEPRINT_URI_JS.'jquery-ui-1.10.3.custom.min.js', array( 'jquery' ), '1.0.0', true );
        wp_register_style( 'SCM_BLUEPRINT_front_css', SCM_BLUEPRINT_URI_CSS.'front-end.css', true, '1.0.0' );
        wp_enqueue_script( 'SCM_BLUEPRINT_jqueryui_front' );
        wp_enqueue_style( 'SCM_BLUEPRINT_front_css' );
    }

    /**
     * Print relevant scripts on header ( admin )
     *
     * @since 1.0
     */
    public static function printAdminScripts()
    {
        $site_url = get_site_url();
        $url = rtrim($site_url,'/');
        $adminUrl = $url.'/wp-admin/admin.php?page='. SCM_BLUEPRINT_ROUTE_HANDLE .'&';

        ?>
        <script>
            var SCM_BLUEPRINT_DATA = {
                SCM_BLUEPRINT_API_ENDPOINT: '<?php echo $adminUrl; ?>',
                _nonce: '<?php echo Utility::generateNonce(); ?>'
            }
        </script>
        <?php
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
        $layout = new FrontPageHandle();
        $layout->render();
    }

    /**
     * handle the admin page display and dynamic view
     *
     * @since 1.0
     */
    public static function doAdminDisplay()
    {
        $layout = new AdminPageHandle();
        $layout->render();
    }
}