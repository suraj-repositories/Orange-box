let MEDIA_FILES = [];

let fileService = null;

document.addEventListener('DOMContentLoaded', function () {
    fileService = new FileService();

    enableRefactorFileDelete();
});


// ------------------------------------------------------
// show grid and list view
// ------------------------------------------------------
const cardViewContainer = document.getElementById("card-view-container");
const listViewContainer = document.getElementById("list-view-container");

const cardRadio = document.getElementById('card-radio');
if (cardRadio) {
    cardRadio.addEventListener('click', function (event) {
        showView('card');
    });
}
const listRadio = document.getElementById('list-radio');
if (listRadio) {
    listRadio.addEventListener('click', function (event) {
        showView('list');
    });
}
showView('card');


function showView(view = 'card') {

    if (view === 'card') {
        !listViewContainer.classList.contains('hide') && listViewContainer.classList.add('hide');
        cardViewContainer.classList.contains('hide') && cardViewContainer.classList.remove('hide');
        document.getElementById("card-radio").checked = true;

    } else if (view === 'list') {
        !cardViewContainer.classList.contains('hide') && cardViewContainer.classList.add('hide');
        listViewContainer.classList.contains('hide') && listViewContainer.classList.remove('hide');
        document.getElementById("list-radio").checked = true;
    }
}

// ------------------------------------------------------
// add selected files to card logic
// ------------------------------------------------------



document.querySelector("#media-input").addEventListener('change', (event) => {
    event.target.files.forEach(file => {
        MEDIA_FILES.push(file);
        toggleCardListTabBtns('show');

        updateCardsView(file);
        updateListView(file);

    });
});

function updateListView(file) {
    let listRowHTML = `<div class="card border-0 rounded-0 list-row-card" data-media-files-index="${MEDIA_FILES.length - 1}">
                        <div class="horizontal-viewer">
                            <div class="icon"><i class="bi ${fileService.getIconFromExtension(fileService.getExtension(file))}"></i></div>
                            <div class="name text-truncate">${fileService.getName(file)}</div>
                            <div class="type">${fileService.getExtension(file)}</div>
                            <div class="size">${fileService.getSize(file)}</div>
                            <div class="action">
                                <a class="show" href="javascript:void(0)"
                                    data-bs-toggle="tooltip" data-bs-title="View"><i
                                        class='bx bx-show-alt'></i></a>
                                <!--
                                <a class="rename" href="javascript:void(0)"
                                    data-bs-toggle="tooltip" data-bs-title="Rename"><i
                                        class='bx bx-rename'></i></a>
                                -->
                                <a class="delete" href="javascript:void(0)" data-og-dismiss="list-item-card"
                                    data-bs-toggle="tooltip" data-bs-title="Delete" ><i
                                        class='bx bx-trash-alt'></i></a>
                            </div>
        </div>
    </div>`;

    let listRow = document.createRange().createContextualFragment(listRowHTML).firstElementChild;

    listRow.querySelector('[data-og-dismiss="list-item-card"]').addEventListener('click', function () {

        let indexToRemove = parseInt(listRow.getAttribute("data-media-files-index"));
        removeCardAndListItemsWithIndex(indexToRemove);
        console.log(MEDIA_FILES);

    });

    listViewContainer.appendChild(listRow);

}
function removeCardAndListItemsWithIndex(index) {
    document.querySelectorAll(`[data-media-files-index="${index}"]`).forEach(element => {
        element.remove();
        if (index > -1 && index < MEDIA_FILES.length) {
            MEDIA_FILES[index] = null;
        }
    });

    if (MEDIA_FILES.every(element => element === null)) {
        toggleCardListTabBtns('hide');
    }

}

function toggleCardListTabBtns(action) {

    let toggler = document.getElementById('card-list-tab-toggler');

    if (action === "show") {
        if (toggler.classList.contains('hide')) {
            toggler.classList.remove('hide')
        }
    } else if (action === "hide") {
        if (!toggler.classList.contains('hide')) {
            toggler.classList.add('hide')
        }

    }
    showView('card');
}

