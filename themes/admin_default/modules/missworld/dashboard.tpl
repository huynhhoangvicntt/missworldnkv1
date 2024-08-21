<!-- BEGIN: main -->
<div class="well dashboard">
    <h2>{LANG.dashboard}</h2>
    <div class="row">
        <div class="col-xs-24 col-sm-12 col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">{LANG.general_statistics}</div>
                <div class="panel-body">
                    <table class="table table-striped table-bordered">
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
        </div>
        <div class="col-xs-24 col-sm-12 col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">{LANG.average_measurements}</div>
                <div class="panel-body">
                    <table class="table table-striped table-bordered">
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
    </div>
    <div class="row">
        <div class="col-xs-24 col-sm-24 col-md-24">
            <div class="panel panel-default">
                <div class="panel-heading">{LANG.top_contestants}</div>
                <div class="panel-body">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th class="col-xs-2">{LANG.rank}</th>
                                <th class="col-fullname">{LANG.fullname}</th>
                                <th class="col-xs-6 text-right">{LANG.vote}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- BEGIN: top_contestant -->
                            <tr>
                                <td>{TOP_CONTESTANT.rank}</td>
                                <td class="fullname">{TOP_CONTESTANT.fullname}</td>
                                <td class="text-right">{TOP_CONTESTANT.vote}</td>
                            </tr>
                            <!-- END: top_contestant -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END: main -->