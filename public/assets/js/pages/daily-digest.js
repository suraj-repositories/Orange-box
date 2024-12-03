
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
let mediaFiles = [];

document.querySelector("#media-input").addEventListener('change', (event)=>{
    let file = event.target.files[0];
    console.log(file);
    let fileService = new FileService();

    let imageCard = `<div class="col">
                        <div class="card h-100">
                            <div class="img-container">
                                <img src="https://placehold.co/100" alt="Image" />
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">Card title</h5>
                                <ul class="list-unstyled mb-0">
                                    <li><span class="text-muted">Type:</span> Text</li>
                                    <li><span class="text-muted">Size:</span> 512KB</li>
                                </ul>
                            </div>
                        </div>
                    </div>`;
    let iconCard = `<div class="col">
                        <div class="card h-100">
                            <div class="file-thumb-holder">
                                <div class="file-thumb-box">
                                    <i class="bx bxs-file"></i>
                                </div>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title text-truncate">
                                    EAadhaar_2728208020303320241102094945_07112024145514 (1)</h5>
                                <ul class="list-unstyled mb-0">
                                    <li><span class="text-muted">Type:</span> Text</li>
                                    <li><span class="text-muted">Size:</span> 512KB</li>
                                </ul>
                                <i class='bx bx-show-alt'></i>
                                <i class='bx bx-rename'></i>
                                <i class='bx bx-trash-alt'></i>
                            </div>
                        </div>
                    </div>`;

    // const filesize = fileService.getSize(file);
    let iconClasses = fileService.getAllAvailableIcons();
    let icons = "";
    iconClasses.forEach(element => {
        icons += `<i class="bi ${element}"></i>`;
    });

    document.getElementById('card-view-container').innerHTML = icons;
    console.log('the file size is : ' + icons);
});



