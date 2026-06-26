document.addEventListener('DOMContentLoaded', function () {
    enableLoadMoreReviewsButton();

    initImageScroll();
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

function initImageScroll() {
    const containers = document.querySelectorAll('.multi-image-container-scrollable');

    if (!containers.length) return;

    containers.forEach((container) => {
        const wrapper = container.closest('.image-scroll-wrapper');
        if (!wrapper) return;

        const leftBtn = wrapper.querySelector('.image-scroll-btn.left');
        const rightBtn = wrapper.querySelector('.image-scroll-btn.right');

        if (!leftBtn || !rightBtn) return;

        function scrollImages(direction) {
            const firstItem = container.querySelector('.image-box');

            const amount = firstItem
                ? firstItem.offsetWidth + 16
                : container.clientWidth * 0.8;

            container.scrollBy({
                left: direction * amount * 2,
                behavior: 'smooth'
            });
        }

        function updateButtons() {
            const maxScroll = container.scrollWidth - container.clientWidth;

            leftBtn.style.display =
                container.scrollLeft <= 0 ? 'none' : 'flex';

            rightBtn.style.display =
                container.scrollLeft >= maxScroll - 2 ? 'none' : 'flex';
        }

        leftBtn.addEventListener('click', () => scrollImages(-1));
        rightBtn.addEventListener('click', () => scrollImages(1));

        container.addEventListener('scroll', updateButtons);
        window.addEventListener('resize', updateButtons);

        updateButtons();
    });
}
