<?php namespace SCM\Classes;

class View {

    /**
     * the view instance
     *
     * @var
     */
    static $instance;

    /**
     * the template to be used
     *
     * @var null
     */
    static $template = null;

    /**
     * the data to be pass to the view
     *
     * @var null
     */
    static $data = null;

    public function __construct()
    {

    }

    /**
     * singleton instance
     *
     * @return View
     */
    public static function getInstance()
    {
        if( !(self::$instance instanceof self) )
        {
            return self::$instance = new self();
        } else {
            return self::$instance;
        }
    }

    /**
     * setup a view
     *
     * @param $template
     * @param $data
     */
    public static function make($template, $data = array())
    {
        self::$template = $template;
        self::$data = $data;
    }

    /**
     * render the view
     */
    public static function render()
    {
        if( is_null(self::$template) )
        {
            echo '<div class="text-center"><h4><img src="'.SCM_URI_IMG.'book-icon.png"> Sky Course Module. All Rights Reserved.</h4></div>';
        } else {
            $scmData = self::$data;
            $template_path = SCM_PATH.'view/'.self::$template;
            include_once "{$template_path}";
        }

    }

}