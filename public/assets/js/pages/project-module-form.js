document.addEventListener('DOMContentLoaded', function(){
    enableOpenLink("#OpenSelectedProjectBtn", "#pick-project");
});


function enableOpenLink(actionSourceSelector, inputSourceSelector) {
    const actionSource = document.querySelector(actionSourceSelector);
    const select = document.querySelector(inputSourceSelector);
    const redirectLink = select.closest(".select-box").querySelector(".redirect-link");

    actionSource.addEventListener('click', function () {
        const selectedOption = select.options[select.selectedIndex];

        if (selectedOption) {
            const url = selectedOption.dataset.publicUrl;

            if (url) {
                redirectLink.href = url;
                redirectLink.click();
            } else {
                console.warn('No url found for selected option');
                alert("No url found for selected option");
            }
        } else {
            alert("No option selected");
            console.warn('No option selected');
        }
    });

}
