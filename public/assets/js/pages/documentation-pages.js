document.addEventListener('DOMContentLoaded', function () {
    new PageControl().init();
});


class PageControl {
    init() {
        this.enableSidebar();
        this.enableContentScreen('#toggleScreenType');

    }

    enableContentScreen(selector) {
        const checkbox = document.querySelector(selector);
        const icon = checkbox.parentElement.querySelector('.bi');
        if (!checkbox) {
            return;
        }

        checkbox.addEventListener('change', function () {
            if (checkbox.checked) {
                document.body.setAttribute('data-screen-type', 'content');
                icon.classList.remove('bi-fullscreen');
                icon.classList.add('bi-fullscreen-exit');
            } else {
                document.body.removeAttribute('data-screen-type', 'content');
                icon.classList.remove('bi-fullscreen-exit');
                icon.classList.add('bi-fullscreen');
            }
        });
    }

    enableSidebar() {
        var allFolders = $(".directory-list li > ul");

        allFolders.each(function () {
            var $folderUl = $(this);
            var $li = $folderUl.parent();

            $li.addClass("folder");

            $folderUl.remove();
            if (!$li.children("a").length) {
                $li.wrapInner("<a href='#'></a>");
            }
            $li.append($folderUl);
        });

        $(".directory-list").off("click", "li.folder > a").on("click", "li.folder > a", function (e) {
            $(this).siblings("ul").slideToggle("slow");
            e.preventDefault();
        });

        $(".directory-list li").each(function () {
            var $li = $(this);

            $li.contents().filter(function () {
                return this.nodeType === 3 && /\S/.test(this.nodeValue);
            }).each(function () {
                $(this).wrap("<span class='item'></span>");
            });

            $li.children().filter(function () {
                return !$(this).is("a, .item, ul");
            }).each(function () {
                if (!$(this).hasClass("item")) {
                    $(this).wrap("<span class='item'></span>");
                }
            });
        });

        // now every file/label should be either a direct <a> (folder) or a .item span (file/label)
    }

}
