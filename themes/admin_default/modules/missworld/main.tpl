<!-- BEGIN: main -->
<div class="col-lg-6">
  <div class="form-group text-right">
      <label class="visible-sm-block visible-md-block visible-lg-block">&nbsp;</label>
      <a href="{LINK_ADD_NEW}" class="btn btn-success"><i class="fa fa-plus-circle" aria-hidden="true"></i> {LANG.main_add}</a>
  </div>
</div>
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr class="text-center">
                <th class="text-nowrap">{LANG.order}</th>
                <th class="text-nowrap">Họ và tên thí sinh</th>
                <th class="text-nowrap">Ngày sinh</th>
                <th class="text-nowrap">Địa chỉ</th>
                <th class="text-nowrap">Chiều cao</th>
                <th class="text-nowrap">Số đo vòng ngực</th>
                <th class="text-nowrap">Số đo vòng eo</th>
                <th class="text-nowrap">Số đo vòng mông</th>
                <th class="text-nowrap">Địa chỉ email</th>
                <th class="img">Ảnh hồ sơ</th>
                <th class="text-nowrap">Số lượt vote</th>
                <th class="text-nowrap">Chức năng</th>
            </tr>
        </thead>
        <tbody>
            <!-- BEGIN: loop -->
                <tr class="text-center">
                    <td class="text-center">
                        <select id="change_weight_{DATA.id}" onchange="nv_change_player_weight('{DATA.id}', '{NV_CHECK_SESSION}');"
                        class="form-control input-sm">
                            <!-- BEGIN: weight -->
                            <option value="{WEIGHT.w}" {WEIGHT.selected}>{WEIGHT.w}</option>
                            <!-- END: weight -->
                        </select>
                    </td>
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
                        <a class="btn btn-sm btn-default" href="{DATA.url_edit}"><i class="fa fa-edit"></i> {GLANG.edit}</a>
                        <a class="btn btn-sm btn-danger" href="javascript:void(0);" onclick="nv_delele_player('{DATA.id}', '{NV_CHECK_SESSION}');"><i class="fa fa-trash"></i> {GLANG.delete}</a>
                    </td>
                </tr>
            <!-- END: loop -->
        </tbody>
    </table>
</div>
<!-- END: main -->
