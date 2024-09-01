<!-- BEGIN: main -->
<div class="missworld-contestants">
    <h2>{LANG.contestant_list}</h2>
    <div class="contestant-grid">
        <!-- BEGIN: loop -->
        <div class="contestant-card" data-id="{DATA.id}">
            <a href="{DATA.url_view}">
                <img src="{DATA.image}" alt="{DATA.fullname}" class="contestant-image">
              </a>
            <h3 class="contestant-name">{DATA.fullname}</h3>
            <p class="vote-count">{LANG.vote_count}: <span>{DATA.vote}</span></p>
            <button class="vote-button" data-contestant-id="{DATA.id}">{LANG.vote}</button>
        </div>
        <!-- END: loop -->
    </div>
    <!-- BEGIN: generate_page -->
    <div class="text-center">
        {GENERATE_PAGE}
    </div>
    <!-- END: generate_page -->
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