<?php namespace SCM_BLUEPRINT\View;

use SCM_BLUEPRINT\Classes\Utility;
use SCM_BLUEPRINT\Classes\View;

class AdminPageHandle {

    public function __construct()
    {

    }

    public function render()
    {
        ?>
        <style>
            body {
                background-color: transparent !important;
            }
        </style>
        <div class="wrap">

            <!-- scmAdminWrapper -->
            <div class="scm">

                <div class="card scm-main-view">
                    <div class="card-header">
                        <h3 class="panel-title">
                            <i class="fa fa-laptop"></i> <?php echo SCM_BLUEPRINT_PLUGIN_NAME; ?>
                        </h3>
                    </div>
                    <div class="card-block">
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link <?php echo Utility::navCanActive('',''); ?>" href="<?php echo Utility::adminBuildUrl(''); ?>"><i class="fa fa-dashboard"></i> Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo Utility::navCanActive('Settings','index'); ?>" href="<?php echo Utility::adminBuildUrl('state=Settings&action=index'); ?>"><i class="fa fa-cogs"></i> Settings</a>
                            </li>
                        </ul>
                        <div class="scm-view-body">
                            <!-- global alert -->
                            <?php if( Utility::hasFlashMessage() ): ?>
                                <div class="alert alert-<?php echo Utility::getFlashMessageMode(); ?> text-center">
                                    <h4><?php echo Utility::getFlashMessage(); ?></h4>
                                </div>
                            <?php endif; ?>
                            <?php View::render(); ?>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    <?php
    }

}