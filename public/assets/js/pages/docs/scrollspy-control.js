class ScrollSpyControl {
    smoothScrollBehaviour() {
        document.querySelectorAll('#scrollpsy-nav a').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href'))
                    .scrollIntoView({
                        behavior: 'smooth'
                    });
            });
        });
    }

    generateScrollSpy(contentSelector) {
        const obj = this;
        const content = document.querySelector(contentSelector);
        const navContainer = document.querySelector('#scrollpsy-nav .nav');
        const dropdownMenu = document.getElementById('onThisPageMenu');

        if (!content || !navContainer) return;


        const navbarHeight = 70;

        navContainer.innerHTML = '<div class="active-indicator"></div>';
        if (dropdownMenu) dropdownMenu.innerHTML = '';


        const allHeadings = content.querySelectorAll('h1, h2, h3, h4, h5, h6');
        const spyHeadings = content.querySelectorAll('h2, h3');

        let currentParentNav = null;

        console.log(allHeadings);

        allHeadings.forEach((heading) => {

            if (!heading.id) {
                heading.id = obj.generateHeadingId(heading.textContent);
            }

            const headingId = `#${heading.id}`;

            heading.style.cursor = 'pointer';

            heading.addEventListener('click', function () {
                const targetPosition = this.getBoundingClientRect().top + window.pageYOffset;

                window.scrollTo({
                    top: targetPosition - navbarHeight,
                    behavior: 'smooth'
                });

                history.replaceState(null, null, headingId);
            });

        });

        spyHeadings.forEach((heading) => {

            const headingId = `#${heading.id}`;
            const headingText = heading.textContent;

            if (heading.tagName === 'H2') {

                const parentLink = document.createElement('a');
                parentLink.className = 'nav-link';
                parentLink.href = headingId;
                parentLink.textContent = headingText;

                navContainer.appendChild(parentLink);

                currentParentNav = document.createElement('nav');
                currentParentNav.className = 'nav nav-pills flex-column';
                navContainer.appendChild(currentParentNav);
            }

            else if (heading.tagName === 'H3') {

                const childLink = document.createElement('a');
                childLink.href = headingId;
                childLink.textContent = headingText;

                if (currentParentNav) {
                    childLink.className = 'nav-link ms-2';
                    currentParentNav.appendChild(childLink);
                }

                else {
                    childLink.className = 'nav-link';
                    navContainer.appendChild(childLink);
                }
            }

            if (dropdownMenu) {

                const li = document.createElement('li');
                const dropdownLink = document.createElement('a');

                dropdownLink.className = 'dropdown-item';
                dropdownLink.href = headingId;
                dropdownLink.textContent = headingText;

                if (heading.tagName === 'H3') {
                    dropdownLink.classList.add('ps-4');
                }

                li.appendChild(dropdownLink);
                dropdownMenu.appendChild(li);
            }

        });

        new bootstrap.ScrollSpy(document.body, {
            target: '#scrollpsy-nav',
            offset: navbarHeight
        });
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
    enableScrollpsyIndicator() {
        const navContainer = document.querySelector("#scrollpsy-nav .nav");
        const indicator = navContainer.querySelector(".active-indicator");

        function moveIndicator() {

            const activeLinks = navContainer.querySelectorAll(".nav-link.active");

            if (!activeLinks.length) return;

            const active = activeLinks[activeLinks.length - 1];

            const containerRect = navContainer.getBoundingClientRect();
            const activeRect = active.getBoundingClientRect();

            const offset = activeRect.top - containerRect.top + navContainer.scrollTop;

            indicator.style.transform = `translateY(${offset}px)`;
            indicator.style.height = active.offsetHeight + "px";
        }

        document.addEventListener("scroll", moveIndicator);
        window.addEventListener("resize", moveIndicator);

        moveIndicator();
    }
}
