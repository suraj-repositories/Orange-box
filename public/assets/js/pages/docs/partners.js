document.addEventListener('DOMContentLoaded', function () {
    enableSearchPartnerInput();
});

function enableSearchPartnerInput() {
    const input = document.querySelector("#partnersSearchInput");
    const area = document.querySelector("#partnersListRender");

    if (!input || !area) return;

    input.addEventListener('keyup', function () {

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch(input.getAttribute('data-search-url'), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'x-csrf-token': csrfToken
            },
            body: JSON.stringify({ search: input.value })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    area.innerHTML = data.html;
                } else {
                    area.innerHTML = "";
                }
            })
            .catch(error => {
                console.error('Error:', error);
                area.innerHTML = "";
            });
    });
}
