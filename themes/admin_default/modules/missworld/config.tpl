<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->
<form action="{FORM_ACTION}" method="post">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <caption><em class="fa fa-file-text-o">&nbsp;</em>{LANG.config}</caption>
            <colgroup>
                <col style="width: 300px" />
                <col />
            </colgroup>
            <tbody>
                <tr>
                    <th>{LANG.setting_per_page}</th>
                    <td>
                        <select class="form-control w200" name="per_page">
                            <!-- BEGIN: per_page -->
                            <option value="{PER_PAGE.key}"{PER_PAGE.selected}>{PER_PAGE.title}</option>
                            <!-- END: per_page -->
                        </select>
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2">
                        <input type="submit" name="submit" value="{LANG.save}" class="btn btn-primary" />
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</form>
<!-- END: main -->