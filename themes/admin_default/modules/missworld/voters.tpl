<!-- BEGIN: main -->
<div class="well">
    <!-- BEGIN: contestant_votes -->
    <h2 class="text-ellipsis">{LANG.votes_for} <span class="contestant-fullname">{CONTESTANT.fullname}</span></h2>
    <!-- END: contestant_votes -->
    <!-- BEGIN: all_votes -->
    <h2>{LANG.all_votes}</h2>
    <!-- END: all_votes -->
</div>
<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th class="w50 text-center">
                        <input name="check_all[]" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);">
                    </th>
                    <th class="col-voter-name">{LANG.voter_name}</th>
                    <th class="col-voter-email">{LANG.voter_email}</th>
                    <th class="col-vote-time">{LANG.vote_time}</th>
                    <th class="col-user-id">{LANG.user_id}</th>
                    <!-- BEGIN: contestant_column -->
                    <th class="col-contestant">{LANG.contestant}</th>
                    <!-- END: contestant_column -->
                    <th class="col-function">{LANG.function}</th>
                </tr>
            </thead>
            <tbody>
                <!-- BEGIN: loop -->
                <tr id="vote-{ROW.vote_id}">
                    <td class="text-center"><input type="checkbox" onclick="nv_UncheckAll(this.form, 'idcheck[]', 'check_all[]', this.checked);" value="{ROW.vote_id}" name="idcheck[]" /></td>
                    <td class="voter-name"><div class="fullname-wrapper" title="{ROW.fullname}"><span class="fullname-text">{ROW.voter_name}</span></div></td>
                    <td class="voter-email"><div class="email-wrapper" title="{ROW.email}"><span class="email-text">{ROW.email}</span></div></td>
                    <td class="vote-time">{ROW.vote_time}</td>
                    <td class="user-id">{ROW.userid}</td>
                    <!-- BEGIN: contestant_name -->
                    <td class="contestant-name"><div class="contestant-wrapper" title="{ROW.contestant_name}"><span class="contestant-text">{ROW.contestant_name}</span></div></td>
                    <!-- END: contestant_name -->
                    <td class="function">
                        <a href="javascript:void(0);" onclick="nv_delete_vote('{ROW.vote_id}', '{NV_CHECK_SESSION}');" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i> {GLANG.delete}</a>
                    </td>
                </tr>
                <!-- END: loop -->
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="7">
                        <select class="form-control w200 pull-left" id="action" name="action">
                            <option value="delete">{GLANG.delete}</option>
                        </select>
                        <input type="button" class="btn btn-primary" value="{GLANG.submit}" onclick="nv_voters_action(this.form, '{NV_CHECK_SESSION}', '{LANG.msgnocheck}')" />
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</form>
<!-- BEGIN: generate_page -->
<div class="text-center">
    {GENERATE_PAGE}
</div>
<!-- END: generate_page -->
<!-- END: main -->
 