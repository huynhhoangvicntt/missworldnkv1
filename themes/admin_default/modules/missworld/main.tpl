<!-- BEGIN: main -->
<link type="text/css" href="{NV_STATIC_URL}themes/default/images/{MODULE_FILE}/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet" />
<link type="text/css" href="{NV_STATIC_URL}themes/default/images/{MODULE_FILE}/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" />
<script type="text/javascript" src="{NV_STATIC_URL}themes/default/images/{MODULE_FILE}/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="{NV_STATIC_URL}themes/default/images/{MODULE_FILE}/bootstrap-datepicker/locales/bootstrap-datepicker.{NV_LANG_INTERFACE}.min.js"></script>
<div class="DATA">
    <div class="col-lg-18">
        <form method="get" action="{NV_BASE_ADMINURL}index.php">
            <input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}">
            <input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}">
            <div class="DATA">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="element_q">{LANG.search_keywords}:</label>
                        <input type="text" class="form-control" id="element_q" name="q" value="{SEARCH.q}" placeholder="{LANG.enter_search_key}">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="element_from">{LANG.from_day}:</label>
                        <input type="text" class="form-control datepicker" id="element_from" name="f" value="{SEARCH.from}" placeholder="dd/mm/yyyy" autocomplete="off">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="element_to">{LANG.to_day}:</label>
                        <input type="text" class="form-control datepicker" id="element_to" name="t" value="{SEARCH.to}" placeholder="dd/mm/yyyy" autocomplete="off">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="visible-sm-block visible-md-block visible-lg-block">&nbsp;</label>
                        <button class="btn btn-primary" type="submit"><i class="fa fa-search" aria-hidden="true"></i> {GLANG.search}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="col-lg-6">
        <div class="form-group text-right">
            <label class="visible-sm-block visible-md-block visible-lg-block">&nbsp;</label>
            <a href="{LINK_ADD_NEW}" class="btn btn-success"><i class="fa fa-plus-circle" aria-hidden="true"></i> {LANG.player_add}</a>
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
    $('.datepicker').datepicker({
        language: '{NV_LANG_INTERFACE}',
        format: 'dd-mm-yyyy',
        weekStart: 1,
        todayBtn: 'linked',
        autoclose: true,
        todayHighlight: true,
        zIndexOffset: 1000
    });
});
</script>
<form>
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th class="text-nowrap text-center">{LANG.id}</th>
                    <th class="text-nowrap text-center">{LANG.fullname}</th>
                    <th class="text-nowrap text-center">{LANG.dob}</th>
                    <th class="text-nowrap text-center">{LANG.address}</th>
                    <th class="text-nowrap text-center">{LANG.height}</th>
                    <th class="text-nowrap text-center">{LANG.chest}</th>
                    <th class="text-nowrap text-center">{LANG.waist}</th>
                    <th class="text-nowrap text-center">{LANG.hips}</th>
                    <th class="text-nowrap text-center">{LANG.email}</th>
                    <th class="text-nowrap text-center">{LANG.images}</th>
                    <th class="text-nowrap text-center">{LANG.vote}</th>
                    <th class="text-nowrap text-center">{LANG.function}</th>
                </tr>
            </thead>
            <tbody>
                <!-- BEGIN: loop -->
                <tr>
                    <td class="text-nowrap">{DATA.id}</td>
                    <td class="text-nowrap">{DATA.fullname}</td>
                    <td class="text-nowrap">{DATA.dob}</td>
                    <td class="text-nowrap">{DATA.address}</td>
                    <td class="text-nowrap">{DATA.height}</td>
                    <td class="text-nowrap">{DATA.chest}</td>
                    <td class="text-nowrap">{DATA.waist}</td>
                    <td class="text-nowrap">{DATA.hips}</td>
                    <td class="text-nowrap">{DATA.email}</td>
                    <td class="img-responsive-wrap">
                        <img class="img-inner" src="{DATA.image}"/>
                    </td>
                    <td class="text-nowrap">{DATA.vote}</td>
                    <td class="text-center text-nowrap">
                        <a href="{DATA.url_edit}" class="btn btn-xs btn-default"><i class="fa fa-edit"></i> {GLANG.edit}</a>
                        <a href="javascript:void(0);" onclick="nv_delele_player('{DATA.id}', '{NV_CHECK_SESSION}');" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i> {GLANG.delete}</a>
                    </td>   
                </tr>
                <!-- END: loop -->
            </tbody>
            <!-- BEGIN: generate_page -->
            <tfoot>
                <tr>
                    <td colspan="6">
                        {GENERATE_PAGE}
                    </td>
                </tr>
            </tfoot>
            <!-- END: generate_page -->
        </table>
    </div>
</form>
<!-- END: main -->
