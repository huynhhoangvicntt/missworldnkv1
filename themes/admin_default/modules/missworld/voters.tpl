<!-- BEGIN: main -->
<div class="well">
    <!-- BEGIN: contestant_votes -->
    <h2 class="text-ellipsis">{LANG.votes_for} <span class="contestant-fullname">{CONTESTANT.fullname}</span></h2>
    <!-- END: contestant_votes -->
    <!-- BEGIN: all_votes -->
    <h2>{LANG.all_votes}</h2>
    <!-- END: all_votes -->
</div>
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
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
                <td class="voter-name"><div class="fullname-wrapper" title="{ROW.fullname}"><span class="fullname-text">{ROW.fullname}</span></div></td>
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
    </table>
</div>
<!-- BEGIN: generate_page -->
<div class="text-center">
    {GENERATE_PAGE}
</div>
<!-- END: generate_page -->
<!-- END: main -->