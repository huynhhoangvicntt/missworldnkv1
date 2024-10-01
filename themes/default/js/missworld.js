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
        var contestantName, contestantImage;
        
        if ($(this).closest('.contestant-detail-container').length) {
            contestantName = $('.contestant-detail-name').text();
            contestantImage = $('.contestant-detail-image').attr('src');
        } else {
            contestantName = $(this).closest('.contestant-card').find('.contestant-name').text();
            contestantImage = $(this).closest('.contestant-card').find('img').attr('src');
        }
        
        checkUserLoginStatus(contestantId, contestantName, contestantImage);
    });

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

    function submitVote(contestantId, voterName, email) {
        showLoading();
        $('.alert.alert-danger').remove();
        
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
                        updateVotingHistory(contestantId);
                        hideModal(votingModal);
                    }
                } else {
                    if (response.error_content) {
                        $('#voting-form').prepend(response.error_content);
                    } else {
                        hideModal(votingModal);
                    }
                }
    
                if (response.isToast && response.message) {
                    showToast(response.message);
                }
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
                    updateVotingHistory(contestantId);
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

    function showVerificationModal(contestantId, email) {
        $('#verification-contestant-id').val(contestantId);
        $('#verification-email').val(email);
        $('#verification-email-display').text(email);
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

    function handleSuccessfulVote(contestantId, newVoteCount) {
        var voteButton = $('.vote-button[data-contestant-id="' + contestantId + '"]');
        var voteCountElement = voteButton.siblings('.vote-count').find('span');
        
        if (typeof newVoteCount !== 'undefined') {
            voteCountElement.text(newVoteCount);
        }

        if (voteButton.closest('.contestant-detail-container').length) {
            setTimeout(function() {
                location.reload();
            }, 3000);
        }
    }

    function updateVotingHistory(contestantId) {
        $.ajax({
            type: 'POST',
            url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=detail',
            data: {
                action: 'get_voting_history',
                id: contestantId
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#voting-history-container').html(response.html);
                } else {
                    console.error("Failed to update voting history");
                }
            },
            error: function(xhr, status, error) {
                console.error("Error updating voting history:", status, error);
            }
        });
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
});
