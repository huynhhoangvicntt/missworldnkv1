<!-- BEGIN: main -->
<div class="missworld-contestants">
    <h2>{LANG.contestant_list}</h2>
    
    <div class="contestant-grid">
        <!-- BEGIN: loop -->
        <div class="contestant-card" data-id="{DATA.id}">
            <img src="{DATA.image}" alt="{DATA.fullname}" class="contestant-image">
            <h3 class="contestant-name">{DATA.fullname}</h3>
            <p class="vote-count">{LANG.vote_count}: <span>{DATA.vote}</span></p>
            <button class="vote-button">{LANG.vote}</button>
        </div>
        <!-- END: loop -->
    </div>

    <!-- BEGIN: generate_page -->
    <div class="text-center">
        {GENERATE_PAGE}
    </div>
    <!-- END: generate_page -->
</div>
<!-- END: main -->