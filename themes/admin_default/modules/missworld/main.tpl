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
            <div class="col-md-8">
                <div class="form-group">
                    <label for="element_q"><strong>{LANG.search_keywords}</strong></label>
                    <input class="form-control" type="text" value="{SEARCH.q}" maxlength="64" name="q" id="element_q" placeholder="{LANG.enter_search_key}"/>
                </div>
            </div>
            <div class="col-md-8">
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
            <div class="col-md-8">
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
            <div class="col-md-24 text-center">
                <button class="btn btn-primary" type="submit"><i class="fa fa-search" aria-hidden="true"></i> {GLANG.search}</button>
                <a href="{LINK_ADD_NEW}" class="btn btn-success"><i class="fa fa-plus-circle" aria-hidden="true"></i> {LANG.contestant_add}</a>
            </div>
        </div>
    </form>
</div>
<form action="{NV_BASE_ADMINURL}index.php" method="post">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th class="text-center w-50 check-column">
                        <input name="check_all[]" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);">
                    </th>
                    <th class="text-nowrap text-center" title="{LANG.id}">{LANG.id}</th>
                    <th class="text-nowrap text-center" title="{LANG.images}">{LANG.images}</th>
                    <th class="text-nowrap text-center" title="{LANG.fullname}">{LANG.fullname}</th>
                    <th class="text-nowrap text-center" title="{LANG.vote}">{LANG.vote}</th>
                    <th class="text-nowrap text-center" title="{LANG.function}">{LANG.function}</th>
                </tr>
            </thead>
            <tbody>
                <!-- BEGIN: loop -->
                <tr>
                    <td class="text-center check-column">
                        <input type="checkbox" onclick="nv_UncheckAll(this.form, 'idcheck[]', 'check_all[]', this.checked);" value="{DATA.id}" name="idcheck[]">
                    </td>
                    <td class="text-nowrap id">{DATA.id}</td>
                    <td class="img-responsive-wrap">
                        <div class="img-container"> 
                            <img class="img-inner" src="{DATA.image}" alt="{DATA.fullname}"/>
                        </div>
                    </td>
                    <td class="text-ellipsis fullname" title="{DATA.fullname}">{DATA.fullname}</td>
                    <td class="text-nowrap vote">{DATA.vote}</td>
                    <td class="text-center">
                        <a href="{DATA.url_edit}" class="btn btn-xs btn-default"><i class="fa fa-edit"></i> {GLANG.edit}</a>
                        <a href="javascript:void(0);" onclick="nv_delete_contestant('{DATA.id}', '{NV_CHECK_SESSION}');" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i> {GLANG.delete}</a>
                        <button type="button" class="btn btn-xs btn-info view-details" data-contestant='{DATA.encoded_data}'><i class="fa fa-eye"></i> {LANG.view_details}</button>
                    </td>
                </tr>
                <!-- END: loop -->
            </tbody>
            <tfoot>
                <!-- BEGIN: generate_page -->
                <tr>
                    <td colspan="6">
                        {GENERATE_PAGE}
                    </td>
                </tr>
                <!-- END: generate_page -->
                <tr>
                    <td colspan="6">
                        <div class="row">
                            <div class="col-sm-24">
                                <div class="form-group form-inline">
                                    <select class="form-control" id="action" name="action">
                                        <option value="delete">{GLANG.delete}</option>
                                    </select>
                                    <button type="button" class="btn btn-primary" onclick="nv_main_action(this.form, '{NV_CHECK_SESSION}', '{LANG.msgnocheck}')">{GLANG.submit}</button>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</form>
<div class="modal fade" id="contestantDetailsModal" tabindex="-1" role="dialog" aria-labelledby="contestantDetailsModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="contestantDetailsModalLabel">{LANG.contestant_details}</h4>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{LANG.close}</button>
            </div>
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

    $('#from-btn').click(function(){
        $("#element_from").datepicker('show');
    });

    $('#to-btn').click(function(){
        $("#element_to").datepicker('show');
    });

    $('.view-details').on('click', function() {
        var contestantData = JSON.parse($(this).attr('data-contestant'));
        var modalBody = $('#contestantDetailsModal .modal-body');
        
        modalBody.empty();
        
        var detailsHtml = '<table class="table">';
        detailsHtml += '<tr><th>{LANG.fullname}</th><td>' + contestantData.fullname + '</td></tr>';
        detailsHtml += '<tr><th>{LANG.dob}</th><td>' + contestantData.dob + '</td></tr>';
        detailsHtml += '<tr><th>{LANG.address}</th><td>' + contestantData.address + '</td></tr>';
        detailsHtml += '<tr><th>{LANG.height}</th><td>' + contestantData.height + '</td></tr>';
        detailsHtml += '<tr><th>{LANG.chest}</th><td>' + contestantData.chest + '</td></tr>';
        detailsHtml += '<tr><th>{LANG.waist}</th><td>' + contestantData.waist + '</td></tr>';
        detailsHtml += '<tr><th>{LANG.hips}</th><td>' + contestantData.hips + '</td></tr>';
        detailsHtml += '<tr><th>{LANG.email}</th><td>' + contestantData.email + '</td></tr>';
        detailsHtml += '<tr><th>{LANG.vote}</th><td>' + contestantData.vote + '</td></tr>';
        detailsHtml += '</table>';
        
        if (contestantData.image) {
            detailsHtml = '<div class="text-center mb-3"><img src="' + contestantData.image + '" alt="{LANG.contestant_image}" class="img-responsive" style="max-height: 200px;"></div>' + detailsHtml;
        }
        
        modalBody.html(detailsHtml);
        
        $('#contestantDetailsModal').modal('show');
    });
});
</script>
<!-- END: main -->