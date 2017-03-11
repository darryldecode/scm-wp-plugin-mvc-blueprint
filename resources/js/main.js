(function($){

    var SCM = {};

    SCM.init = function () {
        this.adminCreateCourse();
        this.activateSettingsTab();
    };

    SCM.adminCreateCourse = function () {
        $('.forDate').datepicker();
    };

    SCM.activateSettingsTab = function () {
        $('a#mainOpen').tab('show');
    };

    $(document).ready(function(){
        SCM.init();
    })

})(jQuery);