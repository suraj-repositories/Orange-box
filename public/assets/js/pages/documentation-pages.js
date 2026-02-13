

document.addEventListener('DOMContentLoaded', function () {
    new PageControl().init();
    const icon = document.querySelector('#toggleScreenType');
    if (localStorage.getItem('documentation-editor-full-screen')) {
        document.body.setAttribute('data-screen-type', 'content');
        if (icon) {
            icon.checked = true;
            icon.classList.remove('bi-fullscreen');
            icon.classList.add('bi-fullscreen-exit');
        }
    }
});
class PageControl {

    static tabBuilder;
    static apiService;

    init() {
        this.enableSidebar();
        this.enableContentScreen('#toggleScreenType');
        this.enableSeperator(document.querySelector("#separator"));
        this.enableExplorerNavber();
        this.enableDraggable();
        this.createContextMenu();
        this.enableRightClickMenu();
        PageControl.tabBuilder = new DocumentationTabs(
            'documentationExplorerTab',
            'documentationExplorerTabBody'
        );

        PageControl.apiService = new ApiService();
    }

    enableContentScreen(selector) {
        const checkbox = document.querySelector(selector);
        const icon = checkbox.parentElement.querySelector('.bi');
        if (!checkbox) {
            return;
        }

        checkbox.addEventListener('change', function () {
            if (checkbox.checked) {
                localStorage.setItem('documentation-editor-full-screen', true);
                document.body.setAttribute('data-screen-type', 'content');
                icon.classList.remove('bi-fullscreen');
                icon.classList.add('bi-fullscreen-exit');
            } else {
                if (localStorage.getItem('documentation-editor-full-screen')) {
                    localStorage.removeItem('documentation-editor-full-screen', true);
                }
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

            folderAndName.wrapInner("<span class='folder-name'></span>");
            folderAndName.append(backupOfThisFolder);
        });

        let allListItems = $(".directory-list li");

        const classObj = this;

        allListItems.each(function () {
            const $li = $(this);

            const cover = document.createElement("div");
            cover.classList.add("ob-li-row-cover");
            cover.setAttribute("doc-ob-draggable", true);
            cover.setAttribute("doc-ob-dropable", true);

            $li.prepend(cover);

            const textNode = $li
                .contents()
                .filter(function () {
                    return (
                        this.nodeType === 3 &&
                        $.trim(this.nodeValue).length > 0
                    );
                })
                .first();

            if (textNode.length) {
                const span = $('<span class="li-item"></span>').text(
                    textNode.text()
                );
                textNode.replaceWith(span);
            }

            cover.addEventListener("click", function (e) {
                e.stopPropagation();

                classObj.sidebarCoverEvent(this);
            });
        });

        let ul = document.querySelector(".directory-list");

        ul.addEventListener("click", function (e) {
            e.stopPropagation();

            if (e.target.tagName === "INPUT") {
                return;
            }

            const skipLi = e.target.closest(
                ".directory-list li:has(input):not(:has(li))"
            );

            if (skipLi) {
                return;
            }
            if (!e.target.classList.contains('ob-li-row-cover')) {
                $(".ob-li-row-cover").removeClass("active");
                ul.classList.toggle("active");
            }
        });
    }

    sidebarCoverEvent(cover) {
        $(".ob-li-row-cover").not(cover).removeClass("active");
        $(".directory-list").removeClass("active");


        cover.classList.add("active");
        console.log('here');
        const li = cover.closest('li');
        if (li) {
            const uuid = li.getAttribute('data-doc-page-uuid');
            const loader = document.querySelector('#editor-page-loader');
            if (loader) loader.classList.remove('d-none');

            PageControl.apiService.getPage(uuid)
                .then(data => {
                    PageControl.tabBuilder.createNewTab(data.data.title, data.data);
                    if (loader) loader.classList.add('d-none');
                })
                .catch(error => {
                    console.error('Failed to create tab:', error);
                    if (loader) loader.classList.add('d-none');

                })
                ;
        }



        if (cover.parentElement.classList.contains("folder")) {
            $(cover.parentElement)
                .children("ul")
                .stop(true, true)
                .slideToggle("fast");
        }
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

    createNewFileNameInputElement() {
        const li = document.createElement('li');

        const input = document.createElement('input');
        input.classList.add('new_file');
        input.name = "new_file";
        input.type = "text";

        li.append(input);

        setTimeout(() => input.focus(), 10);

        let finalized = false;
        const classObj = this;

        const finalize = () => {
            if (finalized) return;
            finalized = true;

            const fileName = input.value.trim();

            if (fileName === "") {
                li.remove();
                cleanup();
                return;
            }

            const div = document.createElement('div');
            div.classList.add('ob-li-row-cover');
            div.setAttribute("doc-ob-draggable", "true");
            div.setAttribute("doc-ob-dropable", "true");

            const span = document.createElement("span");
            span.classList.add("li-item");
            span.textContent = fileName;

            li.innerHTML = "";

            const loader = document.querySelector('#editor-page-loader');
            if (loader) loader.classList.remove('d-none');

            PageControl.apiService.createPage(fileName, 1, 'file', li)
                .then(data => {
                    PageControl.tabBuilder.createNewTab(fileName, {
                        uuid: data.uuid,
                        content: `<p>Content for ${fileName}</p>`
                    });
                    if (loader) loader.classList.add('d-none');
                })
                .catch(error => {
                    console.error('Failed to create tab:', error);
                    if (loader) loader.classList.add('d-none');

                })
                ;

            li.appendChild(div);
            li.appendChild(span);
            cleanup();
            div.addEventListener('click', () => {
                classObj.sidebarCoverEvent(div);
            });
            classObj.enableDraggable();
        };

        const onKeydown = (e) => {
            if (e.key === "Enter") {
                e.preventDefault();
                finalize();
            }
            if (e.key === "Escape") {
                li.remove();
                cleanup();
            }
        };

        const cleanup = () => {
            input.removeEventListener("keydown", onKeydown);
            input.removeEventListener("blur", finalize);
        };

        input.addEventListener("keydown", onKeydown);
        input.addEventListener("blur", finalize);
        input.addEventListener("click", (e) => e.stopPropagation());

        return li;
    }

    createNewFolderNameInputElement() {
        const li = document.createElement('li');

        const input = document.createElement('input');
        input.classList.add('new_folder');
        input.name = "new_folder";
        input.type = "text";

        li.appendChild(input);

        setTimeout(() => input.focus(), 10);

        let finalized = false;
        const classObj = this;

        const finalize = () => {
            if (finalized) return;
            finalized = true;

            const folderName = input.value.trim();

            if (folderName === "") {
                li.remove();
                cleanup();
                return;
            }

            li.innerHTML = "";
            li.classList.add("folder");

            const rowCover = document.createElement("div");
            rowCover.className = "ob-li-row-cover";
            rowCover.setAttribute("doc-ob-draggable", "true");
            rowCover.setAttribute("doc-ob-dropable", "true");

            const span = document.createElement("span");
            span.classList.add("folder-name");
            span.textContent = folderName;

            const ul = document.createElement("ul");

            PageControl.apiService.createPage(
                folderName,
                1,
                'folder',
                li
            );

            li.appendChild(rowCover);
            li.appendChild(span);
            li.appendChild(ul);

            cleanup();
            rowCover.addEventListener('click', () => {
                classObj.sidebarCoverEvent(rowCover);
            });
            classObj.enableDraggable();
        };

        const onKeydown = (e) => {
            if (e.key === "Enter") {
                e.preventDefault();
                finalize();
            }
            if (e.key === "Escape") {
                li.remove();
                cleanup();
            }
        };

        const cleanup = () => {
            input.removeEventListener("keydown", onKeydown);
            input.removeEventListener("blur", finalize);
        };

        input.addEventListener("keydown", onKeydown);
        input.addEventListener("blur", finalize);
        input.addEventListener("click", e => e.stopPropagation());

        return li;
    }

    enableExplorerNavber() {
        const newFileButton = document.querySelector("#newFile");
        const newFolderButton = document.querySelector("#newFolder");
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

                targetUL.prepend(classObj.createNewFileNameInputElement());
                $(targetUL).slideDown('fast');
                $('.ob-li-row-cover').removeClass('active');


            });
        }

