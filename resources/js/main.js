(function($){

    var SCM_BLUEPRINT = {};

    SCM_BLUEPRINT.init = function () {
        this.adminCreateCourse();
        this.activateSettingsTab();
    };

    SCM_BLUEPRINT.adminCreateCourse = function () {
        $('.forDate').datepicker();
    };

    SCM_BLUEPRINT.activateSettingsTab = function () {
        $('a#mainOpen').tab('show');
    };

    $(document).ready(function(){
        SCM_BLUEPRINT.init();
    })

})(jQuery);