<!-- BEGIN: main -->
<div class="missworld-contestants">
    <div class="contestant-detail-container">
        <div class="contestant-detail-card">
            <div class="contestant-detail-image-container">
                <div class="contestant-detail-rank">
                    <span>{LANG.ranking}</span>
                    <span class="rank-number">{DATA.rank}</span>
                </div>
                <img src="{DATA.image}" alt="{DATA.fullname}" class="contestant-detail-image">
            </div>
            <div class="contestant-detail-info">
                <h1 class="contestant-detail-name">{DATA.fullname}</h1>
                <table class="contestant-detail-table">
                    <tr class="contestant-detail-in">
                        <td colspan="2">{LANG.info}</td>
                    </tr>
                    <tr>
                        <td class="info-label"><span class="label-text">{LANG.address}</span><span class="info-colon">:</span></td>
                        <td class="info-value">{DATA.address}</td>
                    </tr>
                    <tr>
                        <td class="info-label"><span class="label-text">{LANG.date_of_birth}</span><span class="info-colon">:</span></td>
                        <td class="info-value">{DATA.dob}</td>
                    </tr>
                    <tr>
                        <td class="info-label"><span class="label-text">{LANG.height}</span><span class="info-colon">:</span></td>
                        <td class="info-value">{DATA.height}&nbsp;{LANG.units}</td>
                    </tr>
                    <tr>
                        <td class="info-label"><span class="label-text">{LANG.measurements}</span><span class="info-colon">:</span></td>
                        <td class="info-value">{DATA.chest} {LANG.separator} {DATA.waist} {LANG.separator} {DATA.hips}</td>
                    </tr>
                </table>
                <button class="vote-button" data-contestant-id="{DATA.id}">{LANG.vote}</button>
            </div>
        </div>
        <div class="contestant-detail-voting-history">
            <h2>{LANG.voting_history}</h2>
            <div id="voting-history-container">
                <!-- BEGIN: voting_history -->
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="col-voter-email">{LANG.voter_email}</th>
                                <th class="col-vote-time">{LANG.vote_time}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- BEGIN: loop -->
                            <tr>
                                <td class="voter-email"><div class="email-wrapper" title="{VOTE.email}"><span class="email-text">{VOTE.email}</span></div></td>
                                <td class="vote-time">{VOTE.vote_time}</td>
                            </tr>
                            <!-- END: loop -->
                        </tbody>
                    </table>
                </div>
                <!-- END: voting_history -->
                <!-- BEGIN: no_votes -->
                <p class="no-votes-message">{LANG.no_votes_yet}</p>
                <!-- END: no_votes -->
            </div>
        </div>
    </div>
</div>
<div id="voting-modal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <div class="modal-grid">
            <div class="modal-image">
                <img id="modal-contestant-image" src="" alt="Contestant Image">
            </div>
            <div class="modal-form">
                <h2>{LANG.vote_for} <span id="contestant-name"></span></h2>
                <div class="progress-dots">
                    <div class="progress-step">
                        <div class="progress-dot active">{LANG.number_one}</div>
                        <span>{LANG.choose_contestant}</span>
                    </div>
                    <div class="progress-step">
                        <div class="progress-dot">{LANG.number_two}</div>
                        <span>{LANG.verify}</span>
                    </div>
                </div>
                <form id="voting-form">
                    <input type="hidden" id="contestant-id" name="contestant_id">
                    <div class="form-group">
                        <label for="voter-name">{LANG.voter_name}</label>
                        <input type="text" id="voter-name" name="voter_name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">{LANG.email}</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <button type="submit">{LANG.submit_vote}</button>
                </form>
            </div>
        </div>
    </div>
</div>
<div id="verification-modal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>{LANG.email_verification}</h2>
        <div class="progress-dots">
            <div class="progress-step">
                <div class="progress-dot">{LANG.number_one}</div>
                <span>{LANG.choose_contestant}</span>
            </div>
            <div class="progress-step">
                <div class="progress-dot active">{LANG.number_two}</div>
                <span>{LANG.verify}</span>
            </div>
        </div>
        <form id="verification-form">
            <input type="hidden" id="verification-contestant-id" name="contestant_id">
            <input type="hidden" id="verification-email" name="email">
            <div class="form-group">
                <label for="verification-code">{LANG.verification_code}:</label>
                <input type="text" id="verification-code" name="verification_code" required>
            </div>
            <button type="submit" class="verify-btn">{LANG.verify_vote}</button>
        </form>
        <button id="resend-code-btn" type="button" class="resend-btn">{LANG.resend_verification_code}</button>
    </div>
</div>
<div id="toast" class="toast"></div>
<div id="loading-overlay" class="loading-overlay">
    <div class="loader"></div>
</div>
<!-- END: main -->