        if (newFolderButton) {
            const classObj = this;

            newFolderButton.addEventListener('click', function () {
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

                targetUL.prepend(classObj.createNewFolderNameInputElement());
                $(targetUL).slideDown('fast');
                $('.ob-li-row-cover').removeClass('active');
            });
        }
    }

    enableDraggable() {
        const draggables = document.querySelectorAll('[doc-ob-draggable="true"]');
        if (!draggables.length) return;

        draggables.forEach((element) => {

            let isDown = false;
            let ghostCard = null;
            let dragListItem = null;
            let startX = 0;
            let startY = 0;
            let hasMoved = false;


            element.addEventListener('mousedown', (e) => {
                isDown = true;
                hasMoved = false;

                startX = e.pageX;
                startY = e.pageY;
            });

            document.addEventListener('mousemove', (e) => {
                if (!isDown) return;

                const dx = e.pageX - startX;
                const dy = e.pageY - startY;

                if (!hasMoved && Math.sqrt(dx * dx + dy * dy) < 5) {
                    return;
                }

                if (!hasMoved) {
                    hasMoved = true;

                    ghostCard = document.createElement("div");
                    ghostCard.classList.add("drag-ghost-card");

                    dragListItem = element.closest('li');

                    let text = '';
                    if (dragListItem.classList.contains('folder')) {
                        const folder = dragListItem.querySelector('.folder-name');
                        if (folder) {
                            text = folder.innerText.trim();
                        }
                    } else {
                        const file = dragListItem.querySelector('.li-item');
                        if (file) {
                            text = file.innerText.trim();
                        }
                    }

                    ghostCard.innerText = text;
                    document.body.appendChild(ghostCard);
                }

                if (ghostCard) {
                    ghostCard.style.left = e.pageX + "px";
                    ghostCard.style.top = e.pageY + "px";
                }
            });

            document.addEventListener('mouseup', (e) => {
                if (!isDown) return;
                isDown = false;

                if (ghostCard) {
                    ghostCard.remove();
                    ghostCard = null;
                }

                if (hasMoved) {
                    const dropLocation = e.target;

                    if (dropLocation.getAttribute('doc-ob-dropable') != 'true') {
                        return;
                    }

                    const liParent = dropLocation.closest('li');

                    if (liParent && dragListItem.contains(liParent)) {
                        return;
                    }

                    if (liParent && liParent.classList.contains('folder')) {
                        const innerUl = liParent.querySelector(':scope > ul');
                        (innerUl || liParent.appendChild(document.createElement('ul')))
                            .prepend(dragListItem);
                    } else if (liParent) {
                        liParent.insertAdjacentElement('beforebegin', dragListItem);
                    } else {
                        console.error("Drop failed: No parent <li> found for dropLocation.");
                    }



                }

            });
        });
    }

    createContextMenu() {
        const menu = document.createElement("div");
        menu.id = "contextMenu";
        menu.classList.add('ctx-menu');

        menu.innerHTML = `
            <div class="ctx-item" data-action="new_file" style="padding:8px 12px; cursor:pointer;">New File</div>
            <div class="ctx-item" data-action="new_folder" style="padding:8px 12px; cursor:pointer;">New Folder</div>
            <div class="ctx-item" data-action="rename" style="padding:8px 12px; cursor:pointer;">Rename</div>
        `;

        document.body.appendChild(menu);
    }

    enableRightClickMenu() {

        const menu = document.getElementById("contextMenu");
        let currentLi = null;
        const classObj = this;

        const createNewFileItem = (liNode) => {
            let targetUL;

            if (liNode.classList.contains('folder')) {
                targetUL = liNode.querySelector(':scope > ul');

                if (!targetUL) {
                    targetUL = document.createElement('ul');
                    liNode.appendChild(targetUL);
                }
            } else {
                targetUL = liNode.parentElement;
            }

            targetUL.prepend(classObj.createNewFileNameInputElement());

            $(targetUL).slideDown('fast');
            $('.ob-li-row-cover').removeClass('active');
        };

        const createNewFolderItem = (liNode) => {
            let targetUL;

            if (liNode.classList.contains('folder')) {
                targetUL = liNode.querySelector(':scope > ul');

                if (!targetUL) {
                    targetUL = document.createElement('ul');
                    liNode.appendChild(targetUL);
                }
            } else {
                targetUL = liNode.parentElement;
            }

            targetUL.prepend(classObj.createNewFolderNameInputElement());

            $(targetUL).slideDown('fast');
            $('.ob-li-row-cover').removeClass('active');
        };

        const renameItem = (liNode) => {

            $(".ob-li-row-cover").removeClass("active");
            if (liNode.querySelector('.inline-rename-input')) return;
            const textEl =
                liNode.querySelector('.folder-name') ||
                liNode.querySelector('.node-title') ||
                liNode.querySelector('.li-item');

            if (!textEl) return;

            const oldText = textEl.textContent.trim();

            const input = document.createElement('input');
            input.className = 'inline-rename-input';
            input.value = oldText;

            let finished = false;

            const finish = () => {
                if (finished) return;
                finished = true;

                const newValue = input.value.trim() || oldText;
                textEl.textContent = newValue;
                input.replaceWith(textEl);
                PageControl.apiService.renamePage(newValue, liNode.getAttribute('data-doc-page-uuid'));
            };

            textEl.replaceWith(input);
            input.focus();
            input.setSelectionRange(0, input.value.length);

            input.addEventListener('blur', finish);
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') finish();
                if (e.key === 'Escape') {
                    input.value = oldText;
                    finish();
                }
            });
        };

        document.addEventListener("contextmenu", (e) => {
            const li = e.target.closest(".directory-list li");
            if (!li) return;

            e.preventDefault();

            currentLi = li;
            menu.style.left = `${e.pageX}px`;
            menu.style.top = `${e.pageY}px`;
            menu.style.display = "block";
        });

        document.addEventListener("click", (e) => {
            if (!menu.contains(e.target)) {
                menu.style.display = "none";
            }
        });

        menu.addEventListener("click", (e) => {
            const action = e.target.getAttribute("data-action");
            if (!action || !currentLi) return;

            if (action === "new_file") createNewFileItem(currentLi);
            if (action === "new_folder") createNewFolderItem(currentLi);
            if (action === "rename") renameItem(currentLi);

            menu.style.display = "none";
        });

    }
}

