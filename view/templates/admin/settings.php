<!-- Tab panes -->
<div class="tab-content">

<!-- system settings tab -->
<div class="tab-pane active scm-settings" id="systemSettings">
    <form method="post" action="<?php echo \SCM_BLUEPRINT\Classes\Utility::adminBuildUrl('state=Settings&action=updateSystemSettings'); ?>">
        <table class="table table-bordered scm-settings-tbl">
            <tr>
                <td colspan="2"><b><?php echo SCM_BLUEPRINT_PLUGIN_NAME; ?> SETTINGS</b></td>
            </tr>
            <tr>
                <td>
                    API Key
                    <br> <small>(The API key to access the api on this module.)</small>
                </td>
                <td>
                    <input type="text" name="<?php echo SCM_BLUEPRINT_PLUGIN_SLUG; ?>_api_key" value="<?php echo $scmData['settings'][SCM_BLUEPRINT_PLUGIN_SLUG.'_api_key']; ?>">
                </td>
            </tr>
        </table>
        <table class="table table-bordered scm-settings-tbl">
            <tr>
                <td colspan="2"><b>SYSTEM SETTINGS</b></td>
            </tr>
            <tr>
                <td>
                    Use built-in CSS?:
                    <br> <small>(By default this uses Twitter Bootstrap)</small>
                </td>
                <td>
                    <select name="<?php echo SCM_BLUEPRINT_PLUGIN_SLUG; ?>_use_app_style">
                        <option <?php echo ($scmData['settings'][SCM_BLUEPRINT_PLUGIN_SLUG.'_use_app_style'] == 1) ? 'selected' : ''; ?> value="1">yes</option>
                        <option <?php echo ($scmData['settings'][SCM_BLUEPRINT_PLUGIN_SLUG.'_use_app_style'] == 0) ? 'selected' : ''; ?> value="0">no</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    Front Page Course URL:
                    <br> <small>(please follow the format properly.)</small>
                </td>
                <td>
                    <input type="text" name="<?php echo SCM_BLUEPRINT_PLUGIN_SLUG; ?>_front_page_url" value="<?php echo $scmData['settings'][SCM_BLUEPRINT_PLUGIN_SLUG.'_front_page_url']; ?>">
                </td>
            </tr>
            <tr>
                <td>
                    Safe Mode:
                    <br> <small><span class="glyphicon glyphicon-exclamation-sign"></span> (Warning! Disabling this will totally reset all database records during plugin deactivation of this module.)</small>
                </td>
                <td>
                    <select name="<?php echo SCM_BLUEPRINT_PLUGIN_SLUG; ?>_safe_mode">
                        <option <?php echo ($scmData['settings'][SCM_BLUEPRINT_PLUGIN_SLUG.'_safe_mode'] == 'enabled') ? 'selected' : ''; ?> value="enabled">enabled</option>
                        <option <?php echo ($scmData['settings'][SCM_BLUEPRINT_PLUGIN_SLUG.'_safe_mode'] == 'disabled') ? 'selected' : ''; ?> value="disabled">disabled</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    System Debug Mode:
                    <br> <small>(This will throw informational errors on frontend. Enable only in development.)</small>
                </td>
                <td>
                    <select name="<?php echo SCM_BLUEPRINT_PLUGIN_SLUG; ?>_debug_mode">
                        <option <?php echo ($scmData['settings'][SCM_BLUEPRINT_PLUGIN_SLUG.'_debug_mode'] == 1) ? 'selected' : ''; ?> value="1">enabled</option>
                        <option <?php echo ($scmData['settings'][SCM_BLUEPRINT_PLUGIN_SLUG.'_debug_mode'] == 0) ? 'selected' : ''; ?> value="0">disabled</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    Admin Email:
                    <br> <small>(Emails for this module activities will send here.)</small>
                </td>
                <td>
                    <input type="text" name="<?php echo SCM_BLUEPRINT_PLUGIN_SLUG; ?>_admin_email" value="<?php echo $scmData['settings'][SCM_BLUEPRINT_PLUGIN_SLUG.'_admin_email']; ?>">
                </td>
            </tr>
            <tr>
                <td colspan="2" class="text-right">
                    <input type="submit" value="update" class="btn btn-primary">
                    <input type="hidden" name="_nonce" value="<?php echo wp_create_nonce(SCM_BLUEPRINT_PLUGIN_SLUG.'_nonce') ?>">
                </td>
            </tr>
        </table>
    </form>
</div>



</div>