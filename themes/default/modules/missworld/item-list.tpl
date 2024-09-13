<!-- BEGIN: main -->
<div class="items-list">
  <!-- BEGIN: loop -->
  <div class="item">
      <div class="panel panel-default">
          <div class="panel-body">
              <div class="item-inner">
                  <!-- BEGIN: image -->
                  <div class="item-image">
                      <a href="{ROW.link}" style="background-image: url('{ROW.thumb}');"><img alt="{ROW.title_text}" src="{ROW.thumb}" class="hidden"></a>
                  </div>
                  <!-- END: image -->
                  <div class="item-content">
                      <h3><a href="{ROW.link}">{ROW.fullname}</a></h3>
                      <div class="meta text-muted small">
                          <i class="fa fa-clock-o"></i> {ROW.dob}
                      </div>
                      <div class="item-hometext">
                          {ROW.keywords}
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
  <!-- END: loop -->
</div>
<!-- END: main -->
