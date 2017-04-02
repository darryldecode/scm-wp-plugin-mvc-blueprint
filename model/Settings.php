<?php namespace SCM_BLUEPRINT\Model;

class Settings {

    /**
     * get SCM_BLUEPRINT System settings
     *
     * @return bool|mixed
     */
    public static function getScmSystemSettings()
    {
        $option = get_option( SCM_BLUEPRINT_PLUGIN_SLUG.'_settings' );

        $res    = unserialize( $option );
        return $res;
    }

    /**
     * get SCM_BLUEPRINT Admin Email
     *
     * @return bool|mixed
     */
    public static function getScmAdminEmail()
    {
        $option = get_option( SCM_BLUEPRINT_PLUGIN_SLUG.'_settings' );

        $res    = unserialize( $option );
        return $res[SCM_BLUEPRINT_PLUGIN_SLUG.'_admin_email'];
    }

    /**
     * check if system is in debug mode
     *
     * @return bool
     */
    public static function isDebugMode()
    {
        if( (isset($_SESSION[SCM_BLUEPRINT_PLUGIN_SLUG.'_debug_mode'])) && ($_SESSION[SCM_BLUEPRINT_PLUGIN_SLUG.'_debug_mode']==true) ) return true;

        if( (isset($_SESSION[SCM_BLUEPRINT_PLUGIN_SLUG.'_debug_mode'])) && ($_SESSION[SCM_BLUEPRINT_PLUGIN_SLUG.'_debug_mode']==false) ) return false;

        $option = get_option( SCM_BLUEPRINT_PLUGIN_SLUG.'_settings' );

        $res    = unserialize( $option );

        if($res[SCM_BLUEPRINT_PLUGIN_SLUG.'_debug_mode']==0) return false;

        return true;
    }

    /**
     * check if use app style is enable
     *
     * @return bool
     */
    public static function isUseBuiltInCSSEnabled()
    {
        if( (isset($_SESSION[SCM_BLUEPRINT_PLUGIN_SLUG.'_use_app_style'])) && ($_SESSION[SCM_BLUEPRINT_PLUGIN_SLUG.'_use_app_style']==true) ) return true;

        if( (isset($_SESSION[SCM_BLUEPRINT_PLUGIN_SLUG.'_use_app_style'])) && ($_SESSION[SCM_BLUEPRINT_PLUGIN_SLUG.'_use_app_style']==false) ) return false;

        $option = get_option( SCM_BLUEPRINT_PLUGIN_SLUG.'_settings' );

        $res    = unserialize( $option );

        if($res[SCM_BLUEPRINT_PLUGIN_SLUG.'_use_app_style']==0) return false;

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
        $_SESSION[SCM_BLUEPRINT_PLUGIN_SLUG.'_debug_mode']     = ($data[SCM_BLUEPRINT_PLUGIN_SLUG.'_debug_mode']==0) ? false : true;
        $_SESSION[SCM_BLUEPRINT_PLUGIN_SLUG.'_use_app_style']  = ($data[SCM_BLUEPRINT_PLUGIN_SLUG.'_use_app_style']==0) ? false : true;

        $data = serialize($data);
        $res = update_option(SCM_BLUEPRINT_PLUGIN_SLUG.'_settings',$data);

        return $res;
    }

}