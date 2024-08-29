$(document).ready(function() {
    const voteButtons = $('.vote-button');
    const votingModal = $('#voting-modal');
    const verificationModal = $('#verification-modal');
    const votingForm = $('#voting-form');
    const verificationForm = $('#verification-form');
    const resendCodeBtn = $('#resend-code-btn');
    const toast = $('.toast');
    const loadingOverlay = $('.loading-overlay');

    voteButtons.on('click', function() {
        const contestantId = $(this).data('contestant-id');
        const contestantName = $(this).closest('.contestant-card').find('.contestant-name').text();
        const contestantImage = $(this).closest('.contestant-card').find('img').attr('src');
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
                    if (response.isLoggedIn) {
                        $('#contestant-id').val(contestantId);
                        $('#contestant-name').text(contestantName);
                        $('#modal-contestant-image').attr('src', contestantImage);
                        $('#voter-name').val(response.fullname).prop('disabled', true);
                        $('#email').val(response.email).prop('disabled', true);
                    } else {
                        $('#contestant-id').val(contestantId);
                        $('#contestant-name').text(contestantName);
                        $('#modal-contestant-image').attr('src', contestantImage);
                        $('#voter-name, #email').val('').prop('disabled', false);
                    }
                    showModal(votingModal);
                    updateProgressBar(1);
                } else {
                    showToast('Có lỗi xảy ra. Vui lòng thử lại sau.');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                hideLoading();
                console.error('AJAX error:', textStatus, errorThrown);
                showToast('Có lỗi xảy ra khi kiểm tra trạng thái đăng nhập. Vui lòng thử lại sau.');
            }
        });
    }

    votingForm.on('submit', function(e) {
        e.preventDefault();
        const contestantId = $('#contestant-id').val();
        const voterName = $('#voter-name').val();
        const email = $('#email').val();

        submitVote(contestantId, voterName, email);
    });

    verificationForm.on('submit', function(e) {
        e.preventDefault();
        const verificationCode = $('#verification-code').val();
        const contestantId = $('#verification-contestant-id').val();
        const email = $('#verification-email').val();

        verifyVote(contestantId, email, verificationCode);
    });

    resendCodeBtn.on('click', function() {
        const contestantId = $('#verification-contestant-id').val();
        const email = $('#verification-email').val();
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
                console.log('Server response:', response);
                if (response.success) {
                    if (response.requiresVerification) {
                        showVerificationModal(contestantId, email);
                    } else {
                        handleSuccessfulVote(contestantId, response.newVoteCount);
                        hideModal(votingModal);
                    }
                } else {
                    showToast(response.message || 'Có lỗi xảy ra. Vui lòng thử lại sau.');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                hideLoading();
                console.error('AJAX error:', textStatus, errorThrown);
                showToast('Có lỗi xảy ra khi gửi yêu cầu. Vui lòng thử lại sau.');
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
                if (response.success) {
                    showToast('Mã xác minh mới đã được gửi đến email của bạn.');
                } else {
                    showToast(response.message || 'Có lỗi xảy ra khi gửi lại mã xác minh. Vui lòng thử lại sau.');
                }
            },
            error: function() {
                hideLoading();
                showToast('Có lỗi xảy ra khi gửi yêu cầu. Vui lòng thử lại sau.');
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
                } else {
                    showToast(response.message || 'Mã xác minh không hợp lệ. Vui lòng thử lại.');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                hideLoading();
                console.error('AJAX error:', textStatus, errorThrown);
                showToast('Có lỗi xảy ra khi xác minh. Vui lòng thử lại sau.');
            }
        });
    }

    function handleSuccessfulVote(contestantId, newVoteCount) {
        const contestantCard = $('.contestant-card[data-id="' + contestantId + '"]');
        const voteButton = contestantCard.find('.vote-button');
        const voteCountElement = contestantCard.find('.vote-count span');

        voteButton.prop('disabled', true);
        voteButton.text('ĐÃ BÌNH CHỌN');
        if (newVoteCount !== undefined) {
            voteCountElement.text(newVoteCount);
        }

        showToast('Cảm ơn bạn đã bình chọn!');
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

    function showToast(message) {
        toast.text(message);
        toast.addClass('show');
        setTimeout(function() {
            toast.removeClass('show');
        }, 5000);
    }

    function showLoading() {
        loadingOverlay.show();
    }

    function hideLoading() {
        loadingOverlay.hide();
    }

    $('.close').on('click', function() {
        hideModal($(this).closest('.modal'));
    });

    $(window).on('click', function(event) {
        if ($(event.target).hasClass('modal')) {
            hideModal($(event.target));
        }
    });
});