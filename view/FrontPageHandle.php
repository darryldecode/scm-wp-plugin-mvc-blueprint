<?php namespace SCM_BLUEPRINT\View;

use SCM_BLUEPRINT\Classes\Utility;
use SCM_BLUEPRINT\Classes\View;
use SCM_BLUEPRINT\Model\Settings;

class FrontPageHandle {

    public function __construct()
    {

    }

    public function render()
    {

        ?>
        <!-- sorry for being slappy, just for now ! :-) -->

        <!-- Latest compiled and minified CSS -->
        <?php if( Settings::isUseBuiltInCSSEnabled() ): ?>
        <link rel="stylesheet" href="<?php echo SCM_BLUEPRINT_URI_CSS.'bootstrap.min.css'; ?>">
        <?php endif; ?>

        <!-- scmFrontWrapper -->
        <div class="scm" id="scmFrontWrapper">

            <!-- main page display -->
            <div class="panel panel-default">

                <!-- title -->
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <?php echo SCM_BLUEPRINT_PLUGIN_NAME; ?>
                    </h3>
                </div>

                <!-- body -->
                <div class="panel-body">

                    <!-- master navigation -->
                    <div id="mainNavWrapper">
                        <div class="btn-group">
                            <a href="<?php echo Utility::frontBuildURL('state=Front&action=show'); ?>" type="button" class="btn btn-default <?php echo Utility::navCanActive('',''); ?>">
                                <span class="glyphicon glyphicon-dashboard"></span> Sample
                            </a>
                        </div>
                    </div>

                    <!-- global alert -->
                    <?php if( Utility::hasFlashMessage() ): ?>
                        <div class="alert alert-<?php echo Utility::getFlashMessageMode(); ?> text-center">
                            <h4><?php echo Utility::getFlashMessage(); ?></h4>
                        </div>
                    <?php endif; ?>

                    <!-- dynamic view -->
                    <div class="panel panel-default" id="dynamicView">
                        <div class="panel-body">

                            <?php View::render(); ?>

                        </div>
                    </div>

                </div>

            </div>

        </div>

    <?php

    }

}