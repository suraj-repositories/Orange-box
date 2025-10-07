document.addEventListener('DOMContentLoaded', function () {
    enableFolderFactoryDelete(".delete-folder-button");
});

function enableFolderFactoryDelete(selector) {
    const buttons = document.querySelectorAll(selector);
    if (!buttons) return;

    buttons.forEach(button => {
        button.addEventListener('click', function () {

            const folderFactoryId = button.getAttribute('data-folder-factory-id');

            if (!folderFactoryId) {
                return;
            }

            const folderCard = button.closest(".folder-card");
            if (folderCard) {
                folderCard.classList.add('deleting');
            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            fetch(authRoute('user.folder-factory.delete', { folderFactory: folderFactoryId }), {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'x-csrf-token': csrfToken
                }
            })
                .then(response => response.json())
                .then(data => {
                    folderCard.classList.remove('deleting');
                    const isCol = folderCard.closest(".col");
                    if (isCol) {
                        isCol.remove();
                    } else {
                        folderCard.remove();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    folderCard.classList.remove('deleting');
                    folderCard.classList.add('delete-error');
                    setTimeout(() => {
                        folderCard.classList.remove('delete-error');
                    }, 3000);
                });

        });
    });

}
