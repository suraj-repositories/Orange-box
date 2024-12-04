let MEDIA_FILES = [];

let fileService = null;

document.addEventListener('DOMContentLoaded',function(){
    fileService = new FileService();
});


// ------------------------------------------------------
// show grid and list view
// ------------------------------------------------------
const cardViewContainer = document.getElementById("card-view-container");
const listViewContainer = document.getElementById("list-view-container");

document.getElementById('card-radio').addEventListener('click', function(event){
    showView('card');
});
document.getElementById('list-radio').addEventListener('click', function(event){
    showView('list');
});
showView('card');


function showView(view = 'card'){

    if(view === 'card'){
        !listViewContainer.classList.contains('hide') && listViewContainer.classList.add('hide');
       cardViewContainer.classList.contains('hide') && cardViewContainer.classList.remove('hide');
    }else if(view === 'list'){
        !cardViewContainer.classList.contains('hide') && cardViewContainer.classList.add('hide');
        listViewContainer.classList.contains('hide') && listViewContainer.classList.remove('hide');
    }
}

// ------------------------------------------------------
// add selected files to card logic
// ------------------------------------------------------



document.querySelector("#media-input").addEventListener('change', (event)=>{
    event.target.files.forEach(file => {
        MEDIA_FILES.push(file);

        updateCardsView(file);
        updateListView(file);

    });
});

function updateListView(file){
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
                                <a class="rename" href="javascript:void(0)"
                                    data-bs-toggle="tooltip" data-bs-title="Rename"><i
                                        class='bx bx-rename'></i></a>
                                <a class="delete delete-list-item" href="javascript:void(0)"
                                    data-bs-toggle="tooltip" data-bs-title="Delete" ><i
                                        class='bx bx-trash-alt'></i></a>
                            </div>
                        </div>
                    </div>`;

    let listRow = document.createRange().createContextualFragment(listRowHTML).firstElementChild;
    listRow.querySelector('.delete-list-item').addEventListener('click', function(){
        listRow.remove();
        let indexToRemove = parseInt(listRow.getAttribute("data-media-files-index"));
        if (indexToRemove > -1 && indexToRemove < MEDIA_FILES.length) {
            MEDIA_FILES.splice(indexToRemove, 1);


            // HERE YOU forget when items is deleted the index should decresed....
        }

        console.log(MEDIA_FILES);
    });

    listViewContainer.appendChild(listRow);

}

function updateCardsView(file){
    let imageCardHTML = `<div class="col">
        <div class="card h-100">
        <div class="img-container">
            <img src=".." alt="Image" />
            <div class="hover-actions">
                <a class="show" href="javascript:void(0)"
                    data-bs-toggle="tooltip" data-bs-title="View">
                    <i class='bx bx-show-alt'></i>
                </a>
                <a class="rename" href="javascript:void(0)"
                    data-bs-toggle="tooltip" data-bs-title="Rename">
                    <i class='bx bx-rename'></i>
                </a>
                <a class="delete" href="javascript:void(0)"
                    data-bs-toggle="tooltip" data-bs-title="Delete">
                    <i class='bx bx-trash-alt'></i>
                </a>
            </div>
        </div>
        <div class="card-body">
            <h5 class="card-title">${fileService.getName(file)}</h5>
            <ul class="list-unstyled mb-0">
                <li><span class="text-muted">Type:</span> ${fileService.getExtension(file)}</li>
                <li><span class="text-muted">Size:</span> ${fileService.getSize(file)}</li>
            </ul>
        </div>
        </div>
    </div>`;

    let iconCardHTML = ` <div class="col">
    <div class="card h-100">
        <div class="file-thumb-holder">
            <div class="file-thumb-box">
                <i class="bi ${fileService.getIconFromExtension(fileService.getExtension(file))}"></i>
            </div>
            <div class="hover-actions">
                <a class="show" href="javascript:void(0)"
                    data-bs-toggle="tooltip" data-bs-title="View">
                    <i class='bx bx-show-alt'></i>
                </a>
                <a class="rename" href="javascript:void(0)"
                    data-bs-toggle="tooltip" data-bs-title="Rename">
                    <i class='bx bx-rename'></i>
                </a>
                <a class="delete" href="javascript:void(0)"
                    data-bs-toggle="tooltip" data-bs-title="Delete">
                    <i class='bx bx-trash-alt'></i>
                </a>
            </div>
        </div>
        <div class="card-body">
            <h5 class="card-title text-truncate">${fileService.getName(file)}</h5>
            <ul class="list-unstyled mb-0">
                <li><span class="text-muted">Type:</span> ${fileService.getExtension(file)}</li>
                <li><span class="text-muted">Size:</span> ${fileService.getSize(file)}</li>
            </ul>
            </div>
    </div>
    </div>`;

    let iconCard = document.createRange().createContextualFragment(iconCardHTML).firstElementChild;
    let imageCard = document.createRange().createContextualFragment(imageCardHTML).firstElementChild;


    if(fileService.setImageOnView(file, imageCard.querySelector('img'))){
        cardViewContainer.appendChild(imageCard);
    }else{
        console.log(iconCard);
        cardViewContainer.appendChild(iconCard);
    }

}




