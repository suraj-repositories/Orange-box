
document.addEventListener('DOMContentLoaded', function () {
    new PageControl().init();
    const icon = document.querySelector('#toggleScreenType');
    if (localStorage.getItem('documentation-editor-full-screen')) {
        document.documentElement.setAttribute('data-screen-type', 'content');
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
    static tabs = new Map();

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
                document.documentElement.setAttribute('data-screen-type', 'content');
                icon.classList.remove('bi-fullscreen');
                icon.classList.add('bi-fullscreen-exit');
            } else {
                if (localStorage.getItem('documentation-editor-full-screen')) {
                    localStorage.removeItem('documentation-editor-full-screen', true);
                }
                document.documentElement.removeAttribute('data-screen-type', 'content');
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
                const span = $('<span class="node-title"></span>').text(
                    textNode.text()
                );
                textNode.replaceWith(span);
            }

            cover.addEventListener("click", function (e) {
                e.stopPropagation();

                if (e.detail === 1) {
                    setTimeout(() => {
                        if (e.detail === 1) {
                            classObj.sidebarCoverEvent(this);
                        }
                    }, 250);
                }

                if (e.detail === 2) {
                    classObj.sidebarCoverEvent(this, 'double');
                }
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

    sidebarCoverEvent(cover, clickType = "single") {
        console.log(clickType == 'single' ? 'single click' : 'double click');

        $(".ob-li-row-cover").not(cover).removeClass("active");
        $(".directory-list").removeClass("active");
        cover.classList.add("active");
        const li = cover.closest('li');

        if (li && !li.classList.contains('folder')) {
            const uuid = li.getAttribute('data-doc-page-uuid');
            const loader = document.querySelector('#editor-page-loader');
            if (loader) loader.classList.remove('d-none');

            PageControl.apiService.getPage(uuid)
                .then(data => {
                    const tempTab = document.querySelector("#documentationExplorerTab li.temp");

                    if (!PageControl.tabs.has(data.data.uuid)) {
                        if (tempTab) {
                            const closeBtn = tempTab.querySelector('.tab-close');
                            if (closeBtn) closeBtn.click();
                        }
                        PageControl.tabs.set(data.data.uuid, {});
                        PageControl.tabBuilder.createNewTab(data.data.title, data.data, { isTemp: clickType == 'single' });


                    } else {
                        if (tempTab && tempTab.getAttribute('data-page-uuid') == data.data.uuid) {
                            tempTab.classList.remove('temp');
                        }
                    }

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
            span.classList.add("node-title");
            span.textContent = fileName;

            li.innerHTML = "";

            const loader = document.querySelector('#editor-page-loader');
            if (loader) loader.classList.remove('d-none');

            PageControl.apiService.createPage(fileName, 1, 'file', li)
                .then(data => {
                    PageControl.tabs.set(data.uuid, {});
                    PageControl.tabBuilder.createNewTab(fileName, {
                        uuid: data.uuid,
                        content: `# ${fileName}`
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

                    if (e.detail === 1) {
                        setTimeout(() => {
                            if (e.detail === 1) {
                                classObj.sidebarCoverEvent(div);
                            }
                        }, 250);
                    }

                    if (e.detail === 2) {
                        classObj.sidebarCoverEvent(div, 'double');
                    }


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
            rowCover.addEventListener('click', (e) => {

                if (e.detail === 1) {
                    setTimeout(() => {
                        if (e.detail === 1) {
                            classObj.sidebarCoverEvent(rowCover);
                        }
                    }, 250);
                }

                if (e.detail === 2) {
                    classObj.sidebarCoverEvent(rowCover, 'double');
                }
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
        const collapseExplorerButton = document.querySelector("#collapseExplorer");
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

        if (collapseExplorerButton) {
            collapseExplorerButton.addEventListener('click', function () {
                if (explorerFiles) {
                    const allfolderUls = explorerFiles.querySelectorAll('ul');
                    if (allfolderUls) {
                        allfolderUls.forEach(ul => $(ul).slideUp('fast'));
                    }
                }
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
                        const file = dragListItem.querySelector('.node-title');
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
                    const sourceUuid = dragListItem.getAttribute('data-doc-page-uuid');

                    let newParentUuid = null;

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

                        newParentUuid = liParent.getAttribute('data-doc-page-uuid');

                    } else if (liParent) {
                        liParent.insertAdjacentElement('beforebegin', dragListItem);

                        newParentUuid = liParent.closest('ul')?.closest('li')
                            ?.getAttribute('data-doc-page-uuid') || null;
                    } else if (dropLocation.classList.contains('directory-list')) {
                        dropLocation.appendChild(dragListItem);
                    }
                    else {
                        console.error("Drop failed: No parent <li> found for dropLocation.");
                    }
                    const parentUl = dragListItem.parentElement;

                    const siblings = [...parentUl.children];

                    const orderedUuids = siblings.map((li, index) => {
                        return {
                            uuid: li.getAttribute('data-doc-page-uuid'),
                            sort_order: index + 1
                        };
                    });

                    this.moveFile(sourceUuid, newParentUuid, orderedUuids);
                }

            });
        });
    }

    moveFile(source, destination, orderData) {
        PageControl.apiService.moveFile(orderData, destination)
            .then((data) => {
                console.log("moved successfully");
            })
            .catch(err => {
                console.error('Move failed');
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
            <div class="ctx-item" data-action="delete" style="padding:8px 12px; cursor:pointer;">Delete</div>
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
                liNode.querySelector('.node-title');

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

                const uuid = liNode.getAttribute('data-doc-page-uuid');
                PageControl.apiService.renamePage(newValue, uuid)
                    .then((data) => {
                        if (PageControl.tabs.has(uuid)) {
                            PageControl.tabBuilder.updateTabContent(uuid, data.data);
                        }
                    })
                    .catch((err) => {

                    });
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

        const deleteItem = (liNode) => {
            const uuid = liNode.getAttribute('data-doc-page-uuid');
            liNode.classList.add('hide');

            if (liNode.classList.contains('folder')) {
                const children = liNode.querySelectorAll('li');

                children.forEach(child => {
                    PageControl.tabBuilder.closeTab(child.getAttribute('data-doc-page-uuid'));
                });
            } else {
                PageControl.tabBuilder.closeTab(uuid);
            }
            PageControl.tabs.delete(uuid);

            PageControl.apiService.deletePage(uuid)
                .then((data) => {
                    if (!data.success) {
                        liNode.classList.remove('hide');
                    } else {
                        const deletedUuids = liNode.querySelectorAll("data-doc-page-uuid");
                        if(deletedUuids){
                            deletedUuids.forEach(uid => PageControl.tabBuilder.closeTab(uid));
                        }

                        liNode.remove();
                    }
                })
                .catch((err) => {
                    liNode.classList.remove('hide');
                });

        };

        document.addEventListener("contextmenu", (e) => {
            const li = e.target.closest(".directory-list li");
            if (!li) return;

            e.preventDefault();
            currentLi = li;

            menu.style.display = "block";

            const menuRect = menu.getBoundingClientRect();
            const viewportWidth = window.innerWidth;
            const viewportHeight = window.innerHeight;

            let left = e.pageX;
            let top = e.pageY;

            if (left + menuRect.width > viewportWidth) {
                left = viewportWidth - menuRect.width - 10;
            }

            if (top + menuRect.height > viewportHeight) {
                top = viewportHeight - menuRect.height - 10;
            }

            menu.style.left = `${left}px`;
            menu.style.top = `${top}px`;
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
            if (action === "delete") deleteItem(currentLi);

            menu.style.display = "none";
        });

    }
}

class DocumentationTabs {

    static apiService;
    static loader;

    constructor(tabListId, tabBodyId) {
        this.tabList = document.getElementById(tabListId);
        this.tabBody = document.getElementById(tabBodyId);
        this.tabIndex = 0;

        if (!this.tabList || !this.tabBody) {
            throw new Error('Tab container not found');
        }

        DocumentationTabs.apiService = new ApiService();
    }

    createNewTab(title, pageData = {}, {
        iconClass = 'bi bi-app',
        isActive = true,
        isTemp = true
    } = {}) {
        const classObj = this;
        const id = `doc-tab-${this.tabIndex++}`;

        const li = document.createElement('li');
        li.className = `nav-item ${isTemp ? 'temp' : ''}`;
        li.setAttribute('data-page-uuid', pageData.uuid);

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
        block.setAttribute('data-page-uuid', pageData.uuid);

        const obEitor = block.querySelector('.ob-rich-editor');

        const tabEditorSaveBtn = block.querySelector('.tab-editor-toolbar .tab-save');

        if (obEitor) {
            const div = document.createElement('div');
            obEitor.insertAdjacentElement('beforeend', div);

            div.style.height = '700px';
            div.style.width = '100%';

            const mdEditor = new MarkdownEditor(div);
            mdEditor.init(pageData.content ?? '');

            const tab = PageControl.tabs.get(pageData.uuid);
            if (tab) tab.editor = mdEditor;

            mdEditor.onSave((content) => {
                tabEditorSaveBtn.click();
            });

            mdEditor.onChange((content) => {
                li.classList.remove('temp');
                li.classList.add("content-edited");
            });
        };

        const githubLinkInput = block.querySelector('.github-load-zone input');
        if (githubLinkInput) githubLinkInput.value = pageData.git_link ?? '';

        const previewZone = block.querySelector('.page-preview-zone');
        if (previewZone) previewZone.innerHTML = pageData.content_html ?? '';

        this.tabBody.appendChild(pane);
        this.activateTab(button);

        button.addEventListener('click', (e) => {
            if (e.target === closeBtn) return;
            if (e.target == text && e.detail === 2) {
                li.classList.remove('temp');
            }
            this.activateTab(button);
        });

        closeBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            this.closeTab(pageData.uuid);
            PageControl.tabs.delete(pageData.uuid);
        });

        tabEditorSaveBtn.addEventListener('click', () => classObj.savePageContent(tabEditorSaveBtn, pageData.uuid));

        return { button, pane };
    }

    createGitEditorBlock(container) {
        const block = document.createElement('div');
        block.className = 'git-editor-block';

        const row = document.createElement('div');
        row.className = 'row';

        const gitPane = document.createElement('div');
        gitPane.className = 'col-12 mb-3 github-load-zone';
        gitPane.innerHTML = `
        <label class="form-label">Git Link</label>
        <div class="d-flex">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-git"></i></span>
                <input type="text" class="form-control" placeholder="Enter autofetch link">
            </div>
            <button type="button" class="btn btn-outline-primary ms-1 px-2 load-button" >
                <i class='bx bx-download fs-4'></i>
            </button>
        </div>
        <div class="git-link-load-body mt-2">
        </div>
    `;

        const editorPane = document.createElement('div');
        editorPane.className = 'col-12 rich-editor-zone';
        editorPane.innerHTML = `
            <div class="ob-rich-editor"> </div>
        `;
        const previewPane = document.createElement('div');
        previewPane.className = 'col-12 page-preview-zone';
        previewPane.innerHTML = "";

        row.appendChild(previewPane);
        row.appendChild(gitPane);
        row.appendChild(editorPane);
        block.appendChild(row);

        const classObj = this;

        const toolbar = document.createElement('div');
        toolbar.className = 'tab-editor-toolbar';
        toolbar.innerHTML = `
            <ul class="list-inline">
                <li class="list-inline-item"><button class="tab-editor"><i class='bx bx-pencil'></i></button></li>
                <li class="list-inline-item"><button class="tab-github"><i class='bx bxl-github'></i></button></li>
                <li class="list-inline-item"><button class="tab-preview"><i class='bx bx-show-alt'></i></button></li>
                <li class="list-inline-item"><button class="tab-save"><i class='bx bx-save'></i></button></li>
            </ul>
        `;
        block.appendChild(toolbar);

        const showPane = (pane) => {

            previewPane.classList.add('hide');
            gitPane.classList.add('hide');
            editorPane.classList.add('hide');

            if (pane === 'preview') {
                previewPane.classList.remove('hide');
                classObj.openingPreviewTab(previewPane);
            } else if (pane === 'github') {
                gitPane.classList.remove('hide');
            } else if (pane === 'editor') {
                editorPane.classList.remove('hide');
            }

            toolbar.querySelectorAll('button').forEach(btn =>
                btn.classList.remove('active')
            );

            if (pane === 'editor') {
                toolbar.querySelector('.tab-editor').classList.add('active');
            } else if (pane === 'github') {
                toolbar.querySelector('.tab-github').classList.add('active');
            } else if (pane === 'preview') {
                toolbar.querySelector('.tab-preview').classList.add('active');

            }
        }
        showPane('editor');

        const btn = block.querySelector(".github-load-zone .load-button");
        const input = block.querySelector(".github-load-zone input");

        btn.addEventListener('click', () => {
            classObj.loadGithubPageButtonClicked(btn, block, showPane);
        });

        input.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                classObj.loadGithubPageButtonClicked(btn, block, showPane);
            }
        });

        toolbar.querySelector('.tab-editor').addEventListener('click', () => showPane('editor'));
        toolbar.querySelector('.tab-github').addEventListener('click', () => showPane('github'));
        toolbar.querySelector('.tab-preview').addEventListener('click', () => showPane('preview'));

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

    savePageContent(saveBtn, uuid) {
        const obj = PageControl.tabs.get(uuid);
        const markdown = obj.editor.getValue();

        const prev = saveBtn.innerHTML;
        saveBtn.innerHTML = `<div class="spinner-border spinner-border-sm" role="status">
            <span class="visually-hidden">Loading...</span>
            </div>`;
        saveBtn.disabled = true;

        const git_link = document.querySelector(`.git-editor-block[data-page-uuid='${uuid}'] .github-load-zone input`)?.value ?? "";

        DocumentationTabs.apiService.saveMarkdownContent(uuid, { markdown: markdown, git_link: git_link.trim() })
            .then(data => {
                if (data.success) {
                    console.log('saved successfully!');
                    saveBtn.innerHTML = `<i class="bx bx-check-circle"></i>`;
                    setTimeout(() => {
                        saveBtn.innerHTML = prev;
                    }, 2000);

                    const tabLi = document.querySelector(`#documentationExplorerTab .nav-item[data-page-uuid='${uuid}']`);
                    if (tabLi) {
                        tabLi.classList.remove("content-edited");
                    }
                } else {
                    console.error('Saving failed!', data);
                    saveBtn.innerHTML = prev;
                }
                saveBtn.disabled = false;
            })
            .catch(error => {
                console.error(error);
                saveBtn.innerHTML = prev;
                saveBtn.disabled = false;
            });


    }

    openingPreviewTab(previewPane) {

        const gitEditorBlock = previewPane.closest('.git-editor-block');
        const uuid = gitEditorBlock.getAttribute('data-page-uuid');

        previewPane.innerHTML = AppUI.loader();

        const obj = PageControl.tabs.get(uuid);
        const markdown = obj.editor.getValue();

        DocumentationTabs.apiService.getHtmlFromMarkdown(uuid, markdown)
            .then((data) => {
                if (data.success) {
                    previewPane.innerHTML = data.html;
                } else {
                    previewPane.innerHTML = AppUI.error("Error", data.message);
                }
            })
            .catch((err) => {
                console.error("Error", err);
                previewPane.innerHTML = AppUI.error("Error", 'Something went wrong!');
            });
    }

    loadGithubPageButtonClicked(btn, block, showPane) {
        const input = block.querySelector('.github-load-zone input');
        if (input.value.trim() == '') {
            console.error("Invalid Input");
        }
        const mdArea = block.querySelector('.git-link-load-body');

        mdArea.innerHTML = `${AppUI.loader()}`;
        DocumentationTabs.apiService.loadGithubLinkPage(block.getAttribute('data-page-uuid'), input.value.trim())
            .then(data => {
                const obj = PageControl.tabs.get(data.data.uuid);
                obj.editor.setValue(data.markdown);
                const successMsgHtml = AppUI.success('Success', 'Loaded Successfully! view preview or edit page');

                mdArea.innerHTML = `
                        <div class="success-message">
                            ${successMsgHtml}
                        </div>
                        <div class="loaded-by-link mt-2">
                            <button class="view-btn">
                                <i class="bx bx-show-alt"></i> Preview
                            </button>
                            <button class="edit-btn">
                                <i class="bx bx-pencil"></i> Open Editor
                            </button>
                        </div>
                    `;

                mdArea.querySelector('.view-btn')
                    .addEventListener('click', () => showPane('preview'));

                mdArea.querySelector('.edit-btn')
                    .addEventListener('click', () => showPane('editor'));

                setTimeout(() => {
                    const successEl = mdArea.querySelector('.success-message');
                    if (successEl) successEl.remove();
                }, 8000);
            })
            .catch(error => {
                console.error('Failed to load page:', error);
                mdArea.innerHTML = AppUI.error("Error", error.message);
            });
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

    closeTab(uuid) {
        const li = document.querySelector(`#documentationExplorerTab .nav-item[data-page-uuid="${uuid}"]`);

        if (!li) {
            console.log("Tab not present - ", uuid);
            return;
        }

        const button = li.querySelector('button');
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

    updateTabContent(uuid, data) {
        let tab = document.querySelector(`#documentationExplorerTab li[data-page-uuid='${uuid}']`);
        if (tab) {
            const title = tab.querySelector('button .tab-title');
            if (title) title.textContent = data.title;
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

    loadGithubLinkPage(uuid, link) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        return fetch(authRoute('user.documentation.pages.git.load.content', { docPage: uuid }), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'x-csrf-token': csrfToken
            },
            body: JSON.stringify({
                git_link: link,
            })
        })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    console.error(data);
                    return Promise.reject(data.message || 'Fetch failed');
                }
                return data;
            })
            .catch(error => {
                console.error('Error:', error);
                return Promise.reject(error);
            });
    }

    deletePage(uuid) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        return fetch(authRoute('user.documentation.pages.delete', { docPage: uuid }), {
            method: 'DELETE',
            headers: {
                'x-csrf-token': csrfToken
            }
        })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    console.error(data);
                    return Promise.reject(data.message || 'Deletion failed');
                }
                return data;
            })
            .catch(error => {
                console.error('Error:', error);
                return Promise.reject(error);
            });
    }

    getHtmlFromMarkdown(uuid, markdown) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        return fetch(authRoute('user.documentation.pages.md-to-html', { docPage: uuid }), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'x-csrf-token': csrfToken
            },
            body: JSON.stringify({
                markdown: markdown,
            })
        })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    console.error(data);
                    return Promise.reject(data.message || 'Load failed');
                }
                return data;
            })
            .catch(error => {
                console.error('Error:', error);
                return Promise.reject(error);
            });
    }

    saveMarkdownContent(uuid, data) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        return fetch(authRoute('user.documentation.pages.udpate.md.content', { docPage: uuid }), {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'x-csrf-token': csrfToken
            },
            body: JSON.stringify(data)
        })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    console.error(data);
                    return Promise.reject(data.message || 'Save failed');
                }
                return data;
            })
            .catch(error => {
                console.error('Error:', error);
                return Promise.reject(error);
            });
    }

    moveFile(orderData, parentUuid) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        return fetch(authRoute('user.documentation.pages.move'), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'x-csrf-token': csrfToken
            },
            body: JSON.stringify({
                items: orderData,
                parent_uuid: parentUuid
            })
        })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    console.error(data);
                    return Promise.reject(data.message || 'Move failed');
                }
                return data;
            })
            .catch(error => {
                console.error('Error:', error);
                return Promise.reject(error);
            });
    }

}

