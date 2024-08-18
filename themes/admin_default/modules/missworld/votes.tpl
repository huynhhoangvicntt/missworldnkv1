<!-- BEGIN: main -->
<div class="well">
  <h2>{LANG.vote_list_for} {CONTESTANT.fullname}</h2>
</div>

<div class="table-responsive">
  <table class="table table-striped table-bordered table-hover">
      <thead>
          <tr>
              <th>{LANG.voter_name}</th>
              <th>{LANG.voter_email}</th>
              <th>{LANG.vote_time}</th>
              <th>{LANG.user_id}</th>
          </tr>
      </thead>
      <tbody>
          <!-- BEGIN: loop -->
          <tr>
              <td>{ROW.fullname}</td>
              <td>{ROW.email}</td>
              <td>{ROW.vote_time}</td>
              <td>{ROW.userid}</td>
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
