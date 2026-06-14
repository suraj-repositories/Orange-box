class ScrollSpyControl {

    constructor() {
        this._navbarHeight = 70;
        this._isProgrammaticScroll = false;
        this._scrollTimeout = null;
    }

    generateScrollSpy(contentSelector) {
        const content = document.querySelector(contentSelector);
        const navContainer = document.querySelector('#scrollpsy-nav .nav');
        const dropdownMenu = document.getElementById('onThisPageMenu');

        if (!content || !navContainer) return;

        navContainer.innerHTML = '<div class="active-indicator"></div>';

        if (dropdownMenu) {
            dropdownMenu.innerHTML = '';
        }

        const allHeadings = content.querySelectorAll('h1, h2, h3, h4, h5, h6');
        const spyHeadings = content.querySelectorAll('h2, h3');

        let currentParentNav = null;

        allHeadings.forEach((heading) => {
            if (!heading.id) {
                heading.id = this.generateHeadingId(heading.textContent);
            }

            heading.style.cursor = 'pointer';

            heading.addEventListener('click', () => {
                this._scrollToId(heading.id);
                console.log('action/');

                history.replaceState(null, null, '#' + heading.id);
            });
        });

        spyHeadings.forEach((heading) => {
            const headingId = heading.id;
            const headingHref = '#' + headingId;
            const headingText = heading.textContent;

            if (heading.tagName === 'H2') {
                const parentLink = document.createElement('a');
                parentLink.className = 'nav-link';
                parentLink.href = headingHref;
                parentLink.dataset.scrollTarget = headingId;
                parentLink.textContent = headingText;

                navContainer.appendChild(parentLink);
                currentParentNav = null;
            }

            if (heading.tagName === 'H3') {
                if (!currentParentNav) {
                    currentParentNav = document.createElement('nav');
                    currentParentNav.className = 'nav nav-pills flex-column';

                    const lastLink = navContainer.lastElementChild;

                    if (lastLink && lastLink.classList.contains('nav-link')) {
                        lastLink.insertAdjacentElement('afterend', currentParentNav);
                    } else {
                        navContainer.appendChild(currentParentNav);
                    }
                }

                const childLink = document.createElement('a');
                childLink.href = headingHref;
                childLink.dataset.scrollTarget = headingId;
                childLink.textContent = headingText;
                childLink.className = 'nav-link ms-2';

                currentParentNav.appendChild(childLink);
            }

            if (dropdownMenu) {
                const li = document.createElement('li');

                const dropdownLink = document.createElement('a');
                dropdownLink.className = 'dropdown-item';
                dropdownLink.href = headingHref;
                dropdownLink.dataset.scrollTarget = headingId;
                dropdownLink.textContent = headingText;

                if (heading.tagName === 'H3') {
                    dropdownLink.classList.add('ps-4');
                }

                li.appendChild(dropdownLink);
                dropdownMenu.appendChild(li);
            }
        });

        this._bindNavClicks();
        this._initScrollSpy();
        this.enableScrollpsyIndicator();

        if (window.location.hash) {
            const hash = window.location.hash;
            setTimeout(() => {
                this._scrollToId(hash.substring(1));
                this._setActiveByHref(hash);
            }, 100);
        }
    }

    _initScrollSpy() {
        const existingSpy = bootstrap.ScrollSpy.getInstance(document.body);
        if (existingSpy) {
            existingSpy.dispose();
        }

        // We use an offset matching your navbar height + extra padding
        const spy = new bootstrap.ScrollSpy(document.body, {
            target: '#scrollpsy-nav',
            offset: this._navbarHeight + 15
        });

        spy.refresh();
    }

    _bindNavClicks() {
        document
            .querySelectorAll('#scrollpsy-nav a[data-scroll-target]')
            .forEach(link => {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    console.log('action/');

                    const targetId = link.dataset.scrollTarget;
                    const href = link.getAttribute('href');

                    this._isProgrammaticScroll = true;

                    // 1. Turn off Bootstrap's scrollspy tracking instantly while animating
                    const spyInstance = bootstrap.ScrollSpy.getInstance(document.body);
                    if (spyInstance) {
                        spyInstance.dispose();
                    }

                    // 2. Lock the active indicator immediately to the clicked link
                    this._setActiveByHref(href);

                    // 3. Execute scroll
                    this._scrollToId(targetId);

                    history.replaceState(null, null, href);

                    // 4. Reset programmatic lock and re-enable ScrollSpy after scroll finishes
                    clearTimeout(this._scrollTimeout);
                    this._scrollTimeout = setTimeout(() => {
                        this._isProgrammaticScroll = false;
                        this._initScrollSpy();
                        this._syncIndicatorToActive();
                    }, 800); // 800ms gives plenty of time for smooth scroll to finish
                });
            });
    }

    _scrollToId(id) {
        const target = document.getElementById(id);
        if (!target) return;

        // Modern browser approach: applying options directly
        target.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }

    _setActiveByHref(href) {
        const navContainer = document.querySelector('#scrollpsy-nav .nav');
        if (!navContainer) return;

        navContainer
            .querySelectorAll('.nav-link')
            .forEach(link => link.classList.remove('active'));

        const activeLink = navContainer.querySelector(
            `.nav-link[href="${href}"]`
        );

        if (activeLink) {
            activeLink.classList.add('active');
            this._moveIndicatorToLink(activeLink);
        }
    }

    enableScrollpsyIndicator() {
        const navContainer = document.querySelector('#scrollpsy-nav .nav');
        if (!navContainer) return;

        document.addEventListener('activate.bs.scrollspy', () => {
            if (this._isProgrammaticScroll) return;
            this._syncIndicatorToActive();
        });

        let scrollTimer;
        window.addEventListener('scroll', () => {
            if (this._isProgrammaticScroll) return;

            clearTimeout(scrollTimer);
            scrollTimer = setTimeout(() => {
                this._syncIndicatorToActive();
            }, 50);
        }, { passive: true });

        window.addEventListener('resize', () => {
            this._syncIndicatorToActive();
        });

        setTimeout(() => {
            this._syncIndicatorToActive();
        }, 200);
    }

    _syncIndicatorToActive() {
        const navContainer = document.querySelector('#scrollpsy-nav .nav');
        if (!navContainer) return;

        const activeLinks = navContainer.querySelectorAll('.nav-link.active');
        if (!activeLinks.length) return;

        const active = activeLinks[activeLinks.length - 1];
        this._moveIndicatorToLink(active);
    }

    _moveIndicatorToLink(linkEl) {
        const navContainer = document.querySelector('#scrollpsy-nav .nav');
        if (!navContainer || !linkEl) return;

        const indicator = navContainer.querySelector('.active-indicator');
        if (!indicator) return;

        let offset = linkEl.offsetTop;
        let parent = linkEl.offsetParent;

        while (parent && parent !== navContainer) {
            offset += parent.offsetTop;
            parent = parent.offsetParent;
        }

        indicator.style.transform = `translateY(${offset}px)`;
        indicator.style.height = `${linkEl.offsetHeight}px`;
    }

    generateHeadingId(text) {
        let slug = text
            .toLowerCase()
            .trim()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/(^-|-$)/g, '');

        if (!slug || /^[0-9]/.test(slug)) {
            slug = 'section-' + slug;
        }

        return slug;
    }
}