class AppUI {
    static loader() {
        return `
            <div class="text-center">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        `;
    }

    static error(title = "Error", message = "Something went wrong!") {
        return `<div class="alert alert-danger" role="alert">
            <strong> ${title} </strong> - ${message}
        </div>`;
    }
    static success(title = "Success", message = "") {
        return `<div class="alert alert-success" role="alert">
            <strong> ${title} </strong> - ${message}
        </div>`;
    }
}

class MarkdownEditor {

    static monacoLoader = null;

    #editor = null;
    #element = null;
    #observer = null;
    #resizeObserver = null;
    #windowResizeHandler = null;
    #saveCallback = null;
    #changeCallback = null;
    #contentChangeDisposable = null;
    #saveCommandDisposable = null;
    #isDirty = false;

    constructor(element = null) {
        if (element) {
            this.setElement(element);
        }
    }

    static loadMonaco() {
        if (this.monacoLoader) {
            return this.monacoLoader;
        }

        this.monacoLoader = new Promise((resolve) => {
            require.config({
                paths: { 'vs': 'https://cdn.jsdelivr.net/npm/monaco-editor/min/vs' }
            });

            require(['vs/editor/editor.main'], function () {

                monaco.editor.defineTheme('custom-dark', {
                    base: 'vs-dark',
                    inherit: true,
                    rules: [],
                    colors: {
                        'editor.background': '#212529',
                    }
                });

                resolve();
            });
        });

        return this.monacoLoader;
    }

