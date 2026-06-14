document.addEventListener('DOMContentLoaded', function () {
    updateStatus();
});
function updateStatus() {

    const checkboxes = document.querySelectorAll('.statusSwitch');

    if (checkboxes.length === 0) return;

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function () {

            const url = this.getAttribute('data-url');
            const isActive = this.checked ? 1 : 0;

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch(url, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    is_active: isActive
                })
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Toastify.success(data.message ?? 'Updated successfully!');
                    } else {
                        Toastify.error(data.message);
                    }

                    checkbox.checked = data.status;
                })
                .catch(err => {
                    console.error(err);
                    Toastify.error('Something went wrong!');
                });

        });
    });
}
