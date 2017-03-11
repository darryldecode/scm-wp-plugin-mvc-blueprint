<?php namespace SCM\Classes;

use SCM\Model\Settings;

class SCMUtility {

    /**
     * use to clean text
     *
     * @param $text
     * @return mixed
     */
    public static function cleanText($text)
    {
        $cleaned = preg_replace('#[^a-z 0-9?!%]#i','',$text);

        return $cleaned;
    }

    /**
     * strip tags
     *
     * @param $text
     * @return string
     */
    public static function stripTags($text)
    {
        $stripped = strip_tags($text);

        return $stripped;
    }

    /**
     * get if set or set default
     *
     * @param $var
     * @param string $default
     * @return string
     */
    public static function issetOrAssign(&$var, $default = '')
    {
        if( (isset($var)) && (!empty($var)) ) return $var;

        return $var = $default;
    }

    /**
     * generates scm_nonce
     *
     * @return string
     */
    public static function generateNonce()
    {
        return wp_create_nonce('scm_nonce');
    }

    /**
     * check if nonce was valid
     *
     * @param $nonce
     * @return bool
     */
    public static function isNonceValid($nonce)
    {
        return wp_verify_nonce($nonce);
    }

    public static function setFlashMessage($message, $mode = 'info')
    {
        $_SESSION['scm_flash_message']  = $message;
        $_SESSION['scm_flash_mode']     = $mode;
    }

    /**
     * determine if flash message is set
     *
     * @return bool
     */
    public static function hasFlashMessage()
    {
        if(isset($_SESSION['scm_flash_message']))
        {
            return true;
        }
        return false;
    }

    /**
     * return the flash message
     *
     * @return bool
     */
    public static function getFlashMessage()
    {
        $FM = $_SESSION['scm_flash_message'];

        unset($_SESSION['scm_flash_message']);

        return $FM;
    }

    /**
     * get flash message mode
     *
     * @return mixed
     */
    public static function getFlashMessageMode()
    {
        $FMODE = $_SESSION['scm_flash_mode'];

        unset($_SESSION['scm_flash_mode']);

        return $FMODE;
    }

    /**
     * clears the flash message
     */
    public static function clearFlashMessage()
    {
        unset($_SESSION['scm_flash_message']);
        unset($_SESSION['scm_flash_mode']);
    }

    /**
     * redirect to a given url and params
     *
     * @param $query_string
     */
    public static function redirect($query_string)
    {
        // setup the plugin url
        $site_url = get_site_url();
        $url = rtrim($site_url,'/');
        $adminUrl = $url.'/wp-admin/admin.php?page='.SCM_ROUTE_HANDLE.'&';

        header("Location: {$adminUrl}{$query_string}");
        die();
    }

    /**
     * build a URL on admin base on query string
     *
     * @param $query_string
     * @return string
     */
    public static function adminBuildUrl($query_string)
    {
        // setup the plugin url
        $site_url = get_site_url();
        $url = rtrim($site_url,'/');
        $adminUrl = $url.'/wp-admin/admin.php?page='. SCM_ROUTE_HANDLE .'&' .  $query_string;

        return $adminUrl;
    }

    /**
     * redirect to a given url on front end base and params, use in SCM shortcode
     *
     * @param $query_string
     */
    public static function frontRedirectTo($query_string)
    {
        $Course_page_url_settings = Settings::getScmSystemSettings();
        $course_url = $Course_page_url_settings['scm_front_page_url'];

        // build the whole url
        $actual_link = get_site_url() . $course_url . '?page='. SCM_ROUTE_HANDLE .'&' .  $query_string;

        header("Location: {$actual_link}");
        die();
    }

    /**
     * build a URL to front routing
     *
     * @param $query_string
     * @return string
     */
    public static function frontBuildURL($query_string)
    {
        $Course_page_url_settings = Settings::getScmSystemSettings();
        $course_url = $Course_page_url_settings['scm_front_page_url'];

        // build the whole url
        $actual_link = get_site_url() . $course_url . '?page='. SCM_ROUTE_HANDLE .'&' .  $query_string;

        return $actual_link;
    }

    /**
     * returns an object
     *
     * @param $object
     * @return mixed
     */
    public static function with(&$object)
    {
        return $object;
    }

    /**
     * debug buffer for logging
     *
     * @param $data
     * @return string
     */
    public static function debugBuffer($data)
    {
        ob_start();
        print_r($data);

        return ob_get_clean();
    }

    /**
     * template include buffer
     *
     * @param $path
     * @param $data
     * @return string
     */
    public static function templateBuffer($path, $data)
    {
        ob_start();
        $scmData = $data;
        $path = SCM_PATH.'view/'.$path;
        include_once $path;

        return ob_get_clean();
    }

    /**
     * check whether the request is POST
     *
     * @return bool
     */
    public static function requestIsPost()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') return true;

        return false;
    }

    /**
     * check whether the request is GET
     *
     * @return bool
     */
    public static function requestIsGet()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') return true;

        return false;
    }

    /**
     * check whther the reqeust is AJAX
     *
     * @return bool
     */
    public static function requestIsAjax()
    {
        if( ! empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') return true;

        return false;
    }

    /**
     * converts to readable date format ( accetps xxxx-xx-xx xx:xx:xx )
     *
     * @param $date
     * @return string
     */
    public static function toReadableDateFormat($date)
    {
        $date = new \DateTime($date);
        return $date->format('g:i a \o\n l jS F Y');
    }

    /**
     * adds a filter to an executed method to only be proceed if executed in admin context or redirect if not
     */
    public static function addFilterAdminOnly()
    {
        if( ! is_admin() )
        {
            SCMUtility::setFlashMessage('Woops! Looks like something went wrong..If you believe this is a system error, please contact the administrator.','success');
            SCMUtility::frontRedirectTo('state=Front&action=index');
            return;
        }
    }

    /**
     * use to activate a nav if matches current given state and action
     *
     * @param $state
     * @param $action
     * @return string
     */
    public static function navCanActive($state, $action)
    {
        $currentState  = $_GET['state'];
        $currentAction = $_GET['action'];

        if( is_array($action) )
        {
            foreach($action as $a)
            {
                if( ($currentState == $state) && ($currentAction == $a) )
                {
                    return "active";
                }
            }
        }
        else
        {
            if( ($currentState == $state) && ($currentAction == $action) )
            {
                return "active";
            }
        }

        return "";
    }
}