    async init(data = {}) {
        if (!this.#element) {
            throw new Error('Editor element is not set.');
        }

        await MarkdownEditor.loadMonaco();

        const theme = this.#getTheme();

        this.#editor = monaco.editor.create(this.#element, {
            value: typeof data === 'string'
                ? data
                : JSON.stringify(data, null, 4),
            language: 'markdown',
            theme: theme,
            readOnly: false,
            stickyScroll: { enabled: false },
            automaticLayout: true
        });

        this.#attachResizeHandler();
        this.#observeThemeChange();
        this.#attachEditorListeners();
    }

    #getTheme() {
        return document.body.classList.contains("dark")
            ? "custom-dark"
            : "vs-light";
    }

    #attachResizeHandler() {
        if (!this.#element) return;

        this.#resizeObserver = new ResizeObserver(() => {
            if (!this.#editor) return;

            const { offsetWidth, offsetHeight } = this.#element;

            if (offsetWidth > 0 && offsetHeight > 0) {
                this.#editor.layout();
            }
        });

        this.#resizeObserver.observe(this.#element);

        this.#windowResizeHandler = () => {
            this.#editor?.layout();
        };

        window.addEventListener('resize', this.#windowResizeHandler);
    }

    #observeThemeChange() {
        this.#observer = new MutationObserver(() => {
            monaco.editor.setTheme(this.#getTheme());
        });

        this.#observer.observe(document.body, { attributes: true });
    }

    #attachEditorListeners() {
        if (!this.#editor) return;

        this.#saveCommandDisposable = this.#editor.addCommand(
            monaco.KeyMod.CtrlCmd | monaco.KeyCode.KeyS,
            () => {
                this.#isDirty = false;
                if (typeof this.#saveCallback === 'function') {
                    this.#saveCallback(this.getValue());
                }
            }
        );

        this.#contentChangeDisposable = this.#editor.onDidChangeModelContent(() => {
            this.#isDirty = true;

            if (typeof this.#changeCallback === 'function') {
                this.#changeCallback(this.getValue());
            }
        });
    }

    getEditor() {
        return this.#editor;
    }

    setEditor(editorInstance) {
        this.#editor = editorInstance;
    }

    getElement() {
        return this.#element;
    }

    setElement(element) {
        if (!(element instanceof HTMLElement)) {
            throw new Error('Element must be a valid HTMLElement.');
        }
        this.#element = element;
    }

    setValue(value) {
        if (!this.#editor) return;
        this.#editor.setValue(
            typeof value === 'string'
                ? value
                : JSON.stringify(value, null, 4)
        );
    }

    getValue() {
        return this.#editor?.getValue() ?? null;
    }

    onSave(callback) {
        this.#saveCallback = callback;
    }

    onChange(callback) {
        this.#changeCallback = callback;
    }

    isDirty() {
        return this.#isDirty;
    }

    destroy() {
        if (this.#observer) {
            this.#observer.disconnect();
            this.#observer = null;
        }

        if (this.#resizeObserver) {
            this.#resizeObserver.disconnect();
            this.#resizeObserver = null;
        }

        if (this.#windowResizeHandler) {
            window.removeEventListener('resize', this.#windowResizeHandler);
            this.#windowResizeHandler = null;
        }

        if (this.#contentChangeDisposable) {
            this.#contentChangeDisposable.dispose();
            this.#contentChangeDisposable = null;
        }

        if (this.#saveCommandDisposable) {
            this.#editor.removeCommand(this.#saveCommandDisposable);
            this.#saveCommandDisposable = null;
        }

        if (this.#editor) {
            this.#editor.dispose();
            this.#editor = null;
        }


    }
}