function updateCardsView(file) {

    const hoverActions = ` <div class="hover-actions">
                <a class="show" href="javascript:void(0)"
                    data-bs-toggle="tooltip" data-bs-title="View">
                    <i class='bx bx-show-alt'></i>
                </a>
                <!--
                <a class="rename" href="javascript:void(0)"
                    data-bs-toggle="tooltip" data-bs-title="Rename">
                    <i class='bx bx-rename'></i>
                </a>
                   -->
                <a class="delete" href="javascript:void(0)"
                    data-bs-toggle="tooltip" data-bs-title="Delete" data-ob-dismiss="delete-card">
                    <i class='bx bx-trash-alt'></i>
                </a>
            </div>`;
    const cardBody = ` <div class="card-body">
            <h5 class="card-title">${fileService.getName(file)}</h5>
            <ul class="list-unstyled mb-0">
                <li><span class="text-muted">Type:</span> ${fileService.getExtension(file)}</li>
                <li><span class="text-muted">Size:</span> ${fileService.getSize(file)}</li>
            </ul>
        </div>`;

    let imageCardHTML = `<div class="col" data-media-files-index="${MEDIA_FILES.length - 1}">
        <div class="card h-100">
        <div class="img-container">
            <img src=".." alt="Image" />
            ${hoverActions}
        </div>
            ${cardBody}
        </div>
    </div>`;

    let iconCardHTML = ` <div class="col" data-media-files-index="${MEDIA_FILES.length - 1}">
        <div class="card h-100">
            <div class="file-thumb-holder">
                <div class="file-thumb-box">
                    <i class="bi ${fileService.getIconFromExtension(fileService.getExtension(file))}"></i>
                </div>
                ${hoverActions}
            </div>
            ${cardBody}
        </div>
    </div>`;

    let iconCard = document.createRange().createContextualFragment(iconCardHTML).firstElementChild;
    let imageCard = document.createRange().createContextualFragment(imageCardHTML).firstElementChild;

    let appliedCard = null;
    if (fileService.setImageOnView(file, imageCard.querySelector('img'))) {
        cardViewContainer.appendChild(imageCard);
        appliedCard = imageCard;
    } else {
        console.log(iconCard);
        cardViewContainer.appendChild(iconCard);
        appliedCard = iconCard;

    }

    appliedCard.querySelector(`[data-ob-dismiss='delete-card']`).addEventListener('click', function () {
        let indexToRemove = parseInt(appliedCard.getAttribute("data-media-files-index"));
        removeCardAndListItemsWithIndex(indexToRemove);
        console.log(MEDIA_FILES);

    });
}


// ------------------------------------------------------
// adding images to form
// ------------------------------------------------------

document.querySelector("#add-digest-form").addEventListener('submit', function (e) {
    e.preventDefault();

    let dataTransfer = new DataTransfer();
    MEDIA_FILES.forEach(file => {
        if (file && file != null) {
            dataTransfer.items.add(file);
        }
    });

    this.querySelector('#media-input').files = dataTransfer.files;
    console.log(this.querySelector('#media-input'), this.querySelector('#media-input').files);

    this.submit();

});

function enableRefactorFileDelete() {
    const fileCards = document.querySelectorAll('[data-ob-deleteable-card="true"]');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    if (!fileCards.length) return;

    fileCards.forEach(card => {
        const deleteBtn = card.querySelector('[data-ob-dismiss="editing-delete-card"]');
        if (!deleteBtn) return;

        deleteBtn.addEventListener('click', (e) => {

            e.preventDefault();
            e.stopPropagation();

            card.style.filter = "grayscale(1)";
            card.style.opacity = "0.5";
            const url = deleteBtn.getAttribute('data-ob-delete-url');
            console.log("Deleting:", url);

            fetch(url, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
                .then(response => {
                    if (!response.ok) throw new Error("Failed request");
                    return response.json();
                })
                .then(data => {
                    const tooltip = bootstrap.Tooltip.getInstance(deleteBtn);
                    if (tooltip) tooltip.dispose();
                    card.remove();
                    console.log('File deleted successfully');
                })
                .catch(error => {
                    console.error('Error:', error);
                    card.style.filter = "grayscale(0)";
                     card.style.opacity = "1";
                });
        });
    });
}
