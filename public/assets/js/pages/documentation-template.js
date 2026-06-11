document.addEventListener('DOMContentLoaded', function () {
    enableLoadMoreReviewsButton();
});


function enableLoadMoreReviewsButton() {
    const btn = document.querySelector('#loadMoreReviewButton');
    if (!btn) return;

    const reviewContainer = document.querySelector('.review-container');


    btn.addEventListener('click', function () {
        btn.disabled = true;
        const prevTxt = btn.innerHTML;

        const uuid = btn.dataset.templateUuid;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');


        btn.innerHTML = '<div class"px-3">Loading...</div>';

        fetch(route('ajax.doc-template.load-more-reviews', { template: uuid }), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'x-csrf-token': csrfToken
            },
            body: JSON.stringify({
                offset: btn.dataset.offset,
            })
        })
            .then(response => response.json())
            .then(data => {
                btn.innerHTML = prevTxt;
                if (data.success) {
                    reviewContainer.insertAdjacentHTML('beforeend', data.data);
                }

                if (!data.has_more) {
                    btn.remove();
                }

                btn.setAttribute('data-offset', data.new_offset)
                btn.disabled = false;

            })
            .catch(error => {
                btn.innerHTML = prevTxt;
                console.error('Error:', error);
                btn.disabled = false;
            });
    });
}
