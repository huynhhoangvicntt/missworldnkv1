<!-- BEGIN: main -->
<!-- BEGIN: contestant_votes -->
<div class="well">
    <h2>{LANG.votes_for} {CONTESTANT.fullname}</h2>
</div>
<!-- END: contestant_votes -->

<!-- BEGIN: all_votes -->
<div class="well">
    <h2>{LANG.all_votes}</h2>
</div>
<!-- END: all_votes -->

<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th>{LANG.voter_name}</th>
                <th>{LANG.voter_email}</th>
                <th>{LANG.vote_time}</th>
                <th>{LANG.user_id}</th>
                <!-- BEGIN: contestant_column -->
                <th>{LANG.contestant}</th>
                <!-- END: contestant_column -->
            </tr>
        </thead>
        <tbody>
            <!-- BEGIN: loop -->
            <tr>
                <td>{ROW.fullname}</td>
                <td>{ROW.email}</td>
                <td>{ROW.vote_time}</td>
                <td>{ROW.userid}</td>
                <!-- BEGIN: contestant_column -->
                <td>{ROW.contestant_name}</td>
                <!-- END: contestant_column -->
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