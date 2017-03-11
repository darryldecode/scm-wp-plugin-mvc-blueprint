<?php namespace SCM\View;

use SCM\Classes\SCMUtility;
use SCM\Classes\View;
use SCM\Model\Settings;

class SCMFrontPageHandle {

    public function __construct()
    {

        ?>
        <!-- sorry for being slappy, just for now ! :-) -->

        <!-- Latest compiled and minified CSS -->
        <?php if( Settings::isUseBuiltInCSSEnabled() ): ?>
        <link rel="stylesheet" href="<?php echo SCM_URI_CSS.'bootstrap.min.css'; ?>">
    <?php endif; ?>

        <!-- scmFrontWrapper -->
        <div class="scm" id="scmFrontWrapper">

            <!-- main page display -->
            <div class="panel panel-default">

                <!-- title -->
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <?php echo SCM_PLUGIN_NAME; ?>
                    </h3>
                </div>

                <!-- body -->
                <div class="panel-body">

                    <!-- master navigation -->
                    <div id="mainNavWrapper">
                        <div class="btn-group">
                            <a href="<?php echo SCMUtility::frontBuildURL('state=Front&action=show'); ?>" type="button" class="btn btn-default <?php echo SCMUtility::navCanActive('',''); ?>">
                                <span class="glyphicon glyphicon-dashboard"></span> Sample
                            </a>
                        </div>
                    </div>

                    <!-- global alert -->
                    <?php if( SCMUtility::hasFlashMessage() ): ?>
                        <div class="alert alert-<?php echo SCMUtility::getFlashMessageMode(); ?> text-center">
                            <h4><?php echo SCMUtility::getFlashMessage(); ?></h4>
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