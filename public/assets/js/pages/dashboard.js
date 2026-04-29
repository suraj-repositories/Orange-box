document.addEventListener('DOMContentLoaded', function () {
    initTagScroll();
    initUserScroll();

    initUserBlurBackground();
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
    const container = document.getElementById('userScrollContainer');
    if (!container) return;

    const wrapper = container.closest('.user-scroll-wrapper');
    const leftBtn = wrapper?.querySelector('.scroll-btn.left');
    const rightBtn = wrapper?.querySelector('.scroll-btn.right');

    if (!leftBtn || !rightBtn) return;

    function scrollUsers(direction) {
        const amount = container.clientWidth * 0.8;
        container.scrollBy({
            left: direction * amount,
            behavior: 'smooth'
        });
    }

    window.scrollUsers = scrollUsers;

    function updateUI() {
        const maxScroll = container.scrollWidth - container.clientWidth;

        const atStart = container.scrollLeft <= 0;
        const atEnd = container.scrollLeft >= maxScroll - 1;

        leftBtn.style.display = atStart ? 'none' : 'flex';
        rightBtn.style.display = atEnd ? 'none' : 'flex';
    }

    container.addEventListener('scroll', updateUI);
    window.addEventListener('resize', updateUI);

    updateUI();
}
