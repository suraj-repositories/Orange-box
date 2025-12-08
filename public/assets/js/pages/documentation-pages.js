document.addEventListener('DOMContentLoaded', function () {
    new PageControl().init();
});


class PageControl {
    init() {
        this.enableSidebar();
        this.enableContentScreen('#toggleScreenType');
        this.enableSeperator(document.querySelector("#separator"));
        this.enableExplorerNavber();

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

            var folderAndName = $(this).parent();
            folderAndName.addClass("folder");

            var backupOfThisFolder = $(this);
            $(this).remove();
            folderAndName.wrapInner("<a href='#' />");
            folderAndName.append(backupOfThisFolder);

            folderAndName.find("a").click(function (e) {
                $(this).siblings("ul").slideToggle("slow");
                e.preventDefault();
            });

        });

        let allListItems = $(".directory-list li");
        allListItems.each(function () {

            const $li = $(this);

            const cover = document.createElement('div');
            cover.classList.add('ob-li-row-cover');
            $li.prepend(cover);

            const textNode = $li
                .contents()
                .filter(function () {
                    return this.nodeType === 3 && $.trim(this.nodeValue).length > 0;
                })
                .first();

            if (textNode.length) {
                const span = $('<span class="li-item"></span>').text(textNode.text());
                textNode.replaceWith(span);
            }

            cover.addEventListener('click', function (e) {
                e.stopPropagation();

                $(".ob-li-row-cover").not(this).removeClass("active");
                $(".directory-list").removeClass("active")
                this.classList.toggle('active');
            });
        });

        let ul = document.querySelector('.directory-list');
        ul.addEventListener('click', function (e) {
            e.stopPropagation();
            if (e.target.tagName === 'INPUT') {
                return;
            }
            const skipLi = e.target.closest(".directory-list li:has(input):not(:has(li))");
            if (skipLi) {
                return;
            }
            $(".ob-li-row-cover").removeClass("active");
            ul.classList.toggle('active');
        });

    }

    enableSeperator(element) {
        let startX = 0;
        let startWidth = 0;

        element.onmousedown = function (e) {
            startX = e.clientX;
            startWidth = document.getElementById("explorer-sidebar").offsetWidth;

            document.onmousemove = onDrag;
            document.onmouseup = stopDrag;
        };

        function onDrag(e) {
            const explorer = document.getElementById("explorer-sidebar");
            const splitter = document.getElementById("splitter");

            let newWidth = startWidth + (e.clientX - startX);

            if (newWidth < 150) newWidth = 150;
            if (newWidth > splitter.clientWidth - 150)
                newWidth = splitter.clientWidth - 150;

            explorer.style.width = newWidth + "px";

            element.style.left = newWidth + "px";
        }

        function stopDrag() {
            document.onmousemove = document.onmouseup = null;
        }
    }

    createNewFileInput() {
        const li = document.createElement('li');

        const input = document.createElement('input');
        input.classList.add('new_file');
        input.name = "new_file";
        input.type = "text";

        li.append(input);

        setTimeout(() => input.focus(), 10);

        const finalize = () => {
            const fileName = input.value.trim();

            if (fileName === "") {
                li.remove();
                return;
            }

            const span = document.createElement("span");
            span.classList.add("li-item");
            span.textContent = fileName;

            li.innerHTML = "";
            li.appendChild(span);
        };

        // Save on Enter
        input.addEventListener("keydown", (e) => {
            if (e.key === "Enter") {
                finalize();
            }
            if (e.key === "Escape") {
                li.remove();
            }
        });

        // Save or remove on blur (unfocus)
        input.addEventListener("blur", () => {
            finalize();
        });

        // Prevent parent click triggering
        input.addEventListener("click", (e) => e.stopPropagation());

        return li;
    }



    enableExplorerNavber() {
        const newFileButton = document.querySelector("#newFile");
        const explorerFiles = document.querySelector("#explorer-sidebar .directory-list");

        if (newFileButton) {
            const classObj = this;

            newFileButton.addEventListener('click', function () {

                const active = document.querySelector('#explorer-sidebar .ob-li-row-cover.active');

                let targetUL = explorerFiles;

                $(".ob-li-row-cover").removeClass("active");
                $(".directory-list").removeClass("active")

                if (active) {
                    const activeLI = active.closest('li');

                    if (activeLI.classList.contains('folder')) {
                        const folderUL = activeLI.querySelector('ul');
                        if (folderUL) targetUL = folderUL;
                    } else {
                        const nearestFolder = activeLI.closest('.folder');
                        if (nearestFolder) {
                            targetUL = nearestFolder.querySelector('ul');
                        }
                    }
                }

                targetUL.prepend(classObj.createNewFileInput());
            });
        }
    }


    createFile() {

    }
}


