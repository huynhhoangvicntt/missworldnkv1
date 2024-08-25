document.addEventListener('DOMContentLoaded', function() {
  const voteButtons = document.querySelectorAll('.vote-button');

  voteButtons.forEach(button => {
      button.addEventListener('click', function() {
          const contestantCard = this.closest('.contestant-card');
          const contestantName = contestantCard.querySelector('h3').textContent;
          const contestantId = contestantCard.dataset.id;

          const fullname = prompt("Vui lòng nhập họ tên của bạn:");
          if (!fullname) return;

          const email = prompt("Vui lòng nhập địa chỉ email của bạn:");
          if (!email) return;

          const url = `${nv_base_siteurl}index.php?${nv_lang_variable}=${nv_lang_data}&${nv_name_variable}=${nv_module_name}&${nv_fc_variable}=main`;

          fetch(url, {
              method: 'POST',
              headers: {
                  'Content-Type': 'application/x-www-form-urlencoded',
              },
              body: `action=vote&contestant_id=${contestantId}&fullname=${encodeURIComponent(fullname)}&email=${encodeURIComponent(email)}`
          })
          .then(response => response.json())
          .then(data => {
              if (data.success) {
                  alert(`Cảm ơn bạn đã bình chọn cho ${contestantName}!`);
                  this.disabled = true;
                  this.textContent = 'ĐÃ BÌNH CHỌN';
                  if (data.newVoteCount !== undefined) {
                      const voteCountElement = contestantCard.querySelector('.vote-count');
                      if (voteCountElement) {
                          voteCountElement.textContent = data.newVoteCount;
                      }
                  }
              } else {
                  alert(data.message || 'Có lỗi xảy ra. Vui lòng thử lại sau.');
              }
          })
          .catch(error => {
              console.error('Error:', error);
              alert('Có lỗi xảy ra khi gửi yêu cầu. Vui lòng thử lại sau.');
          });
      });
  });
});