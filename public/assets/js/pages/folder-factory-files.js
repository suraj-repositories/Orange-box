document.addEventListener('DOMContentLoaded', function () {
    enableFileDelete(".delete-file-button");

    enableRenameFile('.rename-file-btn');
});

function enableFileDelete(selector) {
    const buttons = document.querySelectorAll(selector);
    if (!buttons) return;

    buttons.forEach(button => {
        button.addEventListener('click', function () {

            const fileId = button.getAttribute('data-file-id');

            if (!fileId) {
                return;
            }

            const accItem = button.closest(".accordion-item");
            if (accItem) {
                accItem.classList.add('deleting');
            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            fetch(route('file.delete', fileId), {
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

function enableRenameFile(selector) {
    const btns = document.querySelectorAll(selector);

    if (!btns) return;

    btns.forEach(btn => {
        btn.addEventListener('click', () => {
            const accordionItem = btn.closest(".accordion-item");
            const fileId = btn.getAttribute('data-ob-file-id');

            if (!accordionItem && !fileId) return;

            const name = accordionItem.querySelector('.name');

            const nameInput = document.createElement("input");
            nameInput.type = 'text';
            nameInput.classList.add('form-control');
            nameInput.name = 'name';
            nameInput.value = name.textContent.trim();

            name.replaceWith(nameInput);
            nameInput.focus();
            nameInput.select();

            const renameFile = () => {
                const newName = document.createElement('span');
                newName.classList.add('name');
                newName.textContent = nameInput.value || "Untitled";
                nameInput.replaceWith(newName);

                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch(authRoute('file.rename.ajax', { file: fileId }), {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'x-csrf-token': csrfToken
                    },
                    body: JSON.stringify({ new_name: nameInput.value || "Untitled" })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status != "success") {
                            Swal.fire({
                                title: "Oops!",
                                text: data.message,
                                icon: "error"
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            title: "Oops!",
                            text: error,
                            icon: "error"
                        });
                    });
            };

            nameInput.addEventListener('blur', renameFile);
            nameInput.addEventListener('keydown', (e) => {
                if (e.key === "Enter") {
                    e.preventDefault();
                    renameFile();
                }
            });
        });

    });

}
