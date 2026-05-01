document.addEventListener('DOMContentLoaded', function () {
    initTagScroll();
    initUserScroll();

    initUserBlurBackground();
    enableFollowControl();
});

function initTagScroll() {
    const container = document.getElementById('tagScrollContainer');
    if (!container) return;

    const wrapper = container.closest('.tag-scroll-wrapper');
    const leftBtn = wrapper?.querySelector('.scroll-btn.left');
    const rightBtn = wrapper?.querySelector('.scroll-btn.right');

    if (!leftBtn || !rightBtn) return;

    const FADE_SIZE = 96;

    function scrollTags(direction) {
        const amount = container.clientWidth * 0.8;
        container.scrollBy({
            left: direction * amount,
            behavior: 'smooth'
        });
    }

    window.scrollTags = scrollTags;

    function updateUI() {
        const maxScroll = container.scrollWidth - container.clientWidth;
        const hasOverflow = maxScroll > 1;

        if (!hasOverflow) {
            leftBtn.style.display = 'none';
            rightBtn.style.display = 'none';
            container.style.setProperty('--fade-left', `0px`);
            container.style.setProperty('--fade-right', `0px`);
            return;
        }

        const scrollLeft = container.scrollLeft;
        const atStart = scrollLeft <= 0;
        const atEnd = scrollLeft >= maxScroll - 1;

        leftBtn.style.display = atStart ? 'none' : 'flex';
        rightBtn.style.display = atEnd ? 'none' : 'flex';

        leftBtn.style.opacity = atStart ? '0.3' : '1';
        rightBtn.style.opacity = atEnd ? '0.3' : '1';

        leftBtn.style.pointerEvents = atStart ? 'none' : 'auto';
        rightBtn.style.pointerEvents = atEnd ? 'none' : 'auto';

        container.style.setProperty('--fade-left', atStart ? '0px' : `${FADE_SIZE}px`);
        container.style.setProperty('--fade-right', atEnd ? '0px' : `${FADE_SIZE}px`);
    }

    container.addEventListener('scroll', updateUI);
    window.addEventListener('resize', updateUI);

    updateUI();
}

function initUserBlurBackground() {
    document.querySelectorAll('.blur-bg').forEach(el => {
        const img = el.getAttribute('data-image');

        console.log('img', img);
        if (img) {
            el.style.setProperty('--bg-image', `url(${img})`);
            console.log('done');
        }
    });
}

function initUserScroll() {
    const containers = document.querySelectorAll('.user-scroll-container');

    if (!containers.length) return;

    containers.forEach((container) => {
        const wrapper = container.closest('.user-scroll-wrapper');
        if (!wrapper) return;

        const leftBtn = wrapper.querySelector('.scroll-btn.left');
        const rightBtn = wrapper.querySelector('.scroll-btn.right');

        if (!leftBtn || !rightBtn) return;

        function scrollUsers(direction) {
            const amount = container.clientWidth * 0.8;
            container.scrollBy({
                left: direction * amount,
                behavior: 'smooth'
            });
        }

        function updateUI() {
            const maxScroll = container.scrollWidth - container.clientWidth;

            const atStart = container.scrollLeft <= 0;
            const atEnd = container.scrollLeft >= maxScroll - 1;

            leftBtn.style.display = atStart ? 'none' : 'flex';
            rightBtn.style.display = atEnd ? 'none' : 'flex';
        }

        // Attach button events per instance
        leftBtn.addEventListener('click', () => scrollUsers(-1));
        rightBtn.addEventListener('click', () => scrollUsers(1));

        container.addEventListener('scroll', updateUI);
        window.addEventListener('resize', updateUI);

        updateUI();
    });
}

function enableFollowControl() {
    const buttons = document.querySelectorAll('.follow-btn');

    buttons.forEach(button => {
        button.addEventListener('click', () => handleFollowToggle(button));
    });

    async function handleFollowToggle(button) {
        const isFollowing = button.dataset.following === "true";
        const url = isFollowing
            ? button.dataset.unfollowUrl
            : button.dataset.followUrl;

        try {

            button.disabled = true;
            button.textContent = 'Loading...';
            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': getCsrfToken(),
                },
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        button.dataset.following = (!isFollowing).toString();
                    } else {
                        Toastify.error(data.message);
                    }
                    button.textContent = isFollowing ? 'Follow' : 'Unfollow';
                    button.disabled = false;
                })
                .catch(error => {
                    console.error('Error:', error);
                    Toastify.error('Something went wrong!');

                });

        } catch (error) {
            console.error('Error:', error);
        }
    }

    function getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    }
}
