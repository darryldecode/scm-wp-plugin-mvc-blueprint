<?php

if( ! function_exists('scmQueueAdminMainPage') ) :

    function scmQueueAdminMainPage()
    {
        ?>
        <style>
            body {
                background-color: transparent !important;
            }
        </style>
        <div class="wrap">

            <!-- scmAdminWrapper -->
            <div class="scm" id="scmAdminWrapper">

                <!-- main page display -->
                <div class="panel panel-default">

                    <!-- title -->
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <img src="<?php echo SCM_URI_IMG.'book-icon.png'; ?>" title="SCM"> SKY DAILY DEALS QUEUEING SYSTEM MODULE
                        </h3>
                    </div>

                    <!-- body -->
                    <div class="panel-body">

                        <!-- master navigation -->
                        <div id="mainNavWrapper">
                            <div class="btn-group">
                                <a href="<?php echo SCM\Classes\SCMUtility::adminBuildUrl(''); ?>" type="button" class="btn btn-default">
                                    <span class="glyphicon glyphicon-dashboard"></span> Dashboard
                                </a>
                                <a href="<?php echo SCM\Classes\SCMUtility::adminBuildUrl('state=Settings&action=index'); ?>" type="button" class="btn btn-default">
                                    <span class="glyphicon glyphicon-wrench"></span> Settings
                                </a>
                            </div>
                        </div>

                        <!-- global alert -->
                        <?php if( \SCM\Classes\SCMUtility::hasFlashMessage() ): ?>
                            <div class="alert alert-<?php echo \SCM\Classes\SCMUtility::getFlashMessageMode(); ?> text-center">
                                <h4><?php echo \SCM\Classes\SCMUtility::getFlashMessage(); ?></h4>
                            </div>
                        <?php endif; ?>

                        <!-- dynamic view -->
                        <div class="panel panel-default" id="dynamicView">
                            <div class="panel-body">

                                <?php SCM\Classes\View::render(); ?>

                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    <?php
    }

endif; //end function_exists