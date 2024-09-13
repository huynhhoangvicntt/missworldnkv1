<!-- BEGIN: main -->
<link type="text/css" href="{NV_STATIC_URL}themes/default/images/{MODULE_FILE}/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet" />
<link type="text/css" href="{NV_STATIC_URL}themes/default/images/{MODULE_FILE}/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" />
<script type="text/javascript" src="{NV_STATIC_URL}themes/default/images/{MODULE_FILE}/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="{NV_STATIC_URL}themes/default/images/{MODULE_FILE}/bootstrap-datepicker/locales/bootstrap-datepicker.{NV_LANG_INTERFACE}.min.js"></script>
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
<div class="module-name-{MODULE_NAME} module-file-{MODULE_FILE} op-{OP}">
    <h2 class="title-search">{LANG.search}</h2>
    <hr class="space-search" />
    <form method="get" action="{FORM_ACTION}">
        <!-- BEGIN: no_rewrite -->
        <input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}">
        <input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}">
        <input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}">
        <!-- END: no_rewrite -->
        <div class="row">
            <div class="col-sm-6 col-md-6">
                <div class="form-group">
                    <label for="element_q">{LANG.search_keywords}:</label>
                    <input type="text" class="form-control" id="element_q" name="q" value="{SEARCH.q}" placeholder="{LANG.search_keywords}">
                </div>
            </div>
            <div class="col-sm-6 col-md-6">
                <div class="form-group">
                    <label for="element_from">{LANG.search_from}:</label>
                    <input type="text" class="form-control datepicker" id="element_from" name="f" value="{SEARCH.from}" placeholder="dd/mm/yyyy" autocomplete="off">
                </div>
            </div>
            <div class="col-sm-6 col-md-6">
                <div class="form-group">
                    <label for="element_to">{LANG.search_to}:</label>
                    <input type="text" class="form-control datepicker" id="element_to" name="t" value="{SEARCH.to}" placeholder="dd/mm/yyyy" autocomplete="off">
                </div>
            </div>
            <div class="col-sm-6 col-md-6">
                <div class="form-group">
                    <label class="visible-sm-block visible-md-block visible-lg-block">&nbsp;</label>
                    <button class="btn btn-primary" type="submit"><i class="fa fa-search" aria-hidden="true"></i> {GLANG.search}</button>
                </div>
            </div>
        </div>
    </form>
    <!-- BEGIN: please -->
    <div class="alert alert-info search-please">{LANG.search_please}</div>
    <!-- END: please -->
    <!-- BEGIN: empty -->
    <div class="alert alert-warning search-empty">{LANG.search_empty}</div>
    <!-- END: empty -->
    <!-- BEGIN: data -->
    <p class="search-result-total text-right">{LANG.search_result_count} <strong class="text-danger">{NUM_ITEMS}</strong></p>
    <div class="items-outer">
        {HTML}
    </div>
    <!-- END: data -->
    <!-- BEGIN: generate_page -->
    <div class="text-center generate-page">
        {GENERATE_PAGE}
    </div>
    <!-- END: generate_page -->
</div>
<!-- END: main -->
