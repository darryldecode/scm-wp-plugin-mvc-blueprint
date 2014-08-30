<?php namespace SCM\Model;

class Settings {

    /**
     * get SCM System settings
     *
     * @return bool|mixed
     */
    public static function getScmSystemSettings()
    {
        $option = get_option( 'scm_settings' );

        $res    = unserialize( $option );
        return $res;
    }

    /**
     * get SCM Admin Email
     *
     * @return bool|mixed
     */
    public static function getScmAdminEmail()
    {
        $option = get_option( 'scm_settings' );

        $res    = unserialize( $option );
        return $res['scm_admin_email'];
    }

    /**
     * check if system is in debug mode
     *
     * @return bool
     */
    public static function isDebugMode()
    {
        if( (isset($_SESSION['scm_debug_mode'])) && ($_SESSION['scm_debug_mode']==true) ) return true;

        if( (isset($_SESSION['scm_debug_mode'])) && ($_SESSION['scm_debug_mode']==false) ) return false;

        $option = get_option( 'scm_settings' );

        $res    = unserialize( $option );

        if($res['scm_debug_mode']==0) return false;

        return true;
    }

    /**
     * check if use app style is enable
     *
     * @return bool
     */
    public static function isUseBuiltInCSSEnabled()
    {
        if( (isset($_SESSION['scm_use_app_style'])) && ($_SESSION['scm_use_app_style']==true) ) return true;

        if( (isset($_SESSION['scm_use_app_style'])) && ($_SESSION['scm_use_app_style']==false) ) return false;

        $option = get_option( 'scm_settings' );

        $res    = unserialize( $option );

        if($res['scm_use_app_style']==0) return false;

        return true;
    }

    /**
     * updates the system settings
     *
     * @param $data
     * @return bool
     */
    public static function updateSystemSettings($data)
    {
        $_SESSION['scm_debug_mode']     = ($data['scm_debug_mode']==0) ? false : true;
        $_SESSION['scm_use_app_style']  = ($data['scm_use_app_style']==0) ? false : true;

        $data = serialize($data);
        $res = update_option('scm_settings',$data);

        return $res;
    }

}