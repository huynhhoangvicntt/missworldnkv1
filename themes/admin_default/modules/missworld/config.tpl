<!-- BEGIN: main -->
<link rel="stylesheet" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.css"/>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.js"></script>
<!-- BEGIN: error -->
<div class="alert alert-danger">{error}</div>
<!-- END: error -->
<form class="form-inline" role="form" action="{NV_BASE_ADMINURL}index.php" method="post" autocomplete="off">
    <input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}"/>
    <input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}"/>
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <caption><em class="fa fa-file-text-o">&nbsp;</em></caption>
            <tbody>
                <tr>
                    <th>{LANG.setting_per_page}</th>
                    <td>
                        <select class="form-control" name="per_page">
                            <!-- BEGIN: per_page -->
                            <option value="{PER_PAGE.key}" {PER_PAGE.selected}>{PER_PAGE.title}</option>
                            <!-- END: per_page -->
                        </select>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</form>
<!-- END: main -->
