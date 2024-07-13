<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->
<div class="panel panel-default">
    <div class="panel-body">
        <form method="post" action="{FORM_ACTION}" class="form-horizontal">
            <div class="form-group">
                <label class="col-sm-6 control-label" for="element_title">{LANG.fullname}<span class="fa-required text-danger">(<em class="fa fa-asterisk"></em>)</span>:</label>
                <div class="col-sm-18 col-lg-10">
                    <input type="text" id="element_title" name="fullname" value="{DATA.fullname}" class="form-control"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-6 control-label" for="element_alias">{LANG.alias}</label>
                <div class="col-sm-18 col-lg-10">
                    <div class="input-group">
                        <input type="text" id="element_alias" name="alias" value="{DATA.alias}" class="form-control"/>
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button" onclick="get_player_alias('{DATA.id}', '{NV_CHECK_SESSION}')"><i class="fa fa-retweet"></i></button>
                        </span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-6 control-label" for="element_dob">{LANG.dob}<span class="fa-required text-danger">(<em class="fa fa-asterisk"></em>)</span>:</label>
                <div class="col-sm-18 col-lg-10">
                    <input type="text" id="element_dob" name="dob" value="{DATA.dob}" class="form-control"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-6 control-label" for="element_address">{LANG.address}<span class="fa-required text-danger">(<em class="fa fa-asterisk"></em>)</span>:</label>
                <div class="col-sm-18 col-lg-10">
                    <input type="text" id="element_address" name="address" value="{DATA.address}" class="form-control"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-6 control-label" for="height">{LANG.height}<span class="fa-required text-danger">(<em class="fa fa-asterisk"></em>)</span>:</label>
                <div class="col-sm-18 col-lg-10">
                    <input type="text" id="element_height" name="height" value="{DATA.height}" class="form-control"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-6 control-label" for="element_chest">{LANG.chest}<span class="fa-required text-danger">(<em class="fa fa-asterisk"></em>)</span>:</label>
                <div class="col-sm-18 col-lg-10">
                    <input type="text" id="element_chest" name="chest" value="{DATA.chest}" class="form-control"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-6 control-label" for="element_waist">{LANG.waist}<span class="fa-required text-danger">(<em class="fa fa-asterisk"></em>)</span>:</label>
                <div class="col-sm-18 col-lg-10">
                    <input type="text" id="element_waist" name="waist" value="{DATA.waist}" class="form-control"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-6 control-label" for="element_hips">{LANG.hips}<span class="fa-required text-danger">(<em class="fa fa-asterisk"></em>)</span>:</label>
                <div class="col-sm-18 col-lg-10">
                    <input type="text" id="element_hips" name="hips" value="{DATA.hips}" class="form-control"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-6 control-label" for="element_email">{LANG.email}<span class="fa-required text-danger">(<em class="fa fa-asterisk"></em>)</span>:</label>
                <div class="col-sm-18 col-lg-10">
                    <input type="text" id="element_email" name="email" value="{DATA.email}" class="form-control"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-6 control-label" for="element_image">{LANG.images}:</label>
                <div class="col-sm-18 col-lg-10">
                    <div class="input-group">
                        <input type="text" id="element_image" name="image" value="{DATA.image}" class="form-control"/>
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button" id="element_image_pick"><i class="fa fa-file-image-o"></i></button>
                        </span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-6 control-label" for="element_vote">{LANG.vote}<span class="fa-required text-danger">(<em class="fa fa-asterisk"></em>)</span>:</label>
                <div class="col-sm-18 col-lg-10">
                    <input type="text" id="element_vote" name="vote" value="{DATA.vote}" class="form-control"/>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-18 col-sm-offset-6">
                    <input type="hidden" name="save" value="{NV_CHECK_SESSION}" />
                    <button type="submit" class="btn btn-primary">{GLANG.submit}</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- BEGIN: getalias -->
<script type="text/javascript">
  $(document).ready(function() {
      var autoAlias = true;
      $('#element_title').on('change', function() {
          if (autoAlias) {
            get_player_alias('{DATA.id}', '{NV_CHECK_SESSION}');
          }
      });
      $('#element_alias').on('keyup', function() {
          if (trim($(this).val()) == '') {
              autoAlias = true;
          } else {
              autoAlias = false;
          }
      });
  });
  </script>
<!-- END: getalias -->

<script type="text/javascript">
  $(document).ready(function(){
      $('#element_image_pick').on('click', function(e) {
          e.preventDefault();
          nv_open_browse(script_name + "?" + nv_name_variable + "=upload&popup=1&area=element_image&path={UPLOAD_PATH}&type=image&currentpath={UPLOAD_CURRENT}", "NVImg", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
      });
  });
  </script>
<!-- END: main -->
 