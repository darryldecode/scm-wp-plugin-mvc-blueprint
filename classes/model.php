<?php namespace SCM\Classes;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Eloquent\Model as Eloquent;

class SCMModel extends Eloquent {

    public function __construct()
    {
        parent::__construct();

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

}