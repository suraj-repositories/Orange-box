
document.addEventListener('DOMContentLoaded', function () {
    enableStatusUpdate(".releaseStatusToggleSwitch");
});

function enableStatusUpdate(selector) {
    const checkboxes = document.querySelectorAll(selector);
    if (checkboxes.length <= 0) return;

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            const status = checkbox.checked ? 'active' : 'inactive';

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch(authRoute('user.documentation.release.status.update', { release: checkbox.dataset.documentationReleaseId }), {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'x-csrf-token': csrfToken
                },
                body: JSON.stringify({ status: status })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Toastify.success(data.message);
                        if (data.refresh) {
                            setTimeout(() => {
                                window.location.reload();
                            }, 500);
                        }
                    } else {
                        Toastify.error(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Toastify.error("Something went wrong!");
                });

        });
    });
}
