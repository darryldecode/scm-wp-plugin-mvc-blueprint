<?php namespace SCM_BLUEPRINT\Classes;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Model extends Eloquent {

    public static $instance;

    public function __construct()
    {
        parent::__construct();
        self::$instance = $this;
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}