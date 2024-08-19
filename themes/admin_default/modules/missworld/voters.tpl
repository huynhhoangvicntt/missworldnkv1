<!-- BEGIN: main -->
<div class="well">
    <!-- BEGIN: contestant_votes -->
    <h2>{LANG.votes_for} {CONTESTANT.fullname}</h2>
    <!-- END: contestant_votes -->
    <!-- BEGIN: all_votes -->
    <h2>{LANG.all_votes}</h2>
    <!-- END: all_votes -->
</div>

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
                <th>{LANG.function}</th>
            </tr>
        </thead>
        <tbody>
            <!-- BEGIN: loop -->
            <tr id="vote-{ROW.vote_id}">
                <td>{ROW.fullname}</td>
                <td>{ROW.email}</td>
                <td>{ROW.vote_time}</td>
                <td>{ROW.userid}</td>
                <!-- BEGIN: contestant_name -->
                <td>{ROW.contestant_name}</td>
                <!-- END: contestant_name -->
                <td>
                    <button class="btn btn-danger btn-xs delete-vote" data-vote-id="{ROW.vote_id}">
                        <i class="fa fa-trash"></i> {LANG.delete}
                    </button>
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

<script type="text/javascript">
$(document).ready(function() {
    $('.delete-vote').click(function() {
        var voteId = $(this).data('vote-id');
        if (confirm('{LANG.confirm_delete_vote}')) {
            $.ajax({
                url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=voters',
                method: 'POST',
                data: {
                    delete: 1,
                    vote_id: voteId
                },
                success: function(response) {
                    if (response.status === 'OK') {
                        $('#vote-' + voteId).remove();
                        alert(response.message);
                    } else {
                        alert(response.message);
                    }
                },
                error: function() {
                    alert('{LANG.error_deleting_vote}');
                }
            });
        }
    });
});
</script>
<!-- END: main -->