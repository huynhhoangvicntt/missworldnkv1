$(document).ready(function() {
    var voteButtons = $('.vote-button');
    var votingModal = $('#voting-modal');
    var verificationModal = $('#verification-modal');
    var votingForm = $('#voting-form');
    var verificationForm = $('#verification-form');
    var resendCodeBtn = $('#resend-code-btn');
    var toast = $('.toast');
    var loadingOverlay = $('.loading-overlay');

    voteButtons.on('click', function() {
        var contestantId = $(this).data('contestant-id');
        var contestantName = $(this).closest('.contestant-card').find('.contestant-name').text();
        var contestantImage = $(this).closest('.contestant-card').find('img').attr('src');
        checkUserLoginStatus(contestantId, contestantName, contestantImage);
    });

    function checkUserLoginStatus(contestantId, contestantName, contestantImage) {
        showLoading();
        $.ajax({
            type: 'POST',
            url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=main',
            data: {
                action: 'check_user'
            },
            dataType: 'json',
            success: function(response) {
                hideLoading();
                if (response.success) {
                    $('#contestant-id').val(contestantId);
                    $('#contestant-name').text(contestantName);
                    $('#modal-contestant-image').attr('src', contestantImage);
                    if (response.isLoggedIn) {
                        $('#voter-name').val(response.fullname).prop('disabled', true);
                        $('#email').val(response.email).prop('disabled', true);
                    } else {
                        $('#voter-name, #email').val('').prop('disabled', false);
                    }
                    showModal(votingModal);
                    updateProgressBar(1);
                }
                if (response.message) {
                    showToast(response.message);
                }
            },
            error: function(xhr) {
                hideLoading();
                handleAjaxError(xhr);
            }
        });
    }

    votingForm.on('submit', function(e) {
        e.preventDefault();
        var contestantId = $('#contestant-id').val();
        var voterName = $('#voter-name').val();
        var email = $('#email').val();

        submitVote(contestantId, voterName, email);
    });

    verificationForm.on('submit', function(e) {
        e.preventDefault();
        var verificationCode = $('#verification-code').val();
        var contestantId = $('#verification-contestant-id').val();
        var email = $('#verification-email').val();

        verifyVote(contestantId, email, verificationCode);
    });

    resendCodeBtn.on('click', function() {
        var contestantId = $('#verification-contestant-id').val();
        var email = $('#verification-email').val();
        resendVerificationCode(contestantId, email);
    });

    function submitVote(contestantId, voterName, email) {
        showLoading();
        $.ajax({
            type: 'POST',
            url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=main',
            data: {
                action: 'vote',
                contestant_id: contestantId,
                voter_name: voterName,
                email: email
            },
            dataType: 'json',
            success: function(response) {
                hideLoading();
                if (response.success) {
                    if (response.requiresVerification) {
                        showVerificationModal(contestantId, email);
                    } else {
                        handleSuccessfulVote(contestantId, response.newVoteCount);
                        hideModal(votingModal);
                    }
                } else {
                    hideModal(votingModal);
                }
                showToast(response.message);
            },
            error: function(xhr) {
                hideLoading();
                handleAjaxError(xhr);
            }
        });
    }

    function showVerificationModal(contestantId, email) {
        $('#verification-contestant-id').val(contestantId);
        $('#verification-email').val(email);
        hideModal(votingModal);
        showModal(verificationModal);
        updateProgressBar(2);
    }

    function resendVerificationCode(contestantId, email) {
        showLoading();
        $.ajax({
            type: 'POST',
            url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=main',
            data: {
                action: 'resend_verification',
                contestant_id: contestantId,
                email: email
            },
            dataType: 'json',
            success: function(response) {
                hideLoading();
                showToast(response.message);
            },
            error: function(xhr) {
                hideLoading();
                handleAjaxError(xhr);
            }
        });
    }

    function verifyVote(contestantId, email, verificationCode) {
        showLoading();
        $.ajax({
            type: 'POST',
            url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=main',
            data: {
                action: 'verify',
                contestant_id: contestantId,
                email: email,
                verification_code: verificationCode
            },
            dataType: 'json',
            success: function(response) {
                hideLoading();
                if (response.success) {
                    handleSuccessfulVote(contestantId, response.newVoteCount);
                    hideModal(verificationModal);
                }
                showToast(response.message);
            },
            error: function(xhr) {
                hideLoading();
                handleAjaxError(xhr);
            }
        });
    }

    function handleSuccessfulVote(contestantId, newVoteCount) {
        var contestantCard = $('.contestant-card[data-id="' + contestantId + '"]');
        var voteCountElement = contestantCard.find('.vote-count span');
        if (typeof newVoteCount !== 'undefined') {
            voteCountElement.text(newVoteCount);
        }
    }

    function deleteVerificationCode() {
        var contestantId = $('#verification-contestant-id').val();
        var email = $('#verification-email').val();
        
        $.ajax({
            type: 'POST',
            url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=main',
            data: {
                action: 'delete_verification',
                contestant_id: contestantId,
                email: email
            },
            dataType: 'json',
            success: function(response) {
                if (!response.success) {
                    showToast(response.message);
                }
            },
            error: function(xhr) {
                handleAjaxError(xhr);
            }
        });
    }

    function showModal(modal) {
        modal.show();
    }

    function hideModal(modal) {
        modal.hide();
    }

    function updateProgressBar(step) {
        $('.progress-step').removeClass('active');
        $('.progress-step:nth-child(' + step + ')').addClass('active');
    }

    function showLoading() {
        loadingOverlay.show();
    }

    function hideLoading() {
        loadingOverlay.hide();
    }

    function handleAjaxError(xhr) {
        if (xhr.responseJSON && xhr.responseJSON.message) {
            showToast(xhr.responseJSON.message);
        }
    }
    
    function showToast(message) {
        if (message) {
            toast.text(message);
            toast.addClass('show');
            setTimeout(function() {
                toast.removeClass('show');
            }, 3000);
        }
    }

    $('.close').on('click', function() {
        var modal = $(this).closest('.modal');
        if (modal.attr('id') === 'verification-modal') {
            deleteVerificationCode();
        }
        hideModal(modal);
    });

    $(window).on('click', function(event) {
        if ($(event.target).hasClass('modal')) {
            var modal = $(event.target);
            if (modal.attr('id') === 'verification-modal') {
                deleteVerificationCode();
            }
            hideModal(modal);
        }
    });

// Cache để lưu trữ kết quả đã tải
var pageCache = {};

function normalizeUrl(url) {
    var urlObj = new URL(url, window.location.origin);
    var id = urlObj.searchParams.get('id');
    var page = urlObj.searchParams.get('page') || '1';
    
    // Tạo URL phù hợp với cấu trúc NukeViet
    return window.location.origin + '/index.php?language=vi&nv=missworld&op=detail&id=' + id + '&page=' + page;
}

function updateBrowserUrl(url) {
    var urlObj = new URL(url, window.location.origin);
    var id = urlObj.searchParams.get('id');
    var page = urlObj.searchParams.get('page');
    var shortUrl = '/missworld/detail/' + id;
    if (page && page !== '1') {
        shortUrl += '&page=' + page;
    }
    history.pushState(null, '', shortUrl);
}

function loadPage(url) {
    var normalizedUrl = normalizeUrl(url);
    
    // Kiểm tra cache
    if (pageCache[normalizedUrl]) {
        console.log('Loading from cache:', normalizedUrl);
        updateContent(pageCache[normalizedUrl]);
        updateBrowserUrl(normalizedUrl);
        return;
    }

    // Hiển thị loading indicator
    showLoading();

    $.ajax({
        url: normalizedUrl + '&ajax=1',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            // Lưu vào cache
            pageCache[normalizedUrl] = data;
            updateContent(data);
            updateBrowserUrl(normalizedUrl);
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', status, error);
            $('#voting-history-container').html('<p>Đã xảy ra lỗi khi tải dữ liệu. Vui lòng thử lại sau.</p>');
            $('#pagination-container').empty();
        },
        complete: function() {
            hideLoading();
        }
    });
}

function updateContent(data) {
    if (data && data.content) {
        $('#voting-history-container').html(data.content);
        if (data.pagination) {
            $('#pagination-container').html(data.pagination);
        } else {
            $('#pagination-container').empty();
        }
        attachPaginationListeners();
    } else {
        $('#voting-history-container').html('<p>Không có dữ liệu để hiển thị.</p>');
        $('#pagination-container').empty();
    }
}

function showLoading() {
    $('#loading-indicator').show();
}

function hideLoading() {
    $('#loading-indicator').hide();
}

function attachPaginationListeners() {
    $('#pagination-container').off('click', 'a').on('click', 'a', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        loadPage(url);
    });
}

$(document).ready(function() {
    attachPaginationListeners();

    // Xử lý nút back/forward của trình duyệt
    $(window).on('popstate', function() {
        loadPage(window.location.href);
    });
});
});

