<!-- BEGIN: main -->
<div class="missworld-search-block">
  <form action="{FORM_ACTION}" method="get" id="missworld-search-form">
      <!-- BEGIN: no_rewrite -->
      <input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}" />
      <input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
      <input type="hidden" name="{NV_OP_VARIABLE}" value="search" />
      <!-- END: no_rewrite -->
      <div class="form-group">
          <input type="text" class="form-control" name="q" value="{SEARCH_QUERY}" placeholder="{LANG.enter_search_key}" />
      </div>
      <button type="submit" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i> {LANG.search}</button>
  </form>
</div>
<!-- END: main -->
 