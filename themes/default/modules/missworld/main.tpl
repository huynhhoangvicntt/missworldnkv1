<!-- BEGIN: main -->
<div class="table-responsive">
  <table class="table table-striped table-bordered table-hover">
    <thead>
      <tr class="text-center">
        <th class="text-nowrap">Thứ tự</th>
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
      </tr>
    </thead>
    <tbody>
      <!-- BEGIN: loop -->
      <tr class="text-center">
        <td class="text-nowrap">{DATA.weight}</td>
        <td class="text-nowrap">{DATA.fullname}</td>
        <td class="text-nowrap">{DATA.dob}</td>
        <td class="text-nowrap">{DATA.address}</td>
        <td class="text-nowrap">{DATA.height}</td>
        <td class="text-nowrap">{DATA.chest}</td>
        <td class="text-nowrap">{DATA.waist}</td>
        <td class="text-nowrap">{DATA.hips}</td>
        <td class="text-nowrap">{DATA.email}</td>
        <td class="img-responsive-wrap">
          <img
            class="img-inner"
            style="width: 100px; height: 100px"
            src="{DATA.image}"
          />
        </td>
        <td class="text-nowrap">{DATA.vote}</td>
      </tr>
      <!-- END: loop -->
    </tbody>
  </table>
</div>
<!-- END: main -->
