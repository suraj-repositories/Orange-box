document.addEventListener('DOMContentLoaded', function(){
    enableFileDelete(".delete-file-button");
});

function enableFileDelete(selector){
    const buttons = document.querySelectorAll(selector);
    if(!buttons) return;

    buttons.forEach(button => {
        button.addEventListener('click', function(){

            const fileId = button.getAttribute('data-file-id');

            if(!fileId){
                return;
            }

            const accItem = button.closest(".accordion-item");
            if(accItem){
                accItem.classList.add('deleting');
            }

           const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
           fetch( route('file.delete', fileId), {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'x-csrf-token': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                 accItem.classList.remove('deleting');
                 accItem.remove();
            })
            .catch(error => {
                console.error('Error:', error);
                accItem.classList.remove('deleting');
                accItem.classList.add('delete-error');
                setTimeout(() => {
                    accItem.classList.remove('delete-error');
                }, 3000);
            });

        });
    });

}