class DocumentationTabs {

    constructor(tabListId, tabBodyId) {
        this.tabList = document.getElementById(tabListId);
        this.tabBody = document.getElementById(tabBodyId);
        this.tabIndex = 0;

        if (!this.tabList || !this.tabBody) {
            throw new Error('Tab container not found');
        }
    }

    createNewTab(title, pageData = {}, iconClass = 'bi bi-app') {
        const id = `doc-tab-${this.tabIndex++}`;

        const li = document.createElement('li');
        li.className = 'nav-item';

        const button = document.createElement('button');
        button.className = 'nav-link d-flex align-items-center gap-2';
        button.type = 'button';
        button.dataset.target = id;

        const icon = document.createElement('i');
        icon.className = iconClass;

        const text = document.createElement('span');
        text.className = 'tab-title';
        text.textContent = title;

        const closeBtn = document.createElement('span');
        closeBtn.className = 'tab-close';
        closeBtn.innerHTML = '&times;';

        button.append(icon, text, closeBtn);
        li.appendChild(button);
        this.tabList.appendChild(li);

        const pane = document.createElement('div');
        pane.className = 'tab-pane fade';
        pane.id = id;
        pane.tabIndex = 0;
        const { block, addButton } = this.createGitEditorBlock(pane);
        const textarea = block.querySelector('textarea');
        if (textarea) {
            textarea.value = pageData.content ?? '';
        }

        this.tabBody.appendChild(pane);

        this.activateTab(button);

        button.addEventListener('click', (e) => {
            if (e.target === closeBtn) return;
            this.activateTab(button);
        });

        closeBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            this.closeTab(button);
        });

        return { button, pane };
    }

    createGitEditorBlock(container) {
        const block = document.createElement('div');
        block.className = 'git-editor-block';

        const row = document.createElement('div');
        row.className = 'row';

        const gitPane = document.createElement('div');
        gitPane.className = 'col-12 mb-3';
        gitPane.innerHTML = `
        <label class="form-label">Git Link</label>
        <div class="d-flex">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-git"></i></span>
                <input type="text" class="form-control" placeholder="Enter autofetch link">
            </div>
            <button type="button" class="btn btn-outline-primary ms-1 px-2">
                <i class='bx bx-download fs-4'></i>
            </button>
        </div>
    `;

        const editorPane = document.createElement('div');
        editorPane.className = 'col-12';
        editorPane.innerHTML = `
        <label class="form-label">Content</label>
        <textarea class="form-control" cols="30" rows="3"></textarea>
    `;

        row.appendChild(gitPane);
        row.appendChild(editorPane);
        block.appendChild(row);

        const toolbar = document.createElement('div');
        toolbar.className = 'tab-editor-toolbar';
        toolbar.innerHTML = `
        <ul class="list-inline">
            <li class="list-inline-item"><button class="tab-editor"><i class='bx bx-pencil'></i></button></li>
            <li class="list-inline-item"><button class="tab-github"><i class='bx bxl-github'></i></button></li>
            <li class="list-inline-item"><button class="tab-save"><i class='bx bx-save'></i></button></li>
        </ul>
    `;
        block.appendChild(toolbar);

        function showPane(pane) {
            gitPane.style.display = pane === 'github' ? 'block' : 'none';
            editorPane.style.display = pane === 'editor' ? 'block' : 'none';

            toolbar.querySelectorAll('button').forEach(btn => btn.classList.remove('active'));
            if (pane === 'editor') {
                toolbar.querySelector('.tab-editor').classList.add('active');
            } else if (pane === 'github') {
                toolbar.querySelector('.tab-github').classList.add('active');
            }
        }

        showPane('editor');

        toolbar.querySelector('.tab-editor').addEventListener('click', () => showPane('editor'));
        toolbar.querySelector('.tab-github').addEventListener('click', () => showPane('github'));

        container.appendChild(block);

        return {
            block,
            addButton: function (label, callback) {
                const btn = document.createElement('button');
                btn.textContent = label;
                btn.className = 'btn btn-sm btn-secondary ms-1';
                btn.addEventListener('click', callback);
                toolbar.appendChild(btn);
                return btn;
            }
        };
    }




    activateTab(button) {
        const targetId = button.dataset.target;

        this.tabList.querySelectorAll('.nav-link')
            .forEach(btn => btn.classList.remove('active'));

        this.tabBody.querySelectorAll('.tab-pane')
            .forEach(pane => pane.classList.remove('show', 'active'));

        button.classList.add('active');

        const pane = document.getElementById(targetId);
        if (pane) {
            pane.classList.add('show', 'active');
        }
    }

    closeTab(button) {
        const li = button.closest('li');
        const paneId = button.dataset.target;
        const pane = document.getElementById(paneId);
        const wasActive = button.classList.contains('active');

        let nextButton = null;
        if (wasActive) {
            const nextLi = li.nextElementSibling || li.previousElementSibling;
            nextButton = nextLi?.querySelector('.nav-link') || null;
        }

        li.remove();
        pane?.remove();

        if (nextButton) {
            this.activateTab(nextButton);
        }
    }
}

