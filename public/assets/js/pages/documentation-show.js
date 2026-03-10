document.addEventListener('DOMContentLoaded', function () {
    enableHotPagesStatusChange();
});

function enableHotPagesStatusChange() {

    const switches = document.querySelectorAll('.hot-pages-card .change-status-checkbox');

    if (switches.length <= 0) {
        console.warn("No switches there");
        return;
    }

    const updateDocumentStatus = (documentId, status, checkbox) => {

        if (!documentId || !status) {
            console.error("Invalid argument passed!");
            return;
        }

        const csrfToken = document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute('content');

        fetch(authRoute('user.documentation.document.status.update', { document: documentId }), {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'x-csrf-token': csrfToken
            },
            body: JSON.stringify({ status: status })
        })
            .then(response => response.json())
            .then(data => {
                console.log(data);
                if (data.success) {

                    Toastify.success(data.message);

                    const listItem = checkbox.closest('.document-list-item');
                    const badge = listItem.querySelector('.page-status-badge');

                    if (!badge) return;

                    if (status === 'active') {
                        badge.textContent = 'Active';
                        badge.classList.remove('bg-danger-subtle', 'text-danger');
                        badge.classList.add('bg-success-subtle', 'text-success');
                    } else {
                        badge.textContent = 'Inactive';
                        badge.classList.remove('bg-success-subtle', 'text-success');
                        badge.classList.add('bg-danger-subtle', 'text-danger');
                    }

                } else {

                    Toastify.error(data.message);
                    checkbox.checked = !checkbox.checked;

                }

            })
            .catch(error => {

                console.error('Error:', error);
                Toastify.error("Something went wrong!");
                checkbox.checked = !checkbox.checked;

            });
    }

    switches.forEach(checkbox => {

        checkbox.addEventListener('change', function () {

            const documentId = checkbox.getAttribute('data-document-id');
            const status = checkbox.checked ? 'active' : 'inactive';

            updateDocumentStatus(documentId, status, checkbox);

        });

    });

}
