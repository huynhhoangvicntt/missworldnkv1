<!-- BEGIN: main -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">{LANG.general_statistics}</div>
            <table class="table table-striped">
                <tr>
                    <td>{LANG.total_contestants}</td>
                    <td class="text-right">{TOTAL_CONTESTANTS}</td>
                </tr>
                <tr>
                    <td>{LANG.total_votes}</td>
                    <td class="text-right">{TOTAL_VOTES}</td>
                </tr>
            </table>
        </div>
    </div>
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">{LANG.average_measurements}</div>
            <table class="table table-striped">
                <tr>
                    <td>{LANG.avg_height}</td>
                    <td class="text-right">{AVG_HEIGHT} cm</td>
                </tr>
                <tr>
                    <td>{LANG.avg_chest}</td>
                    <td class="text-right">{AVG_CHEST} cm</td>
                </tr>
                <tr>
                    <td>{LANG.avg_waist}</td>
                    <td class="text-right">{AVG_WAIST} cm</td>
                </tr>
                <tr>
                    <td>{LANG.avg_hips}</td>
                    <td class="text-right">{AVG_HIPS} cm</td>
                </tr>
            </table>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-24">
        <div class="panel panel-default">
            <div class="panel-heading">{LANG.top_contestants}</div>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>{LANG.rank}</th>
                        <th>{LANG.fullname}</th>
                        <th class="text-right">{LANG.vote}</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- BEGIN: top_contestant -->
                    <tr>
                        <td>{TOP_CONTESTANT.rank}</td>
                        <td class="fullname"><span class="text-ellipsis">{TOP_CONTESTANT.fullname}</span></td>
                        <td class="text-right">{TOP_CONTESTANT.vote}</td>
                    </tr>
                    <!-- END: top_contestant -->
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- END: main -->