class ApiService {
    createPage(title, sortOrder, type, liItem) {
        const folder = liItem.classList.contains('folder')
            ? liItem.parentElement?.closest('.folder')
            : liItem.closest('.folder');
        const parentUUID = folder ? (folder.getAttribute('data-doc-page-uuid') ?? null) : null;

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const docUuidInput = document.getElementById('documentationUuidInput');

        return fetch(authRoute('user.documentation.pages.create', { documentation: docUuidInput.value }), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'x-csrf-token': csrfToken
            },
            body: JSON.stringify({
                title: title,
                parent_uuid: parentUUID,
                sort_order: sortOrder,
                type: type,
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (liItem) {
                        liItem.setAttribute('data-doc-page-uuid', data.data.uuid);
                    }
                    return data.data;
                } else {
                    return Promise.reject(data.message || 'Failed to create page');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                return Promise.reject(error);
            });
    }

    renamePage(title, uuid) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        return fetch(authRoute('user.documentation.pages.rename', { docPage: uuid }), {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'x-csrf-token': csrfToken
            },
            body: JSON.stringify({
                new_name: title,
            })
        })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    console.error(data);
                    return Promise.reject(data.message || 'Rename failed');
                }
                return data;
            })
            .catch(error => {
                console.error('Error:', error);
                return Promise.reject(error);
            });
    }

    getPage(uuid) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        return fetch(authRoute('user.documentation.pages.get', { docPage: uuid }), {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'x-csrf-token': csrfToken
            },
        })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    console.error(data);
                    return Promise.reject(data.message || 'Rename failed');
                }
                return data;
            })
            .catch(error => {
                console.error('Error:', error);
                return Promise.reject(error);
            });
    }
}

