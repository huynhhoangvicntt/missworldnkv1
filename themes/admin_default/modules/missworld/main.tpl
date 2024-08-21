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
                    <label for="element_q"><strong>{LANG.search_contestant_keywords}</strong></label>
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
                    <th class="text-nowrap text-center" title="{LANG.vote}">
                        <a href="{URL_ORDER_VOTE}">{ICON_ORDER_VOTE} {LANG.vote}</a>
                    </th>
                    <th class="text-nowrap text-center" title="{LANG.status}">{LANG.status}</th>
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
                    <td class="fullname" title="{DATA.fullname}">
                        <span class="text-ellipsis">{DATA.fullname}</span>
                    </td>
                    <td class="text-nowrap vote">{DATA.vote}</td>
                    <td class="text-center">
                        <input type="checkbox" name="status" id="change_status{DATA.id}" value="1" onclick="nv_change_contestant_status('{DATA.id}', '{NV_CHECK_SESSION}');" {DATA.status_checked}/>
                    </td>
                    <td class="text-center">
                        <a href="{DATA.url_edit}" class="btn btn-xs btn-default"><i class="fa fa-edit"></i> {GLANG.edit}</a>
                        <a href="javascript:void(0);" onclick="nv_delete_contestant('{DATA.id}', '{NV_CHECK_SESSION}');" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i> {GLANG.delete}</a>
                        <button type="button" class="btn btn-xs btn-info view-details" data-contestant='{DATA.encoded_data}'><i class="fa fa-eye"></i> {LANG.view_details}</button>
                        <a href="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}=voters&amp;contestant_id={DATA.id}" class="btn btn-xs btn-info"><i class="fa fa-list"></i> {LANG.view_votes}</a>
                    </td>
                </tr>
                <!-- END: loop -->
            </tbody>
            <tfoot>
                <!-- BEGIN: generate_page -->
                <tr>
                    <td colspan="7">
                        {GENERATE_PAGE}
                    </td>
                </tr>
                <!-- END: generate_page -->
                <tr>
                    <td colspan="7">
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
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="contestantDetailsModalLabel">{LANG.contestant_details}</h4>
            </div>
            <div class="modal-body">
                <div id="contestantImage" class="text-center mb-3"></div>
                <table id="contestantDetails" class="table"></table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{LANG.close}</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
    // Khởi tạo Datepicker
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
    
    // Xử lý modal
    var langKeys = {
        fullname: '{LANG.fullname}',
        dob: '{LANG.date_of_birth}',
        address: '{LANG.address}',
        height: '{LANG.height}',
        chest: '{LANG.chest}',
        waist: '{LANG.waist}',
        hips: '{LANG.hips}',
        email: '{LANG.email}',
        vote: '{LANG.vote}',
        images: '{LANG.images}'
    };
    
    function populateContestantDetails(data) {
        var $modalBody = $('#contestantDetailsModal .modal-body');
    
        $modalBody.empty();
    
        var detailsHtml = '';
    
        if (data.image) {
            detailsHtml += '<div id="contestantImage" class="text-center mb-3">'
                + '<img src="' + data.image + '" '
                + 'alt="' + langKeys.images + '" '
                + 'class="img-responsive" '
                + 'style="max-height: 200px; margin: 0 auto;">'
                + '</div>';
        }
    
        detailsHtml += '<table id="contestantDetails" class="table">';
        for (var key in data) {
            if (key !== 'image' && langKeys.hasOwnProperty(key)) {
                var value = data[key];
                if (['height', 'chest', 'waist', 'hips'].includes(key) && value !== '' && value !== null) {
                    value += ' cm';
                }
                detailsHtml += '<tr><th>' + langKeys[key] + '</th><td>' + value + '</td></tr>';
            }
        }
        detailsHtml += '</table>';
    
        $modalBody.html(detailsHtml);
    }
    
    $('.view-details').on('click', function() {
        var contestantData = JSON.parse($(this).attr('data-contestant'));
        populateContestantDetails(contestantData);
        $('#contestantDetailsModal').modal('show');
    });
});
</script>
<!-- END: main -->