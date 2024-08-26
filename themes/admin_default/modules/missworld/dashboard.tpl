<!-- BEGIN: main -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">{LANG.general_statistics}</div>
            <table class="table table-striped table-bordered table-hover">
                <tr>
                    <td class="col-md-18">{LANG.total_contestants}</td>
                    <td class="col-md-6 text-right">{TOTAL_CONTESTANTS}</td>
                </tr>
                <tr>
                    <td class="col-md-18">{LANG.total_votes}</td>
                    <td class="col-md-6 text-right">{TOTAL_VOTES}</td>
                </tr>
            </table>
        </div>
    </div>
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">{LANG.average_measurements}</div>
            <table class="table table-striped table-bordered table-hover">
                <tr>
                    <td class="col-md-18">{LANG.avg_height}</td>
                    <td class="col-md-6 text-right">{AVG_HEIGHT} cm</td>
                </tr>
                <tr>
                    <td class="col-md-18">{LANG.avg_chest}</td>
                    <td class="col-md-6 text-right">{AVG_CHEST} cm</td>
                </tr>
                <tr>
                    <td class="col-md-18">{LANG.avg_waist}</td>
                    <td class="col-md-6 text-right">{AVG_WAIST} cm</td>
                </tr>
                <tr>
                    <td class="col-md-18">{LANG.avg_hips}</td>
                    <td class="col-md-6 text-right">{AVG_HIPS} cm</td>
                </tr>
            </table>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-24">
        <div class="panel panel-default">
            <div class="panel-heading">{LANG.top_contestants}</div>
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="col-md-2">{LANG.rank}</th>
                        <th class="col-md-16">{LANG.fullname}</th>
                        <th class="col-md-6 text-right">{LANG.vote}</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- BEGIN: top_contestant -->
                    <tr>
                        <td class="col-md-2">{TOP_CONTESTANT.rank}</td>
                        <td class="col-md-16 fullname"><span class="text-ellipsis">{TOP_CONTESTANT.fullname}</span></td>
                        <td class="col-md-6 text-right">{TOP_CONTESTANT.vote}</td>
                    </tr>
                    <!-- END: top_contestant -->
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- END: main -->