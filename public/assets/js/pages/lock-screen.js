document.addEventListener('DOMContentLoaded', () => {

    new LockScreenControl().init();
});

class LockScreenControl {

    init() {
        this.enableUnlock("#unlock-form");
        this.enableLock('.lock_screen_btn');
    }

    enableUnlock(formSelector) {
        const form = document.querySelector(formSelector);

        if (!form) {
            return;
        }

        form.addEventListener('submit', (event) => {
            event.preventDefault();
            const submitBtn = form.querySelector("button[type='submit']");

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            submitBtn.textContent = "Unlocking...";
            submitBtn.disabled = true;
            fetch(route('unlock'), {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'x-csrf-token': csrfToken
                },
                body: JSON.stringify({
                    pin: form.querySelector('[name="pin"]').value
                })
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


    enableLock(selector) {
        const lockBtns = document.querySelectorAll(selector);

        if (!lockBtns) {
            return;
        }

        const documentbody = document.querySelector("body");

        lockBtns.forEach(btn => {
            btn.addEventListener('click', function () {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                btn.disabled = true;
                btn.textContent = "Locking...";
                documentbody.classList.add('locking');

                fetch(route('lock'), {
                    method: 'POST',
                    headers: {
                        'x-csrf-token': csrfToken
                    },
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
                            documentbody.classList.remove('locking')
                        }
                        btn.disabled = false;
                        btn.textContent = "Lock Screen";

                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            title: "Oops!",
                            text: error.message,
                            icon: "error"
                        });
                        btn.disabled = false;
                        btn.textContent = "Lock Screen";
                        documentbody.classList.remove('locking')
                    });
            });
        });

    }

}
