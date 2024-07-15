<!-- BEGIN: main -->
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <caption>Danh sách thí sinh</caption>
        <thead>
            <tr class="text-center">
                <th class="text-nowrap">Họ và tên thí sinh</th>
                <th class="text-nowrap">Địa chỉ</th>
                <th class="img">Ảnh hồ sơ</th>
            </tr>
        </thead>
        <tbody>
            <!-- BEGIN: loop -->
            <tr class="text-center">
                <td class="text-nowrap">{DATA.fullname}</td>
                <td class="text-nowrap">{DATA.address}</td>
                <td class="img-responsive-wrap"><img class="img-inner" style="width: 100px; height: 100px" src="{DATA.image}"/></td>
            </tr>
            <!-- END: loop -->
        </tbody>
    </table>
</div>
<!-- END: main -->
