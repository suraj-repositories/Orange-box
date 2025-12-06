
class Utility {

    static setUrlParam(key, value) {
        const currentUrl = window.location.href;
        const url = new URL(currentUrl);
        url.searchParams.set(key, value);
        window.history.pushState({}, '', url);
    }

    static getUrlParam(key) {
        const currentUrl = window.location.href;
        const url = new URL(currentUrl);
        return url.searchParams.get(key);
    }

    static beforeCloseTabWaitFor(inProgress, message = "Running... Leave?") {
        if (inProgress) {
            e.preventDefault();
            if (!confirm(message)) e.preventDefault();
        }
    }

    static createUUID() {
        var s = [];
        var hexDigits = "0123456789abcdef";
        for (var i = 0; i < 36; i++) {
            s[i] = hexDigits.substring(Math.floor(Math.random() * 0x10), 1);
        }
        s[14] = "4";
        s[19] = hexDigits.substring((s[19] & 0x3) | 0x8, 1);
        s[8] = s[13] = s[18] = s[23] = "-";

        var uuid = s.join("");
        return uuid;
    }

    static secondsToTime(seconds) {
        const days = Math.floor(seconds / (24 * 60 * 60));
        const hours = Math.floor((seconds % (24 * 60 * 60)) / (60 * 60));
        const minutes = Math.floor((seconds % (60 * 60)) / 60);
        const remainingSeconds = seconds % 60;

        return { days, hours, minutes, seconds: remainingSeconds };
    }

    static formatTimeFromSeconds(seconds) {
        const days = Math.floor(seconds / (24 * 60 * 60));
        const hours = Math.floor((seconds % (24 * 60 * 60)) / (60 * 60));
        const minutes = Math.floor((seconds % (60 * 60)) / 60);
        const remainingSeconds = seconds % 60;

        const timeParts = [];

        if (days > 0) {
            timeParts.push(`${days} day${days > 1 ? 's' : ''}`);
        }
        if (hours > 0) {
            timeParts.push(`${hours} hour${hours > 1 ? 's' : ''}`);
        }
        if (minutes > 0) {
            timeParts.push(`${minutes} minute${minutes > 1 ? 's' : ''}`);
        }
        if (remainingSeconds > 0) {
            timeParts.push(`${remainingSeconds} second${remainingSeconds > 1 ? 's' : ''}`);
        }

        return timeParts.join(', ');
    }


    static convertGoogleMapsUrl(shareUrl) {
        let match = fullUrl.match(/@([-.\d]+),([-.\d]+)/);
        if (match) {
            let lat = match[1];
            let lng = match[2];

            return `https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d10000!2d${lng}!3d${lat}!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sin`;
        }
        return null;
    }
}

class App {

    init() {
        this.initComponents();
        this.initMenu();
        this.initControls();
        this.initResizeableTextArea();
        this.initImage3Gallery();
        this.initTabSpecificUrl();
        this.dismissablePopOvers();
        this.enablePreloader();
        this.enableCopyBtns();
    }

