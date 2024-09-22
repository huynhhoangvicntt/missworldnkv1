<!-- BEGIN: main -->
<script type="text/javascript">
    $(document).ready(function() {
        $('#search-submit').on('click', function(e) {
            e.preventDefault();
            var hasValue = $('#element_q').val().trim() !== '';
            
            if (!hasValue) {
                if ($('.search-please').length === 0) {
                    $('#search-form').after('<div class="alert alert-info search-please">{LANG.search_please}</div>');
                } else {
                    $('.search-please').show();
                }
            } else {
                $('.search-please').hide();
                $('#search-form').submit();
            }
        });
    
        $('#element_q').on('input', function() {
            $('.search-please').hide();
        });
    });
</script>
<div class="module-name-{MODULE_NAME} module-file-{MODULE_FILE} op-{OP}">
    <form action="{FORM_ACTION}" method="get" id="search-form">
        <div class="form-group">
            <input type="text" class="form-control" id="element_q" name="q" value="{SEARCH.q}" placeholder="{LANG.enter_search_key}">
        </div>
        <button type="button" id="search-submit" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i> {GLANG.search}</button>
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
