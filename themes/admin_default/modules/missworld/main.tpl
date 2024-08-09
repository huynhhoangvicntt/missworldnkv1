<!-- BEGIN: main -->
<link type="text/css" href="{NV_STATIC_URL}themes/default/images/{MODULE_FILE}/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet" />
<link type="text/css" href="{NV_STATIC_URL}themes/default/images/{MODULE_FILE}/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" />
<script type="text/javascript" src="{NV_STATIC_URL}themes/default/images/{MODULE_FILE}/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="{NV_STATIC_URL}themes/default/images/{MODULE_FILE}/bootstrap-datepicker/locales/bootstrap-datepicker.{NV_LANG_INTERFACE}.min.js"></script>
<div class="well">
    <form action="{NV_BASE_ADMINURL}index.php" method="get">
        <input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}">
        <input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}">
        <div class="row mb-3">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="element_q"><strong>{LANG.search_keywords}</strong></label>
                    <input class="form-control" type="text" value="{SEARCH.q}" maxlength="64" name="q" id="element_q" placeholder="{LANG.enter_search_key}"/>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="element_from"><strong>{LANG.from_day}</strong></label>
                    <div class="input-group">
                        <input type="text" class="form-control datepicker" id="element_from" name="f" value="{SEARCH.from}" placeholder="dd-mm-yyyy" autocomplete="off">
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button" id="from-btn">
                                <em class="fa fa-calendar fa-fix">&nbsp;</em>
                            </button>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="element_to"><strong>{LANG.to_day}</strong></label>
                    <div class="input-group">
                        <input type="text" class="form-control datepicker" id="element_to" name="t" value="{SEARCH.to}" placeholder="dd-mm-yyyy" autocomplete="off">
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button" id="to-btn">
                                <em class="fa fa-calendar fa-fix">&nbsp;</em>
                            </button>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-center">
                <button class="btn btn-primary" type="submit"><i class="fa fa-search" aria-hidden="true"></i> {GLANG.search}</button>
                <a href="{LINK_ADD_NEW}" class="btn btn-success"><i class="fa fa-plus-circle" aria-hidden="true"></i> {LANG.player_add}</a>
            </div>
        </div>
    </form>
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

    $('#from-btn').click(function(){
        $("#element_from").datepicker('show');
    });

    $('#to-btn').click(function(){
        $("#element_to").datepicker('show');
    });
});
</script>
<form>
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th class="text-center">
                        <input name="check_all[]" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);">
                    </th>
                    <th class="text-nowrap text-center" title="{LANG.id}">{LANG.id}</th>
                    <th class="text-nowrap text-center" title="{LANG.fullname}">{LANG.fullname}</th>
                    <th class="text-nowrap text-center" title="{LANG.dob}">{LANG.dob}</th>
                    <th class="text-nowrap text-center" title="{LANG.address}">{LANG.address}</th>
                    <th class="text-nowrap text-center" title="{LANG.height}">{LANG.height}</th>
                    <th class="text-nowrap text-center" title="{LANG.chest}">{LANG.chest}</th>
                    <th class="text-nowrap text-center" title="{LANG.waist}">{LANG.waist}</th>
                    <th class="text-nowrap text-center" title="{LANG.hips}">{LANG.hips}</th>
                    <th class="text-nowrap text-center" title="{LANG.email}">{LANG.email}</th>
                    <th class="text-nowrap text-center" title="{LANG.images}">{LANG.images}</th>
                    <th class="text-nowrap text-center" title="{LANG.vote}">{LANG.vote}</th>
                    <th class="text-nowrap text-center" title="{LANG.function}">{LANG.function}</th>
                </tr>
            </thead>
            <tbody>
                <!-- BEGIN: loop -->
                <tr>
                    <td class="text-center">
                        <input type="checkbox" onclick="nv_UncheckAll(this.form, 'idcheck[]', 'check_all[]', this.checked);" value="{DATA.id}" name="idcheck[]">
                    </td>
                    <td class="text-nowrap id">{DATA.id}</td>
                    <td class="text-ellipsis fullname" title="{DATA.fullname}">{DATA.fullname}</td>
                    <td class="text-nowrap dob">{DATA.dob}</td>
                    <td class="text-ellipsis address" title="{DATA.address}">{DATA.address}</td>
                    <td class="text-nowrap height">{DATA.height}</td>
                    <td class="text-nowrap chest">{DATA.chest}</td>
                    <td class="text-nowrap waist">{DATA.waist}</td>
                    <td class="text-nowrap hips">{DATA.hips}</td>
                    <td class="text-ellipsis email" title="{DATA.email}">{DATA.email}</td>
                    <td class="img-responsive-wrap">
                        <div class="img-container">
                            <img class="img-inner" src="{DATA.image}" alt="Image"/>
                        </div>
                     </td>
                    <td class="text-nowrap vote">{DATA.vote}</td>
                    <td class="text-center">
                        <a href="{DATA.url_edit}" class="btn btn-xs btn-default"><i class="fa fa-edit"></i> {GLANG.edit}</a>
                        <a href="javascript:void(0);" onclick="nv_delele_player('{DATA.id}', '{NV_CHECK_SESSION}');" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i> {GLANG.delete}</a>
                    </td>
                </tr>
                <!-- END: loop -->
            </tbody>
            <tfoot>
                <!-- BEGIN: generate_page -->
                <tr>
                    <td colspan="12">
                        {GENERATE_PAGE}
                    </td>
                </tr>
                <!-- END: generate_page -->
                <tr>
                    <td colspan="12">
                        <div class="form-group form-inline">
                            <div class="form-group">
                                <select class="form-control" id="action">
                                    <option value="delete">{GLANG.delete}</option>
                                </select>
                            </div>
                            <button type="button" class="btn btn-primary" onclick="nv_main_action(this.form, '{NV_CHECK_SESSION}', '{LANG.msgnocheck}')">{GLANG.submit}</button>
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</form>
<!-- END: main -->