    initComponents() {

        // Waves Effect
        Waves.init();

        // Feather Icons
        feather.replace()

        // Popovers
        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
        var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl)
        })

        // Tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })

        // Tooltips With Title
        const tooltipTriggerListWithTitle = [].slice.call(document.querySelectorAll('[title]'));
        const tooltipListWithTitle = tooltipTriggerListWithTitle.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        document.addEventListener('focusin', function (event) {
            if (!event.target.matches('[title]')) {
                tooltipListWithTitle.forEach(function (tooltip) {
                    tooltip.hide();
                });
            }
        });

        // Toasts
        var toastElList = [].slice.call(document.querySelectorAll('.toast'))
        var toastList = toastElList.map(function (toastEl) {
            return new bootstrap.Toast(toastEl)
        })

        // Invalid Input Field
        var invalidInputList = [].slice.call(document.querySelectorAll('.invalid-input'));
        invalidInputList.map(element =>
            element.addEventListener('keyup', event =>
                event.target.classList.remove('invalid-input')
            )
        );


        // Toasts Placement
        var toastPlacement = document.getElementById("toastPlacement");
        if (toastPlacement) {
            document.getElementById("selectToastPlacement").addEventListener("change", function () {
                if (!toastPlacement.dataset.originalClass) {
                    toastPlacement.dataset.originalClass = toastPlacement.className;
                }
                toastPlacement.className = toastPlacement.dataset.originalClass + " " + this.value;
            });
        }

        // liveAlert
        var alertPlaceholder = document.getElementById('liveAlertPlaceholder')
        var alertTrigger = document.getElementById('liveAlertBtn')

        function alert(message, type) {
            var wrapper = document.createElement('div')
            wrapper.innerHTML = '<div class="alert alert-' + type + ' alert-dismissible" role="alert">' + message + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'

            alertPlaceholder.append(wrapper)
        }

        if (alertTrigger) {
            alertTrigger.addEventListener('click', function () {
                alert('Nice, you triggered this alert message!', 'primary')
            })
        }
    }

    initControls = function () {

        //  Full Screen Controls
        $('[data-toggle="fullscreen"]').on("click", function (e) {
            e.preventDefault();
            $('body').toggleClass('fullscreen-enable');
            if (!document.fullscreenElement && /* alternative standard method */ !document.mozFullScreenElement && !document.webkitFullscreenElement) {  // current working methods
                if (document.documentElement.requestFullscreen) {
                    document.documentElement.requestFullscreen();
                } else if (document.documentElement.mozRequestFullScreen) {
                    document.documentElement.mozRequestFullScreen();
                } else if (document.documentElement.webkitRequestFullscreen) {
                    document.documentElement.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
                }
            } else {
                if (document.cancelFullScreen) {
                    document.cancelFullScreen();
                } else if (document.mozCancelFullScreen) {
                    document.mozCancelFullScreen();
                } else if (document.webkitCancelFullScreen) {
                    document.webkitCancelFullScreen();
                }
            }
        });
        document.addEventListener('fullscreenchange', exitHandler);
        document.addEventListener("webkitfullscreenchange", exitHandler);
        document.addEventListener("mozfullscreenchange", exitHandler);

        function exitHandler() {
            if (!document.webkitIsFullScreen && !document.mozFullScreen && !document.msFullscreenElement) {
                $('body').removeClass('fullscreen-enable');
            }
        }
    }

    dismissablePopOvers() {
        const isPopOvers = document.querySelector('.popover-dismiss');
        if (!isPopOvers) {
            return;
        }

        const popover = new bootstrap.Popover('.popover-dismiss', {
            trigger: 'focus'
        })
    }

    initMenu() {
        var self = this;
        var body = document.body;

        // Menu Toggle Button ( Placed in Topbar) (Left menu collapse)
        var menuToggleBtn = document.querySelector('.button-toggle-menu');
        if (menuToggleBtn) {
            menuToggleBtn.addEventListener('click', function () {

                if (body.getAttribute('data-sidebar') == 'default') {
                    body.setAttribute('data-sidebar', 'hidden');
                } else {
                    body.setAttribute('data-sidebar', 'default');
                }
            });
        }

        const updateOnWindowResize = () => {
            if (window.innerWidth < 1040) {
                body.setAttribute('data-sidebar', 'hidden');
            } else {
                body.setAttribute('data-sidebar', 'default');
            }
        }

        updateOnWindowResize();
        window.addEventListener('resize', updateOnWindowResize)

        // sidebar - main menu
        if ($("#side-menu").length) {
            var navCollapse = $('#side-menu li .collapse');

            // open one menu at a time only
            navCollapse.on({
                'show.bs.collapse': function (event) {
                    var parent = $(event.target).parents('.collapse.show');
                    $('#side-menu .collapse.show').not(parent).collapse('hide');
                }
            });

            // activate the menu in left side bar (Vertical Menu) based on url
            $("#side-menu a").each(function () {
                var pageUrl = window.location.href.split(/[?#]/)[0];
                if (this.href == pageUrl) {
                    $(this).addClass("active");
                    $(this).parent().addClass("menuitem-active");
                    $(this).parent().parent().parent().addClass("show");
                    $(this).parent().parent().parent().parent().addClass("menuitem-active");

                    var firstLevelParent = $(this).parent().parent().parent().parent().parent().parent();
                    if (firstLevelParent.attr('id') !== 'sidebar-menu') firstLevelParent.addClass("show");

                    $(this).parent().parent().parent().parent().parent().parent().parent().addClass("menuitem-active");

                    var secondLevelParent = $(this).parent().parent().parent().parent().parent().parent().parent().parent().parent();
                    if (secondLevelParent.attr('id') !== 'wrapper') secondLevelParent.addClass("show");

                    var upperLevelParent = $(this).parent().parent().parent().parent().parent().parent().parent().parent().parent().parent();
                    if (!upperLevelParent.is('body')) upperLevelParent.addClass("menuitem-active");
                }
            });
        }
    }

    initResizeableTextArea() {
        const textAreas = document.querySelectorAll('textarea[resizeable="true"]');

        if (!textAreas.length) return;
        textAreas.forEach(textArea => {
            if (textArea.dataset.autoResizeAttached) return;
            textArea.style.overflow = "hidden";
            textArea.rows = 1;

            const resizeTextArea = () => {

                textArea.style.height = "auto";
                textArea.style.height = textArea.scrollHeight + "px";
            }

            textArea.addEventListener("input", resizeTextArea);

            const tab = textArea.closest('.tab-pane');
            if (tab) {
                tab.classList.add('invisible-block');
                resizeTextArea();
                tab.classList.remove('invisible-block');
            } else {
                resizeTextArea();
            }


            textArea.dataset.autoResizeAttached = "true";
        });

    }

    initImage3Gallery() {
        document.querySelectorAll('.image-3-gallery').forEach(container => {
            const wrappers = container.querySelectorAll('.img-wrapper');
            const total = wrappers.length;

            if (total > 3) {
                container.classList.add('has-more-content');
                const thirdWrapper = container.querySelector('.img-wrapper:nth-child(3)');
                thirdWrapper.setAttribute('data-remaining-count', total - 3);
            }
        });
    }

    initTabSpecificUrl() {
        const tabBtns = document.querySelectorAll('[data-bs-toggle="tab"]');

        if (!tabBtns) {
            return;
        }

        tabBtns.forEach(tab => {
            tab.addEventListener('click', function () {
                const role = tab.getAttribute('role');
                const href = tab.getAttribute('href');
                let urlAddon = href.match(/#(\w+)/)[1];
                if (href.indexOf("#tab_") != -1) {
                    urlAddon = href.match(/#tab_(\w+)/)[1];
                }
                Utility.setUrlParam(role, urlAddon);

            });
        });
    }

    enablePreloader() {
        window.addEventListener("load", function () {
            const preloader = document.getElementById("preloader");
            preloader.classList.add("hide");
        });
    }

    enableCopyBtns() {
    const copyIcons = document.querySelectorAll('.copy-icon');

    if (!copyIcons.length) {
        return;
    }

    copyIcons.forEach(icon => {
        icon.addEventListener('click', () => {

            icon.classList.remove('bi-copy');

            const copyTxt = icon.getAttribute('data-copy-text');

            navigator.clipboard.writeText(copyTxt)
                .then(() => {
                    icon.classList.add('bi-check-circle');
                })
                .catch(err => {
                    icon.classList.add('bi-exclamation-circle');
                });

            setTimeout(() => {
                icon.classList.remove('bi-check-circle');
                icon.classList.remove('bi-exclamation-circle');
                icon.classList.add('bi-copy');
            }, 1000);
        });
    });
}

}




new App().init();
