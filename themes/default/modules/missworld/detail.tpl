<!-- BEGIN: main -->
<div class="missworld-contestants">
    <div class="contestant-detail-container">
        <div class="contestant-detail-card">
            <div class="contestant-detail-image-container">
                <div class="contestant-detail-rank">10</div>
                <img src="{DATA.image}" alt="{DATA.fullname}" class="contestant-detail-image">
            </div>
            <div class="contestant-detail-info">
                <h1 class="contestant-detail-name">{DATA.fullname}</h1>
                <h2 class="contestant-detail-in">Thông tin</h2>
                <table class="contestant-detail-table">
                    <tr>
                        <td class="info-label"><span class="label-text">{LANG.address}</span><span class="info-colon">:</span></td>
                        <td class="info-value">{DATA.address}</td>
                    </tr>
                    <tr>
                        <td class="info-label"><span class="label-text">{LANG.height}</span><span class="info-colon">:</span></td>
                        <td class="info-value">{DATA.height}</td>
                    </tr>
                    <tr>
                        <td class="info-label"><span class="label-text">{LANG.chest}</span><span class="info-colon">:</span></td>
                        <td class="info-value">{DATA.chest}</td>
                    </tr>
                    <tr>
                        <td class="info-label"><span class="label-text">{LANG.keywords}</span><span class="info-colon">:</span></td>
                        <td class="info-value">{DATA.keywords}</td>
                    </tr>
                </table>
                <button class="contestant-detail-vote-button" data-contestant-id="{DATA.id}">{LANG.vote}</button>
            </div>
        </div>
    </div>
</div>
<!-- END: main -->