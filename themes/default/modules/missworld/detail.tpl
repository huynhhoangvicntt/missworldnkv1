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
                        <td class="info-value">{DATA.chest}{LANG.separator}{DATA.waist}{LANG.separator}{DATA.hips}</td>
                    </tr>
                </table>
                <button class="contestant-detail-vote-button" data-contestant-id="{DATA.id}">{LANG.vote}</button>
            </div>
        </div>
    </div>
</div>
<!-- END: main -->