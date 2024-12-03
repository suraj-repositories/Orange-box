
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
    let file = event.target.files[0];
    console.log(file);
    let fileService = new FileService();

    const filesize = fileService.getSize(file);

    console.log('the file size is : ' +fileService.getExtension(file));
});
