document.addEventListener('DOMContentLoaded', () => {
    enableUnlock("#unlock-form");
});

function enableUnlock(formSelector) {
    const form = document.querySelector(formSelector);

    form.addEventListener('submit', (event) => {
        event.preventDefault();
        const formData = new FormData(form);
        const submitBtn = form.querySelector("button[type='submit']");

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        submitBtn.textContent = "Unlocking...";
        submitBtn.disabled = true;
        fetch(route('unlock'), {
            method: 'POST',
            headers: {
                'x-csrf-token': csrfToken
            },
            body: formData
        })
            .then(response => response.json())
            .then(data => {

                if (data.status == "success") {
                    window.location.href = data.redirect_url;
                } else {
                    Swal.fire({
                        title: "Oops!",
                        text: data.message,
                        icon: "error"
                    });
                    submitBtn.textContent = "Unlock";
                    submitBtn.disabled = false;
                }

            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: "Oops!",
                    text: error.message,
                    icon: "error"
                });
                submitBtn.textContent = "Unlock";
                submitBtn.disabled = false;
            });
    });

}
