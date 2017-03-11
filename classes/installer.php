<?php namespace SCM\Classes;

class SCMInstaller {

    public function createSchema()
    {

    }

    public function dropSchema()
    {

    }

    public function setOptions()
    {
        $options = array(
            'scm_settings' => array(
                'scm_company_name' => 'My Company',
                'scm_safe_mode' => 'disabled',
                'scm_front_page_url' => '/scm/',
                'scm_debug_mode' => 0,
                'scm_admin_email' => 'webmaster@email.com',
                'scm_mailer_engine' => 'Default',
                'scm_use_app_style' => 1,
            )
        );

        foreach ($options as $key => $value){
            $value = serialize($value);
            add_option($key,$value );
        }
    }

    public function deleteOption()
    {
        delete_option('scm_settings');
    }

}